<?php

namespace Tests\Feature;

use App\Models\Donation;
use App\Models\User;
use App\Services\HungerCampaignTracker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\TestCase;

class HungerCampaignTrackerTest extends TestCase
{
    use RefreshDatabase;

    private function tracker(): HungerCampaignTracker
    {
        return app(HungerCampaignTracker::class);
    }

    public function test_only_active_stripe_monthly_partners_are_counted(): void
    {
        Donation::factory()->monthlyPartner()->count(3)->create();

        Donation::factory()->create();                                  // one-time gift
        Donation::factory()->pastDue()->create();                       // failed renewal
        Donation::factory()->canceled()->create();                      // canceled
        Donation::factory()->pendingMonthlyCheckout()->create();        // unfinished checkout
        Donation::factory()->monthlyPartner()->create(['subscription_status' => 'incomplete', 'status' => 'pending']);
        Donation::factory()->monthlyPartner()->create(['payment_method' => 'paypal']); // not Stripe-verified
        Donation::factory()->monthlyPartner()->create(['livemode' => null]);           // not yet verified with Stripe
        Donation::factory()->monthlyPartner()->live()->create();                       // live gift while on test keys
        Donation::factory()->monthlyPartner()->create(['amount' => '25.00']);         // other monthly amount
        Donation::factory()->monthlyPartner()->create(['amount' => '7.00']);          // other monthly amount
        Donation::factory()->create(['amount' => '7.99']);                             // one-time $7.99 gift

        $this->assertSame(3, $this->tracker()->countActivePartners());
    }

    public function test_a_donor_with_two_subscriptions_counts_as_one_partner(): void
    {
        Donation::factory()->monthlyPartner()->count(2)->create(['email' => 'grace@example.com']);
        Donation::factory()->monthlyPartner()->create();

        $this->assertSame(2, $this->tracker()->countActivePartners());
    }

    public function test_a_donor_with_a_7_99_and_another_monthly_amount_counts_once(): void
    {
        Donation::factory()->monthlyPartner()->create(['email' => 'grace@example.com']);
        Donation::factory()->monthlyPartner()->create(['email' => 'grace@example.com', 'amount' => '25.00']);
        Donation::factory()->monthlyPartner()->create(['amount' => '25.00']);

        $this->assertSame(1, $this->tracker()->countActivePartners());
    }

    public function test_test_mode_gifts_are_excluded_when_running_on_live_keys(): void
    {
        config(['services.stripe.secret' => 'sk_live_example']);

        Donation::factory()->monthlyPartner()->live()->count(2)->create();
        Donation::factory()->monthlyPartner()->count(5)->create(); // Stripe test mode

        $this->assertSame(2, $this->tracker()->countActivePartners());
    }

    public function test_progress_reports_percent_and_next_milestone(): void
    {
        Donation::factory()->monthlyPartner()->count(120)->create();

        $progress = $this->tracker()->progress();

        $this->assertTrue($progress['show_count']);
        $this->assertSame(120, $progress['active_partners']);
        $this->assertSame(10000, $progress['goal']);
        $this->assertSame(1.2, $progress['percent']);
        $this->assertSame(250, $progress['next_milestone']);
    }

    public function test_progress_hides_the_count_until_100_partners(): void
    {
        Donation::factory()->monthlyPartner()->count(99)->create();

        $progress = $this->tracker()->progress();

        $this->assertFalse($progress['show_count']);
        $this->assertNull($progress['active_partners']);
        $this->assertNull($progress['percent']);
        $this->assertSame(10000, $progress['goal']);
        $this->assertSame(100, $progress['next_milestone']);
    }

    public function test_progress_shows_the_count_from_100_partners(): void
    {
        Donation::factory()->monthlyPartner()->count(100)->create();

        $progress = $this->tracker()->progress();

        $this->assertTrue($progress['show_count']);
        $this->assertSame(100, $progress['active_partners']);
        $this->assertSame(1.0, $progress['percent']);
        $this->assertSame(250, $progress['next_milestone']);
    }

    public function test_progress_endpoint_does_not_reveal_a_count_below_100(): void
    {
        Donation::factory()->monthlyPartner()->count(4)->create();

        $response = $this->getJson(route('donate.hunger-progress'))
            ->assertOk()
            ->assertJson([
                'available' => true,
                'show_count' => false,
                'active_partners' => null,
                'percent' => null,
                'goal' => 10000,
                'next_milestone' => 100,
            ]);

        $this->assertStringNotContainsString('"active_partners":4', $response->getContent());
    }

    public function test_progress_endpoint_returns_live_count_from_100_partners(): void
    {
        Donation::factory()->monthlyPartner()->count(150)->create();

        $this->getJson(route('donate.hunger-progress'))
            ->assertOk()
            ->assertJson([
                'available' => true,
                'show_count' => true,
                'active_partners' => 150,
                'goal' => 10000,
                'next_milestone' => 250,
            ]);
    }

