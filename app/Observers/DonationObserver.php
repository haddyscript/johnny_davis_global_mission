<?php

namespace App\Observers;

use App\Models\AdminNotification;
use App\Models\Donation;

class DonationObserver
{
    public function created(Donation $donation): void
    {
        match ($donation->status) {
            'completed' => AdminNotification::newDonation($donation),
            'failed'    => AdminNotification::failedDonation($donation),
            'pending'   => AdminNotification::pendingDonation($donation),
            default     => null,
        };
    }

    public function updated(Donation $donation): void
    {
        if (! $donation->wasChanged('status')) {
            return;
        }

        match ($donation->status) {
            'completed' => AdminNotification::newDonation($donation),
            'failed'    => AdminNotification::failedDonation($donation),
            default     => null,
        };
    }
}
