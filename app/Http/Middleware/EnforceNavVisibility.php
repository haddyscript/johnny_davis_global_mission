<?php

namespace App\Http\Middleware;

use App\Models\NavItem;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnforceNavVisibility
{
    /**
     * Block direct URL access to any page whose nav item is marked inactive (hidden).
     *
     * Only internal nav items are checked; external links are skipped entirely.
     * Only the exact path is matched, so POST/API sub-routes (e.g. /donate/charge)
     * are never affected.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Normalise to a leading-slash path: '' → '/', 'news' → '/news'
        $path = '/' . ltrim($request->path(), '/');

        $isHidden = NavItem::where('url', $path)
            ->where('is_external', false)
            ->where('is_active', false)
            ->exists();

        if ($isHidden) {
            abort(404);
        }

        return $next($request);
    }
}
