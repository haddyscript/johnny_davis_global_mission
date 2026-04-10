@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')

{{-- ── Page header ──────────────────────────────────────────── --}}
<div class="db-page-header">
    <div class="db-page-header-left">
        <h1 class="db-heading">Dashboard</h1>
        <p class="db-subheading">{{ $dayLabel }} · <span id="db-sync-label">Live</span></p>
    </div>
    <div class="db-page-header-right">
        <a href="{{ route('admin.donations.export') }}" class="db-btn db-btn-ghost">
            ⬇️ Download Report
        </a>
        <a href="{{ route('admin.donations.index') }}" class="db-btn db-btn-primary">
            + View Donations
        </a>
    </div>
</div>

{{-- ── Greeting banner ──────────────────────────────────────── --}}
@if($revenueThisMonth > 0 || $newDonorsThisMonth > 0)
<div class="db-banner">
    <span class="db-banner-icon">✅</span>
    <div class="db-banner-text">
        @if($revenueThisMonth > 0)
            <strong>${{ number_format($revenueThisMonth, 0) }} raised this month</strong>
            @if($revenueGrowth !== null && $revenueGrowth > 0)
                — {{ $revenueGrowth }}% above last month.
            @endif
        @endif
        @if($newDonorsThisMonth > 0)
            {{ $newDonorsThisMonth }} new donor{{ $newDonorsThisMonth > 1 ? 's' : '' }} joined this month.
        @endif
    </div>
</div>
@endif

{{-- ── Welcome ──────────────────────────────────────────────── --}}
<div class="db-welcome-row">
    <div>
        <div class="db-welcome-greeting">{{ $greeting }}, {{ $userName }} 👋</div>
        <p class="db-welcome-sub">Here's your mission dashboard — all programs, all impact, one view.</p>
    </div>
</div>

{{-- ── New Donors Highlight ──────────────────────────────────── --}}
@if($newDonors->isNotEmpty())
<div class="db-card" style="margin-bottom:24px;">
    <div class="db-card-header" style="padding-bottom:12px;">
        <div>
            <div class="db-card-title">🆕 New Donors</div>
            <div class="db-card-sub">First-time donors · Most recent</div>
        </div>
        <a href="{{ route('admin.donations.index', ['sort' => 'top_donors']) }}" class="db-btn db-btn-sm db-btn-ghost">
            View Top Donors →
        </a>
    </div>
    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(220px,1fr));gap:12px;padding:4px 0 8px;">
        @foreach($newDonors as $nd)
        <div style="display:flex;align-items:center;gap:12px;padding:12px 14px;background:var(--surface-strong);border-radius:12px;border:1px solid rgba(139,92,246,.12);">
            <div style="width:38px;height:38px;border-radius:10px;background:linear-gradient(135deg,#8b5cf6,#7c3aed);color:#fff;display:grid;place-items:center;font-weight:700;font-size:16px;flex-shrink:0;">
                {{ strtoupper(substr($nd->first_name, 0, 1)) }}
            </div>
            <div style="min-width:0;">
                <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
                    <div style="font-weight:600;font-size:13px;color:var(--text-dark);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:110px;" title="{{ $nd->full_name }}">
                        {{ $nd->full_name }}
                    </div>
                    <span style="font-size:10px;font-weight:700;padding:1px 6px;border-radius:5px;background:rgba(139,92,246,.12);color:#7c3aed;text-transform:uppercase;letter-spacing:.04em;border:1px solid rgba(139,92,246,.2);">New</span>
                </div>
                <div style="font-size:13px;font-weight:700;color:#16a34a;margin-top:1px;">${{ number_format((float)$nd->amount, 2) }}</div>
                <div style="font-size:11px;color:var(--text-muted);margin-top:1px;">{{ $nd->created_at->format('M j, Y') }} · {{ $nd->campaign_name }}</div>
            </div>
        </div>
        @endforeach
    </div>
    <div style="padding:8px 0 0;border-top:1px solid var(--border);margin-top:4px;">
        <a href="{{ route('admin.donations.index') }}" class="db-view-all-link">View All Donations →</a>
    </div>
