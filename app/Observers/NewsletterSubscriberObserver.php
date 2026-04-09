<?php

namespace App\Observers;

use App\Models\AdminNotification;
use App\Models\NewsletterSubscriber;
use Illuminate\Support\Facades\Log;

class NewsletterSubscriberObserver
{
    public function created(NewsletterSubscriber $subscriber): void
    {
        try {
            AdminNotification::newSubscriber($subscriber);
        } catch (\Throwable $e) {
            Log::error('NewsletterSubscriberObserver: failed to create notification', [
                'subscriber_id' => $subscriber->id,
                'error'         => $e->getMessage(),
            ]);
        }
    }
}
