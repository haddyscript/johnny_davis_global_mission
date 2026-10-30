<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Remembers which marketing link brought a visitor in (?src=qr, ?utm_source=facebook, ...)
 * so the gift they make afterwards can be credited to that source in admin reporting.
 * Only a short, sanitized label is stored — never anything identifying the visitor.
 */
class CaptureDonationSource
{
    public const SESSION_KEY = 'donation_source';

    public function handle(Request $request, Closure $next): Response
    {
        $source = $request->query('src') ?? $request->query('utm_source');

        if ($request->isMethod('GET') && is_string($source)) {
            $label = substr(preg_replace('/[^a-z0-9_-]+/', '-', strtolower(trim($source))), 0, 50);
            $label = trim($label, '-');

            if ($label !== '') {
                $request->session()->put(self::SESSION_KEY, $label);
            }
        }

        return $next($request);
    }
}
