<?php

namespace Tests\Feature;

use App\Models\Donation;
use App\Services\HungerCampaignTracker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class OneTimeGivingCheckoutTest extends TestCase
{
    use FakesStripeApi;
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->fakeStripe();
        $this->stripeRoute('POST', '/v1/payment_intents', fn (array $params): array => [
            'id' => 'pi_gift',
            'object' => 'payment_intent',
            'amount' => $params['amount'],
            'client_secret' => 'pi_gift_secret_x',
            'livemode' => false,
        ]);
    }

    protected function tearDown(): void
    {
        $this->tearDownFakeStripe();

        parent::tearDown();
    }

    /**
     * @param  array<string, mixed>  $overrides
     */
    private function startGift(array $overrides = []): TestResponse
    {
        return $this->postJson('/donate/charge', array_merge([
            'campaign_name' => 'Uganda Clean Water Initiative',
            'first_name' => 'Grace',
            'last_name' => 'Okello',
            'email' => 'grace@example.com',
            'amount' => 25,
            'frequency' => 'one-time',
            'payment_method' => 'card',
        ], $overrides));
    }

    private function fakePaymentIntentStatus(string $status): void
    {
        $this->stripeRoute('GET', '/v1/payment_intents/pi_gift', [
            'id' => 'pi_gift',
            'object' => 'payment_intent',
            'status' => $status,
            'payment_method' => 'pm_gift',
        ]);
        $this->stripeRoute('GET', '/v1/payment_methods/pm_gift', [
            'id' => 'pm_gift',
            'object' => 'payment_method',
            'card' => ['brand' => 'visa', 'last4' => '4242', 'exp_month' => 7, 'exp_year' => 2031],
        ]);
    }

    public function test_one_time_gift_charges_the_chosen_amount_once_without_a_subscription(): void
    {
        $this->startGift(['amount' => 7.99])->assertOk()->assertJson(['flow' => 'payment', 'client_secret' => 'pi_gift_secret_x']);

        $intent = $this->stripeRequestsTo('POST', '/v1/payment_intents');
        $this->assertCount(1, $intent);
        $this->assertSame(799, (int) $intent[0]['params']['amount']);
        $this->assertSame('one-time', $intent[0]['params']['metadata']['frequency']);
        $this->assertSame([], $this->stripeRequestsTo('POST', '/v1/customers'));
        $this->assertSame([], $this->stripeRequestsTo('POST', '/v1/subscriptions'));
    }

    /**
     * @return array<string, array{int, int}>
     */
    public static function missionBuilderGifts(): array
    {
        return [
            'Hope Builder' => [100, 10000],
            'Compassion Builder' => [300, 30000],
            'Impact Builder' => [500, 50000],
            'Legacy Builder' => [1000, 100000],
        ];
    }

    #[DataProvider('missionBuilderGifts')]
    public function test_mission_builder_gift_is_charged_once_and_not_counted_as_a_monthly_partner(int $amount, int $cents): void
    {
        $donationId = $this->startGift(['campaign_name' => 'Feed Filipino Children', 'amount' => $amount])
            ->assertOk()
            ->assertJson(['flow' => 'payment'])
            ->json('donation_id');
        $this->fakePaymentIntentStatus('succeeded');

        $this->postJson('/donate/confirm', [
            'payment_intent_id' => 'pi_gift',
            'donation_id' => $donationId,
        ])->assertOk()->assertJson(['success' => true]);

        $intent = $this->stripeRequestsTo('POST', '/v1/payment_intents');
        $this->assertSame($cents, (int) $intent[0]['params']['amount']);
        $this->assertSame('one-time', $intent[0]['params']['metadata']['frequency']);
        $this->assertSame([], $this->stripeRequestsTo('POST', '/v1/subscriptions'));
        $this->assertDatabaseHas('donations', ['id' => $donationId, 'amount' => $amount, 'frequency' => 'one-time', 'status' => 'completed']);
        $this->assertSame(0, app(HungerCampaignTracker::class)->countActivePartners());
    }

    public function test_successful_one_time_payment_completes_the_gift(): void
    {
        $donationId = $this->startGift()->json('donation_id');
        $this->fakePaymentIntentStatus('succeeded');

        $this->postJson('/donate/confirm', [
            'payment_intent_id' => 'pi_gift',
            'donation_id' => $donationId,
        ])->assertOk()->assertJson(['success' => true]);

        $this->assertDatabaseHas('donations', [
            'id' => $donationId,
            'campaign_name' => 'Uganda Clean Water Initiative',
            'amount' => 25,
            'frequency' => 'one-time',
            'status' => 'completed',
            'card_last_four' => '4242',
            'card_exp_month' => '07',
        ]);
        $this->assertNull(Donation::find($donationId)->stripe_subscription_id);
    }

    public function test_unsuccessful_one_time_payment_is_marked_failed(): void
    {
        $donationId = $this->startGift()->json('donation_id');
        $this->fakePaymentIntentStatus('requires_payment_method');

        $this->postJson('/donate/confirm', [
            'payment_intent_id' => 'pi_gift',
            'donation_id' => $donationId,
        ])->assertStatus(422)->assertJsonFragment(['error' => 'Payment could not be completed. Status: requires_payment_method']);

        $this->assertDatabaseHas('donations', ['id' => $donationId, 'status' => 'failed']);
    }

    public function test_one_time_gift_rejects_a_payment_from_another_checkout(): void
    {
        $donationId = $this->startGift()->json('donation_id');
        $this->stripeRoute('GET', '/v1/payment_intents/pi_other', ['id' => 'pi_other', 'object' => 'payment_intent', 'status' => 'succeeded']);

        $this->postJson('/donate/confirm', [
            'payment_intent_id' => 'pi_other',
            'donation_id' => $donationId,
        ])->assertStatus(422)->assertJson(['error' => 'Payment intent mismatch.']);

        $this->assertDatabaseHas('donations', ['id' => $donationId, 'status' => 'pending']);
    }

    public function test_one_time_gift_needs_a_valid_amount(): void
    {
        $this->startGift(['amount' => 0])->assertUnprocessable()->assertJsonValidationErrors('amount');
        $this->assertSame([], $this->stripeRequestsTo('POST', '/v1/payment_intents'));
    }
}
