@extends('layouts.admin')

@section('title', 'Monthly Partners')
@section('page-title', 'Monthly Partners — Hunger Can’t Wait')

@section('content')

@if(! $stripeIsLive)
    <div class="admin-card" style="padding:14px 18px;margin-bottom:16px;border-left:4px solid #f59e0b;background:#fffbeb;">
        <strong>Stripe test mode.</strong> The site is using Stripe test keys, so this report shows test subscriptions only.
    </div>
@endif

@if($unverified > 0)
    <div class="admin-card" style="padding:14px 18px;margin-bottom:16px;border-left:4px solid #6366f1;background:#eef2ff;">
        {{ $unverified }} monthly {{ $unverified === 1 ? 'gift has' : 'gifts have' }} not been verified against Stripe yet and {{ $unverified === 1 ? 'is' : 'are' }} not counted.
        They are checked automatically every hour (<code>php artisan donations:sync-stripe-subscriptions</code>).
    </div>
@endif

{{-- Goal progress --}}
<div class="admin-card" style="padding:22px 24px;margin-bottom:16px;">
    <div style="display:flex;justify-content:space-between;align-items:baseline;flex-wrap:wrap;gap:8px;margin-bottom:12px;">
        <div>
            <div style="font-size:12px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:var(--text-muted);">Journey to {{ number_format($goal) }} monthly partners</div>
            <div style="font-size:30px;font-weight:800;color:var(--text-dark);" id="mp-active-partners">{{ number_format($stats['active_partners']) }}</div>
        </div>
        <div style="font-size:14px;color:var(--text-muted);">{{ $goalPercent }}% of goal · {{ $stats['active_partners'] >= $publicThreshold ? 'shown live on the homepage and donate page' : 'hidden publicly until '.number_format($publicThreshold).' partners' }}</div>
    </div>
    <div style="height:14px;border-radius:999px;background:#e5eaf1;overflow:hidden;">
        <div style="height:100%;width:{{ $goalPercent }}%;min-width:{{ $stats['active_partners'] > 0 ? '8px' : '0' }};background:linear-gradient(90deg,#f4511e,#f07c1e);border-radius:inherit;"></div>
    </div>
    <p style="margin:10px 0 0;font-size:12px;color:var(--text-muted);">
        A partner is a unique donor (by email) with an active Stripe monthly subscription. One-time gifts, failed or past-due payments, unfinished checkouts and cancellations are not counted.
    </p>
</div>

{{-- Period filter --}}
<form method="GET" action="{{ route('admin.monthly-partners.index') }}" style="margin-bottom:12px;display:flex;align-items:center;gap:10px;">
    <label for="mp-days" style="font-size:13px;font-weight:600;color:var(--text-dark);">Period</label>
    <div class="filter-select-wrap">
        <select name="days" id="mp-days" class="filter-select" onchange="this.form.submit()">
            @foreach($periods as $period)
                <option value="{{ $period }}" @selected($days === $period)>Last {{ $period }} days</option>
            @endforeach
        </select>
    </div>
</form>

<div class="pages-stats cols-4">
    <div class="stat-card" style="--accent:#10b981;">
        <div class="stat-icon" style="background:rgba(16,185,129,0.12);color:#059669;">🤝</div>
        <div>
            <div class="stat-value">{{ number_format($stats['active_subscriptions']) }}</div>
            <div class="stat-label">Active subscriptions</div>
        </div>
    </div>
    <div class="stat-card" style="--accent:#6366f1;">
        <div class="stat-icon" style="background:rgba(99,102,241,0.12);color:#4f46e5;">📈</div>
        <div>
            <div class="stat-value">${{ number_format($stats['monthly_revenue'], 2) }}</div>
            <div class="stat-label">Est. monthly recurring revenue</div>
        </div>
    </div>
    <div class="stat-card" style="--accent:#14b8a6;">
        <div class="stat-icon" style="background:rgba(20,184,166,0.12);color:#0f766e;">✨</div>
        <div>
            <div class="stat-value">{{ number_format($stats['new_subscriptions']) }}</div>
            <div class="stat-label">New ({{ $days }} days)</div>
        </div>
    </div>
    <div class="stat-card" style="--accent:#64748b;">
        <div class="stat-icon" style="background:rgba(100,116,139,0.12);color:#475569;">🚪</div>
        <div>
            <div class="stat-value">{{ number_format($stats['cancellations']) }}</div>
            <div class="stat-label">Cancellations ({{ $days }} days)</div>
        </div>
    </div>
</div>

<div style="margin:4px 0 8px;font-size:12px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:var(--text-muted);">Payment health</div>
<div class="pages-stats cols-4">
    <div class="stat-card" style="--accent:#ef4444;">
        <div class="stat-icon" style="background:rgba(239,68,68,0.12);color:#dc2626;">⚠️</div>
        <div>
            <div class="stat-value">{{ number_format($stats['past_due']) }}</div>
            <div class="stat-label">Past due now (failed or expired card)</div>
        </div>
    </div>
    <div class="stat-card" style="--accent:#f97316;">
        <div class="stat-icon" style="background:rgba(249,115,22,0.12);color:#ea580c;">💳</div>
        <div>
            <div class="stat-value">{{ number_format($stats['payment_failures']) }}</div>
            <div class="stat-label">Failed renewals ({{ $days }} days)</div>
        </div>
    </div>
    <div class="stat-card" style="--accent:#22c55e;">
        <div class="stat-icon" style="background:rgba(34,197,94,0.12);color:#16a34a;">🔄</div>
        <div>
            <div class="stat-value">{{ number_format($stats['recovered']) }}</div>
            <div class="stat-label">Recovered ({{ $days }} days)</div>
        </div>
    </div>
    <div class="stat-card" style="--accent:#94a3b8;">
        <div class="stat-icon" style="background:rgba(148,163,184,0.14);color:#64748b;">🚫</div>
        <div>
            <div class="stat-value">{{ number_format($stats['failed_checkouts']) }}</div>
            <div class="stat-label">Declined checkouts ({{ $days }} days)</div>
        </div>
    </div>
