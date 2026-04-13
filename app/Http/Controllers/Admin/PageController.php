<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NavItem;
use App\Models\Page;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function index()
    {
        $pages = Page::with('navItem')->orderBy('sort_order')->get();

        return view('admin.pages.index', compact('pages'));
    }

    public function create()
    {
        // Only nav items not yet linked to a page can be selected
        $navItems = NavItem::orderBy('sort_order')
            ->whereDoesntHave('page')
            ->get();

        return view('admin.pages.create', compact('navItems'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nav_item_id' => 'nullable|exists:nav_items,id|unique:pages,nav_item_id',
            'name'        => 'required|string|max:255',
            'slug'        => 'required|string|max:255|unique:pages,slug',
            'description' => 'nullable|string|max:300',
            'is_active'   => 'boolean',
            'sort_order'  => 'nullable|integer|min:0',
        ]);

        $validated['is_active']  = $request->boolean('is_active', true);
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        // Derive slug from nav item URL when one is selected
        if (! empty($validated['nav_item_id'])) {
            $navItem = NavItem::find($validated['nav_item_id']);
            $validated['slug'] = $this->slugFromNavUrl($navItem->url);
        }

        Page::create($validated);

        return redirect()->route('admin.pages.index')
            ->with('success', 'Page "' . $validated['name'] . '" created successfully.');
    }

    public function show(Page $page)
    {
        return view('admin.pages.show', compact('page'));
    }

    public function edit(Page $page)
    {
        // Available = items with no linked page, plus the one already linked to this page
        $navItems = NavItem::orderBy('sort_order')
            ->where(function ($q) use ($page) {
                $q->whereDoesntHave('page')
                  ->orWhereHas('page', fn($q2) => $q2->where('pages.id', $page->id));
            })
            ->get();

        return view('admin.pages.edit', compact('page', 'navItems'));
    }

    public function update(Request $request, Page $page)
    {
        $validated = $request->validate([
            'nav_item_id' => 'nullable|exists:nav_items,id|unique:pages,nav_item_id,' . $page->id,
            'name'        => 'required|string|max:255',
            'slug'        => 'required|string|max:255|unique:pages,slug,' . $page->id,
            'description' => 'nullable|string|max:300',
            'is_active'   => 'boolean',
            'sort_order'  => 'nullable|integer|min:0',
        ]);

        $validated['is_active']  = $request->boolean('is_active');
        $validated['sort_order'] = $validated['sort_order'] ?? $page->sort_order;

        // Re-derive slug if a (different) nav item is linked
        if (! empty($validated['nav_item_id'])) {
            $navItem = NavItem::find($validated['nav_item_id']);
            $validated['slug'] = $this->slugFromNavUrl($navItem->url);
        }

        $page->update($validated);

        return redirect()->route('admin.pages.index')
            ->with('success', 'Page "' . $page->name . '" updated successfully.');
    }

    public function destroy(Page $page)
    {
        $title = $page->name;
        $page->delete();

        return redirect()->route('admin.pages.index')
            ->with('success', 'Page "' . $title . '" deleted.');
    }

    public function toggle(Page $page)
    {
        $page->update(['is_active' => ! $page->is_active]);

        return response()->json(['is_active' => $page->is_active]);
    }

    // ── Helpers ─────────────────────────────────────────────────────────────

    /**
     * Derive a page slug from a nav item URL.
     * "/" → "home", "/donate" → "donate", "/who-we-are" → "who-we-are"
     */
    private function slugFromNavUrl(string $url): string
    {
        $path = ltrim(trim($url), '/');
        return $path === '' ? 'home' : $path;
    }
}
