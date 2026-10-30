<?php

namespace App\Services;

use App\Models\Donation;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;
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
     * Bring a Stripe subscription the site has no record of into the donations table, e.g. one
     * created in the Stripe dashboard or a payment link, so partner counts and reporting match Stripe.
     *
     * A checkout started on the website (metadata.donation_id) is linked to its existing row instead.
     * Imported rows are written silently: no receipt or newsletter email is sent to the donor.
     * Expects the subscription to be retrieved with its customer expanded.
     */
    public function import(StripeObject $subscription): ?Donation
    {
        $interval = $subscription->items->data[0]->price->recurring->interval ?? null;

        if ($interval !== 'month') {
            return null;
        }

        $customer = $subscription->customer;
        $customerId = is_string($customer) ? $customer : $customer->id;

        $websiteDonation = Donation::whereKey($subscription->metadata->donation_id ?? 0)
            ->where('stripe_customer_id', $customerId)
            ->whereNull('subscription_id')
            ->first();

        if ($websiteDonation) {
            // Linked late, so mark it silently rather than emailing a receipt weeks afterwards
            $websiteDonation->update([
                'subscription_id' => $subscription->id,
                'status' => $subscription->status === 'active' ? 'completed' : $websiteDonation->status,
            ]);

            return $this->apply($subscription)->first();
        }

        $name = trim((string) (is_string($customer) ? '' : ($customer->name ?? '')));
        $email = is_string($customer) ? null : ($customer->email ?? null);

        $amountInCents = collect($subscription->items->data)
            ->sum(fn ($item): int => (int) ($item->price->unit_amount ?? 0) * (int) ($item->quantity ?? 1));

        $donation = new Donation([
            'campaign_name' => $subscription->metadata->campaign_name ?? 'Monthly Partner (Stripe)',
            'first_name' => $name !== '' ? Str::before($name, ' ') : 'Stripe',
            'last_name' => $name !== '' && str_contains($name, ' ') ? Str::after($name, ' ') : 'Donor',
            // One placeholder per customer keeps donors without an email counted separately
            'email' => $email ?: $customerId.'@no-email.invalid',
            'amount' => number_format($amountInCents / 100, 2, '.', ''),
            'frequency' => 'monthly',
            'payment_method' => 'card',
            'stripe_customer_id' => $customerId,
            'status' => in_array($subscription->status, ['incomplete', 'incomplete_expired'], true) ? 'failed' : 'completed',
            'source' => $subscription->metadata->source ?? 'stripe-direct',
            ...$this->attributesFor($subscription),
        ]);

        // Date the partnership from when it began in Stripe, not from today's import
        $donation->forceFill([
            'created_at' => Carbon::createFromTimestamp($subscription->created),
            'updated_at' => now(),
        ])->save();

        HungerCampaignTracker::forget();

        return $donation;
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
