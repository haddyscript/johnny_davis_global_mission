<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HungerCampaignTest extends TestCase
{
    use RefreshDatabase;

    public function test_homepage_leads_with_the_hunger_cant_wait_campaign(): void
    {
        $response = $this->get('/');

        $response->assertOk();
        $response->assertSeeInOrder([
            'id="hunger-campaign"',
            'HUNGER CAN&rsquo;T WAIT!',
            'hunger-cant-wait-banner-768.webp',
            'Our journey to 10,000 monthly partners',
            'Become a Monthly Partner',
            '$7.99 a month',
            'Philippines:',
            'Feeding children now.',
            'Uganda:',
            'Coming soon.',
            'id="hero"',
        ], false);
        $response->assertSee('href="'.route('donate').'#give"', false);
    }

    public function test_approved_banner_artwork_is_served_responsively(): void
    {
        $html = $this->get('/')->getContent();

        foreach ([768, 1280, 2048] as $width) {
            $this->assertStringContainsString("hunger-cant-wait-banner-{$width}.webp {$width}w", $html);
            $this->assertStringContainsString("hunger-cant-wait-banner-{$width}.jpg {$width}w", $html);
            $this->assertFileExists(public_path("images/hunger-cant-wait/hunger-cant-wait-banner-{$width}.webp"));
            $this->assertFileExists(public_path("images/hunger-cant-wait/hunger-cant-wait-banner-{$width}.jpg"));
        }
        $this->assertStringContainsString('width="2048" height="768"', $html);
    }

    public function test_uganda_is_presented_as_coming_soon_not_active(): void
    {
        $html = $this->get('/')->getContent();

        preg_match('/<section id="hunger-campaign".*?<\/section>/s', $html, $matches);

        $this->assertStringContainsString('hcw-program--soon">'."\n".'          <span class="hcw-program-name">Uganda:</span> Coming soon.', $matches[0]);
        $this->assertStringContainsString('Uganda: feeding children, coming soon', $matches[0]);
    }

    public function test_campaign_copy_does_not_use_starting_at_wording(): void
    {
        $this->get('/')->assertDontSee('starting at $7.99');
        $this->get('/donate')->assertDontSee('starting at $7.99');
    }

    public function test_donation_page_shows_campaign_above_the_form(): void
    {
        $response = $this->get('/donate');

        $response->assertOk();
        $response->assertSeeInOrder([
            'id="hunger-campaign"',
            'HUNGER CAN&rsquo;T WAIT!',
            'href="#give"',
            'Our journey to 10,000 monthly partners',
            'id="donate-body-screen"',
            'id="give"',
        ], false);
    }

    public function test_donation_form_preselects_7_99_monthly(): void
    {
        $response = $this->get('/donate');

        $response->assertSee('<button class="dtab active" aria-pressed="true">Monthly giving</button>', false);
        $response->assertSee('<button class="amount-btn selected" aria-label="Donate $7.99 — feeds 1 child" aria-pressed="true">', false);
        $response->assertSee('Recurring monthly gift: $7.99 a month.', false);
        $response->assertSee('Complete Gift — $7.99 / Month', false);
        $response->assertSee('id="customAmountInput"', false);
        $response->assertSee('One-time', false);
    }

    public function test_campaign_section_does_not_promote_other_programs(): void
    {
        $html = $this->get('/')->getContent();

        preg_match('/<section id="hunger-campaign".*?<\/section>/s', $html, $matches);

        $this->assertNotEmpty($matches);
        foreach (['Disaster', 'Clean Water', 'Medical', 'Education', 'Earthquake'] as $otherProgram) {
            $this->assertStringNotContainsStringIgnoringCase($otherProgram, $matches[0]);
        }
    }

    public function test_donation_page_uses_local_pastor_photo(): void
    {
        $response = $this->get('/donate');

        $response->assertSee('src="'.asset('images/who-we-are/Johnny-Davis.webp').'" alt="Pastor Johnny Davis"', false);
        $response->assertDontSee('cloudfront.net', false);
    }

    public function test_donors_can_manage_their_monthly_gift_through_stripe(): void
    {
        config(['services.stripe.portal_url' => 'https://billing.stripe.com/p/login/test_123']);

        $response = $this->get('/donate');

        $response->assertSee('href="https://billing.stripe.com/p/login/test_123"', false);
        $response->assertSee('Update your card or cancel your monthly gift', false);
        $response->assertSee('update your card or cancel anytime', false);
    }

    public function test_cancel_instructions_fall_back_to_contact_page_without_stripe_portal(): void
    {
        config(['services.stripe.portal_url' => null]);

        $response = $this->get('/donate');

        $response->assertDontSee('billing.stripe.com', false);
        $response->assertSee('href="'.route('contact').'">contact our team</a>', false);
    }
}
