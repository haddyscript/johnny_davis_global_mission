<?php

namespace App\Helpers;

use App\Models\Page;

/**
 * Wraps a loaded CMS Page (with sections + content_blocks) and provides
 * typed, fallback-safe accessors for Blade views.
 *
 * Usage in views:
 *   {{ $cms->text('hero', 'headline', 'Default text') }}
 *   {{ $cms->image('hero', 'background', asset('images/default.webp')) }}
 *   {{ $cms->url('cta', 'link', route('donate')) }}
 *   @if($cms->has('promo'))  ...  @endif
 */
class CmsPageData
{
    private ?Page $page;

    /** @var array<string, array<string, \App\Models\ContentBlock>> */
    private array $blocks = [];

    public function __construct(?Page $page)
    {
        $this->page = $page;

        if ($page && $page->relationLoaded('sections')) {
            foreach ($page->sections as $section) {
                if ($section->relationLoaded('contentBlocks')) {
                    foreach ($section->contentBlocks as $block) {
                        $this->blocks[$section->slug][$block->key] = $block;
                    }
                }
            }
        }
    }

    /**
     * Return the text content of a block, or $fallback if it doesn't exist / is empty.
     */
    public function text(string $section, string $key, string $fallback = ''): string
    {
        $content = $this->blocks[$section][$key]->content ?? null;
        return ($content !== null && $content !== '') ? $content : $fallback;
    }

    /**
     * Return the URL of an image block (checks url field first, then content), or $fallback.
     */
    public function image(string $section, string $key, string $fallback = ''): string
    {
        $block = $this->blocks[$section][$key] ?? null;
        if (!$block) return $fallback;
        $value = $block->url ?? $block->content ?? '';
        return ($value !== '') ? $value : $fallback;
    }

    /**
     * Return the url field of a block, or $fallback.
     */
    public function url(string $section, string $key, string $fallback = ''): string
    {
        $block = $this->blocks[$section][$key] ?? null;
        if (!$block) return $fallback;
        $value = $block->url ?? '';
        return ($value !== '') ? $value : $fallback;
    }

    /**
     * Check whether a section (and optionally a specific block) exists in the CMS.
     */
    public function has(string $section, ?string $key = null): bool
    {
        if ($key === null) {
            return isset($this->blocks[$section]);
        }
        return isset($this->blocks[$section][$key]);
    }

    /**
     * Return the decoded `extra` array for a list-type block.
     * Used for blocks that store structured data (stats, cards, quotes…).
     * Returns an empty array when the block doesn't exist or has no extra data.
     */
    public function listItems(string $section, string $key): array
    {
        $block = $this->blocks[$section][$key] ?? null;
        if (!$block) return [];
        $extra = $block->extra ?? [];
        return is_array($extra) ? $extra : [];
    }

    /**
     * Whether a CMS page record was found at all.
     */
    public function loaded(): bool
    {
        return $this->page !== null;
    }

    /**
     * The raw Page model (or null).
     */
    public function page(): ?Page
    {
        return $this->page;
    }
}
