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

        $this->assertSame(3, $this->tracker()->countActivePartners());
    }

    public function test_a_donor_with_two_subscriptions_counts_as_one_partner(): void
    {
        Donation::factory()->monthlyPartner()->count(2)->create(['email' => 'grace@example.com']);
        Donation::factory()->monthlyPartner()->create();

        $this->assertSame(2, $this->tracker()->countActivePartners());
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

        $this->assertSame(120, $progress['active_partners']);
        $this->assertSame(10000, $progress['goal']);
        $this->assertSame(1.2, $progress['percent']);
        $this->assertSame(250, $progress['next_milestone']);
    }

    public function test_progress_endpoint_returns_live_count(): void
    {
        Donation::factory()->monthlyPartner()->count(4)->create();

        $this->getJson(route('donate.hunger-progress'))
            ->assertOk()
            ->assertJson([
                'available' => true,
                'active_partners' => 4,
                'goal' => 10000,
                'next_milestone' => 100,
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

    public function test_banner_invites_first_partners_when_none_yet(): void
    {
        $html = $this->get('/')->assertOk()->getContent();

        $this->assertMatchesRegularExpression('/<p class="hcw-count" data-hcw-has-partners\s+hidden\s*>/', $html);
        $this->assertMatchesRegularExpression('/data-hcw-no-partners\s*>\s*Be one of our first monthly partners/', $html);
    }

    public function test_banner_hides_the_tracker_when_the_count_is_unavailable(): void
    {
        $this->app->instance(HungerCampaignTracker::class, Mockery::mock(HungerCampaignTracker::class, [
            'progress' => null,
        ]));

        $html = $this->get('/')->assertOk()->getContent();

        $this->assertMatchesRegularExpression('/data-hcw-tracker\s+data-progress-url="[^"]+"\s+hidden\s*>/', $html);
        $this->assertMatchesRegularExpression('/data-hcw-fallback\s*>/', $html);
        $this->assertStringContainsString('Join us for $7.99 a month', $html);
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

        $response = $this->actingAs(User::factory()->create())
            ->get(route('admin.monthly-partners.index'));

        $response->assertOk();
        $response->assertViewHas('stats', fn (array $stats): bool => $stats['active_partners'] === 4
            && $stats['active_subscriptions'] === 4
            && abs($stats['monthly_revenue'] - 48.97) < 0.001
            && $stats['new_subscriptions'] === 6
            && $stats['cancellations'] === 1
            && $stats['past_due'] === 1
            && $stats['failed_checkouts'] === 1);
        $response->assertSee('Stripe test mode.');
        $response->assertSee('Partners by source');
    }
}
