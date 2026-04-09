<?php

namespace App\Observers;

use App\Models\AdminNotification;
use App\Models\ContactMessage;
use Illuminate\Support\Facades\Log;

class ContactMessageObserver
{
    public function created(ContactMessage $message): void
    {
        try {
            AdminNotification::newContactMessage($message);
        } catch (\Throwable $e) {
            Log::error('ContactMessageObserver: failed to create notification', [
                'contact_message_id' => $message->id,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
