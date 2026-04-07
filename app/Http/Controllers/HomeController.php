<?php

namespace App\Http\Controllers;

use App\Helpers\CmsPageData;
use App\Models\Page;

class HomeController extends Controller
{
    public function index()
    {
        $page = Page::with(['sections' => fn($q) => $q->orderBy('sort_order')
            ->with(['contentBlocks' => fn($q) => $q->orderBy('sort_order')])])
            ->where('slug', 'home')
            ->where('is_active', true)
            ->first();

        $cms = new CmsPageData($page);

        return view('home', [
            'title'       => $cms->text('meta', 'title', 'Johnny Davis Global Missions — Feed Filipino Children'),
            'description' => $cms->text('meta', 'description', 'Johnny Davis Global Missions — Feed Filipino Children. Donate to fight hunger, support disaster relief, and bring hope to communities in the Philippines.'),
            'cms'         => $cms,
        ]);
    }
}
