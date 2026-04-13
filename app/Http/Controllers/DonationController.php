<?php

namespace App\Http\Controllers;

use App\Helpers\CmsPageData;
use App\Models\Campaign;
use App\Models\Donation;
use App\Models\EmailTemplate;
use App\Models\Page;
use App\Services\EmailService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Stripe\Exception\ApiErrorException;
use Stripe\Exception\SignatureVerificationException;
use Stripe\PaymentIntent;
use Stripe\PaymentMethod;
use Stripe\Stripe;
use Stripe\Webhook;

class DonationController extends Controller
{
    // ── Page ────────────────────────────────────────────────────────────

    public function index()
    {
        $page = Page::with(['sections' => fn($q) => $q->orderBy('sort_order')
            ->with(['contentBlocks' => fn($q) => $q->orderBy('sort_order')])])
            ->where('slug', 'donate')
            ->where('is_active', true)
            ->first();

        $cms = new CmsPageData($page);

        return view('donation', [
            'title'       => $cms->text('meta', 'title', 'Donate — Johnny Davis Global Missions'),
            'description' => $cms->text('meta', 'description', 'Donate to Johnny Davis Global Missions — Feed Filipino Children, support disaster relief, and bring hope to communities in need.'),
            'cms'         => $cms,
            'campaigns'   => Campaign::active()->get(),
            'pastorImg'   => 'https://d14tal8bchn59o.cloudfront.net/RhGkp7h3Fm5bBymv78FLEpsQSnC3q7PFpecGpojrkak/w:2000/plain/https://02f0a56ef46d93f03c90-22ac5f107621879d5667e0d7ed595bdb.ssl.cf2.rackcdn.com/sites/104216/photos/23052432/JDM_Logo_6_original.jpg',
            'stripeKey'   => config('services.stripe.key'),
        ]);
    }

    // ── Create PaymentIntent ─────────────────────────────────────────────