    public function test_progress_endpoint_reports_unavailable_instead_of_guessing(): void
    {
        $this->app->instance(HungerCampaignTracker::class, Mockery::mock(HungerCampaignTracker::class, [
            'progress' => null,
        ]));

        $this->getJson(route('donate.hunger-progress'))
            ->assertStatus(503)
            ->assertExactJson(['available' => false]);
    }

    public function test_banner_shows_the_verified_partner_count(): void
    {
        Donation::factory()->monthlyPartner()->count(1234)->create();

        foreach (['/', '/donate'] as $page) {
            $this->get($page)
                ->assertOk()
                ->assertSeeInOrder([
                    'id="hunger-campaign"',
                    'Our journey to 10,000 monthly partners',
                    '1,234',
                    'active monthly partners',
                    'aria-valuenow="1234"',
                    'Become a Monthly Partner',
                ], false);
        }
    }

    public function test_banner_shows_message_instead_of_a_small_partner_count(): void
    {
        Donation::factory()->monthlyPartner()->count(2)->create();

        foreach (['/', '/donate'] as $page) {
            $response = $this->get($page)->assertOk();

            $response->assertSeeInOrder([
                'Our journey to 10,000 monthly partners',
                'Every Partner Makes a Difference',
                'Join our growing community of compassionate monthly supporters.',
                'First milestone',
                '100',
                'Goal: <strong>10,000</strong>',
                'Become a Monthly Partner',
            ], false);

            $html = $response->getContent();
            preg_match('/<section id="hunger-campaign".*?<\/section>/s', $html, $matches);

            $this->assertMatchesRegularExpression('/data-hcw-live\s+hidden\s*>/', $matches[0]);
            $this->assertMatchesRegularExpression('/data-hcw-count><\/span>/', $matches[0]);
            $this->assertStringContainsString('aria-valuenow="0"', $matches[0]);
            $this->assertDoesNotMatchRegularExpression('/data-hcw-message\s+hidden/', $matches[0]);
        }
    }

    public function test_banner_shows_message_when_the_count_is_unavailable(): void
    {
        $this->app->instance(HungerCampaignTracker::class, Mockery::mock(HungerCampaignTracker::class, [
            'progress' => null,
        ]));

        $html = $this->get('/')->assertOk()->getContent();

        $this->assertMatchesRegularExpression('/data-hcw-tracker\s+data-progress-url="[^"]+"\s*>/', $html);
        $this->assertMatchesRegularExpression('/data-hcw-live\s+hidden\s*>/', $html);
        $this->assertStringContainsString('Every Partner Makes a Difference', $html);
        $this->assertStringContainsString('Become a Monthly Partner', $html);
    }

    public function test_campaign_source_link_is_remembered_for_the_visit(): void
    {
        $this->get('/?utm_source=Facebook')->assertSessionHas('donation_source', 'facebook');
        $this->get('/donate?src=qr')->assertSessionHas('donation_source', 'qr');
        $this->get('/donate?src=%20%20')->assertSessionHas('donation_source', 'qr');
    }

    public function test_monthly_partner_report_requires_login(): void
    {
        $this->get(route('admin.monthly-partners.index'))->assertRedirect(route('login'));
    }

    public function test_monthly_partner_report_shows_campaign_metrics(): void
    {
        Donation::factory()->monthlyPartner()->count(3)->create(['source' => 'qr']);
        Donation::factory()->monthlyPartner()->create(['amount' => 25, 'source' => 'email']);
        Donation::factory()->pastDue()->create();
        Donation::factory()->canceled()->create();
        Donation::factory()->monthlyPartner()->create(['status' => 'failed', 'subscription_status' => 'canceled']);
        Donation::factory()->pastDue()->create(['payment_failed_at' => now()->subDays(40)]);           // failed outside the period
        Donation::factory()->monthlyPartner()->create(['payment_failed_at' => now()->subDays(5), 'subscription_recovered_at' => now()->subDay()]);

        $response = $this->actingAs(User::factory()->create())
            ->get(route('admin.monthly-partners.index'));

        $response->assertOk();
        $response->assertViewHas('stats', fn (array $stats): bool => $stats['active_partners'] === 4
            && $stats['active_subscriptions'] === 5
            && abs($stats['monthly_revenue'] - 56.96) < 0.001
            && $stats['new_subscriptions'] === 8
            && $stats['cancellations'] === 1
            && $stats['past_due'] === 2
            && $stats['payment_failures'] === 1
            && $stats['recovered'] === 1
            && $stats['failed_checkouts'] === 1);
        $response->assertSee('Stripe test mode.');
        $response->assertSee('Payment health');
        $response->assertSee('Recovered (30 days)');
        $response->assertSee('hidden publicly until 100 partners');
        $response->assertSee('Partners by source');
    }
}
