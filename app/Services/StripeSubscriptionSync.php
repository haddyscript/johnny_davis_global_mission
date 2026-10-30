<?php

namespace App\Services;

use App\Models\Donation;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Stripe\StripeObject;

/**
 * Copies a Stripe subscription's state onto the matching donation rows.
 *
 * Used by checkout confirmation, the Stripe webhook and the hourly reconciliation
 * so all three agree on what "active", "past_due" and "canceled" mean.
 * Works with the current Stripe API (billing period lives on subscription items)
 * and older webhook payloads (billing period on the subscription itself).
 */
class StripeSubscriptionSync
{
    /**
     * Apply the subscription's state and return the matching donations.
     *
     * @return Collection<int, Donation>
     */
    public function apply(StripeObject $subscription): Collection
    {
        $donations = Donation::where('subscription_id', $subscription->id)->get();

        foreach ($donations as $donation) {
            $donation->update($this->attributesFor($subscription, $donation));

            if ($subscription->status === 'active') {
                $this->markCompleted($donation);
            }
        }

        if ($donations->isNotEmpty()) {
            HungerCampaignTracker::forget();
        }

        return $donations;
    }

    /**
     * @return array{subscription_id: string, subscription_status: string, next_billing_date: Carbon|null, livemode: bool, subscription_canceled_at: Carbon|null}
     */
    public function attributesFor(StripeObject $subscription, ?Donation $donation = null): array
    {
        $periodEnd = $subscription->items->data[0]->current_period_end
            ?? $subscription->current_period_end
            ?? null;

        $canceledAt = null;
        if (in_array($subscription->status, ['canceled', 'incomplete_expired'], true)) {
            $endedAt = $subscription->ended_at ?? $subscription->canceled_at ?? null;
            $canceledAt = $donation?->subscription_canceled_at
                ?? ($endedAt ? Carbon::createFromTimestamp($endedAt) : now());
        }

        return [
            'subscription_id' => $subscription->id,
            'subscription_status' => $subscription->status,
            'next_billing_date' => $periodEnd && $subscription->status !== 'canceled'
                ? Carbon::createFromTimestamp($periodEnd)
                : null,
            'livemode' => (bool) $subscription->livemode,
            'subscription_canceled_at' => $canceledAt,
        ];
    }

    /**
     * Mark a pending gift as completed and send the confirmation email exactly once,
     * even if checkout confirmation and the webhook arrive at the same moment.
     */
    public function markCompleted(Donation $donation): bool
    {
        $updated = Donation::whereKey($donation->id)
            ->where('status', '!=', 'completed')
            ->update(['status' => 'completed']);

        if ($updated === 0) {
            return false;
        }

        $donation->refresh();
        app(DonationReceipt::class)->send($donation);

        return true;
    }
}
