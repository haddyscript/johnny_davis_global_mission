<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\File;
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

    private const VIDEO_FIVE_FILE = 'videos/mission-in-action/feeding-children-purok-xi-c-fatima.mp4';

    /**
     * Run the callback with a Video 5 MP4 in place, leaving the real file untouched if it exists.
     */
    private function withVideoFiveFile(callable $callback): void
    {
        $path = public_path(self::VIDEO_FIVE_FILE);
        $created = ! file_exists($path);

        if ($created) {
            File::ensureDirectoryExists(dirname($path));
            File::put($path, 'test');
        }

        try {
            $callback();
        } finally {
            if ($created) {
                File::delete($path);
            }
        }
    }

    /**
     * Run the callback with no Video 5 MP4, restoring the real file afterwards if it exists.
     */
    private function withoutVideoFiveFile(callable $callback): void
    {
        $path = public_path(self::VIDEO_FIVE_FILE);
        $backup = $path.'.testing-backup';
        $moved = file_exists($path) && File::move($path, $backup);

        try {
            $callback();
        } finally {
            if ($moved) {
                File::move($backup, $path);
            }
        }
    }

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
        $this->withoutVideoFiveFile(function (): void {
            $section = $this->section();

            $players = array_map(
                fn (string $id): string => 'data-src="https://www.tiktok.com/player/v1/'.$id.'?autoplay=1',
                self::VIDEO_IDS_IN_ORDER
            );

            $this->assertSame(7, substr_count($section, 'data-mia-play'));
            $this->get('/')->assertSeeInOrder($players, false);
            $this->assertStringNotContainsString('href="https://www.tiktok.com', $section);
        });
    }

    public function test_video_five_plays_from_the_site_once_the_mp4_is_uploaded(): void
    {
        $this->withVideoFiveFile(function (): void {
            $section = $this->section();

            $this->assertSame(6, substr_count($section, 'data-mia-play'));
            $this->assertSame(1, substr_count($section, '<video'));
            $this->assertStringNotContainsString('player/v1/7702948137345797406', $section);
            $this->assertMatchesRegularExpression(
                '/<video src="'.preg_quote(asset(self::VIDEO_FIVE_FILE), '/').'\?v=\d+#t=0\.1"\s+title="Video 5: Feeding Children: Purok XI-C, Fatima"\s+controls\s+playsinline\s+preload="metadata"\s+poster="[^"]+thumbs\/7702948137345797406\.jpg"\s*><\/video>/',
                $section
            );
            $this->get('/')->assertSeeInOrder([
                'player/v1/7702920164274113822',
                '<video src="',
                'player/v1/7702906737132703006',
            ], false);
        });
    }

    public function test_video_five_falls_back_to_tiktok_until_the_mp4_is_uploaded(): void
    {
        $this->withoutVideoFiveFile(function (): void {
            $section = $this->section();

            $this->assertStringNotContainsString('<video', $section);
            $this->assertStringContainsString('data-src="https://www.tiktok.com/player/v1/7702948137345797406?', $section);
        });
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
            '/<article class="mia-featured" id="mia-panel-1" data-mia-panel="1" aria-labelledby="mia-video-1"\s*>.*?player\/v1\/7702684615672204574.*?From Shopping to Serving/s',
            $section
        );

        foreach (range(2, 6) as $number) {
            $this->assertMatchesRegularExpression('/id="mia-panel-'.$number.'"[^>]*\shidden\s*>/', $section);
        }
    }

    public function test_choosing_a_video_from_the_row_swaps_it_into_the_large_spot(): void
    {
        $section = $this->section();

        $this->assertSame(6, substr_count($section, 'data-mia-panel="'));
        $this->assertStringContainsString('data-mia-stage', $section);

        preg_match_all('/<li class="mia-pick"\s*(hidden)?\s*>\s*<button type="button" class="mia-pick-btn" data-mia-pick="(\d)" aria-controls="mia-panel-(\d)">/', $section, $picks);
        $this->assertSame(['1', '2', '3', '4', '5', '6'], $picks[2]);
        $this->assertSame($picks[2], $picks[3]);
        $this->assertSame(['hidden', '', '', '', '', ''], $picks[1], 'Only the video in the large spot is hidden from the row.');

        $this->assertStringContainsString('data-mia-pick', file_get_contents(public_path('js/mission-in-action.js')));
    }

    public function test_every_video_in_the_large_spot_shows_its_full_description(): void
    {
        $section = $this->section();

        $this->assertMatchesRegularExpression(
            '/id="mia-panel-2".*?id="mia-video-2">Hear From the Children!<\/h3>\s*<p class="mia-featured-lead">🇵🇭 HEAR THEIR VOICES! ❤️ \| FEED FILIPINO CHILDREN<\/p>\s*<p class="mia-featured-text">Listen as children and families share their stories.*?Brgy\. Fatima, Villaba, Leyte, Philippines\.<\/p>\s*<p class="mia-featured-text">Through the generosity of our partners.*?Gospel of Jesus Christ\.<\/p>.*?id="mia-panel-3"/s',
            $section
        );
        $this->assertMatchesRegularExpression(
            '/id="mia-panel-3".*?<p class="mia-featured-lead">🇵🇭 ANOTHER DAY OF FEEDING CHILDREN, SHARING GOD’S LOVE! ❤️<\/p>\s*<p class="mia-featured-tagline">HUNGER CAN’T WAIT! \| Bringing Hope to Children in the Philippines! 🌍<\/p>\s*<p class="mia-featured-text">📍 Purok XI-C, Fatima, General Santos City, Philippines<\/p>.*?<details class="mia-more">.*?❤️ WATCH THE STORY UNFOLD!.*?You are helping us put compassion into action!.*?id="mia-panel-4"/s',
            $section
        );

        $this->assertMatchesRegularExpression(
            '/id="mia-panel-4".*?<p class="mia-featured-lead">FEEDING CHILDREN, SHARING GOD’S LOVE! 🇵🇭<\/p>\s*<p class="mia-featured-tagline">HUNGER CAN’T WAIT! \| Real People\. Real Love\. Real Impact\. 🌍<\/p>.*?<details class="mia-more">\s*<summary.*?<\/summary>\s*<div class="mia-more-body">\s*<p class="mia-more-text">As the children begin gathering.*?More than 100 children.*?Every smile reminds us why this mission matters!.*?id="mia-panel-5"/s',
            $section
        );
        $this->assertMatchesRegularExpression(
            '/id="mia-panel-5".*?<p class="mia-featured-lead">😆 BRINGING SMILES TO FILIPINO CHILDREN! 🇵🇭<\/p>\s*<p class="mia-featured-tagline">HUNGER CAN’T WAIT! \| Feeding Children, Sharing God’s Love! 🌍<\/p>.*?<details class="mia-more">.*?❤️ MORE THAN JUST A MEAL!.*?rice, chicken, and fresh fruit.*?One Child\. One Meal\. One Act of Love at a Time! ❤️.*?id="mia-panel-6"/s',
            $section
        );
        $this->assertMatchesRegularExpression(
            '/id="mia-panel-6".*?<p class="mia-featured-lead">🎤 HEARING THE VOICES OF THE COMMUNITY!<\/p>\s*<p class="mia-featured-text">Our Assistant Director, Jennifer Buico joined.*?<details class="mia-more">\s*<summary.*?<\/summary>\s*<div class="mia-more-body">\s*<p class="mia-more-text">One young girl even took the microphone.*?❤️ OVER 100 CHILDREN AND THEIR FAMILIES REACHED!.*?even the smallest act of kindness can make a lasting difference!/s',
            $section
        );

        $this->assertSame(5, substr_count($section, 'class="mia-more"'), 'Every video but Video 2 has a full story to expand.');
        $this->assertSame(1, substr_count($section, 'class="mia-steps"'), 'Only Video 1 shows the Shop to Serve steps.');
    }

    public function test_from_shopping_to_serving_shows_an_intro_with_the_full_story_behind_a_toggle(): void
    {
        $section = $this->section();

        $this->assertMatchesRegularExpression(
            '/id="mia-video-1">From Shopping to Serving<\/h3>\s*<p class="mia-featured-lead">❤️ BEHIND EVERY MEAL IS A STORY OF LOVE, COMPASSION, AND SERVICE!<\/p>.*?It all begins with a mission.*?Watch as our Johnny Davis Global Missions feeding team.*?<details class="mia-more">/s',
            $section
        );

        preg_match('/<details class="mia-more">.*?<\/details>/s', $section, $details);
        $this->assertNotEmpty($details, 'The "Read the full story" toggle is missing.');
        $this->assertStringContainsString('Read the full story', $details[0]);
        $this->assertStringNotContainsString('<details class="mia-more" open', $section);
        $this->get('/')->assertSeeInOrder([
            '🚗 THE JOURNEY BEGINS!',
            '🛒 SHOPPING WITH A PURPOSE!',
            '⛺ PREPARING THE LOCATION!',
            '🍲 COOKING WITH LOVE!',
            '🍽️ THE MOMENT THAT MATTERS MOST!',
            'This is what your generosity makes possible!',
            'they are not forgotten.',
        ], false);
        $this->assertStringContainsString('they are not forgotten.', $details[0]);
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

    public function test_tiktok_players_only_load_when_play_is_pressed(): void
    {
        $this->withoutVideoFiveFile(function (): void {
            $section = $this->section();

            $this->assertStringNotContainsString('<iframe', $section);
            $this->assertStringContainsString('js/mission-in-action.js', $section);
            $this->assertSame(14, substr_count($section, 'loading="lazy"')); // seven thumbnails + six in the video row + team poster
            $this->assertFileExists(public_path('js/mission-in-action.js'));
        });
    }

    public function test_each_video_shows_its_own_poster_as_the_thumbnail(): void
    {
        $this->withoutVideoFiveFile(function (): void {
            $section = $this->section();

            foreach (self::VIDEO_IDS_IN_ORDER as $id) {
                $this->assertFileExists(public_path("images/mission-in-action/thumbs/{$id}.jpg"));
                $this->assertMatchesRegularExpression(
                    '/thumbs\/'.$id.'\.jpg\'\)"\s+data-mia-play\s+data-src="https:\/\/www\.tiktok\.com\/player\/v1\/'.$id.'\?/',
                    $section
                );
            }

            // Video 7's poster already sits beside it, so its thumbnail is the team photo with the title over it
            $this->assertMatchesRegularExpression(
                '/class="mia-facade mia-facade--poster\s+mia-facade--frame\s+mia-facade--titled\s*".*?player\/v1\/7702969922942405918\?.*?<span class="mia-facade-title">Meet the Hearts Behind the Mission<\/span>/s',
                $section
            );
        });
    }

    public function test_video_two_uses_a_frame_from_the_video_as_its_cover(): void
    {
        $this->assertMatchesRegularExpression(
            '/class="mia-facade mia-facade--poster\s+mia-facade--frame\s*"\s+style="--mia-thumb: url\(\'[^\']+thumbs\/7702674176703810846\.jpg\'\)"/',
            $this->section()
        );
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
