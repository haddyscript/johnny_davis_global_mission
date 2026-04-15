<?php

namespace App\Http\Controllers;

use App\Helpers\CmsPageData;
use App\Models\Campaign;
use App\Models\Donation;
use App\Models\EmailTemplate;
use App\Models\NewsletterSubscriber;
use App\Models\Page;
use App\Services\EmailService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Stripe\Customer;
use Stripe\Exception\ApiErrorException;
use Stripe\Exception\SignatureVerificationException;
use Stripe\PaymentIntent;
use Stripe\PaymentMethod;
use Stripe\Price;
use Stripe\SetupIntent;
use Stripe\Stripe;
use Stripe\Subscription;
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

        $campaigns = Campaign::active()->get();

        // One query: sum completed donations grouped by campaign name
        $donationTotals = Donation::where('status', 'completed')
            ->selectRaw('campaign_name, SUM(amount) AS total')
            ->groupBy('campaign_name')
            ->pluck('total', 'campaign_name');

        // Attach live raised/pct to each campaign without touching the DB row
        $campaigns->each(function ($c) use ($donationTotals) {
            $raised            = (float) ($donationTotals[$c->title] ?? 0);
            $goalNumeric       = (float) preg_replace('/[^0-9.]/', '', $c->goal_amount ?? '0');
            $c->live_raised    = $raised;
            $c->live_pct       = $goalNumeric > 0
                ? min(100, (int) round($raised / $goalNumeric * 100))
                : 0;
            $c->live_raised_fmt = '$' . number_format($raised, 0);
        });

        return view('donation', [
            'title'       => $cms->text('meta', 'title', 'Donate — Johnny Davis Global Missions'),
            'description' => $cms->text('meta', 'description', 'Donate to Johnny Davis Global Missions — Feed Filipino Children, support disaster relief, and bring hope to communities in need.'),
            'cms'         => $cms,
            'campaigns'   => $campaigns,
            'pastorImg'   => 'https://d14tal8bchn59o.cloudfront.net/RhGkp7h3Fm5bBymv78FLEpsQSnC3q7PFpecGpojrkak/w:2000/plain/https://02f0a56ef46d93f03c90-22ac5f107621879d5667e0d7ed595bdb.ssl.cf2.rackcdn.com/sites/104216/photos/23052432/JDM_Logo_6_original.jpg',
            'stripeKey'   => config('services.stripe.key'),
        ]);
    }

    // ── Stripe: Create PaymentIntent (one-time) or SetupIntent (monthly) ─

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
            return $validated['frequency'] === 'monthly'
                ? $this->createStripeSubscriptionSetup($validated)
                : $this->createStripePaymentIntent($validated);
        } catch (ApiErrorException $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }

    private function createStripePaymentIntent(array $data): \Illuminate\Http\JsonResponse
    {
        $intent = PaymentIntent::create([
            'amount'                    => (int) round($data['amount'] * 100),
            'currency'                  => 'usd',
            'automatic_payment_methods' => ['enabled' => true],
            'metadata'                  => [
                'campaign_name' => $data['campaign_name'],
                'donor_name'    => $data['first_name'] . ' ' . $data['last_name'],
                'donor_email'   => $data['email'],
                'frequency'     => 'one-time',
            ],
        ]);

        $donation = Donation::create([
            'campaign_name'  => $data['campaign_name'],
            'first_name'     => $data['first_name'],
            'last_name'      => $data['last_name'],
            'email'          => $data['email'],
            'amount'         => $data['amount'],
            'frequency'      => 'one-time',
            'payment_method' => 'card',
            'transaction_id' => $intent->id,
            'status'         => 'pending',
        ]);

        return response()->json([
            'client_secret' => $intent->client_secret,
            'donation_id'   => $donation->id,
            'flow'          => 'payment',
        ]);
    }

    private function createStripeSubscriptionSetup(array $data): \Illuminate\Http\JsonResponse
    {
        // Create a Stripe Customer to associate with this subscription
        $customer = Customer::create([
            'email'    => $data['email'],
            'name'     => $data['first_name'] . ' ' . $data['last_name'],
            'metadata' => ['campaign_name' => $data['campaign_name']],
        ]);

        // SetupIntent saves the card for off-session recurring billing
        $setupIntent = SetupIntent::create([
            'customer' => $customer->id,
            'usage'    => 'off_session',
            'metadata' => [
                'campaign_name' => $data['campaign_name'],
                'donor_email'   => $data['email'],
            ],
        ]);

        $donation = Donation::create([
            'campaign_name'      => $data['campaign_name'],
            'first_name'         => $data['first_name'],
            'last_name'          => $data['last_name'],
            'email'              => $data['email'],
            'amount'             => $data['amount'],
            'frequency'          => 'monthly',
            'payment_method'     => 'card',
            'stripe_customer_id' => $customer->id,
            'status'             => 'pending',
        ]);

        return response()->json([
            'client_secret' => $setupIntent->client_secret,
            'donation_id'   => $donation->id,
            'flow'          => 'setup', // tells the frontend to use confirmCardSetup
        ]);
    }

    // ── Stripe: Confirm payment or subscription ──────────────────────────

    public function confirm(Request $request)
    {
        return $request->has('setup_intent_id')
            ? $this->confirmStripeSubscription($request)
            : $this->confirmStripePayment($request);
    }

    private function confirmStripePayment(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'payment_intent_id' => 'required|string|starts_with:pi_',
            'donation_id'       => 'required|integer|min:1',
        ]);

        Stripe::setApiKey(config('services.stripe.secret'));

        try {
            $intent   = PaymentIntent::retrieve($request->payment_intent_id);
            $donation = Donation::findOrFail($request->donation_id);

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

            $donation->update(['status' => 'failed']);

            return response()->json([
                'error' => 'Payment could not be completed. Status: ' . $intent->status,
            ], 422);
        } catch (ApiErrorException $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }

    private function confirmStripeSubscription(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'setup_intent_id' => 'required|string|starts_with:seti_',
            'donation_id'     => 'required|integer|min:1',
        ]);

        Stripe::setApiKey(config('services.stripe.secret'));

        try {
            $setupIntent = SetupIntent::retrieve($request->setup_intent_id);
            $donation    = Donation::findOrFail($request->donation_id);

            if ($setupIntent->status !== 'succeeded') {
                return response()->json([
                    'error' => 'Card setup did not succeed. Status: ' . $setupIntent->status,
                ], 422);
            }

            // Create a dynamic Price for this campaign/amount (not reused intentionally
            // so each subscription maps to a descriptive product in Stripe dashboard)
            $price = Price::create([
                'currency'     => 'usd',
                'unit_amount'  => (int) round($donation->amount * 100),
                'recurring'    => ['interval' => 'month'],
                'product_data' => [
                    'name' => 'Monthly Donation — ' . $donation->campaign_name,
                ],
            ]);

            // Create the subscription, expanding the first invoice's payment intent
            // so we can detect if 3D Secure authentication is required
            $subscription = Subscription::create([
                'customer'               => $donation->stripe_customer_id,
                'items'                  => [['price' => $price->id]],
                'default_payment_method' => $setupIntent->payment_method,
                'expand'                 => ['latest_invoice.payment_intent'],
                'metadata'               => [
                    'campaign_name' => $donation->campaign_name,
                    'donor_email'   => $donation->email,
                    'donation_id'   => $donation->id,
                ],
            ]);

            // Capture card metadata from the setup intent's payment method
            $pm = PaymentMethod::retrieve($setupIntent->payment_method);

            $donation->update([
                'subscription_id'     => $subscription->id,
                'subscription_status' => $subscription->status,
                'next_billing_date'   => Carbon::createFromTimestamp($subscription->current_period_end),
                'transaction_id'      => $subscription->latest_invoice->id ?? null,
                'card_brand'          => $pm?->card?->brand,
                'card_last_four'      => $pm?->card?->last4,
                'card_exp_month'      => $pm?->card?->exp_month
                    ? str_pad((string) $pm->card->exp_month, 2, '0', STR_PAD_LEFT)
                    : null,
                'card_exp_year'       => $pm?->card?->exp_year
                    ? (string) $pm->card->exp_year
                    : null,
            ]);

            // If the first invoice requires 3DS authentication, return the client_secret
            // so the frontend can prompt the user before marking as complete
            $firstInvoicePaymentIntent = $subscription->latest_invoice->payment_intent ?? null;

            if ($firstInvoicePaymentIntent && $firstInvoicePaymentIntent->status === 'requires_action') {
                return response()->json([
                    'requires_action' => true,
                    'client_secret'   => $firstInvoicePaymentIntent->client_secret,
                    'donation_id'     => $donation->id,
                ]);
            }

            if (in_array($subscription->status, ['active', 'trialing'])) {
                $donation->update(['status' => 'completed']);
                $this->sendDonationConfirmation($donation->fresh());
            }

            return response()->json(['success' => true]);

        } catch (ApiErrorException $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }

    // ── PayPal: Create Order (one-time) ─────────────────────────────────

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
                    'intent'         => 'CAPTURE',
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

    // ── PayPal: Capture Order (one-time) ────────────────────────────────

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
                $ppIssue = $body['details'][0]['issue']       ?? null;
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

    // ── PayPal: Create Subscription Plan + Donation Record (monthly) ─────

    public function paypalCreateSubscription(Request $request)
    {
        $validated = $request->validate([
            'campaign_name' => 'required|string|max:255',
            'first_name'    => 'required|string|max:100',
            'last_name'     => 'required|string|max:100',
            'email'         => 'required|email|max:255',
            'amount'        => 'required|numeric|min:1|max:50000',
        ]);

        try {
            $token  = $this->paypalAccessToken();
            $amount = number_format((float) $validated['amount'], 2, '.', '');

            // Step 1 — Create a PayPal product (catalog entry for this campaign)
            $productRes = Http::withToken($token)
                ->post($this->paypalApi('/v1/catalogs/products'), [
                    'name'     => 'Monthly Donation — ' . $validated['campaign_name'],
                    'type'     => 'SERVICE',
                    'category' => 'CHARITY',
                ]);

            if (! $productRes->successful()) {
                Log::error('PayPal product creation failed', ['body' => $productRes->json()]);
                throw new \RuntimeException('PayPal product creation failed.');
            }

            $productId = $productRes->json('id');

            // Step 2 — Create a billing plan tied to that product
            $planRes = Http::withToken($token)
                ->post($this->paypalApi('/v1/billing/plans'), [
                    'product_id'          => $productId,
                    'name'                => 'Monthly Donation $' . $amount . ' — ' . $validated['campaign_name'],
                    'status'              => 'ACTIVE',
                    'billing_cycles'      => [[
                        'frequency'      => ['interval_unit' => 'MONTH', 'interval_count' => 1],
                        'tenure_type'    => 'REGULAR',
                        'sequence'       => 1,
                        'total_cycles'   => 0, // 0 = infinite
                        'pricing_scheme' => [
                            'fixed_price' => ['value' => $amount, 'currency_code' => 'USD'],
                        ],
                    ]],
                    'payment_preferences' => [
                        'auto_bill_outstanding'     => true,
                        'setup_fee_failure_action'  => 'CONTINUE',
                        'payment_failure_threshold' => 3,
                    ],
                ]);

            if (! $planRes->successful()) {
                Log::error('PayPal plan creation failed', ['body' => $planRes->json()]);
                throw new \RuntimeException('PayPal plan creation failed.');
            }

            $planId = $planRes->json('id');

            // Step 3 — Create a pending donation record
            $donation = Donation::create([
                'campaign_name'  => $validated['campaign_name'],
                'first_name'     => $validated['first_name'],
                'last_name'      => $validated['last_name'],
                'email'          => $validated['email'],
                'amount'         => $validated['amount'],
                'frequency'      => 'monthly',
                'payment_method' => 'paypal',
                'status'         => 'pending',
            ]);

            return response()->json([
                'plan_id'     => $planId,
                'donation_id' => $donation->id,
            ]);

        } catch (\Throwable $e) {
            Log::error('PayPal subscription creation failed', ['message' => $e->getMessage()]);
            return response()->json([
                'error' => 'Failed to create PayPal subscription. Please try again.',
            ], 422);
        }
    }

    // ── PayPal: Confirm Subscription after buyer approval (monthly) ──────

    public function paypalConfirmSubscription(Request $request)
    {
        $request->validate([
            'subscription_id' => 'required|string',
            'donation_id'     => 'required|integer|min:1',
        ]);

        try {
            $token = $this->paypalAccessToken();

            $subRes = Http::withToken($token)
                ->get($this->paypalApi("/v1/billing/subscriptions/{$request->subscription_id}"));

            if (! $subRes->successful()) {
                Log::error('PayPal subscription retrieval failed', [
                    'subscription_id' => $request->subscription_id,
                    'body'            => $subRes->json(),
                ]);
                return response()->json(['error' => 'Could not verify PayPal subscription.'], 422);
            }

            $sub    = $subRes->json();
            $status = $sub['status'] ?? '';

            $donation = Donation::findOrFail($request->donation_id);

            $nextBillingTime = $sub['billing_info']['next_billing_time'] ?? null;

            $donation->update([
                'transaction_id'      => $sub['id'],
                'subscription_id'     => $sub['id'],
                'subscription_status' => strtolower($status),
                'next_billing_date'   => $nextBillingTime ? Carbon::parse($nextBillingTime) : null,
                'status'              => in_array($status, ['ACTIVE', 'APPROVED']) ? 'completed' : 'pending',
            ]);

            if ($donation->fresh()->status === 'completed') {
                $this->sendDonationConfirmation($donation->fresh());
            }

            return response()->json(['success' => true]);

        } catch (\Throwable $e) {
            Log::error('PayPal subscription confirm exception', [
                'subscription_id' => $request->subscription_id ?? null,
                'donation_id'     => $request->donation_id ?? null,
                'message'         => $e->getMessage(),
            ]);
            return response()->json([
                'error' => 'Could not confirm subscription. Please contact support.',
            ], 422);
        }
    }

    // ── PayPal Webhook ───────────────────────────────────────────────────

    public function paypalWebhook(Request $request)
    {
        $eventType = $request->json('event_type');
        $resource  = $request->json('resource') ?? [];

        Log::info('PayPal webhook received', ['event_type' => $eventType]);

        match ($eventType) {
            'BILLING.SUBSCRIPTION.ACTIVATED'      => $this->handlePaypalSubscriptionActivated($resource),
            'BILLING.SUBSCRIPTION.CANCELLED'      => $this->handlePaypalSubscriptionCancelled($resource),
            'BILLING.SUBSCRIPTION.SUSPENDED'      => $this->handlePaypalSubscriptionCancelled($resource),
            'BILLING.SUBSCRIPTION.PAYMENT.FAILED' => $this->handlePaypalPaymentFailed($resource),
            'PAYMENT.SALE.COMPLETED'              => $this->handlePaypalSaleCompleted($resource),
            default                               => null,
        };

        return response()->json(['received' => true]);
    }

    private function handlePaypalSubscriptionActivated(array $resource): void
    {
        $subscriptionId  = $resource['id'] ?? null;
        $nextBillingTime = $resource['billing_info']['next_billing_time'] ?? null;

        if (! $subscriptionId) return;

        Donation::where('subscription_id', $subscriptionId)->update([
            'subscription_status' => 'active',
            'status'              => 'completed',
            'next_billing_date'   => $nextBillingTime ? Carbon::parse($nextBillingTime) : null,
        ]);
    }

    private function handlePaypalSubscriptionCancelled(array $resource): void
    {
        $subscriptionId = $resource['id'] ?? null;
        if (! $subscriptionId) return;

        Donation::where('subscription_id', $subscriptionId)
            ->update(['subscription_status' => 'cancelled']);
    }

    private function handlePaypalPaymentFailed(array $resource): void
    {
        $subscriptionId = $resource['id'] ?? null;
        if (! $subscriptionId) return;

        Donation::where('subscription_id', $subscriptionId)
            ->update(['subscription_status' => 'past_due']);
    }

    private function handlePaypalSaleCompleted(array $resource): void
    {
        // Fired for each successful recurring charge
        $billingAgreementId = $resource['billing_agreement_id'] ?? null;
        if (! $billingAgreementId) return;

        Donation::where('subscription_id', $billingAgreementId)->update([
            'subscription_status' => 'active',
            'status'              => 'completed',
        ]);
    }

    // ── Stripe Webhook ───────────────────────────────────────────────────

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
            // One-time payment events
            'payment_intent.succeeded'       => $this->handleIntentSucceeded($event->data->object),
            'payment_intent.payment_failed'  => $this->handleIntentFailed($event->data->object),
            // Subscription billing events
            'invoice.payment_succeeded'      => $this->handleInvoicePaymentSucceeded($event->data->object),
            'invoice.payment_failed'         => $this->handleInvoicePaymentFailed($event->data->object),
            // Subscription lifecycle events
            'customer.subscription.updated'  => $this->handleSubscriptionUpdated($event->data->object),
            'customer.subscription.deleted'  => $this->handleSubscriptionDeleted($event->data->object),
            default                          => null,
        };

        return response()->json(['received' => true]);
    }

    // ── Stripe Webhook handlers ──────────────────────────────────────────

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
            ->where('status', 'pending')
            ->update(['status' => 'failed']);
    }

    private function handleInvoicePaymentSucceeded(object $invoice): void
    {
        $subscriptionId = $invoice->subscription ?? null;
        if (! $subscriptionId) return;

        Stripe::setApiKey(config('services.stripe.secret'));

        try {
            $sub = Subscription::retrieve($subscriptionId);

            Donation::where('subscription_id', $subscriptionId)->update([
                'subscription_status' => $sub->status,
                'next_billing_date'   => Carbon::createFromTimestamp($sub->current_period_end),
                'status'              => 'completed',
            ]);
        } catch (ApiErrorException $e) {
            Log::error('Stripe invoice.payment_succeeded handling failed', [
                'subscription_id' => $subscriptionId,
                'message'         => $e->getMessage(),
            ]);
        }
    }

    private function handleInvoicePaymentFailed(object $invoice): void
    {
        $subscriptionId = $invoice->subscription ?? null;
        if (! $subscriptionId) return;

        Donation::where('subscription_id', $subscriptionId)
            ->update(['subscription_status' => 'past_due']);
    }

    private function handleSubscriptionUpdated(object $subscription): void
    {
        Donation::where('subscription_id', $subscription->id)->update([
            'subscription_status' => $subscription->status,
            'next_billing_date'   => Carbon::createFromTimestamp($subscription->current_period_end),
        ]);
    }

    private function handleSubscriptionDeleted(object $subscription): void
    {
        Donation::where('subscription_id', $subscription->id)
            ->update(['subscription_status' => 'cancelled']);
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

    // ── Email helper ─────────────────────────────────────────────────────

    private function sendDonationConfirmation(Donation $donation): void
    {
        // Auto-add the donor to the newsletter subscribers list
        NewsletterSubscriber::syncDonor(
            email:     $donation->email,
            firstName: $donation->first_name,
            frequency: $donation->frequency,
        );

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
