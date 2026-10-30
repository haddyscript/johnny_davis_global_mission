<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Keep monthly partner statuses (and the Hunger Can't Wait tracker) aligned with Stripe
Schedule::command('donations:sync-stripe-subscriptions')->hourly()->withoutOverlapping();