</div>

<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(320px,1fr));gap:16px;margin-bottom:16px;">
    <div class="admin-card pages-card">
        <div style="padding:16px 18px 6px;font-weight:700;color:var(--text-dark);">Partners by source</div>
        <div class="table-wrap">
            <table class="admin-table">
                <thead><tr><th>Source</th><th>Active now</th><th>New ({{ $days }}d)</th></tr></thead>
                <tbody>
                    @forelse($bySource as $row)
                        <tr>
                            <td><span class="db-badge badge-blue">{{ $row->source_label }}</span></td>
                            <td>{{ number_format($row->active_count) }}</td>
                            <td>{{ number_format($row->new_count) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="3" style="color:var(--text-muted);">No monthly partners yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <p style="padding:0 18px 14px;margin:0;font-size:12px;color:var(--text-muted);">
            Add <code>?src=qr</code>, <code>?src=sms</code>, <code>?src=email</code> or <code>?utm_source=…</code> to campaign links to credit gifts to that channel.
        </p>
    </div>

    <div class="admin-card pages-card">
        <div style="padding:16px 18px 6px;font-weight:700;color:var(--text-dark);">Active partners by campaign &amp; amount</div>
        <div class="table-wrap">
            <table class="admin-table">
                <thead><tr><th>Campaign</th><th>Monthly</th><th>Partners</th><th>MRR</th></tr></thead>
                <tbody>
                    @forelse($byPlan as $row)
                        <tr>
                            <td>{{ $row->campaign_name }}</td>
                            <td>${{ number_format($row->amount, 2) }}</td>
                            <td>{{ number_format($row->partner_count) }}</td>
                            <td>${{ number_format($row->plan_revenue, 2) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4" style="color:var(--text-muted);">No active subscriptions yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="admin-card pages-card">
    <div style="padding:16px 18px 6px;font-weight:700;color:var(--text-dark);">Monthly subscriptions</div>
    @if($recent->isEmpty())
        <div class="empty-state" style="display:flex;">
            <div class="empty-icon">🤝</div>
            <div class="empty-title">No monthly subscriptions yet</div>
            <div class="empty-sub">New $7.99 monthly partners will appear here as soon as Stripe confirms them.</div>
        </div>
    @else
        <div class="table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Donor</th>
                        <th>Campaign</th>
                        <th>Monthly</th>
                        <th>Subscription</th>
                        <th>Source</th>
                        <th>Started</th>
                        <th>Next charge</th>
                        <th style="width:60px;"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recent as $donation)
                        @php
                            $badge = match ($donation->subscription_status) {
                                'active' => 'badge-success',
                                'past_due', 'unpaid' => 'badge-danger',
                                'incomplete', 'trialing' => 'badge-warn',
                                default => 'badge-blue',
                            };
                        @endphp
                        <tr>
                            <td>
                                <div class="page-name">{{ $donation->full_name }}</div>
                                <div style="font-size:12px;color:var(--text-muted);">{{ $donation->email }}</div>
                            </td>
                            <td>{{ $donation->campaign_name }}</td>
                            <td>${{ number_format($donation->amount, 2) }}</td>
                            <td>
                                <span class="db-badge {{ $badge }}">{{ str_replace('_', ' ', $donation->subscription_status ?? 'unknown') }}</span>
                                @if($donation->subscription_canceled_at)
                                    <div style="font-size:11px;color:var(--text-muted);margin-top:3px;">ended {{ $donation->subscription_canceled_at->format('M j, Y') }}</div>
                                @elseif(in_array($donation->subscription_status, ['past_due', 'unpaid'], true) && $donation->payment_failed_at)
                                    <div style="font-size:11px;color:var(--text-muted);margin-top:3px;">failing since {{ $donation->payment_failed_at->format('M j, Y') }}</div>
                                @elseif($donation->subscription_recovered_at)
                                    <div style="font-size:11px;color:var(--text-muted);margin-top:3px;">recovered {{ $donation->subscription_recovered_at->format('M j, Y') }}</div>
                                @endif
                            </td>
                            <td>{{ $donation->source ?? 'website' }}</td>
                            <td><span class="cm-date">{{ $donation->created_at->format('M j, Y') }}</span></td>
                            <td><span class="cm-date">{{ $donation->next_billing_date?->format('M j, Y') ?? '—' }}</span></td>
                            <td>
                                <div class="row-actions">
                                    <a class="row-btn row-btn-view" title="View donation" href="{{ route('admin.donations.show', $donation) }}">👁️</a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="pagination-wrap">{{ $recent->links('vendor.pagination.admin') }}</div>
    @endif
</div>

@endsection
