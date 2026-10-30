<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Donation;
use App\Services\HungerCampaignTracker;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Hunger Can't Wait — monthly partner report (Stripe subscriptions only).
 * Figures exclude Stripe test-mode gifts whenever the site runs on live Stripe keys.
 */
class MonthlyPartnerController extends Controller
{
    /** @var array<int, int> */
    private const PERIODS = [7, 30, 90, 365];

    public function index(Request $request, HungerCampaignTracker $tracker): View
    {
        $days = in_array((int) $request->input('days'), self::PERIODS, true) ? (int) $request->input('days') : 30;
        $since = now()->subDays($days);

        $active = Donation::activeMonthlyPartners();

        $stats = [
            'active_partners' => $tracker->countActivePartners(),
            'active_subscriptions' => (clone $active)->count(),
            'monthly_revenue' => (float) (clone $active)->sum('amount'),
            'new_subscriptions' => Donation::stripeSubscriptions()
                ->where('status', 'completed')
                ->where('created_at', '>=', $since)
                ->count(),
            'cancellations' => Donation::stripeSubscriptions()
                ->where('status', 'completed')
                ->where('subscription_canceled_at', '>=', $since)
                ->count(),
            'past_due' => Donation::stripeSubscriptions()
                ->whereIn('subscription_status', ['past_due', 'unpaid'])
                ->count(),
            'failed_checkouts' => Donation::where('frequency', 'monthly')
                ->where('payment_method', 'card')
                ->where('status', 'failed')
                ->where('created_at', '>=', $since)
                ->count(),
        ];

        $goalPercent = round(min(100, $stats['active_partners'] / HungerCampaignTracker::GOAL * 100), 2);

        $bySource = Donation::stripeSubscriptions()
            ->where('status', 'completed')
            ->selectRaw("COALESCE(source, 'website') AS source_label")
            ->selectRaw("SUM(CASE WHEN subscription_status = 'active' THEN 1 ELSE 0 END) AS active_count")
            ->selectRaw('SUM(CASE WHEN created_at >= ? THEN 1 ELSE 0 END) AS new_count', [$since])
            ->groupBy('source_label')
            ->orderByDesc('active_count')
            ->get();

        $byPlan = Donation::activeMonthlyPartners()
            ->selectRaw('campaign_name, amount, COUNT(*) AS partner_count, SUM(amount) AS plan_revenue')
            ->groupBy('campaign_name', 'amount')
            ->orderByDesc('partner_count')
            ->get();

        $recent = Donation::stripeSubscriptions()
            ->select(['id', 'first_name', 'last_name', 'email', 'campaign_name', 'amount', 'status',
                'subscription_status', 'source', 'next_billing_date', 'subscription_canceled_at', 'created_at'])
            ->latest()
            ->paginate(25)
            ->withQueryString();

        // Monthly card gifts not yet confirmed as live/test by Stripe (run the sync command)
        $unverified = Donation::where('frequency', 'monthly')
            ->where('payment_method', 'card')
            ->whereNotNull('subscription_id')
            ->whereNull('livemode')
            ->count();

        return view('admin.monthly-partners.index', [
            'stats' => $stats,
            'goal' => HungerCampaignTracker::GOAL,
            'goalPercent' => $goalPercent,
            'bySource' => $bySource,
            'byPlan' => $byPlan,
            'recent' => $recent,
            'unverified' => $unverified,
            'days' => $days,
            'periods' => self::PERIODS,
            'stripeIsLive' => Donation::stripeIsLive(),
        ]);
    }
}
