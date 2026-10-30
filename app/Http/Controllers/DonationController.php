<?php

namespace App\Http\Controllers;

use App\Helpers\CmsPageData;
use App\Http\Middleware\CaptureDonationSource;
use App\Models\Campaign;
use App\Models\Donation;
use App\Models\Page;
use App\Services\DonationReceipt;
use App\Services\HungerCampaignTracker;
use App\Services\StripeSubscriptionSync;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
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
        $page = Page::with(['sections' => fn ($q) => $q->orderBy('sort_order')
            ->with(['contentBlocks' => fn ($q) => $q->orderBy('sort_order')])])
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
            $raised = (float) ($donationTotals[$c->title] ?? 0);
            $goalNumeric = (float) preg_replace('/[^0-9.]/', '', $c->goal_amount ?? '0');
            $c->live_raised = $raised;
            $c->live_pct = $goalNumeric > 0
                ? min(100, (int) round($raised / $goalNumeric * 100))
                : 0;
            $c->live_raised_fmt = '$'.number_format($raised, 0);
        });

        return view('donation', [
            'title' => $cms->text('meta', 'title', 'Donate — Johnny Davis Global Missions'),
            'description' => $cms->text('meta', 'description', 'Donate to Johnny Davis Global Missions — Feed Filipino Children, support disaster relief, and bring hope to communities in need.'),
            'cms' => $cms,
            'campaigns' => $campaigns,
            'pastorImg' => asset('images/who-we-are/Johnny-Davis.webp'),
            'stripeKey' => config('services.stripe.key'),
            'hungerProgress' => app(HungerCampaignTracker::class)->progress(),
            'manageGiftUrl' => config('services.stripe.portal_url'),
        ]);
    }

    // ── Hunger Can't Wait: live partner count for the banner tracker ────

    public function hungerCampaignProgress(HungerCampaignTracker $tracker): JsonResponse
    {
        $progress = $tracker->progress();

        if ($progress === null) {
            return response()->json(['available' => false], 503);
        }

        return response()
            ->json(['available' => true] + $progress)
            ->header('Cache-Control', 'public, max-age=60');
    }

    // ── Stripe: Create PaymentIntent (one-time) or SetupIntent (monthly) ─

    public function charge(Request $request)
    {
        $validated = $request->validate([
            'campaign_name' => 'required|string|max:255',
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => 'required|email|max:255',
            'amount' => 'required|numeric|min:1|max:50000',
            'frequency' => 'required|in:one-time,monthly',
            'payment_method' => 'required|in:card',
        ]);

        $validated['source'] = $request->session()->get(CaptureDonationSource::SESSION_KEY, 'website');

        Stripe::setApiKey(config('services.stripe.secret'));

        try {
            return $validated['frequency'] === 'monthly'
                ? $this->createStripeSubscriptionSetup($validated)
                : $this->createStripePaymentIntent($validated);
        } catch (ApiErrorException $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }

    private function createStripePaymentIntent(array $data): JsonResponse
    {
        $intent = PaymentIntent::create([
            'amount' => (int) round($data['amount'] * 100),
            'currency' => 'usd',
            'automatic_payment_methods' => ['enabled' => true],
            'metadata' => [
                'campaign_name' => $data['campaign_name'],
                'donor_name' => $data['first_name'].' '.$data['last_name'],
                'donor_email' => $data['email'],
                'frequency' => 'one-time',
                'source' => $data['source'],
            ],
        ]);

        $donation = Donation::create([
            'campaign_name' => $data['campaign_name'],
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'amount' => $data['amount'],
            'frequency' => 'one-time',
            'payment_method' => 'card',
            'transaction_id' => $intent->id,
            'status' => 'pending',
            'livemode' => (bool) $intent->livemode,
            'source' => $data['source'],
        ]);

        return response()->json([
            'client_secret' => $intent->client_secret,
            'donation_id' => $donation->id,
            'flow' => 'payment',
        ]);
    }

    private function createStripeSubscriptionSetup(array $data): JsonResponse
    {
        // Create a Stripe Customer to associate with this subscription
        $customer = Customer::create([
            'email' => $data['email'],
            'name' => $data['first_name'].' '.$data['last_name'],
            'metadata' => ['campaign_name' => $data['campaign_name'], 'source' => $data['source']],
        ]);

        // SetupIntent saves the card for off-session recurring billing
        $setupIntent = SetupIntent::create([
            'customer' => $customer->id,
            'usage' => 'off_session',
            'metadata' => [
                'campaign_name' => $data['campaign_name'],
                'donor_email' => $data['email'],
            ],
        ]);

        $donation = Donation::create([
            'campaign_name' => $data['campaign_name'],
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'amount' => $data['amount'],
            'frequency' => 'monthly',
            'payment_method' => 'card',
            'stripe_customer_id' => $customer->id,
            'status' => 'pending',
            'livemode' => (bool) $customer->livemode,
            'source' => $data['source'],
        ]);

        return response()->json([
            'client_secret' => $setupIntent->client_secret,
            'donation_id' => $donation->id,
            'flow' => 'setup', // tells the frontend to use confirmCardSetup
        ]);
    }

    // ── Stripe: Confirm payment or subscription ──────────────────────────

    public function confirm(Request $request)
    {
        return $request->has('setup_intent_id')
            ? $this->confirmStripeSubscription($request)
            : $this->confirmStripePayment($request);
    }

    private function confirmStripePayment(Request $request): JsonResponse
    {
        $request->validate([
            'payment_intent_id' => 'required|string|starts_with:pi_',
            'donation_id' => 'required|integer|min:1',
        ]);

        Stripe::setApiKey(config('services.stripe.secret'));

        try {
            $intent = PaymentIntent::retrieve($request->payment_intent_id);
            $donation = Donation::findOrFail($request->donation_id);

            if ($donation->transaction_id !== $intent->id) {
                return response()->json(['error' => 'Payment intent mismatch.'], 422);
            }

            if ($intent->status === 'succeeded') {
                $pm = $intent->payment_method
                    ? PaymentMethod::retrieve($intent->payment_method)
                    : null;

                $donation->update([
                    'status' => 'completed',
                    'card_brand' => $pm?->card?->brand,
                    'card_last_four' => $pm?->card?->last4,
                    'card_exp_month' => $pm?->card?->exp_month
                        ? str_pad((string) $pm->card->exp_month, 2, '0', STR_PAD_LEFT)
                        : null,
                    'card_exp_year' => $pm?->card?->exp_year
                        ? (string) $pm->card->exp_year
                        : null,
                ]);

                $this->sendDonationConfirmation($donation->fresh());

                return response()->json(['success' => true]);
            }

            $donation->update(['status' => 'failed']);

            return response()->json([
                'error' => 'Payment could not be completed. Status: '.$intent->status,
            ], 422);
        } catch (ApiErrorException $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }

    private function confirmStripeSubscription(Request $request): JsonResponse
    {
        $request->validate([
            'setup_intent_id' => 'required|string|starts_with:seti_',
            'donation_id' => 'required|integer|min:1',
        ]);

        Stripe::setApiKey(config('services.stripe.secret'));

        try {
            $setupIntent = SetupIntent::retrieve($request->setup_intent_id);
            $donation = Donation::findOrFail($request->donation_id);

            if (! $donation->stripe_customer_id || $setupIntent->customer !== $donation->stripe_customer_id) {
                return response()->json(['error' => 'Subscription mismatch.'], 422);
            }

            // Repeat call for the same checkout (double click, network retry, or after
            // 3-D Secure): report the existing subscription instead of billing twice.
            if ($donation->subscription_id) {
                $subscription = Subscription::retrieve([
                    'id' => $donation->subscription_id,
                    'expand' => ['latest_invoice.confirmation_secret'],
                ]);

                return $this->subscriptionCheckoutResponse($donation, $subscription);
            }

            if ($setupIntent->status !== 'succeeded') {
                return response()->json([
                    'error' => 'Card setup did not succeed. Status: '.$setupIntent->status,
                ], 422);
            }

            // The idempotency key makes Stripe return the same subscription if this
            // request is ever sent twice, so a donor can never be subscribed twice.
            $subscription = Subscription::create([
                'customer' => $donation->stripe_customer_id,
                'items' => [['price' => $this->monthlyPriceId($donation)]],
                'default_payment_method' => $setupIntent->payment_method,
                'expand' => ['latest_invoice.confirmation_secret'],
                'metadata' => [
                    'campaign_name' => $donation->campaign_name,
                    'donor_email' => $donation->email,
                    'donation_id' => $donation->id,
                    'source' => $donation->source ?? 'website',
                ],
            ], ['idempotency_key' => 'jdgm-donation-subscription-'.$donation->id]);

            // Capture card metadata from the setup intent's payment method
            $pm = PaymentMethod::retrieve($setupIntent->payment_method);

            $donation->update([
                'transaction_id' => $subscription->latest_invoice->id ?? null,
                'card_brand' => $pm?->card?->brand,
                'card_last_four' => $pm?->card?->last4,
                'card_exp_month' => $pm?->card?->exp_month
                    ? str_pad((string) $pm->card->exp_month, 2, '0', STR_PAD_LEFT)
                    : null,
                'card_exp_year' => $pm?->card?->exp_year
                    ? (string) $pm->card->exp_year
                    : null,
            ]);

            return $this->subscriptionCheckoutResponse($donation, $subscription);

        } catch (ApiErrorException $e) {
            Log::warning('Stripe monthly checkout failed', [
                'donation_id' => $request->donation_id,
                'message' => $e->getMessage(),
            ]);

            return response()->json(['error' => $e->getMessage()], 422);
        }
    }

    /**
     * Tell the browser what happened to the first monthly charge:
     * active → thank-you, needs 3-D Secure → prompt the donor, declined → clear error.
     */
    private function subscriptionCheckoutResponse(Donation $donation, Subscription $subscription): JsonResponse
    {
        $sync = app(StripeSubscriptionSync::class);
        $donation->update($sync->attributesFor($subscription, $donation));
        HungerCampaignTracker::forget();

        if (in_array($subscription->status, ['active', 'trialing'], true)) {
            $sync->markCompleted($donation);

            return response()->json(['success' => true]);
        }

        if ($subscription->status === 'incomplete') {
            $intent = $this->firstInvoicePaymentIntent($subscription);

            if ($intent && in_array($intent->status, ['requires_action', 'requires_confirmation'], true)) {
                return response()->json([
                    'requires_action' => true,
                    'client_secret' => $intent->client_secret,
                    'donation_id' => $donation->id,
                ]);
            }

            // Paid (or still processing) — the webhook activates it moments later
            if ($intent && in_array($intent->status, ['succeeded', 'processing'], true)) {
                return response()->json(['success' => true]);
            }

            // First payment declined: cancel so nothing retries the card later
            $message = $intent?->last_payment_error?->message ?? 'Your card was declined.';
            $canceled = $subscription->cancel();
            $donation->update(array_merge(
                $sync->attributesFor($canceled, $donation),
                ['status' => 'failed'],
            ));

            return response()->json([
                'error' => $message.' No monthly gift was started and you were not charged. Please try another card.',
            ], 422);
        }

        $donation->update(['status' => 'failed']);

        return response()->json([
            'error' => 'We could not start your monthly gift (status: '.$subscription->status.'). You were not charged. Please try again.',
        ], 422);
    }

    /**
     * The PaymentIntent behind a subscription's first invoice, if any.
     */
    private function firstInvoicePaymentIntent(Subscription $subscription): ?PaymentIntent
    {
        $clientSecret = $subscription->latest_invoice->confirmation_secret->client_secret ?? null;

        if (! $clientSecret) {
            return null;
        }

        return PaymentIntent::retrieve(Str::before($clientSecret, '_secret_'));
    }

    /**
     * One reusable Stripe Price per campaign + monthly amount (e.g. $7.99 Feed Filipino Children),
     * so the Stripe dashboard groups every partner under the same product.
     */
    private function monthlyPriceId(Donation $donation): string
    {
        $cents = (int) round($donation->amount * 100);
        $lookupKey = 'jdgm_monthly_'.Str::slug($donation->campaign_name, '_').'_'.$cents;

        $existing = Price::all(['lookup_keys' => [$lookupKey], 'active' => true, 'limit' => 1]);

        if (! empty($existing->data)) {
            return $existing->data[0]->id;
        }

        return Price::create([
            'currency' => 'usd',
            'unit_amount' => $cents,
            'recurring' => ['interval' => 'month'],
            'lookup_key' => $lookupKey,
            'transfer_lookup_key' => true,
            'product_data' => [
                'name' => 'Monthly Donation — '.$donation->campaign_name,
            ],
        ])->id;
    }

    // ── PayPal: Create Order (one-time) ─────────────────────────────────

    public function paypalOrder(Request $request)
    {
        $validated = $request->validate([
            'campaign_name' => 'required|string|max:255',
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => 'required|email|max:255',
            'amount' => 'required|numeric|min:1|max:50000',
            'frequency' => 'required|in:one-time,monthly',
        ]);

        try {
            $token = $this->paypalAccessToken();

            $response = Http::withToken($token)
                ->withHeaders(['Prefer' => 'return=representation'])
                ->post($this->paypalApi('/v2/checkout/orders'), [
                    'intent' => 'CAPTURE',
                    'purchase_units' => [[
                        'reference_id' => uniqid('jdgm_', true),
                        'description' => 'Donation — '.$validated['campaign_name'],
                        'amount' => [
                            'currency_code' => 'USD',
                            'value' => number_format((float) $validated['amount'], 2, '.', ''),
                        ],
                    ]],
                ]);

            if (! $response->successful()) {
                return response()->json(['error' => 'PayPal order creation failed. Please try again.'], 422);
            }

            $orderID = $response->json('id');

            $donation = Donation::create([
                'campaign_name' => $validated['campaign_name'],
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'email' => $validated['email'],
                'amount' => $validated['amount'],
                'frequency' => $validated['frequency'],
                'payment_method' => 'paypal',
                'transaction_id' => $orderID,
                'status' => 'pending',
                'source' => $request->session()->get(CaptureDonationSource::SESSION_KEY, 'website'),
            ]);

            return response()->json([
                'orderID' => $orderID,
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
            'order_id' => 'required|string',
            'donation_id' => 'required|integer|min:1',
        ]);

        try {
            $token = $this->paypalAccessToken();

            $response = Http::withToken($token)
                ->withBody('{}', 'application/json')
                ->post($this->paypalApi("/v2/checkout/orders/{$request->order_id}/capture"));

            $body = $response->json();

            if (! $response->successful()) {
                $ppIssue = $body['details'][0]['issue'] ?? null;
                $ppDesc = $body['details'][0]['description'] ?? ($body['message'] ?? 'unknown');
                Log::error('PayPal capture failed', [
                    'order_id' => $request->order_id,
                    'status' => $response->status(),
                    'body' => $body,
                ]);

                return response()->json([
                    'error' => 'Payment capture failed. Please try again.',
                    'pp_issue' => $ppIssue,
                    'pp_desc' => $ppDesc,
                ], 422);
            }

            $status = $body['status'] ?? '';

            if ($status !== 'COMPLETED') {
                return response()->json([
                    'error' => 'Payment not completed (status: '.$status.').',
                ], 422);
            }

            $donation = Donation::findOrFail($request->donation_id);

            if ($donation->transaction_id !== $request->order_id) {
                return response()->json(['error' => 'Order ID mismatch.'], 422);
            }

            $captureId = $body['purchase_units'][0]['payments']['captures'][0]['id'] ?? $request->order_id;

            $donation->update([
                'status' => 'completed',
                'transaction_id' => $captureId,
            ]);

            $this->sendDonationConfirmation($donation->fresh());

            return response()->json(['success' => true]);
        } catch (\Throwable $e) {
            Log::error('PayPal capture exception', [
                'order_id' => $request->order_id ?? null,
                'donation_id' => $request->donation_id ?? null,
                'message' => $e->getMessage(),
            ]);

            return response()->json(['error' => 'Capture failed. Please contact support.'], 422);
        }
    }

    // ── PayPal: Create Subscription Plan + Donation Record (monthly) ─────

    public function paypalCreateSubscription(Request $request)
    {
        $validated = $request->validate([
            'campaign_name' => 'required|string|max:255',
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => 'required|email|max:255',
            'amount' => 'required|numeric|min:1|max:50000',
        ]);

        try {
            $token = $this->paypalAccessToken();
            $amount = number_format((float) $validated['amount'], 2, '.', '');

            // Step 1 — Create a PayPal product (catalog entry for this campaign)
            $productRes = Http::withToken($token)
                ->post($this->paypalApi('/v1/catalogs/products'), [
                    'name' => 'Monthly Donation — '.$validated['campaign_name'],
                    'type' => 'SERVICE',
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
                    'product_id' => $productId,
                    'name' => 'Monthly Donation $'.$amount.' — '.$validated['campaign_name'],
                    'status' => 'ACTIVE',
                    'billing_cycles' => [[
                        'frequency' => ['interval_unit' => 'MONTH', 'interval_count' => 1],
                        'tenure_type' => 'REGULAR',
                        'sequence' => 1,
                        'total_cycles' => 0, // 0 = infinite
                        'pricing_scheme' => [
                            'fixed_price' => ['value' => $amount, 'currency_code' => 'USD'],
                        ],
                    ]],
                    'payment_preferences' => [
                        'auto_bill_outstanding' => true,
                        'setup_fee_failure_action' => 'CONTINUE',
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
                'campaign_name' => $validated['campaign_name'],
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'email' => $validated['email'],
                'amount' => $validated['amount'],
                'frequency' => 'monthly',
                'payment_method' => 'paypal',
                'status' => 'pending',
                'source' => $request->session()->get(CaptureDonationSource::SESSION_KEY, 'website'),
            ]);

            return response()->json([
                'plan_id' => $planId,
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
            'donation_id' => 'required|integer|min:1',
        ]);

        try {
            $token = $this->paypalAccessToken();

            $subRes = Http::withToken($token)
                ->get($this->paypalApi("/v1/billing/subscriptions/{$request->subscription_id}"));

            if (! $subRes->successful()) {
                Log::error('PayPal subscription retrieval failed', [
                    'subscription_id' => $request->subscription_id,
                    'body' => $subRes->json(),
                ]);

                return response()->json(['error' => 'Could not verify PayPal subscription.'], 422);
            }

            $sub = $subRes->json();
            $status = $sub['status'] ?? '';

            $donation = Donation::findOrFail($request->donation_id);

            $nextBillingTime = $sub['billing_info']['next_billing_time'] ?? null;

            $donation->update([
                'transaction_id' => $sub['id'],
                'subscription_id' => $sub['id'],
                'subscription_status' => strtolower($status),
                'next_billing_date' => $nextBillingTime ? Carbon::parse($nextBillingTime) : null,
                'status' => in_array($status, ['ACTIVE', 'APPROVED']) ? 'completed' : 'pending',
            ]);

            if ($donation->fresh()->status === 'completed') {
                $this->sendDonationConfirmation($donation->fresh());
            }

            return response()->json(['success' => true]);

        } catch (\Throwable $e) {
            Log::error('PayPal subscription confirm exception', [
                'subscription_id' => $request->subscription_id ?? null,
                'donation_id' => $request->donation_id ?? null,
                'message' => $e->getMessage(),
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
        $resource = $request->json('resource') ?? [];

        Log::info('PayPal webhook received', ['event_type' => $eventType]);

        match ($eventType) {
            'BILLING.SUBSCRIPTION.ACTIVATED' => $this->handlePaypalSubscriptionActivated($resource),
            'BILLING.SUBSCRIPTION.CANCELLED' => $this->handlePaypalSubscriptionCancelled($resource),
            'BILLING.SUBSCRIPTION.SUSPENDED' => $this->handlePaypalSubscriptionCancelled($resource),
            'BILLING.SUBSCRIPTION.PAYMENT.FAILED' => $this->handlePaypalPaymentFailed($resource),
            'PAYMENT.SALE.COMPLETED' => $this->handlePaypalSaleCompleted($resource),
            default => null,
        };

        return response()->json(['received' => true]);
    }

    private function handlePaypalSubscriptionActivated(array $resource): void
    {
        $subscriptionId = $resource['id'] ?? null;
        $nextBillingTime = $resource['billing_info']['next_billing_time'] ?? null;

        if (! $subscriptionId) {
            return;
        }

        Donation::where('subscription_id', $subscriptionId)->update([
            'subscription_status' => 'active',
            'status' => 'completed',
            'next_billing_date' => $nextBillingTime ? Carbon::parse($nextBillingTime) : null,
        ]);
    }

    private function handlePaypalSubscriptionCancelled(array $resource): void
    {
        $subscriptionId = $resource['id'] ?? null;
        if (! $subscriptionId) {
            return;
        }

        Donation::where('subscription_id', $subscriptionId)
            ->update(['subscription_status' => 'cancelled']);
    }

    private function handlePaypalPaymentFailed(array $resource): void
    {
        $subscriptionId = $resource['id'] ?? null;
        if (! $subscriptionId) {
            return;
        }

        Donation::where('subscription_id', $subscriptionId)
            ->update(['subscription_status' => 'past_due']);
    }

    private function handlePaypalSaleCompleted(array $resource): void
    {
        // Fired for each successful recurring charge
        $billingAgreementId = $resource['billing_agreement_id'] ?? null;
        if (! $billingAgreementId) {
            return;
        }

        Donation::where('subscription_id', $billingAgreementId)->update([
            'subscription_status' => 'active',
            'status' => 'completed',
        ]);
    }

    // ── Stripe Webhook ───────────────────────────────────────────────────

    public function webhook(Request $request)
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $secret = config('services.stripe.webhook_secret');

        try {
            $event = Webhook::constructEvent($payload, (string) $sigHeader, (string) $secret);
        } catch (SignatureVerificationException|\UnexpectedValueException $e) {
            return response('Webhook signature verification failed.', 400);
        }

        match ($event->type) {
            // One-time payment events
            'payment_intent.succeeded' => $this->handleIntentSucceeded($event->data->object),
            'payment_intent.payment_failed' => $this->handleIntentFailed($event->data->object),
            // Subscription billing events
            'invoice.paid',
            'invoice.payment_succeeded',
            'invoice.payment_failed' => $this->handleSubscriptionInvoice($event->data->object),
            // Subscription lifecycle events
            'customer.subscription.created',
            'customer.subscription.updated',
            'customer.subscription.deleted' => app(StripeSubscriptionSync::class)->apply($event->data->object),
            default => null,
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

    /**
     * A monthly charge succeeded or failed: re-read the subscription from Stripe so
     * active / past_due / canceled always matches Stripe exactly.
     */
    private function handleSubscriptionInvoice(object $invoice): void
    {
        // Current API: invoice.parent.subscription_details.subscription; older payloads: invoice.subscription
        $subscriptionId = $invoice->parent->subscription_details->subscription
            ?? $invoice->subscription
            ?? null;

        if (is_object($subscriptionId)) {
            $subscriptionId = $subscriptionId->id;
        }

        if (! $subscriptionId || ! Donation::where('subscription_id', $subscriptionId)->exists()) {
            return;
        }

        Stripe::setApiKey(config('services.stripe.secret'));

        try {
            app(StripeSubscriptionSync::class)->apply(Subscription::retrieve($subscriptionId));
        } catch (ApiErrorException $e) {
            Log::error('Stripe subscription invoice webhook handling failed', [
                'subscription_id' => $subscriptionId,
                'message' => $e->getMessage(),
            ]);
        }
    }

    // ── PayPal helpers ───────────────────────────────────────────────────

    private function paypalApi(string $path): string
    {
        $base = config('services.paypal.mode') === 'live'
            ? 'https://api-m.paypal.com'
            : 'https://api-m.sandbox.paypal.com';

        return $base.$path;
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
        app(DonationReceipt::class)->send($donation);
    }
}
