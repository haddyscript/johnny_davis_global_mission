<?php

namespace App\Services;

use App\Models\Donation;
use App\Models\EmailTemplate;
use App\Models\NewsletterSubscriber;

/**
 * Sends the donor's confirmation email and adds them to the newsletter list.
 */
class DonationReceipt
{
    public function __construct(private EmailService $emailService) {}

    public function send(Donation $donation): void
    {
        // Auto-add the donor to the newsletter subscribers list
        NewsletterSubscriber::syncDonor(
            email: $donation->email,
            firstName: $donation->first_name,
            frequency: $donation->frequency,
        );

        $template = EmailTemplate::where('name', 'Donation Confirmation')
            ->where('is_active', true)
            ->first();

        if (! $template) {
            return;
        }

        $this->emailService->sendTemplate(
            template: $template,
            toEmail: $donation->email,
            toName: $donation->full_name,
            data: [
                'donor_name' => $donation->full_name,
                'donation_amount' => '$'.number_format($donation->amount, 2)
                    .($donation->frequency === 'monthly' ? ' / month' : ''),
                'donation_date' => $donation->created_at->format('F j, Y'),
                'transaction_id' => $donation->transaction_id ?? '—',
                'manage_subscription_url' => config('services.stripe.portal_url') ?? '',
            ],
        );
    }
}
