<?php

namespace App\Console\Commands;

use App\Models\Donation;
use App\Services\HungerCampaignTracker;
use App\Services\StripeSubscriptionSync;
use Illuminate\Console\Command;
use Stripe\Exception\ApiErrorException;
use Stripe\Stripe;
use Stripe\Subscription;

class SyncStripeSubscriptions extends Command
{
    /**
     * @var string
     */
    protected $signature = 'donations:sync-stripe-subscriptions';

    /**
     * @var string
     */
    protected $description = 'Reconcile monthly donation statuses with Stripe (catches any missed webhooks)';

    public function handle(StripeSubscriptionSync $sync): int
    {
        if (! config('services.stripe.secret')) {
            $this->warn('Stripe is not configured — nothing to sync.');

            return self::SUCCESS;
        }

        Stripe::setApiKey(config('services.stripe.secret'));

        $known = Donation::where('payment_method', 'card')
            ->whereNotNull('subscription_id')
            ->pluck('subscription_id')
            ->flip();

        $checked = 0;
        $updated = 0;

        try {
            $subscriptions = Subscription::all(['status' => 'all', 'limit' => 100]);

            foreach ($subscriptions->autoPagingIterator() as $subscription) {
                $checked++;

                if (! $known->has($subscription->id)) {
                    continue;
                }

                $updated += $sync->apply($subscription)->count();
            }
        } catch (ApiErrorException $e) {
            $this->error('Stripe sync failed: '.$e->getMessage());

            return self::FAILURE;
        }

        HungerCampaignTracker::forget();

        $this->info("Checked {$checked} Stripe subscriptions, updated {$updated} donation records.");

        return self::SUCCESS;
    }
}
