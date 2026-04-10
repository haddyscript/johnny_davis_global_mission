<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminNotification;
use App\Models\Campaign;
use App\Models\ContactMessage;
use App\Models\Donation;
use App\Models\NewsletterSubscriber;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $now          = Carbon::now();
        $startMonth   = $now->copy()->startOfMonth();
        $lastMonthStart = $now->copy()->subMonth()->startOfMonth();
        $lastMonthEnd   = $now->copy()->subMonth()->endOfMonth();

        // ── Revenue stats ────────────────────────────────────────
        $totalRaised = (float) Donation::where('status', 'completed')->sum('amount');

        $revenueThisMonth = (float) Donation::where('status', 'completed')
            ->where('created_at', '>=', $startMonth)->sum('amount');
        $revenueLastMonth = (float) Donation::where('status', 'completed')
            ->where('created_at', '>=', $lastMonthStart)
            ->where('created_at', '<=', $lastMonthEnd)->sum('amount');
        $revenueGrowth = $revenueLastMonth > 0
            ? round((($revenueThisMonth - $revenueLastMonth) / $revenueLastMonth) * 100, 1)
            : null;

        // ── MRR ──────────────────────────────────────────────────
        $monthlyMRR = (float) Donation::where('status', 'completed')
            ->where('frequency', 'monthly')
            ->where('created_at', '>=', $startMonth)->sum('amount');
        $monthlyCount = Donation::where('status', 'completed')
            ->where('frequency', 'monthly')
            ->where('created_at', '>=', $startMonth)->count();

        // ── One-time ─────────────────────────────────────────────
        $oneTimeThisMonth = (float) Donation::where('status', 'completed')
            ->where('frequency', 'one-time')
            ->where('created_at', '>=', $startMonth)->sum('amount');
        $oneTimeCount = Donation::where('status', 'completed')
            ->where('frequency', 'one-time')
            ->where('created_at', '>=', $startMonth)->count();

        // ── Donors ───────────────────────────────────────────────
        $newDonorsThisMonth = Donation::where('status', 'completed')
            ->where('created_at', '>=', $startMonth)
            ->distinct('email')->count('email');
        $newDonorsLastMonth = Donation::where('status', 'completed')
            ->where('created_at', '>=', $lastMonthStart)
            ->where('created_at', '<=', $lastMonthEnd)
            ->distinct('email')->count('email');
        $donorGrowth = $newDonorsLastMonth > 0
            ? $newDonorsThisMonth - $newDonorsLastMonth
            : null;

        $totalDonors = Donation::where('status', 'completed')
            ->distinct('email')->count('email');

        // ── Subscribers ──────────────────────────────────────────
        $totalSubscribers        = NewsletterSubscriber::where('is_active', true)->count();
        $newSubscribersThisMonth = NewsletterSubscriber::where('is_active', true)
            ->where('created_at', '>=', $startMonth)->count();

        // ── Inbox / system ───────────────────────────────────────
        $unreadMessages  = ContactMessage::where('is_read', false)->count();
        $failedPayments  = Donation::where('status', 'failed')->count();
        $pendingDonations = Donation::where('status', 'pending')->count();
        $unreadNotifs    = AdminNotification::where('is_read', false)->count();

        // ── Campaign progress (dynamic from DB) ─────────────────
        $palette = [
            ['bar' => '#22c55e', 'bg' => '#dcfce7'],
            ['bar' => '#3b82f6', 'bg' => '#dbeafe'],
            ['bar' => '#f59e0b', 'bg' => '#fef3c7'],
            ['bar' => '#8b5cf6', 'bg' => '#ede9fe'],
            ['bar' => '#ec4899', 'bg' => '#fce7f3'],
            ['bar' => '#14b8a6', 'bg' => '#ccfbf1'],
        ];

        $donationTotals = Donation::where('status', 'completed')
            ->selectRaw('campaign_name, SUM(amount) as total')
            ->groupBy('campaign_name')
            ->pluck('total', 'campaign_name');

        $campaigns = Campaign::orderBy('sort_order')->get()
            ->values()
            ->map(function ($campaign, $index) use ($donationTotals, $palette) {
                $raised   = (float) ($donationTotals[$campaign->title] ?? 0);
                $goalNum  = (float) preg_replace('/[^0-9.]/', '', $campaign->goal_amount ?? '0');
                $pct      = ($goalNum > 0) ? min(100, round(($raised / $goalNum) * 100)) : 0;
                $remaining = ($goalNum > 0) ? max(0, $goalNum - $raised) : null;

                return [
                    'name'      => $campaign->title,
                    'icon'      => $campaign->icon,
                    'raised'    => $raised,
                    'goal'      => $goalNum ?: null,
                    'pct'       => $pct,
                    'remaining' => $remaining,
                    'color'     => $palette[$index % count($palette)],
                ];
            });

        // ── Chart: daily totals last 30 days ─────────────────────
        $rawChart = Donation::where('status', 'completed')
            ->where('created_at', '>=', $now->copy()->subDays(29)->startOfDay())
            ->selectRaw('DATE(created_at) as date, SUM(amount) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('total', 'date');

        $chartDates  = [];
        $chartTotals = [];
        for ($i = 29; $i >= 0; $i--) {
            $date          = $now->copy()->subDays($i)->format('Y-m-d');
            $chartDates[]  = $now->copy()->subDays($i)->format('M j');
            $chartTotals[] = (float) ($rawChart[$date] ?? 0);
        }

        // ── Recent donations ─────────────────────────────────────
        $recentDonations     = Donation::latest()->take(5)->get();
        $totalDonationsCount = Donation::count();

        // ── New donors (first-time donors, most recent) ───────────
        $firstTimerEmails = Donation::where('status', 'completed')
            ->selectRaw('email')
            ->groupBy('email')
            ->havingRaw('COUNT(*) = 1')
            ->pluck('email');

        $newDonors = Donation::where('status', 'completed')
            ->whereIn('email', $firstTimerEmails)
            ->latest()
            ->take(6)
            ->get();

        // ── Greeting ─────────────────────────────────────────────
        $hour     = (int) $now->format('H');
        $greeting = $hour < 12 ? 'Good morning' : ($hour < 17 ? 'Good afternoon' : 'Good evening');
        $userName = auth()->user()?->name ?? 'Pastor Johnny';
        $dayLabel = $now->format('l, F j, Y');

        // ── Action items ─────────────────────────────────────────
        $actionItems = collect();

        if ($failedPayments > 0) {
            $actionItems->push([
                'icon'    => '💳',
                'color'   => '#ef4444',
                'bg'      => '#fef2f2',
                'title'   => $failedPayments . ' Failed Payment' . ($failedPayments > 1 ? 's' : ''),
                'sub'     => 'Card declined · requires follow-up',
                'label'   => 'View',
                'url'     => route('admin.donations.index', ['status' => 'failed']),
            ]);
        }
        if ($unreadMessages > 0) {
            $actionItems->push([
                'icon'    => '✉️',
                'color'   => '#3b82f6',
                'bg'      => '#eff6ff',
                'title'   => $unreadMessages . ' Unread Message' . ($unreadMessages > 1 ? 's' : ''),
                'sub'     => 'Contact form submissions awaiting reply',
                'label'   => 'View',
                'url'     => route('admin.contact-messages.index'),
            ]);
        }
        if ($pendingDonations > 0) {
            $actionItems->push([
                'icon'    => '⏳',
                'color'   => '#f59e0b',
                'bg'      => '#fffbeb',
                'title'   => $pendingDonations . ' Pending Donation' . ($pendingDonations > 1 ? 's' : ''),
                'sub'     => 'Awaiting payment confirmation',
                'label'   => 'Review',
                'url'     => route('admin.donations.index', ['status' => 'pending']),
            ]);
        }
        if ($newSubscribersThisMonth > 0) {
            $actionItems->push([
                'icon'    => '📧',
                'color'   => '#8b5cf6',
                'bg'      => '#f5f3ff',
                'title'   => $newSubscribersThisMonth . ' New Subscriber' . ($newSubscribersThisMonth > 1 ? 's' : '') . ' This Month',
                'sub'     => 'Consider sending a welcome email',
                'label'   => 'View',
                'url'     => route('admin.subscribers.index'),
            ]);
        }

        return view('admin.dashboard.index', compact(
            'totalRaised', 'revenueThisMonth', 'revenueLastMonth', 'revenueGrowth',
            'monthlyMRR', 'monthlyCount',
            'oneTimeThisMonth', 'oneTimeCount',
            'newDonorsThisMonth', 'newDonorsLastMonth', 'donorGrowth', 'totalDonors',
            'totalSubscribers', 'newSubscribersThisMonth',
            'unreadMessages', 'failedPayments', 'pendingDonations', 'unreadNotifs',
            'campaigns',
            'chartDates', 'chartTotals',
            'recentDonations', 'totalDonationsCount',
            'newDonors',
            'greeting', 'userName', 'dayLabel',
            'actionItems'
        ));
    }
}
