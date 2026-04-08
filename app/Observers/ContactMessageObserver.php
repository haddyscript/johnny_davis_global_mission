<?php

namespace App\Observers;

use App\Models\AdminNotification;
use App\Models\ContactMessage;

class ContactMessageObserver
{
    public function created(ContactMessage $message): void
    {
        AdminNotification::newContactMessage($message);
    }
}
