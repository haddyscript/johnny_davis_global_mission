<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NavItem;
use Illuminate\Http\Request;

class NavItemController extends Controller
{
    public function index()
    {
        $items = NavItem::orderBy('sort_order')->get();
        return view('admin.nav-items.index', compact('items'));
    }

    public function store(Request $request): \Illuminate\Http\JsonResponse
    {
        $data = $request->validate([
            'label'      => 'required|string|max:80',
            'url'        => 'required|string|max:500',
            'nav_class'  => 'nullable|string|max:100',
            'is_external'=> 'boolean',
        ]);

        $maxOrder = NavItem::max('sort_order') ?? 0;
        $item = NavItem::create(array_merge($data, [
            'is_active'   => true,
            'sort_order'  => $maxOrder + 10,
        ]));

        return response()->json(['ok' => true, 'item' => $item]);
    }

    public function update(Request $request, NavItem $navItem): \Illuminate\Http\JsonResponse
    {
        $data = $request->validate([
            'label'      => 'required|string|max:80',
            'url'        => 'required|string|max:500',
            'nav_class'  => 'nullable|string|max:100',
            'is_external'=> 'boolean',
        ]);

        $navItem->update($data);

        return response()->json(['ok' => true, 'item' => $navItem]);
    }

    public function destroy(NavItem $navItem): \Illuminate\Http\JsonResponse
    {
        $navItem->delete();
        return response()->json(['ok' => true]);
    }

    public function toggle(NavItem $navItem): \Illuminate\Http\JsonResponse
    {
        $navItem->update(['is_active' => ! $navItem->is_active]);
        return response()->json(['is_active' => $navItem->is_active]);
    }

    /** Save drag-and-drop sort order. Expects: { ids: [1, 3, 2, ...] } */
    public function reorder(Request $request): \Illuminate\Http\JsonResponse
    {
        $ids = $request->validate(['ids' => 'required|array', 'ids.*' => 'integer'])['ids'];

        foreach ($ids as $position => $id) {
            NavItem::where('id', $id)->update(['sort_order' => ($position + 1) * 10]);
        }

        return response()->json(['ok' => true]);
    }
}
