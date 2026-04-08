<?php

namespace App\Providers;

use App\Models\ContactMessage;
use App\Models\Donation;
use App\Models\NewsletterSubscriber;
use App\Observers\ContactMessageObserver;
use App\Observers\DonationObserver;
use App\Observers\NewsletterSubscriberObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Donation::observe(DonationObserver::class);
        ContactMessage::observe(ContactMessageObserver::class);
        NewsletterSubscriber::observe(NewsletterSubscriberObserver::class);
    }
}