</div>
@endif

{{-- ── 8-card stats grid ────────────────────────────────────── --}}
<div class="db-stats-grid">

    {{-- 1. Total Raised --}}
    <div class="db-stat-card">
        <div class="db-stat-header">
            <span class="db-stat-label">Total Raised (All Time)</span>
            <span class="db-stat-icon" style="background:#dcfce7;color:#16a34a;">💰</span>
        </div>
        <div class="db-stat-value">${{ number_format($totalRaised, 0) }}</div>
        @if($revenueGrowth !== null)
        <div class="db-stat-delta {{ $revenueGrowth >= 0 ? 'delta-up' : 'delta-down' }}">
            {{ $revenueGrowth >= 0 ? '↑' : '↓' }} {{ abs($revenueGrowth) }}% vs last month
        </div>
        @else
        <div class="db-stat-delta delta-neutral">All-time total</div>
        @endif
    </div>

    {{-- 2. Monthly Recurring MRR --}}
    <div class="db-stat-card">
        <div class="db-stat-header">
            <span class="db-stat-label">Monthly Recurring MRR</span>
            <span class="db-stat-icon" style="background:#dbeafe;color:#1d4ed8;">🔄</span>
        </div>
        <div class="db-stat-value">${{ number_format($monthlyMRR, 0) }}</div>
        <div class="db-stat-delta delta-neutral">{{ $monthlyCount }} active subscription{{ $monthlyCount !== 1 ? 's' : '' }}</div>
    </div>

    {{-- 3. One-Time Donations --}}
    <div class="db-stat-card">
        <div class="db-stat-header">
            <span class="db-stat-label">One-Time (This Month)</span>
            <span class="db-stat-icon" style="background:#fef3c7;color:#d97706;">⚡</span>
        </div>
        <div class="db-stat-value">${{ number_format($oneTimeThisMonth, 0) }}</div>
        <div class="db-stat-delta delta-neutral">{{ $oneTimeCount }} transaction{{ $oneTimeCount !== 1 ? 's' : '' }}</div>
    </div>

    {{-- 4. New Donors --}}
    <div class="db-stat-card">
        <div class="db-stat-header">
            <span class="db-stat-label">New Donors (This Month)</span>
            <span class="db-stat-icon" style="background:#f3e8ff;color:#7c3aed;">🆕</span>
        </div>
        <div class="db-stat-value">{{ $newDonorsThisMonth }}</div>
        @if($donorGrowth !== null)
        <div class="db-stat-delta {{ $donorGrowth >= 0 ? 'delta-up' : 'delta-down' }}">
            {{ $donorGrowth >= 0 ? '↑' : '↓' }} {{ abs($donorGrowth) }} vs last month
        </div>
        @else
        <div class="db-stat-delta delta-neutral">This month</div>
        @endif
    </div>

    {{-- 5. Total Donors All Time --}}
    <div class="db-stat-card">
        <div class="db-stat-header">
            <span class="db-stat-label">Total Donors (All Time)</span>
            <span class="db-stat-icon" style="background:#fce7f3;color:#be185d;">👥</span>
        </div>
        <div class="db-stat-value">{{ number_format($totalDonors) }}</div>
        <div class="db-stat-delta delta-neutral">Unique donors</div>
    </div>

    {{-- 6. Email Subscribers --}}
    <div class="db-stat-card">
        <div class="db-stat-header">
            <span class="db-stat-label">Email Subscribers</span>
            <span class="db-stat-icon" style="background:#e0f2fe;color:#0284c7;">📧</span>
        </div>
        <div class="db-stat-value">{{ number_format($totalSubscribers) }}</div>
        <div class="db-stat-delta delta-up">↑ {{ $newSubscribersThisMonth }} this month</div>
    </div>

    {{-- 7. Unread Messages --}}
    <div class="db-stat-card">
        <div class="db-stat-header">
            <span class="db-stat-label">Unread Messages</span>
            <span class="db-stat-icon" style="background:#fef9c3;color:#a16207;">✉️</span>
        </div>
        <div class="db-stat-value">{{ $unreadMessages }}</div>
        <div class="db-stat-delta {{ $unreadMessages > 0 ? 'delta-warn' : 'delta-neutral' }}">
            {{ $unreadMessages > 0 ? 'Needs attention' : 'All caught up' }}
        </div>
    </div>

    {{-- 8. Failed / Pending --}}
    <div class="db-stat-card">
        <div class="db-stat-header">
            <span class="db-stat-label">Failed Payments</span>
            <span class="db-stat-icon" style="background:#fee2e2;color:#dc2626;">❌</span>
        </div>
        <div class="db-stat-value">{{ $failedPayments }}</div>
        <div class="db-stat-delta {{ $failedPayments > 0 ? 'delta-warn' : 'delta-neutral' }}">
            {{ $pendingDonations }} pending review
        </div>
    </div>

