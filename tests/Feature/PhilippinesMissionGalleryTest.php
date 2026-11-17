<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PhilippinesMissionGalleryTest extends TestCase
{
    use RefreshDatabase;

    private const DIR = 'images/philippines-mission/';

    /**
     * Photos in lightbox order, with their pixel sizes.
     *
     * @var array<string, array{0: int, 1: int}>
     */
    private const PHOTOS = [
        'pastor-buico.jpg' => [306, 428],
        'horseback-outreach.jpg' => [228, 340],
        'mission-banner-team.jpg' => [578, 254],
        'church-frame-raising.jpg' => [330, 206],
        'church-sanctuary-floor.jpg' => [382, 208],
        'church-roof-and-walls.jpg' => [360, 200],
        'faith-in-action-flyer.webp' => [1199, 1312],
    ];

    private function section(): string
    {
        $html = $this->get('/')->assertOk()->getContent();

        preg_match('/<section id="philippines-mission-gallery".*?<\/section>/s', $html, $matches);
        $this->assertNotEmpty($matches, 'Philippines mission gallery is missing from the homepage.');

        return $matches[0];
    }

    public function test_gallery_sits_directly_above_the_october_2026_rebuilding_update(): void
    {
        $html = $this->get('/')->assertOk()->getContent();

        $this->assertSame(1, substr_count($html, 'id="philippines-mission-gallery"'));
        $this->assertMatchesRegularExpression(
            '/<\/section>\s*<script src="[^"]*js\/philippines-mission-gallery\.js[^"]*" defer><\/script>\s*<section id="rebuilding-update"/',
            $html
        );
    }

    public function test_every_photo_is_shown_with_its_real_size_and_exists(): void
    {
        $section = $this->section();

        foreach (self::PHOTOS as $file => [$width, $height]) {
            $this->assertFileExists(public_path(self::DIR.$file));
            $this->assertSame([$width, $height], array_slice(getimagesize(public_path(self::DIR.$file)), 0, 2), $file);
            $this->assertStringContainsString('src="'.asset(self::DIR.$file).'"', $section);
            $this->assertStringContainsString('width="'.$width.'" height="'.$height.'"', $section);
        }
    }

    public function test_spotlight_features_pastor_buico_and_the_horseback_outreach(): void
    {
        $this->get('/')->assertSeeInOrder([
            'id="philippines-mission-gallery"',
            'Faith in Action: <span>Rebuilding Communities</span>',
            'Sarangani Mountains, Philippines',
            'Church Rehabilitation Project',
            'pastor-buico.jpg',
            'Pastor Edgardo Buico (Barco)',
            'Philippines Ministry Director',
            'horseback-outreach.jpg',
            'Horseback outreach',
            'Rebuilding a House of Worship',
            'Strengthening the Community',
            'Sharing the Love of Christ',
            'mission-banner-team.jpg',
            'Thank you to our generous donors!',
            'id="rebuilding-update"',
        ], false);
    }

    public function test_every_photo_opens_in_the_lightbox_in_gallery_order(): void
    {
        $section = $this->section();

        preg_match('/<script type="application\/json" id="pmgPhotos">(.*?)<\/script>/s', $section, $json);
        $photos = json_decode($json[1], true);

        $this->assertSame(
            array_map(fn (string $file): string => asset(self::DIR.$file), array_keys(self::PHOTOS)),
            array_column($photos, 'src')
        );

        preg_match_all('/class="pmg-open[^"]*" data-pmg-index="(\d+)"/', $section, $indexes);
        $this->assertEqualsCanonicalizing(range(0, count(self::PHOTOS) - 1), array_map('intval', $indexes[1]));

        foreach ($photos as $photo) {
            $this->assertNotSame('', $photo['alt']);
            $this->assertNotSame('', $photo['caption']);
        }
    }

    public function test_gallery_styles_and_script_are_loaded(): void
    {
        $this->get('/')
            ->assertSee('css/philippines-mission-gallery.css', false)
            ->assertSee('js/philippines-mission-gallery.js', false);

        $this->assertFileExists(public_path('css/philippines-mission-gallery.css'));
        $this->assertFileExists(public_path('js/philippines-mission-gallery.js'));
    }
}
