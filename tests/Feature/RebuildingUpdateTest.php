<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RebuildingUpdateTest extends TestCase
{
    use RefreshDatabase;

    private const FLYER = 'images/rebuilding-update/more-than-meals-october-2026.webp';

    private function section(): string
    {
        $html = $this->get('/')->assertOk()->getContent();

        preg_match('/<section id="rebuilding-update".*?<\/section>/s', $html, $matches);
        $this->assertNotEmpty($matches, 'Rebuilding update section is missing from the homepage.');

        return $matches[0];
    }

    public function test_flyer_sits_directly_above_the_earthquake_relief_update(): void
    {
        $html = $this->get('/')->assertOk()->getContent();

        $this->assertMatchesRegularExpression(
            '/<\/section>\s*<!-- =+\s*EARTHQUAKE RELIEF VIDEO UPDATE\s*=+ -->\s*<section id="earthquake-relief-video"/',
            substr($html, strpos($html, 'id="rebuilding-update"'))
        );
        $this->assertSame(1, substr_count($html, 'id="rebuilding-update"'));
    }

    public function test_flyer_is_shown_unmodified_and_sized_for_layout(): void
    {
        $section = $this->section();

        $this->assertStringContainsString('src="'.asset(self::FLYER).'"', $section);
        $this->assertStringContainsString('width="1199" height="1312"', $section);
        $this->assertStringContainsString('alt="More than meals. We help rebuild lives.', $section);
        $this->assertFileExists(public_path(self::FLYER));
    }

    public function test_caption_highlights_the_october_2026_project_update(): void
    {
        $this->get('/')->assertSeeInOrder([
            'id="rebuilding-update"',
            '<strong>October 2026</strong> &mdash; Project Update',
            'Disaster Relief &amp; Rebuilding',
            'More Than Meals. <span>We Help Rebuild Lives!</span>',
            'Sarangani Mountain Region, Philippines',
            'Pastor Edgardo Buico, Philippines Ministry Director',
            'This is your generosity in action!',
            'THANK YOU!',
            'Sharing God&rsquo;s Love. Changing Lives.',
            'id="earthquake-relief-video"',
        ], false);
    }

    public function test_earthquake_relief_section_is_unchanged(): void
    {
        $this->get('/')->assertSeeInOrder([
            'id="earthquake-relief-video"',
            '🚨 Earthquake Relief Update — General Santos &amp; Sarangani 🚨',
            'Live From The Ground',
            'jdgm-earthquake-relief-update-interview.mov',
            'Donate Now — Be The Miracle',
        ], false);
    }

    public function test_section_styles_are_loaded(): void
    {
        $this->get('/')->assertSee('css/rebuilding-update.css', false);
        $this->assertFileExists(public_path('css/rebuilding-update.css'));
    }
}
