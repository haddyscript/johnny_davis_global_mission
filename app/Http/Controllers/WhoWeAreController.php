<?php

namespace App\Http\Controllers;

use App\Helpers\CmsPageData;
use App\Models\Page;

class WhoWeAreController extends Controller
{
    public function index()
    {
        $page = Page::with(['sections' => fn($q) => $q->orderBy('sort_order')
            ->with(['contentBlocks' => fn($q) => $q->orderBy('sort_order')])])
            ->where('slug', 'who-we-are')
            ->where('is_active', true)
            ->first();

        $cms = new CmsPageData($page);

        return view('who-we-are', [
            'title'       => $cms->text('meta', 'title', 'Who We Are — Johnny Davis Global Missions'),
            'description' => $cms->text('meta', 'description', 'Who We Are — Johnny Davis Global Missions. Meet the team behind the mission to transform lives and empower communities across the Philippines.'),
            'cms'         => $cms,
        ]);
    }
}
