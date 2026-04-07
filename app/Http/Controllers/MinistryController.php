<?php

namespace App\Http\Controllers;

use App\Helpers\CmsPageData;
use App\Models\Page;

class MinistryController extends Controller
{
    public function index()
    {
        $page = Page::with(['sections' => fn($q) => $q->orderBy('sort_order')
            ->with(['contentBlocks' => fn($q) => $q->orderBy('sort_order')])])
            ->where('slug', 'ministry')
            ->where('is_active', true)
            ->first();

        $cms = new CmsPageData($page);

        return view('ministry', [
            'title'       => $cms->text('meta', 'title', 'Johnny Davis Ministries — Transforming Lives'),
            'description' => $cms->text('meta', 'description', 'Johnny Davis Ministries — Transforming Lives, Empowering Communities, Expanding the Kingdom of God. Explore the ministry, Daily Push videos, podcast, and upcoming events.'),
            'cms'         => $cms,
        ]);
    }
}
