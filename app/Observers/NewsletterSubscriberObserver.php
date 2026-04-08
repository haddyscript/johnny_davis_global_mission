<?php

namespace App\Observers;

use App\Models\AdminNotification;
use App\Models\NewsletterSubscriber;

class NewsletterSubscriberObserver
{
    public function created(NewsletterSubscriber $subscriber): void
    {
        AdminNotification::newSubscriber($subscriber);
    }
}