</div>

{{-- ── Chart + Campaign Progress ────────────────────────────── --}}
<div class="db-mid-grid">

    {{-- Bar Chart --}}
    <div class="db-card">
        <div class="db-card-header">
            <div>
                <div class="db-card-title">Donation Revenue</div>
                <div class="db-card-sub">Daily total · One-time + Recurring · Last 30 days</div>
            </div>
        </div>
        <div class="db-chart-wrap">
            <canvas id="revenueChart" height="200"></canvas>
        </div>
    </div>

    {{-- Campaign Progress --}}
    <div class="db-card">
        <div class="db-card-header">
            <div class="db-card-title">Campaign Progress</div>
        </div>
        <div class="db-campaigns">
            @forelse($campaigns as $c)
            <div class="db-campaign-item">
                <div class="db-camp-top">
                    <span class="db-camp-name">
                        @if(!empty($c['icon'])) <span style="margin-right:4px;">{{ $c['icon'] }}</span> @endif
                        {{ $c['name'] }}
                    </span>
                    <span class="db-camp-pct" style="color:{{ $c['color']['bar'] }};">
                        {{ $c['goal'] ? $c['pct'] . '%' : '—' }}
                    </span>
                </div>
                @if($c['goal'])
                <div class="db-camp-bar-bg">
                    <div class="db-camp-bar-fill" style="width:{{ $c['pct'] }}%;background:{{ $c['color']['bar'] }};transition:width .3s;"></div>
                </div>
                <div class="db-camp-meta" style="display:flex;justify-content:space-between;">
                    <span>${{ number_format($c['raised'], 0) }} of ${{ number_format($c['goal'], 0) }}</span>
                    @if(isset($c['remaining']))
                        <span style="color:{{ $c['remaining'] > 0 ? '#94a3b8' : '#16a34a' }};">
                            {{ $c['remaining'] > 0 ? '$'.number_format($c['remaining'], 0).' left' : '✓ Funded' }}
                        </span>
                    @endif
                </div>
                @else
                <div class="db-camp-meta" style="color:#94a3b8;">Flexible · ${{ number_format($c['raised'], 0) }} raised</div>
                @endif
            </div>
            @empty
            <div style="padding:20px;text-align:center;color:var(--text-muted);font-size:13px;">
                No campaigns yet. <a href="{{ route('admin.campaigns.create') }}" style="color:#0f766e;">Create one →</a>
            </div>
            @endforelse
        </div>
        <div style="display:flex;gap:10px;padding:12px 0 0;border-top:1px solid var(--border);margin-top:4px;">
            <a href="{{ route('admin.campaigns.index') }}" class="db-view-all-link">Manage Campaigns →</a>
            <a href="{{ route('admin.donations.index') }}" class="db-view-all-link" style="margin-left:auto;">View Donations →</a>
        </div>
    </div>

</div>

