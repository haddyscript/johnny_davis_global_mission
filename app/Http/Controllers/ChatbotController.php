<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ChatbotController extends Controller
{
    private const API_BASE = 'https://generativelanguage.googleapis.com/v1beta/models/';

    // ── Streaming endpoint (SSE) ─────────────────────────────────────────

    public function stream(Request $request): StreamedResponse
    {
        $request->validate([
            'message' => ['required', 'string', 'max:1000'],
        ]);

        $apiKey        = config('services.gemini.api_key');
        $primaryModel  = config('services.gemini.primary_model',  'gemini-2.5-flash');
        $fallbackModel = config('services.gemini.fallback_model', 'gemini-2.5-flash-lite');
        $timeout       = config('services.gemini.timeout', 30);

        if (empty($apiKey)) {
            return $this->streamError('The chatbot is not configured. Please contact the site administrator.');
        }

        $knowledge = $this->loadKnowledge();
        $prompt    = $this->buildPrompt($knowledge, $request->input('message'));
        $body      = $this->buildRequestBody($prompt);

        return response()->stream(function () use ($apiKey, $primaryModel, $fallbackModel, $body, $timeout) {
            // Disable any output buffering so SSE reaches the browser immediately
            while (ob_get_level() > 0) {
                ob_end_flush();
            }

            $sent = $this->curlStream($apiKey, $primaryModel, $body, $timeout);

            // Primary failed before sending any delta — try fallback
            if (! $sent) {
                $sent = $this->curlStream($apiKey, $fallbackModel, $body, $timeout);
            }

            if (! $sent) {
                $this->emitEvent(['error' => true, 'message' => "I'm having trouble connecting right now. Please try again or reach us at info@johnnydavisglobalmissions.org."]);
            }

            $this->emitDone();
        }, 200, [
            'Content-Type'      => 'text/event-stream',
            'Cache-Control'     => 'no-cache, no-store',
            'X-Accel-Buffering' => 'no',
            'Connection'        => 'keep-alive',
        ]);
    }

    // ── Non-streaming fallback ──────────────────────────────────────────

    public function chat(Request $request): JsonResponse
    {
        $request->validate([
            'message' => ['required', 'string', 'max:1000'],
        ]);

        $apiKey        = config('services.gemini.api_key');
        $primaryModel  = config('services.gemini.primary_model',  'gemini-2.5-flash');
        $fallbackModel = config('services.gemini.fallback_model', 'gemini-2.5-flash-lite');
        $timeout       = config('services.gemini.timeout', 20);

        if (empty($apiKey)) {
            return response()->json(['reply' => 'The chatbot is not configured yet. Please contact the site administrator.']);
        }

        $knowledge = $this->loadKnowledge();
        $prompt    = $this->buildPrompt($knowledge, $request->input('message'));

        $reply = $this->callGemini($primaryModel, $apiKey, $prompt, $timeout)
              ?? $this->callGemini($fallbackModel, $apiKey, $prompt, $timeout)
              ?? "I'm sorry, I'm having trouble connecting right now. Please try again or contact us at info@johnnydavisglobalmissions.org.";

        return response()->json(['reply' => $reply]);
    }

    // ── Private: SSE streaming via cURL ─────────────────────────────────

    /**
     * Open a streaming connection to Gemini, parse SSE chunks, and emit them.
     * Returns true if at least one delta was successfully emitted.
     */
    private function curlStream(string $apiKey, string $model, array $body, int $timeout): bool
    {
        $url      = self::API_BASE . $model . ':streamGenerateContent?alt=sse&key=' . $apiKey;
        $deltaSent = false;
        $buffer    = '';

        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => json_encode($body),
            CURLOPT_HTTPHEADER     => ['Content-Type: application/json', 'Accept: text/event-stream'],
            CURLOPT_RETURNTRANSFER => false,
            CURLOPT_TIMEOUT        => $timeout,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_WRITEFUNCTION  => function ($_handle, $data) use (&$buffer, &$deltaSent) {
                $buffer .= $data;

                // Process complete SSE lines
                while (($pos = strpos($buffer, "\n")) !== false) {
                    $line   = substr($buffer, 0, $pos);
                    $buffer = substr($buffer, $pos + 1);
                    $line   = rtrim($line, "\r");

                    if (! str_starts_with($line, 'data: ')) {
                        continue;
                    }

                    $payload = substr($line, 6);
                    if ($payload === '[DONE]') {
                        continue;
                    }

                    $parsed = json_decode($payload, true);
                    $text   = $parsed['candidates'][0]['content']['parts'][0]['text'] ?? null;

                    if ($text !== null) {
                        $this->emitEvent(['delta' => $text]);
                        $deltaSent = true;
                    }
                }

                return strlen($data);
            },
        ]);

        $success = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        // If curl failed or server returned non-2xx, report failure
        if ($success === false || $httpCode < 200 || $httpCode >= 300) {
            return false;
        }

        return $deltaSent;
    }

    private function emitEvent(array $payload): void
    {
        echo 'data: ' . json_encode($payload) . "\n\n";
        flush();
    }

    private function emitDone(): void
    {
        echo "data: [DONE]\n\n";
        flush();
    }

    private function streamError(string $message): StreamedResponse
    {
        return response()->stream(function () use ($message) {
            $this->emitEvent(['error' => true, 'message' => $message]);
            $this->emitDone();
        }, 200, [
            'Content-Type'      => 'text/event-stream',
            'Cache-Control'     => 'no-cache',
            'X-Accel-Buffering' => 'no',
        ]);
    }

    // ── Private: helpers ────────────────────────────────────────────────

    private function loadKnowledge(): string
    {
        $path = base_path('knowledge-base.md');
        return file_exists($path) ? mb_substr(file_get_contents($path), 0, 60000) : '';
    }

    private function buildPrompt(string $knowledge, string $userMessage): string
    {
        $knowledgeSection = $knowledge
            ? "You have access to the following knowledge base about Johnny Davis Global Missions:\n\n---\n{$knowledge}\n---\n\n"
            : '';

        return <<<PROMPT
You are a helpful, friendly assistant for Johnny Davis Global Missions (JDGM), a 501(c)(3) nonprofit serving communities in the Philippines and Uganda through feeding programs, medical missions, disaster relief, education support, and clean water initiatives.

{$knowledgeSection}Instructions:
- Answer questions using the knowledge base above as your primary source.
- Be warm, concise, and encouraging in your tone.
- If a question is about donating, direct users to the /donate page or mention the \$7.99/month option to feed a child.
- If asked about contact information, provide: email info@johnnydavisglobalmissions.org, phone (404) 426-2856.
- Keep responses under 200 words unless more detail is genuinely needed.
- Do not invent facts, names, or statistics not present in the knowledge base.
- Format responses with bullet points or short paragraphs for readability.

User question: {$userMessage}
PROMPT;
    }

    private function buildRequestBody(string $prompt): array
    {
        return [
            'contents' => [
                ['parts' => [['text' => $prompt]]],
            ],
            'generationConfig' => [
                'temperature'     => 0.7,
                'maxOutputTokens' => 512,
            ],
            'safetySettings' => [
                ['category' => 'HARM_CATEGORY_HARASSMENT',        'threshold' => 'BLOCK_MEDIUM_AND_ABOVE'],
                ['category' => 'HARM_CATEGORY_HATE_SPEECH',       'threshold' => 'BLOCK_MEDIUM_AND_ABOVE'],
                ['category' => 'HARM_CATEGORY_SEXUALLY_EXPLICIT', 'threshold' => 'BLOCK_MEDIUM_AND_ABOVE'],
                ['category' => 'HARM_CATEGORY_DANGEROUS_CONTENT', 'threshold' => 'BLOCK_MEDIUM_AND_ABOVE'],
            ],
        ];
    }

    private function callGemini(string $model, string $apiKey, string $prompt, int $timeout): ?string
    {
        try {
            $url      = self::API_BASE . $model . ':generateContent?key=' . $apiKey;
            $response = Http::timeout($timeout)
                ->withHeaders(['Content-Type' => 'application/json'])
                ->post($url, $this->buildRequestBody($prompt));

            if (! $response->successful()) {
                return null;
            }

            return $response->json('candidates.0.content.parts.0.text');
        } catch (\Throwable) {
            return null;
        }
    }
}
