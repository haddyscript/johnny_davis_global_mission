<?php

namespace App\Helpers;

/**
 * Defines every known content-block key for each page → section combination.
 * Used to power the Key dropdown in the admin Content Block forms.
 *
 * Structure:  "{page_slug}.{section_slug}" => [ key definitions… ]
 * Each definition: ['key', 'type', 'label', 'hint']
 */
class CmsKeyMap
{
    /**
     * Returns the full map of all known keys, grouped by "page_slug.section_slug".
     *
     * @return array<string, array<int, array{key:string, type:string, label:string, hint:string}>>
     */
    public static function all(): array
    {
        $meta = [
            ['key' => 'title',       'type' => 'text', 'label' => 'Page Title',        'hint' => 'Browser tab title and <title> tag'],
            ['key' => 'description', 'type' => 'text', 'label' => 'Meta Description',  'hint' => 'SEO <meta name="description"> tag'],
        ];

        $heroCommon = [
            ['key' => 'eyebrow',       'type' => 'text', 'label' => 'Eyebrow / Badge',       'hint' => 'Small label above the headline'],
            ['key' => 'headline',      'type' => 'text', 'label' => 'Headline',               'hint' => 'Primary <h1> heading text'],
            ['key' => 'subtitle',      'type' => 'text', 'label' => 'Subtitle',               'hint' => 'Secondary line below the headline'],
            ['key' => 'description',   'type' => 'text', 'label' => 'Description',            'hint' => 'Supporting paragraph text'],
            ['key' => 'primary_cta',   'type' => 'text', 'label' => 'Primary CTA Label',      'hint' => 'Text for the main call-to-action button'],
            ['key' => 'secondary_cta', 'type' => 'text', 'label' => 'Secondary CTA Label',    'hint' => 'Text for the secondary button'],
        ];

        return [

            /* ── HOME ─────────────────────────────────────────── */
            'home.meta' => $meta,

            'home.hero' => array_merge($heroCommon, [
                ['key' => 'primary_cta_label',   'type' => 'text', 'label' => 'Primary CTA Label (alt)',   'hint' => 'Alternate field name used in home hero'],
                ['key' => 'secondary_cta_label', 'type' => 'text', 'label' => 'Secondary CTA Label (alt)', 'hint' => 'Alternate field name used in home hero'],
                ['key' => 'stats',               'type' => 'list', 'label' => 'Stats',                     'hint' => 'List of {value, label} stat items shown in the hero'],
            ]),

            'home.mission' => [
                ['key' => 'headline', 'type' => 'text', 'label' => 'Headline',      'hint' => 'Mission section heading'],
                ['key' => 'body',     'type' => 'text', 'label' => 'Body',          'hint' => 'Mission section paragraph text'],
                ['key' => 'pillars',  'type' => 'list', 'label' => 'Pillars',       'hint' => 'List of {title, description} focus-area cards'],
            ],

            'home.donation_highlight' => [
                ['key' => 'headline',    'type' => 'text', 'label' => 'Headline',     'hint' => 'Donation highlight heading'],
                ['key' => 'highlight_amount',    'type' => 'text', 'label' => 'Highlight Amount',     'hint' => 'Donation highlight amount text, e.g. "$7.99 / month = 1 child fed"'],
                ['key' => 'body',        'type' => 'text', 'label' => 'Body',         'hint' => 'Supporting paragraph text'],
                ['key' => 'button_text', 'type' => 'text', 'label' => 'Button Text',  'hint' => 'CTA button label (url field = destination)'],
            ],

            'home.urgency' => [
                ['key' => 'headline', 'type' => 'text', 'label' => 'Headline', 'hint' => 'Urgency section heading'],
                ['key' => 'body',     'type' => 'text', 'label' => 'Body',     'hint' => 'Urgency appeal paragraph'],
                ['key' => 'price',    'type' => 'text', 'label' => 'Price',    'hint' => 'Displayed donation amount e.g. "$25 / month"'],
            ],

            'home.disaster' => [
                ['key' => 'headline',   'type' => 'text', 'label' => 'Headline',   'hint' => 'Disaster relief section heading'],
                ['key' => 'body',       'type' => 'text', 'label' => 'Body',       'hint' => 'Disaster relief paragraph'],
                ['key' => 'highlights', 'type' => 'list', 'label' => 'Highlights', 'hint' => 'List of {title, description} highlight items'],
            ],

            'home.help' => [
                ['key' => 'headline', 'type' => 'text', 'label' => 'Headline', 'hint' => '"How You Can Help" section heading'],
                ['key' => 'body',     'type' => 'text', 'label' => 'Body',     'hint' => '"How You Can Help" section paragraph'],
                ['key' => 'cards',    'type' => 'list', 'label' => 'Cards',    'hint' => 'List of {title, description, cta_text, cta_url} help cards'],
            ],

            'home.testimonials' => [
                ['key' => 'headline', 'type' => 'text', 'label' => 'Headline', 'hint' => 'Testimonials section heading'],
                ['key' => 'quotes',   'type' => 'list', 'label' => 'Quotes',   'hint' => 'List of {quote, author, location} testimonial items'],
            ],

            'home.donate_cta' => [
                ['key' => 'headline',     'type' => 'text', 'label' => 'Headline',       'hint' => 'Donate CTA section heading'],
                ['key' => 'section_label',     'type' => 'text', 'label' => 'Section Label',       'hint' => 'Small label above the headline eg. "Make a Difference Today"'],
                ['key' => 'body',         'type' => 'text', 'label' => 'Body',           'hint' => 'Supporting paragraph'],
                ['key' => 'primary_cta',  'type' => 'text', 'label' => 'Primary CTA',   'hint' => 'Main donate button label (url field = destination)'],
                ['key' => 'secondary_cta','type' => 'text', 'label' => 'Secondary CTA', 'hint' => 'Monthly giving button label'],
                ['key' => 'impact_note',  'type' => 'text', 'label' => 'Impact Note',   'hint' => 'Small print below the buttons (tax/501c3 note)'],
            ],

            /* ── CONTACT ───────────────────────────────────────── */
            'contact.meta'  => $meta,
            'contact.hero'  => $heroCommon,

            /* ── NEWS ─────────────────────────────────────────── */
            'news.meta' => $meta,

            'news.hero' => array_merge($heroCommon, [
                ['key' => 'stats', 'type' => 'list', 'label' => 'Stats', 'hint' => 'List of {value, label} stat items shown in the hero'],
            ]),

            /* ── WHO WE ARE ───────────────────────────────────── */
            'who-we-are.meta' => $meta,
            'who-we-are.hero' => $heroCommon,

            /* ── MINISTRY ─────────────────────────────────────── */
            'ministry.meta' => $meta,

            'ministry.hero' => [
                ['key' => 'headline',      'type' => 'text', 'label' => 'Headline',            'hint' => 'Primary <h1> heading text'],
                ['key' => 'subtitle',      'type' => 'text', 'label' => 'Subtitle',            'hint' => 'Secondary line below the headline'],
                ['key' => 'description',   'type' => 'text', 'label' => 'Description',         'hint' => 'Supporting paragraph text'],
                ['key' => 'primary_cta',   'type' => 'text', 'label' => 'Primary CTA Label',   'hint' => 'Text for the main call-to-action button'],
                ['key' => 'secondary_cta', 'type' => 'text', 'label' => 'Secondary CTA Label', 'hint' => 'Text for the secondary button'],
            ],

            /* ── DONATE ───────────────────────────────────────── */
            'donate.meta' => $meta,

            'donate.hero' => [
                ['key' => 'headline',    'type' => 'text', 'label' => 'Headline',          'hint' => 'Donate page hero heading'],
                ['key' => 'subtitle',    'type' => 'text', 'label' => 'Subtitle',          'hint' => 'Secondary line below the headline'],
                ['key' => 'description', 'type' => 'text', 'label' => 'Description',       'hint' => 'Supporting paragraph text'],
                ['key' => 'primary_cta', 'type' => 'text', 'label' => 'Primary CTA Label', 'hint' => 'Main donate button label'],
            ],
        ];
    }

    /**
     * Build a section_id → key_definitions lookup, ready to JSON-encode for the frontend.
     *
     * @param  \Illuminate\Support\Collection $sections  Collection of Section models (with page loaded)
     * @return array<int, array>
     */
    public static function forSections($sections): array
    {
        $map = static::all();
        $result = [];

        foreach ($sections as $section) {
            $mapKey = $section->page->slug . '.' . $section->slug;
            $result[$section->id] = $map[$mapKey] ?? [];
        }

        return $result;
    }
}
