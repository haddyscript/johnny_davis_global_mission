<?php

namespace App\Observers;

use App\Models\AdminNotification;
use App\Models\Donation;
use Illuminate\Support\Facades\Log;

class DonationObserver
{
    public function created(Donation $donation): void
    {
        try {
            match ($donation->status) {
                'completed' => AdminNotification::newDonation($donation),
                'failed'    => AdminNotification::failedDonation($donation),
                'pending'   => AdminNotification::pendingDonation($donation),
                default     => null,
            };
        } catch (\Throwable $e) {
            Log::error('DonationObserver: failed to create notification', [
                'donation_id' => $donation->id,
                'status'      => $donation->status,
                'error'       => $e->getMessage(),
            ]);
        }
    }

    public function updated(Donation $donation): void
    {
        if (! $donation->wasChanged('status')) {
            return;
        }

        try {
            match ($donation->status) {
                'completed' => AdminNotification::newDonation($donation),
                'failed'    => AdminNotification::failedDonation($donation),
                default     => null,
            };
        } catch (\Throwable $e) {
            Log::error('DonationObserver: failed to create notification on status change', [
                'donation_id' => $donation->id,
                'status'      => $donation->status,
                'error'       => $e->getMessage(),
            ]);
        }
    }
}