    public function charge(Request $request)
    {
        $validated = $request->validate([
            'campaign_name'  => 'required|string|max:255',
            'first_name'     => 'required|string|max:100',
            'last_name'      => 'required|string|max:100',
            'email'          => 'required|email|max:255',
            'amount'         => 'required|numeric|min:1|max:50000',
            'frequency'      => 'required|in:one-time,monthly',
            'payment_method' => 'required|in:card',
        ]);

        Stripe::setApiKey(config('services.stripe.secret'));

        try {
            $amountCents = (int) round($validated['amount'] * 100);

            $intent = PaymentIntent::create([
                'amount'               => $amountCents,
                'currency'             => 'usd',
                'automatic_payment_methods' => ['enabled' => true],
                'metadata'             => [
                    'campaign_name' => $validated['campaign_name'],
                    'donor_name'    => $validated['first_name'] . ' ' . $validated['last_name'],
                    'donor_email'   => $validated['email'],
                    'frequency'     => $validated['frequency'],
                ],
            ]);

            $donation = Donation::create([
                'campaign_name'  => $validated['campaign_name'],
                'first_name'     => $validated['first_name'],
                'last_name'      => $validated['last_name'],
                'email'          => $validated['email'],
                'amount'         => $validated['amount'],
                'frequency'      => $validated['frequency'],
                'payment_method' => 'card',
                'transaction_id' => $intent->id,
                'status'         => 'pending',
            ]);

            return response()->json([
                'client_secret' => $intent->client_secret,
                'donation_id'   => $donation->id,
            ]);
        } catch (ApiErrorException $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }

    // ── Confirm payment after Stripe client-side confirmation ────────────

    public function confirm(Request $request)
    {
        $request->validate([
            'payment_intent_id' => 'required|string|starts_with:pi_',
            'donation_id'       => 'required|integer|min:1',
        ]);

        Stripe::setApiKey(config('services.stripe.secret'));

        try {
            $intent   = PaymentIntent::retrieve($request->payment_intent_id);
            $donation = Donation::findOrFail($request->donation_id);

            // Security: make sure this intent belongs to this donation record
            if ($donation->transaction_id !== $intent->id) {
                return response()->json(['error' => 'Payment intent mismatch.'], 422);
            }

            if ($intent->status === 'succeeded') {
                $pm = $intent->payment_method
                    ? PaymentMethod::retrieve($intent->payment_method)
                    : null;

                $donation->update([
                    'status'         => 'completed',
                    'card_brand'     => $pm?->card?->brand,
                    'card_last_four' => $pm?->card?->last4,
                    'card_exp_month' => $pm?->card?->exp_month
                        ? str_pad((string) $pm->card->exp_month, 2, '0', STR_PAD_LEFT)
                        : null,
                    'card_exp_year'  => $pm?->card?->exp_year
                        ? (string) $pm->card->exp_year
                        : null,
                ]);

                $this->sendDonationConfirmation($donation->fresh());

                return response()->json(['success' => true]);
            }

            // Any other status (requires_action, processing, etc.)
            $donation->update(['status' => 'failed']);

            return response()->json([
                'error' => 'Payment could not be completed. Status: ' . $intent->status,
            ], 422);
        } catch (ApiErrorException $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }

    // ── PayPal: Create Order ─────────────────────────────────────────────

    public function paypalOrder(Request $request)
    {
        $validated = $request->validate([
            'campaign_name' => 'required|string|max:255',
            'first_name'    => 'required|string|max:100',
            'last_name'     => 'required|string|max:100',
            'email'         => 'required|email|max:255',
            'amount'        => 'required|numeric|min:1|max:50000',
            'frequency'     => 'required|in:one-time,monthly',
        ]);

        try {
            $token = $this->paypalAccessToken();

            $response = Http::withToken($token)
                ->withHeaders(['Prefer' => 'return=representation'])
                ->post($this->paypalApi('/v2/checkout/orders'), [
                    'intent' => 'CAPTURE',
                    'purchase_units' => [[
                        'reference_id' => uniqid('jdgm_', true),
                        'description'  => 'Donation — ' . $validated['campaign_name'],
                        'amount'       => [
                            'currency_code' => 'USD',
                            'value'         => number_format((float) $validated['amount'], 2, '.', ''),
                        ],
                    ]],
                ]);

            if (! $response->successful()) {
                return response()->json(['error' => 'PayPal order creation failed. Please try again.'], 422);
            }

            $orderID = $response->json('id');

            $donation = Donation::create([
                'campaign_name'  => $validated['campaign_name'],
                'first_name'     => $validated['first_name'],
                'last_name'      => $validated['last_name'],
                'email'          => $validated['email'],
                'amount'         => $validated['amount'],
                'frequency'      => $validated['frequency'],
                'payment_method' => 'paypal',
                'transaction_id' => $orderID,
                'status'         => 'pending',
            ]);

            return response()->json([
                'orderID'     => $orderID,
                'donation_id' => $donation->id,
            ]);
        } catch (\Throwable $e) {
            return response()->json(['error' => 'Failed to create PayPal order. Please try again.'], 422);
        }
    }

    // ── PayPal: Capture Order (after buyer approval) ─────────────────────

    public function paypalCapture(Request $request)
    {
        $request->validate([
            'order_id'    => 'required|string',
            'donation_id' => 'required|integer|min:1',
        ]);

        try {
            $token = $this->paypalAccessToken();

            $response = Http::withToken($token)
                ->withBody('{}', 'application/json')
                ->post($this->paypalApi("/v2/checkout/orders/{$request->order_id}/capture"));

            $body = $response->json();

            if (! $response->successful()) {
                $ppIssue = $body['details'][0]['issue']      ?? null;
                $ppDesc  = $body['details'][0]['description'] ?? ($body['message'] ?? 'unknown');
                Log::error('PayPal capture failed', [
                    'order_id' => $request->order_id,
                    'status'   => $response->status(),
                    'body'     => $body,
                ]);
                return response()->json([
                    'error'    => 'Payment capture failed. Please try again.',
                    'pp_issue' => $ppIssue,
                    'pp_desc'  => $ppDesc,
                ], 422);
            }

            $status = $body['status'] ?? '';

            if ($status !== 'COMPLETED') {
                return response()->json([
                    'error' => 'Payment not completed (status: ' . $status . ').',
                ], 422);
            }

            $donation = Donation::findOrFail($request->donation_id);

            if ($donation->transaction_id !== $request->order_id) {
                return response()->json(['error' => 'Order ID mismatch.'], 422);
            }

            // Use the capture ID as the final transaction reference
            $captureId = $body['purchase_units'][0]['payments']['captures'][0]['id'] ?? $request->order_id;

            $donation->update([
                'status'         => 'completed',
                'transaction_id' => $captureId,
            ]);

            $this->sendDonationConfirmation($donation->fresh());

            return response()->json(['success' => true]);
        } catch (\Throwable $e) {
            Log::error('PayPal capture exception', [
                'order_id'    => $request->order_id ?? null,
                'donation_id' => $request->donation_id ?? null,
                'message'     => $e->getMessage(),
            ]);
            return response()->json(['error' => 'Capture failed. Please contact support.'], 422);
        }
    }

    // ── PayPal helpers ───────────────────────────────────────────────────

    private function paypalApi(string $path): string
    {
        $base = config('services.paypal.mode') === 'live'
            ? 'https://api-m.paypal.com'
            : 'https://api-m.sandbox.paypal.com';

        return $base . $path;
    }

    private function paypalAccessToken(): string
    {
        $response = Http::withBasicAuth(
            config('services.paypal.client_id'),
            config('services.paypal.secret')
        )
        ->asForm()
        ->post($this->paypalApi('/v1/oauth2/token'), ['grant_type' => 'client_credentials']);

        if (! $response->successful()) {
            throw new \RuntimeException('Could not authenticate with PayPal.');
        }

        return $response->json('access_token');
    }

    // ── Stripe Webhook (async confirmations / disputes / refunds) ────────

    public function webhook(Request $request)
    {
        $payload   = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $secret    = config('services.stripe.webhook_secret');

        try {
            $event = Webhook::constructEvent($payload, $sigHeader, $secret);
        } catch (SignatureVerificationException $e) {
            return response('Webhook signature verification failed.', 400);
        }

        match ($event->type) {
            'payment_intent.succeeded' => $this->handleIntentSucceeded($event->data->object),
            'payment_intent.payment_failed' => $this->handleIntentFailed($event->data->object),
            default => null,
        };

        return response()->json(['received' => true]);
    }

    // ── Webhook helpers ──────────────────────────────────────────────────

    private function handleIntentSucceeded(object $intent): void
    {
        $donation = Donation::where('transaction_id', $intent->id)
            ->where('status', 'pending')
            ->first();

        if ($donation) {
            $donation->update(['status' => 'completed']);
            $this->sendDonationConfirmation($donation);
        }
    }

    private function handleIntentFailed(object $intent): void
    {
        Donation::where('transaction_id', $intent->id)
            ->whereIn('status', ['pending'])
            ->update(['status' => 'failed']);
    }

    private function sendDonationConfirmation(Donation $donation): void
    {
        $template = EmailTemplate::where('name', 'Donation Confirmation')
            ->where('is_active', true)
            ->first();

        if (! $template) {
            return;
        }

        app(EmailService::class)->sendTemplate(
            template: $template,
            toEmail:  $donation->email,
            toName:   $donation->full_name,
            data: [
                'donor_name'      => $donation->full_name,
                'donation_amount' => '$' . number_format($donation->amount, 2),
                'donation_date'   => $donation->created_at->format('F j, Y'),
                'transaction_id'  => $donation->transaction_id ?? '—',
            ],
        );
    }
}
