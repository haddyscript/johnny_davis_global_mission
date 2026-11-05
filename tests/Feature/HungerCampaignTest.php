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
            'Feeding program coming soon.',
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

    public function test_philippines_is_active_and_uganda_feeding_is_coming_soon(): void
    {
        $html = $this->get('/')->getContent();

        preg_match('/<ul class="hcw-programs".*?<\/ul>/s', $html, $matches);

        $this->assertNotEmpty($matches);
        $this->assertMatchesRegularExpression('/hcw-program--active">\s*<span class="hcw-program-name">Philippines:<\/span> Feeding children now\./', $matches[0]);
        $this->assertMatchesRegularExpression('/hcw-program--soon">\s*<span class="hcw-program-name">Uganda:<\/span> Feeding program coming soon\./', $matches[0]);
        $this->assertStringNotContainsString('Mission work active', $matches[0]);
    }

    public function test_banner_keeps_the_approved_uganda_coming_soon_artwork(): void
    {
        foreach (['/', '/donate'] as $page) {
            $html = $this->get($page)->assertOk()->getContent();
            preg_match('/<section id="hunger-campaign".*?<\/section>/s', $html, $matches);

            $this->assertStringNotContainsString('hcw-banner-status', $matches[0]);
            $this->assertStringContainsString('Uganda: feeding children, coming soon.', $matches[0]);
            $this->assertStringNotContainsString('feeding children, active', $matches[0]);
        }
    }

    public function test_uganda_clean_water_initiative_stays_active_on_the_donation_page(): void
    {
        $donatePage = $this->get('/donate')->assertOk()->getContent();

        $this->assertStringNotContainsStringIgnoringCase('launching soon', $donatePage);
        $this->assertMatchesRegularExpression('/Uganda Clean Water Initiative<\/h3>\s*<div class="card-raised-row">\s*<span><strong>Active<\/strong><\/span>/', $donatePage);
        $this->assertMatchesRegularExpression('/<div class="camp-title">Uganda Clean Water Initiative<\/div>.*?>Active<\/span>/s', $donatePage);
    }

    public function test_mission_builders_offer_the_four_one_time_gift_tiers(): void
    {
        $html = $this->get('/donate')->assertOk()->getContent();

        preg_match('/<div class="mission-builders".*?Make a greater impact through a one-time gift\./s', $html, $matches);

        $this->assertNotEmpty($matches);
        $this->assertStringContainsString('Special one-time gifts', $matches[0]);
        foreach (['$100' => 'Hope', '$300' => 'Compassion', '$500' => 'Impact', '$1,000' => 'Legacy'] as $amount => $tier) {
            $this->assertMatchesRegularExpression(
                '/<button class="amount-btn mission-builder-btn" aria-label="Give '.preg_quote($amount, '/').' one time — '.$tier.' Builder">\s*<span class="amount-price">'.preg_quote($amount, '/').'<\/span>\s*<span class="amount-impact">'.$tier.' Builder<\/span>/',
                $matches[0]
            );
        }
        $this->assertStringNotContainsStringIgnoringCase('monthly', $matches[0]);

        $this->assertLessThan(strpos($html, 'id="customAmountInput"'), strpos($html, 'class="mission-builders"'));
        $this->assertGreaterThan(strpos($html, 'aria-label="Preset donation amounts"'), strpos($html, 'class="mission-builders"'));
    }

    public function test_every_approved_campaign_and_amount_is_still_offered(): void
    {
        $response = $this->get('/donate')->assertOk();

        foreach (['Feed Filipino Children', 'Disaster Relief Fund', 'Uganda Clean Water Initiative', "Where it's needed most"] as $campaign) {
            $response->assertSee('data-campaign-opt="'.$campaign.'"', false);
        }

        preg_match_all('/<span class="amount-price">([^<]+)<\/span>/', $response->getContent(), $amounts);
        $this->assertSame('$7.99', $amounts[1][0]);
        $this->assertGreaterThan(1, count($amounts[1]));
        $response->assertSee('id="customAmountInput"', false);
    }

    public function test_monthly_partner_buttons_on_the_donation_page_reselect_7_99_monthly(): void
    {
        $this->get('/donate')->assertOk()->assertSee('<a href="#give" class="hcw-btn" id="hcwPartnerBtn">', false);

        $script = file_get_contents(public_path('js/for_donationpage.js'));

        $this->assertStringContainsString("document.querySelectorAll('a[href=\"#give\"]')", $script);
        $this->assertMatchesRegularExpression("/function selectMonthlyPartnerGift\(\).*?setCampaign\('Feed Filipino Children'\).*?monthlyTab.click\(\).*?=== '\\$7\.99'/s", $script);
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

        $response->assertSee('src="'.asset('images/who-we-are/Johnny%20Davis.jpeg').'" alt="Pastor Johnny Davis"', false);
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
