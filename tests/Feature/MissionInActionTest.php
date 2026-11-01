<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MissionInActionTest extends TestCase
{
    use RefreshDatabase;

    /** @var array<int, string> */
    private const VIDEO_IDS_IN_ORDER = [
        '7702684615672204574', // 1. From Shopping to Serving
        '7702674176703810846', // 2. Hear From the Children!
        '7702931280853273886', // 3. Feeding Filipino Children: Sharing God's Love
        '7702920164274113822', // 4. Feeding Outreach: General Santos City
        '7702948137345797406', // 5. Feeding Children: Purok XI-C, Fatima
        '7702906737132703006', // 6. Hear Their Stories: Real Voices, Real Impact
        '7702969922942405918', // 7. Meet the Hearts Behind the Mission
    ];

    private function section(): string
    {
        $html = $this->get('/')->assertOk()->getContent();

        preg_match('/<section id="mission-in-action".*?<\/section>/s', $html, $matches);
        $this->assertNotEmpty($matches, 'Mission in Action section is missing from the homepage.');

        return $matches[0];
    }

    public function test_section_sits_directly_beneath_the_campaign_tracker(): void
    {
        $this->get('/')->assertOk()->assertSeeInOrder([
            'id="hunger-campaign"',
            'Become a Monthly Partner',
            'id="mission-in-action"',
            'Our Mission in Action',
            'Watch the Difference!',
            'id="hero"',
        ], false);
    }

    public function test_seven_videos_play_on_the_site_in_the_requested_order(): void
    {
        $section = $this->section();

        $embeds = array_map(
            fn (string $id): string => 'src="https://www.tiktok.com/player/v1/'.$id.'?',
            self::VIDEO_IDS_IN_ORDER
        );

        $this->assertSame(7, substr_count($section, '<iframe'));
        $this->assertSame(7, substr_count($section, 'src="https://www.tiktok.com/player/v1/'));
        $this->get('/')->assertSeeInOrder($embeds, false);
        $this->assertStringNotContainsString('href="https://www.tiktok.com', $section);
    }

    public function test_videos_have_titles_in_the_requested_order(): void
    {
        $this->get('/')->assertSeeInOrder([
            'Video 1: From Shopping to Serving',
            'Video 2: Hear From the Children!',
            'Video 3: Feeding Filipino Children: Sharing God’s Love',
            'Video 4: Feeding Outreach: General Santos City',
            'Video 5: Feeding Children: Purok XI-C, Fatima',
            'Video 6: Hear Their Stories: Real Voices, Real Impact',
            'Video 7: Meet the Hearts Behind the Mission',
        ], false);
    }

    public function test_from_shopping_to_serving_is_featured_first(): void
    {
        $section = $this->section();

        $this->assertMatchesRegularExpression(
            '/class="mia-featured".*?player\/v1\/7702684615672204574.*?From Shopping to Serving/s',
            $section
        );
        $this->assertStringNotContainsString('7702684615672204574', substr($section, strpos($section, 'class="mia-grid"')));
    }

    public function test_team_poster_is_shown_alongside_video_seven(): void
    {
        $section = $this->section();

        $this->assertMatchesRegularExpression(
            '/class="mia-team-media">.*?player\/v1\/7702969922942405918.*?class="mia-poster">.*?meet-the-hearts-poster\.jpg/s',
            $section
        );
        $this->assertStringContainsString('Pastor Edgardo Buico', $section);
        $this->assertFileExists(public_path('images/mission-in-action/meet-the-hearts-poster.jpg'));
    }

    public function test_offscreen_videos_load_lazily(): void
    {
        $this->assertSame(8, substr_count($this->section(), 'loading="lazy"'));
    }

    public function test_monthly_partner_button_follows_the_videos(): void
    {
        $section = $this->section();

        $this->assertMatchesRegularExpression(
            '/meet-the-hearts-poster\.jpg.*?<a href="'.preg_quote(route('donate').'#give', '/').'" class="mia-btn">\s*Become a \$7\.99 Monthly Partner/s',
            $section
        );
    }
}
