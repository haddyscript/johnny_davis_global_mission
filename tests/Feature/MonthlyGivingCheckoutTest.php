<?php

namespace Tests\Feature;

use App\Models\Donation;
use App\Services\HungerCampaignTracker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class MonthlyGivingCheckoutTest extends TestCase
{
    use FakesStripeApi;
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->fakeStripe();
    }

    protected function tearDown(): void
    {
        $this->tearDownFakeStripe();

        parent::tearDown();
    }

    private function pendingCheckout(): Donation
    {
        return Donation::factory()->pendingMonthlyCheckout()->create([
            'stripe_customer_id' => 'cus_donor',
            'source' => 'qr',
        ]);
    }

    private function fakeSucceededSetupIntent(string $customer = 'cus_donor'): void
    {
        $this->stripeRoute('GET', '/v1/setup_intents/seti_123', [
            'id' => 'seti_123',
            'object' => 'setup_intent',
            'status' => 'succeeded',
            'customer' => $customer,
            'payment_method' => 'pm_123',
        ]);
        $this->stripeRoute('GET', '/v1/payment_methods/pm_123', [
            'id' => 'pm_123',
            'object' => 'payment_method',
            'card' => ['brand' => 'visa', 'last4' => '4242', 'exp_month' => 4, 'exp_year' => 2030],
        ]);
        $this->stripeRoute('GET', '/v1/prices', ['object' => 'list', 'data' => []]);
        $this->stripeRoute('POST', '/v1/prices', ['id' => 'price_799', 'object' => 'price']);
    }

    /**
     * @return array<string, mixed>
     */
    private function createdSubscription(string $status): array
    {
        return $this->stripeSubscriptionPayload('sub_123', $status, [
            'latest_invoice' => [
                'id' => 'in_123',
                'object' => 'invoice',
                'confirmation_secret' => ['client_secret' => 'pi_123_secret_abc', 'type' => 'payment_intent'],
            ],
        ]);
    }

    private function confirm(Donation $donation): TestResponse
    {
        return $this->postJson('/donate/confirm', [
            'setup_intent_id' => 'seti_123',
            'donation_id' => $donation->id,
        ]);
    }

    public function test_checkout_records_source_and_stripe_mode(): void
    {
        $this->stripeRoute('POST', '/v1/customers', ['id' => 'cus_new', 'object' => 'customer', 'livemode' => false]);
        $this->stripeRoute('POST', '/v1/setup_intents', ['id' => 'seti_new', 'object' => 'setup_intent', 'client_secret' => 'seti_new_secret_x']);

        $this->get('/donate?src=QR Code!')->assertOk();

        $this->postJson('/donate/charge', [
            'campaign_name' => 'Feed Filipino Children',
            'first_name' => 'Maria',
            'last_name' => 'Santos',
            'email' => 'maria@example.com',
            'amount' => 7.99,
            'frequency' => 'monthly',
            'payment_method' => 'card',
        ])->assertOk()->assertJson(['flow' => 'setup']);

        $this->assertDatabaseHas('donations', [
            'email' => 'maria@example.com',
            'frequency' => 'monthly',
            'status' => 'pending',
            'stripe_customer_id' => 'cus_new',
            'source' => 'qr-code',
            'livemode' => false,
        ]);
    }

    public function test_checkout_without_campaign_link_is_credited_to_website(): void
    {
        $this->stripeRoute('POST', '/v1/payment_intents', ['id' => 'pi_new', 'object' => 'payment_intent', 'client_secret' => 'pi_new_secret_x', 'livemode' => false]);

        $this->postJson('/donate/charge', [
            'campaign_name' => 'Feed Filipino Children',
            'first_name' => 'Ana',
            'last_name' => 'Cruz',
            'email' => 'ana@example.com',
            'amount' => 100,
            'frequency' => 'one-time',
            'payment_method' => 'card',
        ])->assertOk()->assertJson(['flow' => 'payment']);

        $this->assertDatabaseHas('donations', ['email' => 'ana@example.com', 'frequency' => 'one-time', 'source' => 'website']);
    }

    public function test_successful_first_payment_activates_the_monthly_partner(): void
    {
        $donation = $this->pendingCheckout();
        $this->fakeSucceededSetupIntent();
        $this->stripeRoute('POST', '/v1/subscriptions', $this->createdSubscription('active'));
        Cache::put(HungerCampaignTracker::CACHE_KEY, ['active_partners' => 0, 'updated_at' => now()->toIso8601String()]);

        $this->confirm($donation)->assertOk()->assertJson(['success' => true]);

        $donation->refresh();
        $this->assertSame('completed', $donation->status);
        $this->assertSame('sub_123', $donation->subscription_id);
        $this->assertSame('active', $donation->subscription_status);
        $this->assertSame('in_123', $donation->transaction_id);
        $this->assertSame('4242', $donation->card_last_four);
        $this->assertNotNull($donation->next_billing_date);
        $this->assertFalse($donation->livemode);

        $this->assertSame(1, app(HungerCampaignTracker::class)->progress()['active_partners']);
    }

    public function test_subscription_uses_a_reusable_price_and_an_idempotency_key(): void
    {
        $donation = $this->pendingCheckout();
        $this->fakeSucceededSetupIntent();
        $this->stripeRoute('POST', '/v1/subscriptions', $this->createdSubscription('active'));

        $this->confirm($donation)->assertOk();

        $priceRequest = $this->stripeRequestsTo('POST', '/v1/prices')[0];
        $this->assertSame('jdgm_monthly_feed_filipino_children_799', $priceRequest['params']['lookup_key']);
        $this->assertSame(799, $priceRequest['params']['unit_amount']);
        $this->assertSame('month', $priceRequest['params']['recurring']['interval']);

        $subscriptionRequest = $this->stripeRequestsTo('POST', '/v1/subscriptions')[0];
        $this->assertSame('price_799', $subscriptionRequest['params']['items'][0]['price']);
        $this->assertSame(['latest_invoice.confirmation_secret'], $subscriptionRequest['params']['expand']);
        $this->assertSame('qr', $subscriptionRequest['params']['metadata']['source']);
        $this->assertContains('Idempotency-Key: jdgm-donation-subscription-'.$donation->id, $subscriptionRequest['headers']);
    }

    public function test_existing_price_is_reused(): void
    {
        $donation = $this->pendingCheckout();
        $this->fakeSucceededSetupIntent();
        $this->stripeRoute('GET', '/v1/prices', ['object' => 'list', 'data' => [['id' => 'price_existing', 'object' => 'price']]]);
        $this->stripeRoute('POST', '/v1/subscriptions', $this->createdSubscription('active'));

        $this->confirm($donation)->assertOk();

        $this->assertCount(0, $this->stripeRequestsTo('POST', '/v1/prices'));
        $this->assertSame('price_existing', $this->stripeRequestsTo('POST', '/v1/subscriptions')[0]['params']['items'][0]['price']);
    }

    public function test_repeat_confirmation_never_creates_a_second_subscription(): void
    {
        $donation = $this->pendingCheckout();
        $this->fakeSucceededSetupIntent();
        $this->stripeRoute('POST', '/v1/subscriptions', $this->createdSubscription('active'));
        $this->stripeRoute('GET', '/v1/subscriptions/sub_123', $this->createdSubscription('active'));

        $this->confirm($donation)->assertOk();
        $this->confirm($donation)->assertOk()->assertJson(['success' => true]);

        $this->assertCount(1, $this->stripeRequestsTo('POST', '/v1/subscriptions'));
        $this->assertSame(1, Donation::where('subscription_id', 'sub_123')->count());
    }

    public function test_declined_first_payment_cancels_the_subscription_and_reports_the_error(): void
    {
        $donation = $this->pendingCheckout();
        $this->fakeSucceededSetupIntent();
        $this->stripeRoute('POST', '/v1/subscriptions', $this->createdSubscription('incomplete'));
        $this->stripeRoute('GET', '/v1/payment_intents/pi_123', [
            'id' => 'pi_123',
            'object' => 'payment_intent',
            'status' => 'requires_payment_method',
            'last_payment_error' => ['message' => 'Your card has insufficient funds.'],
        ]);
        $this->stripeRoute('DELETE', '/v1/subscriptions/sub_123', $this->stripeSubscriptionPayload('sub_123', 'canceled', [
            'ended_at' => now()->timestamp,
        ]));

        $this->confirm($donation)
            ->assertStatus(422)
            ->assertJsonPath('error', 'Your card has insufficient funds. No monthly gift was started and you were not charged. Please try another card.');

        $donation->refresh();
        $this->assertSame('failed', $donation->status);
        $this->assertSame('canceled', $donation->subscription_status);
        $this->assertCount(1, $this->stripeRequestsTo('DELETE', '/v1/subscriptions/sub_123'));
        $this->assertSame(0, app(HungerCampaignTracker::class)->countActivePartners());
    }

    public function test_first_payment_needing_3d_secure_asks_the_donor_to_verify(): void
    {
        $donation = $this->pendingCheckout();
        $this->fakeSucceededSetupIntent();
        $this->stripeRoute('POST', '/v1/subscriptions', $this->createdSubscription('incomplete'));
        $this->stripeRoute('GET', '/v1/payment_intents/pi_123', [
            'id' => 'pi_123',
            'object' => 'payment_intent',
            'status' => 'requires_action',
            'client_secret' => 'pi_123_secret_abc',
        ]);

        $this->confirm($donation)
            ->assertOk()
            ->assertJson(['requires_action' => true, 'client_secret' => 'pi_123_secret_abc']);

        $donation->refresh();
        $this->assertSame('pending', $donation->status);
        $this->assertSame('incomplete', $donation->subscription_status);
        $this->assertSame(0, app(HungerCampaignTracker::class)->countActivePartners());
    }

    public function test_setup_intent_from_another_customer_is_rejected(): void
    {
        $donation = $this->pendingCheckout();
        $this->fakeSucceededSetupIntent(customer: 'cus_someone_else');

        $this->confirm($donation)->assertStatus(422)->assertJson(['error' => 'Subscription mismatch.']);

        $this->assertCount(0, $this->stripeRequestsTo('POST', '/v1/subscriptions'));
    }

    public function test_webhook_marks_renewal_failure_as_past_due_and_refreshes_the_tracker(): void
    {
        $donation = Donation::factory()->monthlyPartner()->create(['subscription_id' => 'sub_abc']);
        Cache::put(HungerCampaignTracker::CACHE_KEY, ['active_partners' => 1, 'updated_at' => now()->toIso8601String()]);

        $this->postStripeWebhook('customer.subscription.updated', $this->stripeSubscriptionPayload('sub_abc', 'past_due'))
            ->assertOk();

        $this->assertSame('past_due', $donation->fresh()->subscription_status);
        $this->assertSame(0, app(HungerCampaignTracker::class)->progress()['active_partners']);
    }

    public function test_webhook_records_cancellation(): void
    {
        $donation = Donation::factory()->monthlyPartner()->create(['subscription_id' => 'sub_abc']);
        $endedAt = now()->subHour()->startOfSecond();

        $this->postStripeWebhook('customer.subscription.deleted', $this->stripeSubscriptionPayload('sub_abc', 'canceled', [
            'canceled_at' => $endedAt->timestamp,
            'ended_at' => $endedAt->timestamp,
        ]))->assertOk();

        $donation->refresh();
        $this->assertSame('canceled', $donation->subscription_status);
        $this->assertTrue($donation->subscription_canceled_at->equalTo($endedAt));
        $this->assertNull($donation->next_billing_date);
    }

    public function test_paid_invoice_webhook_activates_a_gift_confirmed_after_3d_secure(): void
    {
        $donation = Donation::factory()->monthlyPartner()->create([
            'subscription_id' => 'sub_abc',
            'subscription_status' => 'incomplete',
            'status' => 'pending',
        ]);
        $this->stripeRoute('GET', '/v1/subscriptions/sub_abc', $this->stripeSubscriptionPayload('sub_abc', 'active'));

        $this->postStripeWebhook('invoice.paid', [
            'id' => 'in_abc',
            'object' => 'invoice',
            'parent' => [
                'type' => 'subscription_details',
                'subscription_details' => ['subscription' => 'sub_abc'],
            ],
        ])->assertOk();

        $donation->refresh();
        $this->assertSame('active', $donation->subscription_status);
        $this->assertSame('completed', $donation->status);
    }

    public function test_webhook_with_bad_signature_is_rejected(): void
    {
        $this->call('POST', '/stripe/webhook', [], [], [], [
            'HTTP_STRIPE_SIGNATURE' => 't=1,v1=forged',
            'CONTENT_TYPE' => 'application/json',
        ], '{"type":"customer.subscription.updated"}')->assertStatus(400);
    }

    public function test_hourly_sync_reconciles_statuses_missed_by_webhooks(): void
    {
        $stillActive = Donation::factory()->monthlyPartner()->create(['subscription_id' => 'sub_ok', 'livemode' => null]);
        $nowPastDue = Donation::factory()->monthlyPartner()->create(['subscription_id' => 'sub_late']);

        $this->stripeRoute('GET', '/v1/subscriptions', [
            'object' => 'list',
            'has_more' => false,
            'url' => '/v1/subscriptions',
            'data' => [
                $this->stripeSubscriptionPayload('sub_ok', 'active'),
                $this->stripeSubscriptionPayload('sub_late', 'past_due'),
                $this->stripeSubscriptionPayload('sub_unrelated', 'active'),
            ],
        ]);

        $this->artisan('donations:sync-stripe-subscriptions')
            ->expectsOutputToContain('Checked 3 Stripe subscriptions, updated 2 donation records, imported 0 new.')
            ->assertSuccessful();

        $this->assertFalse($stillActive->fresh()->livemode);
        $this->assertSame('active', $stillActive->fresh()->subscription_status);
        $this->assertSame('past_due', $nowPastDue->fresh()->subscription_status);
        $this->assertSame(1, app(HungerCampaignTracker::class)->countActivePartners());
    }

    /**
     * A subscription as returned by the list call with `data.customer` expanded.
     *
     * @return array<string, mixed>
     */
    private function dashboardSubscription(string $id, string $status, array $customer, string $interval = 'month', array $metadata = []): array
    {
        return $this->stripeSubscriptionPayload($id, $status, [
            'created' => now()->subMonths(6)->startOfSecond()->timestamp,
            'customer' => ['object' => 'customer'] + $customer,
            'metadata' => $metadata,
            'items' => [
                'object' => 'list',
                'data' => [[
                    'id' => 'si_'.$id,
                    'object' => 'subscription_item',
                    'quantity' => 1,
                    'current_period_end' => now()->addWeek()->timestamp,
                    'price' => [
                        'id' => 'price_799',
                        'object' => 'price',
                        'unit_amount' => 799,
                        'recurring' => ['interval' => $interval],
                    ],
                ]],
            ],
        ]);
    }

    /**
     * @param  array<int, array<string, mixed>>  $subscriptions
     */
    private function fakeSubscriptionList(array $subscriptions): void
    {
        $this->stripeRoute('GET', '/v1/subscriptions', [
            'object' => 'list',
            'has_more' => false,
            'url' => '/v1/subscriptions',
            'data' => $subscriptions,
        ]);
    }

    public function test_sync_imports_monthly_partners_created_in_the_stripe_dashboard(): void
    {
        $this->fakeSubscriptionList([
            $this->dashboardSubscription('sub_dash', 'active', ['id' => 'cus_dash', 'name' => 'Grace Ann Lee', 'email' => 'grace@example.com']),
            $this->dashboardSubscription('sub_old', 'canceled', ['id' => 'cus_old', 'name' => null, 'email' => null]),
            $this->dashboardSubscription('sub_yearly', 'active', ['id' => 'cus_year', 'name' => 'Yearly Giver', 'email' => 'y@example.com'], 'year'),
        ]);

        $this->artisan('donations:sync-stripe-subscriptions')
            ->expectsOutputToContain('Checked 3 Stripe subscriptions, updated 0 donation records, imported 2 new.')
            ->assertSuccessful();

        $imported = Donation::where('subscription_id', 'sub_dash')->sole();
        $this->assertSame('Grace', $imported->first_name);
        $this->assertSame('Ann Lee', $imported->last_name);
        $this->assertSame('grace@example.com', $imported->email);
        $this->assertSame('7.99', $imported->amount);
        $this->assertSame('monthly', $imported->frequency);
        $this->assertSame('completed', $imported->status);
        $this->assertSame('active', $imported->subscription_status);
        $this->assertSame('stripe-direct', $imported->source);
        $this->assertSame('cus_dash', $imported->stripe_customer_id);
        $this->assertTrue($imported->created_at->isSameDay(now()->subMonths(6)));

        $canceled = Donation::where('subscription_id', 'sub_old')->sole();
        $this->assertSame('cus_old@no-email.invalid', $canceled->email);
        $this->assertSame('Stripe', $canceled->first_name);
        $this->assertSame('canceled', $canceled->subscription_status);
        $this->assertNotNull($canceled->subscription_canceled_at);

        $this->assertDatabaseMissing('donations', ['subscription_id' => 'sub_yearly']);
        $this->assertSame(1, app(HungerCampaignTracker::class)->countActivePartners());

        // Imported silently: donors are not emailed or added to the newsletter
        $this->assertDatabaseCount('newsletter_subscribers', 0);
    }

    public function test_sync_import_is_not_repeated_on_the_next_run(): void
    {
        $this->fakeSubscriptionList([
            $this->dashboardSubscription('sub_dash', 'active', ['id' => 'cus_dash', 'name' => 'Grace Lee', 'email' => 'grace@example.com']),
        ]);

        $this->artisan('donations:sync-stripe-subscriptions')->assertSuccessful();
        $this->artisan('donations:sync-stripe-subscriptions')
            ->expectsOutputToContain('updated 1 donation records, imported 0 new.')
            ->assertSuccessful();

        $this->assertSame(1, Donation::where('subscription_id', 'sub_dash')->count());
    }

    public function test_sync_links_a_website_checkout_whose_subscription_id_was_never_saved(): void
    {
        $donation = $this->pendingCheckout();

        $this->fakeSubscriptionList([
            $this->dashboardSubscription('sub_orphan', 'active', ['id' => 'cus_donor', 'name' => 'Maria Santos', 'email' => $donation->email], 'month', [
                'donation_id' => (string) $donation->id,
            ]),
        ]);

        $this->artisan('donations:sync-stripe-subscriptions')->assertSuccessful();

        $donation->refresh();
        $this->assertSame('sub_orphan', $donation->subscription_id);
        $this->assertSame('active', $donation->subscription_status);
        $this->assertSame('completed', $donation->status);
        $this->assertSame(1, Donation::count());
        $this->assertDatabaseCount('newsletter_subscribers', 0);
    }
}
