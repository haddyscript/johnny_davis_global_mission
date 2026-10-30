<?php

namespace App\Services;

use App\Models\Donation;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Throwable;

/**
 * Live progress for the "Hunger Can't Wait!" $7.99 monthly partner campaign.
 *
 * Data source: the donations table, kept in sync with Stripe by the Stripe webhook
 * (real time) and the hourly `donations:sync-stripe-subscriptions` reconciliation.
 * A partner is one donor (unique email) with at least one active Stripe monthly
 * subscription. One-time gifts, failed or past-due payments, unfinished checkouts,
 * canceled subscriptions and Stripe test-mode gifts are never counted.
 */
class HungerCampaignTracker
{
    public const GOAL = 10000;

    public const MONTHLY_AMOUNT = 7.99;

    /** Seconds the public count is cached; webhooks clear it immediately on any change. */
    public const CACHE_SECONDS = 300;

    public const CACHE_KEY = 'hunger-campaign:active-partners';

    /** @var array<int, int> */
    private const MILESTONES = [100, 250, 500, 1000, 2500, 5000, 7500, 10000];

    /**
     * Public progress snapshot, or null when the count cannot be read
     * (the banner then hides the number instead of showing a wrong one).
     *
     * @return array{active_partners: int, goal: int, percent: float, next_milestone: int, updated_at: string}|null
     */
    public function progress(): ?array
    {
        try {
            $snapshot = Cache::remember(self::CACHE_KEY, self::CACHE_SECONDS, fn (): array => [
                'active_partners' => $this->countActivePartners(),
                'updated_at' => now()->toIso8601String(),
            ]);
        } catch (Throwable $e) {
            Log::error('Hunger campaign tracker unavailable', ['message' => $e->getMessage()]);

            return null;
        }

        $activePartners = $snapshot['active_partners'];

        return [
            'active_partners' => $activePartners,
            'goal' => self::GOAL,
            'percent' => round(min(100, $activePartners / self::GOAL * 100), 2),
            'next_milestone' => $this->nextMilestone($activePartners),
            'updated_at' => $snapshot['updated_at'],
        ];
    }

    /**
     * Unique donors with an active Stripe monthly subscription.
     */
    public function countActivePartners(): int
    {
        return Donation::activeMonthlyPartners()
            ->distinct()
            ->count('email');
    }

    /**
     * Clear the cached count so the next page view shows the latest Stripe data.
     */
    public static function forget(): void
    {
        Cache::forget(self::CACHE_KEY);
    }

    private function nextMilestone(int $activePartners): int
    {
        foreach (self::MILESTONES as $milestone) {
            if ($activePartners < $milestone) {
                return $milestone;
            }
        }

        return self::GOAL;
    }
}
