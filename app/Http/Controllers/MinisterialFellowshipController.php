<?php

namespace App\Http\Controllers;

use App\Helpers\CmsPageData;
use App\Models\Page;

class MinisterialFellowshipController extends Controller
{
    public function index()
    {
        // The Ministerial Fellowship page belongs to Johnny Davis Ministries only —
        // bounce visitors back home if they hit this route on the Global Missions domain.
        if (str_contains(request()->getHost(), 'johnnydavisglobalmissions.org')) {
            return redirect('/');
        }

        $page = Page::with(['sections' => fn($q) => $q->orderBy('sort_order')
            ->with(['contentBlocks' => fn($q) => $q->orderBy('sort_order')])])
            ->where('slug', 'ministerial-fellowship')
            ->where('is_active', true)
            ->first();

        $cms = new CmsPageData($page);

        return view('ministerial-fellowship', [
            'title'       => $cms->text('meta', 'title', 'Johnny Davis Ministerial Fellowship — Uganda Leadership Meeting'),
            'description' => $cms->text('meta', 'description', 'Johnny Davis Ministerial Fellowship — an international leadership fellowship equipping pastors, ministers, missionaries, and Christian leaders through biblical teaching, prayer, and leadership training. Weekly Uganda Leadership Meeting every Monday, 7:00 PM Uganda Time.'),
            'cms'         => $cms,
        ]);
    }
}
