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
            'Be one of our first <strong>10,000</strong> monthly partners.',
            '$7.99',
            'a month',
            'Philippines:',
            'Feeding children now.',
            'Uganda:',
            'Coming soon.',
            'Become a Monthly Partner',
            'id="hero"',
        ], false);
        $response->assertSee('href="'.route('donate').'#give"', false);
        $response->assertSee('images/hunger-cant-wait/hunger-cant-wait-flyer.jpg', false);
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
}
