<?php

namespace Tests\Feature;

use Illuminate\Testing\TestResponse;
use Stripe\ApiRequestor;
use Stripe\HttpClient\ClientInterface;

/**
 * Swaps stripe-php's HTTP client for canned responses so the real SDK code
 * (request building, object hydration, idempotency headers) runs without network calls.
 */
trait FakesStripeApi
{
    /** @var array<string, array<string, mixed>|callable> */
    protected array $stripeResponses = [];

    /** @var array<int, array{method: string, path: string, params: array<string, mixed>, headers: array<int, string>}> */
    protected array $stripeRequests = [];

    protected function fakeStripe(): void
    {
        config(['services.stripe.secret' => 'sk_test_fake', 'services.stripe.webhook_secret' => 'whsec_test']);

        $test = $this;

        ApiRequestor::setHttpClient(new class($test) implements ClientInterface
        {
            public function __construct(private $test) {}

            public function request($method, $absUrl, $headers, $params, $hasFile, $apiMode = 'v1', $maxNetworkRetries = null)
            {
                return $this->test->handleFakeStripeRequest(strtoupper($method), $absUrl, $headers, $params ?? []);
            }
        });
    }

    protected function tearDownFakeStripe(): void
    {
        ApiRequestor::setHttpClient(null);
    }

    /**
     * @param  array<string, mixed>|callable  $response
     */
    protected function stripeRoute(string $method, string $path, array|callable $response): void
    {
        $this->stripeResponses[$method.' '.$path] = $response;
    }

    /**
     * @return array{0: string, 1: int, 2: array<string, string>}
     */
    public function handleFakeStripeRequest(string $method, string $absUrl, array $headers, array $params): array
    {
        $path = parse_url($absUrl, PHP_URL_PATH);
        $this->stripeRequests[] = compact('method', 'path', 'params', 'headers');

        $response = $this->stripeResponses[$method.' '.$path] ?? null;

        if ($response === null) {
            $this->fail("Unexpected Stripe request: {$method} {$path}");
        }

        $body = is_callable($response) ? $response($params) : $response;
        $status = $body['__status'] ?? 200;
        unset($body['__status']);

        return [json_encode($body), $status, []];
    }

    /**
     * @return array<int, array{method: string, path: string, params: array<string, mixed>, headers: array<int, string>}>
     */
    protected function stripeRequestsTo(string $method, string $path): array
    {
        return array_values(array_filter(
            $this->stripeRequests,
            fn (array $request): bool => $request['method'] === $method && $request['path'] === $path,
        ));
    }

    /**
     * POST a correctly signed Stripe webhook event.
     *
     * @param  array<string, mixed>  $object
     */
    protected function postStripeWebhook(string $type, array $object): TestResponse
    {
        $payload = json_encode([
            'id' => 'evt_test',
            'object' => 'event',
            'type' => $type,
            'data' => ['object' => $object],
        ]);
        $timestamp = time();
        $signature = hash_hmac('sha256', "{$timestamp}.{$payload}", 'whsec_test');

        return $this->call('POST', '/stripe/webhook', [], [], [], [
            'HTTP_STRIPE_SIGNATURE' => "t={$timestamp},v1={$signature}",
            'CONTENT_TYPE' => 'application/json',
        ], $payload);
    }

    /**
     * A Stripe subscription in the current API shape (billing period on the item).
     *
     * @return array<string, mixed>
     */
    protected function stripeSubscriptionPayload(string $id, string $status, array $overrides = []): array
    {
        return array_merge([
            'id' => $id,
            'object' => 'subscription',
            'status' => $status,
            'livemode' => false,
            'canceled_at' => null,
            'ended_at' => null,
            'items' => [
                'object' => 'list',
                'data' => [[
                    'id' => 'si_test',
                    'object' => 'subscription_item',
                    'current_period_end' => now()->addMonth()->timestamp,
                ]],
            ],
        ], $overrides);
    }
}
