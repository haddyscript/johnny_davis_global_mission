<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckSiteLock
{
    public function handle(Request $request, Closure $next): Response
    {
        $flagFile = storage_path('app/site_locked');

        if (! file_exists($flagFile)) {
            return $next($request);
        }

        // Always allow: lock/unlock routes, the locked page itself, admin area, webhooks, auth
        $allowed = [
            'lock-website',
            'unlock-website',
            'site-locked',
            'deployer',
            'admin',
            'login',
            'logout',
            'register',
            'stripe/webhook',
            'paypal/webhook',
            '.well-known',
        ];

        $path = ltrim($request->getPathInfo(), '/');

        foreach ($allowed as $prefix) {
            if ($path === $prefix || str_starts_with($path, $prefix . '/')) {
                return $next($request);
            }
        }

        return redirect()->route('site-locked');
    }
}
