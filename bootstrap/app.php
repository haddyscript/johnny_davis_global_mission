<?php

use App\Http\Middleware\CaptureDonationSource;
use App\Http\Middleware\CheckSiteLock;
use App\Http\Middleware\EnforceNavVisibility;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->trustProxies(at: '*');
        $middleware->validateCsrfTokens(except: [
            'stripe/webhook',
            'paypal/webhook',
        ]);
        $middleware->alias([
            'nav.visibility' => EnforceNavVisibility::class,
        ]);
        $middleware->appendToGroup('web', CheckSiteLock::class);
        $middleware->appendToGroup('web', CaptureDonationSource::class);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