{{-- ── Recent Donations + Action Required ──────────────────── --}}
<div class="db-bottom-grid">

    {{-- Recent Donations table --}}
    <div class="db-card">
        <div class="db-card-header">
            <div class="db-card-title">Recent Donations</div>
            <div style="display:flex;gap:8px;">
                <a href="{{ route('admin.donations.export') }}" class="db-btn db-btn-sm db-btn-ghost">Export CSV</a>
                <a href="{{ route('admin.donations.index') }}" class="db-btn db-btn-sm db-btn-primary">View All →</a>
            </div>
        </div>

        @if($recentDonations->isEmpty())
        <div class="db-empty">
            <span style="font-size:28px;">💰</span>
            <div>No donations yet</div>
        </div>
        @else
        <div class="db-table-wrap">
            <table class="db-table">
                <thead>
                    <tr>
                        <th>Donor</th>
                        <th>Campaign</th>
                        <th>Amount</th>
                        <th>Type</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentDonations as $d)
                    <tr>
                        <td>
                            <div style="display:flex;align-items:center;gap:8px;">
                                <div class="db-donor-avatar">{{ strtoupper(substr($d->first_name, 0, 1)) }}</div>
                                <div>
                                    <div style="font-weight:600;font-size:13px;">{{ $d->full_name }}</div>
                                    <div style="font-size:11px;color:#94a3b8;">{{ $d->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td style="font-size:13px;max-width:140px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;" title="{{ $d->campaign_name }}">
                            {{ $d->campaign_name }}
                        </td>
                        <td style="font-weight:700;color:#16a34a;">${{ number_format((float)$d->amount, 2) }}</td>
                        <td>
                            <span class="db-badge {{ $d->frequency === 'monthly' ? 'badge-blue' : 'badge-green' }}">
                                {{ $d->frequency === 'monthly' ? 'Monthly' : 'One-time' }}
                            </span>
                        </td>
                        <td style="font-size:12px;color:#64748b;">{{ $d->created_at->format('M j') }}</td>
                        <td>
                            <span class="db-badge {{ $d->status === 'completed' ? 'badge-success' : ($d->status === 'pending' ? 'badge-warn' : 'badge-danger') }}">
                                {{ ucfirst($d->status) }}
                            </span>
                        </td>
                        <td>
                            <button class="db-view-btn"
                                data-url="{{ route('admin.donations.show', $d) }}"
                                onclick="openDonationModal(this.dataset.url)">
                                View
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div style="padding:12px 20px;font-size:12px;color:#94a3b8;border-top:1px solid #f1f5f9;">
            Showing {{ $recentDonations->count() }} of {{ $totalDonationsCount }} donation{{ $totalDonationsCount !== 1 ? 's' : '' }}
        </div>
        @endif
    </div>

    {{-- Right column: Action Required + Upcoming --}}
    <div style="display:flex;flex-direction:column;gap:20px;">

        {{-- Action Required --}}
        <div class="db-card">
            <div class="db-card-header">
                <div class="db-card-title">🔔 Action Required</div>
            </div>
            @if($actionItems->isEmpty())
            <div class="db-empty" style="padding:20px;">
                <span style="font-size:22px;">✅</span>
                <div style="font-size:13px;">All clear — nothing needs attention.</div>
            </div>
            @else
            <div class="db-action-list">
                @foreach($actionItems as $item)
                <div class="db-action-item">
                    <div class="db-action-icon" style="background:{{ $item['bg'] }};color:{{ $item['color'] }};">
                        {!! $item['icon'] !!}
                    </div>
                    <div class="db-action-body">
                        <div class="db-action-title">{{ $item['title'] }}</div>
                        <div class="db-action-sub">{{ $item['sub'] }}</div>
                    </div>
                    <a href="{{ $item['url'] }}" class="db-action-btn">{{ $item['label'] }}</a>
                </div>
                @endforeach
            </div>
            @endif
        </div>

        {{-- Quick Links --}}
        <div class="db-card">
            <div class="db-card-header">
                <div class="db-card-title">📋 Quick Links</div>
            </div>
            <div class="db-quick-links">
                <a href="{{ route('admin.donations.index') }}" class="db-quick-link">
                    <span>💰</span> All Donations
                </a>
                <a href="{{ route('admin.contact-messages.index') }}" class="db-quick-link">
                    <span>✉️</span> Messages
                    @if($unreadMessages > 0)
                        <span class="db-quick-badge">{{ $unreadMessages }}</span>
                    @endif
                </a>
                <a href="{{ route('admin.subscribers.index') }}" class="db-quick-link">
                    <span>📧</span> Subscribers
                </a>
                <a href="{{ route('admin.nav-items.index') }}" class="db-quick-link">
                    <span>🔗</span> Navigation
                </a>
                <a href="{{ route('admin.pages.index') }}" class="db-quick-link">
                    <span>📄</span> Pages
                </a>
                <a href="{{ route('admin.email-templates.index') }}" class="db-quick-link">
                    <span>📨</span> Email Templates
                </a>
            </div>
        </div>

    </div>

</div>

{{-- ── Detail modal (reused from donations page) ───────────── --}}
<div class="modal-overlay" id="dash-detail-modal">
    <div class="modal-card" role="dialog" aria-modal="true"
         style="max-width:600px;width:95%;padding:0;overflow:hidden;text-align:left;">
        <div style="display:flex;align-items:center;justify-content:space-between;padding:18px 22px;border-bottom:1px solid var(--border);">
            <div style="display:flex;align-items:center;gap:12px;">
                <div id="dm-avatar" style="width:38px;height:38px;border-radius:10px;background:linear-gradient(135deg,#22c55e,#16a34a);color:#fff;display:grid;place-items:center;font-weight:700;font-size:16px;flex-shrink:0;"></div>
                <div>
                    <div style="font-weight:700;font-size:15px;" id="dm-name">—</div>
                    <div style="font-size:12px;color:var(--text-muted);" id="dm-email">—</div>
                </div>
            </div>
            <button class="modal-btn-cancel" id="dm-close" style="margin:0;padding:7px 14px;font-size:13px;">✕ Close</button>
        </div>
        <div id="dm-loading" style="padding:48px;text-align:center;color:var(--text-muted);">
            <div style="width:24px;height:24px;border:3px solid var(--border);border-top-color:#22c55e;border-radius:50%;animation:don-spin .7s linear infinite;margin:0 auto 12px;"></div>
            Loading…
        </div>
        <div id="dm-content" style="display:none;padding:20px 22px;">
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                <div class="don-detail-block"><div class="don-detail-label">Amount</div><div class="don-detail-value" id="dm-amount" style="font-size:22px;color:#16a34a;font-weight:800;"></div></div>
                <div class="don-detail-block"><div class="don-detail-label">Status</div><div class="don-detail-value" id="dm-status"></div></div>
                <div class="don-detail-block"><div class="don-detail-label">Campaign</div><div class="don-detail-value" id="dm-campaign"></div></div>
                <div class="don-detail-block"><div class="don-detail-label">Frequency</div><div class="don-detail-value" id="dm-freq"></div></div>
                <div class="don-detail-block"><div class="don-detail-label">Payment Method</div><div class="don-detail-value" id="dm-method"></div></div>
                <div class="don-detail-block"><div class="don-detail-label">Card Details</div><div class="don-detail-value" id="dm-card"></div></div>
                <div class="don-detail-block" style="grid-column:1/-1;"><div class="don-detail-label">Transaction ID</div><div class="don-detail-value" id="dm-txn" style="font-family:monospace;font-size:12px;"></div></div>
                <div class="don-detail-block"><div class="don-detail-label">Date</div><div class="don-detail-value" id="dm-date"></div></div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
{{-- Chart.js --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
(function () {

/* ── Revenue chart ── */
var ctx    = document.getElementById('revenueChart').getContext('2d');
var labels = @json($chartDates);
var data   = @json($chartTotals);
var maxVal = Math.max.apply(null, data);

new Chart(ctx, {
    type: 'bar',
    data: {
        labels: labels,
        datasets: [{
            data:            data,
            backgroundColor: 'rgba(34,197,94,.75)',
            hoverBackgroundColor: 'rgba(34,197,94,1)',
            borderRadius:    5,
            borderSkipped:   false,
        }],
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: { display: false },
            tooltip: {
                callbacks: {
                    label: function(ctx) {
                        return ' $' + ctx.raw.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                    }
                }
            }
        },
        scales: {
            x: {
                grid: { display: false },
                ticks: {
                    color: '#94a3b8',
                    font: { size: 10 },
                    maxTicksLimit: 10,
                    maxRotation: 0,
                },
            },
            y: {
                grid: { color: '#f1f5f9', drawBorder: false },
                ticks: {
                    color: '#94a3b8',
                    font: { size: 10 },
                    callback: function(v) { return v === 0 ? '$0' : '$' + (v >= 1000 ? (v / 1000).toFixed(1) + 'k' : v); }
                },
                beginAtZero: true,
            }
        }
    }
});

/* ── Donation detail modal ── */
function openDonationModal(url) {
    var modal   = document.getElementById('dash-detail-modal');
    var loading = document.getElementById('dm-loading');
    var content = document.getElementById('dm-content');
    modal.classList.add('modal-open');
    loading.style.display = 'block';
    content.style.display = 'none';

    fetch(url, { headers: { 'Accept': 'application/json' } })
        .then(function(r) { return r.json(); })
        .then(function(d) {
            document.getElementById('dm-avatar').textContent  = (d.first_name || '?')[0].toUpperCase();
            document.getElementById('dm-name').textContent    = d.full_name  || '—';
            document.getElementById('dm-email').textContent   = d.email      || '—';
            document.getElementById('dm-amount').textContent  = '$' + d.amount;
            document.getElementById('dm-campaign').textContent = d.campaign_name  || '—';
            document.getElementById('dm-freq').textContent    = d.frequency === 'monthly' ? '🔄 Monthly' : '⚡ One-time';
            document.getElementById('dm-method').textContent  = d.payment_method || '—';
            document.getElementById('dm-txn').textContent     = d.transaction_id  || '—';
            document.getElementById('dm-date').textContent    = d.created_at      || '—';
            var statusEl = document.getElementById('dm-status');
            statusEl.className = 'don-detail-value don-status-badge don-status-' + d.status;
            statusEl.textContent = d.status === 'completed' ? '✅ Completed' : d.status === 'pending' ? '⏳ Pending' : '❌ Failed';
            var cardEl = document.getElementById('dm-card');
            cardEl.textContent = (d.card_brand && d.card_last_four)
                ? d.card_brand + ' •••• ' + d.card_last_four
                : '—';
            loading.style.display = 'none';
            content.style.display = 'block';
        })
        .catch(function() {
            loading.innerHTML = '<span style="color:#ef4444;">Failed to load.</span>';
        });
}
window.openDonationModal = openDonationModal;

document.getElementById('dm-close').addEventListener('click', function() {
    document.getElementById('dash-detail-modal').classList.remove('modal-open');
});
document.getElementById('dash-detail-modal').addEventListener('click', function(e) {
    if (e.target === this) this.classList.remove('modal-open');
});
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') document.getElementById('dash-detail-modal').classList.remove('modal-open');
});

/* ── "Last synced" live label ── */
var syncEl = document.getElementById('db-sync-label');
if (syncEl) {
    var t = 0;
    setInterval(function() {
        t++;
        syncEl.textContent = t === 1 ? 'Last synced 1 min ago'
            : t < 60 ? 'Last synced ' + t + ' min ago'
            : 'Last synced ' + Math.floor(t / 60) + 'h ago';
    }, 60000);
}

})();
</script>
@endpush
