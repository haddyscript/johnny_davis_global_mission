<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;

class PreviewController extends Controller
{
    public function page(string $slug)
    {
        $page = Page::with(['sections' => function ($q) {
            $q->orderBy('sort_order')->with(['contentBlocks' => function ($q) {
                $q->orderBy('sort_order');
            }]);
        }])->where('slug', $slug)->firstOrFail();

        return view('admin.preview.page', compact('page'));
    }
}
