@extends('layouts.admin')

@section('title', 'Donations')
@section('page-title', 'Donations')

@section('content')

{{-- Stats --}}
<div class="pages-stats">
    <div class="stat-card" style="--accent:#14b8a6;">
        <div class="stat-icon" style="background:rgba(20,184,166,0.12);color:#0f766e;">💰</div>
        <div>
            <div class="stat-value" data-target="{{ $stats['total'] }}">0</div>
            <div class="stat-label">Total Donations</div>
        </div>
    </div>
    <div class="stat-card" style="--accent:#10b981;">
        <div class="stat-icon" style="background:rgba(16,185,129,0.12);color:#059669;">✅</div>
        <div>
            <div class="stat-value" data-target="{{ $stats['completed'] }}">0</div>
            <div class="stat-label">Completed</div>
        </div>
    </div>
    <div class="stat-card" style="--accent:#f59e0b;">
        <div class="stat-icon" style="background:rgba(245,158,11,0.12);color:#d97706;">⏳</div>
        <div>
            <div class="stat-value" data-target="{{ $stats['pending'] }}">0</div>
            <div class="stat-label">Pending</div>
        </div>
    </div>
    <div class="stat-card" style="--accent:#ef4444;">
        <div class="stat-icon" style="background:rgba(239,68,68,0.12);color:#dc2626;">❌</div>
        <div>
            <div class="stat-value" data-target="{{ $stats['failed'] }}">0</div>
            <div class="stat-label">Failed</div>
        </div>
    </div>
    <div class="stat-card" style="--accent:#6366f1;">
        <div class="stat-icon" style="background:rgba(99,102,241,0.12);color:#4f46e5;">📈</div>
        <div>
            <div class="stat-value" data-currency data-target="{{ $stats['revenue'] }}">$0</div>
            <div class="stat-label">Total Revenue</div>
        </div>
    </div>
</div>

{{-- Main card --}}
<div class="admin-card pages-card">

    <form method="GET" action="{{ route('admin.donations.index') }}" id="filter-form">
        <div class="pages-toolbar">
            <div class="pages-toolbar-left" style="flex-wrap:wrap;gap:10px;">
                <div class="table-search-wrap">
                    <span class="table-search-icon">🔍</span>
                    <input type="text" name="search" id="page-search" class="table-search"
                        placeholder="Search donor, email, transaction…"
                        value="{{ request('search') }}" autocomplete="off">
                    <button type="button" class="table-search-clear" id="search-clear"
                        style="display:{{ request('search') ? 'flex' : 'none' }};">✕</button>
                </div>
                <div class="filter-select-wrap">
                    <select name="status" class="filter-select" onchange="this.form.submit()">
                        <option value="">All Status</option>
                        <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="pending"   {{ request('status') === 'pending'   ? 'selected' : '' }}>Pending</option>
                        <option value="failed"    {{ request('status') === 'failed'    ? 'selected' : '' }}>Failed</option>
                    </select>
                </div>
                <div class="filter-select-wrap">
                    <select name="payment_method" class="filter-select" onchange="this.form.submit()">
                        <option value="">All Methods</option>
                        <option value="card"   {{ request('payment_method') === 'card'   ? 'selected' : '' }}>Card</option>
                        <option value="gcash"  {{ request('payment_method') === 'gcash'  ? 'selected' : '' }}>GCash</option>
                        <option value="paypal" {{ request('payment_method') === 'paypal' ? 'selected' : '' }}>PayPal</option>
                    </select>
                </div>
                <div class="filter-select-wrap">
                    <select name="frequency" class="filter-select" onchange="this.form.submit()">
                        <option value="">All Frequencies</option>
                        <option value="one-time" {{ request('frequency') === 'one-time' ? 'selected' : '' }}>One-time</option>
                        <option value="monthly"  {{ request('frequency') === 'monthly'  ? 'selected' : '' }}>Monthly</option>
                    </select>
                </div>
                @if(request()->hasAny(['search','status','payment_method','frequency']))
                    <a href="{{ route('admin.donations.index') }}" class="filter-clear-link">Clear</a>
                @endif
            </div>
        </div>
    </form>

    @if($donations->isEmpty())
        <div class="empty-state" style="display:flex;">
            <div class="empty-icon">💰</div>
            <div class="empty-title">No donations found</div>
            <div class="empty-sub">
                @if(request()->hasAny(['search','status','payment_method','frequency']))
                    <a href="{{ route('admin.donations.index') }}" style="color:var(--brand-dark);">Clear filters</a>
                @else
                    Donations submitted through the website will appear here.
                @endif
            </div>
        </div>
    @else
    <div class="table-wrap">
        <table class="admin-table pages-table" id="donations-table">
            <thead>
                <tr>
                    <th style="width:48px;">#</th>
                    <th>Donor</th>
                    <th>Campaign</th>
                    <th>Amount</th>
                    <th>Method</th>
                    <th>Frequency</th>
                    <th>Status</th>
                    <th>Transaction Ref</th>
                    <th>Date</th>
                    <th style="width:80px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($donations as $donation)
                <tr class="page-row" style="opacity:0;transform:translateY(8px);">
                    <td style="color:var(--text-muted);font-size:12px;">{{ $donation->id }}</td>
                    <td>
                        <div style="display:flex;align-items:center;gap:10px;">
                            <div class="don-avatar">{{ strtoupper(substr($donation->first_name, 0, 1)) }}</div>
                            <div>
                                <div class="page-name">{{ $donation->full_name }}</div>
                                <div style="font-size:12px;color:var(--text-muted);">{{ $donation->email }}</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span style="font-size:13px;color:var(--text-dark);max-width:160px;display:inline-block;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;" title="{{ $donation->campaign_name }}">
                            {{ $donation->campaign_name }}
                        </span>
                    </td>
                    <td>
                        <span style="font-weight:700;font-size:15px;color:#0f766e;">${{ number_format($donation->amount, 2) }}</span>
                    </td>
                    <td>
                        <span class="don-method-badge don-method-{{ $donation->payment_method }}">
                            @if($donation->payment_method === 'card') 💳
                            @elseif($donation->payment_method === 'gcash') 📱
                            @else 🅿️
                            @endif
                            {{ ucfirst($donation->payment_method) }}
                            @if($donation->card_brand && $donation->card_last_four)
                                <span style="opacity:.7;">· {{ $donation->card_brand }} ••{{ $donation->card_last_four }}</span>
                            @endif
                        </span>
                    </td>
                    <td>
                        <span class="don-freq-badge {{ $donation->frequency === 'monthly' ? 'don-freq-monthly' : 'don-freq-once' }}">
                            {{ $donation->frequency === 'monthly' ? '🔄 Monthly' : '⚡ One-time' }}
                        </span>
                    </td>
                    <td>
                        <span class="don-status-badge don-status-{{ $donation->status }}">
                            @if($donation->status === 'completed') ✅ Completed
                            @elseif($donation->status === 'pending') ⏳ Pending
                            @else ❌ Failed
                            @endif
                        </span>
                    </td>
                    <td>
                        @if($donation->transaction_id)
                            <code style="font-size:11px;color:var(--text-muted);background:var(--surface-strong);padding:3px 7px;border-radius:6px;">
                                {{ Str::limit($donation->transaction_id, 18) }}
                            </code>
                        @else
                            <span style="color:var(--text-muted);font-size:13px;">—</span>
                        @endif
                    </td>
                    <td>
                        <span class="cm-date" title="{{ $donation->created_at->format('M j, Y g:i A') }}">
                            {{ $donation->created_at->format('M j, Y') }}
                        </span>
                    </td>
                    <td>
                        <div class="row-actions">
                            <button class="row-btn row-btn-view" title="View details"
                                data-url="{{ route('admin.donations.show', $donation) }}">👁️</button>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="table-footer" style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:12px;">
        <span>Showing {{ $donations->firstItem() }}–{{ $donations->lastItem() }} of {{ $donations->total() }} donation{{ $donations->total() !== 1 ? 's' : '' }}</span>
        @if($donations->hasPages())
            <div class="pagination-wrap">{{ $donations->links('vendor.pagination.admin') }}</div>
        @endif
    </div>
    @endif

</div>

{{-- Detail modal --}}
<div class="modal-overlay" id="detail-modal">
    <div class="modal-card" role="dialog" aria-modal="true"
         style="max-width:640px;width:95%;padding:0;overflow:hidden;text-align:left;">

        {{-- Modal header --}}
        <div style="display:flex;align-items:center;justify-content:space-between;padding:20px 24px;border-bottom:1px solid var(--border);">
            <div style="display:flex;align-items:center;gap:12px;">
                <div id="modal-avatar" style="width:42px;height:42px;border-radius:14px;background:linear-gradient(135deg,#14b8a6,#0f766e);color:#fff;display:grid;place-items:center;font-weight:700;font-size:18px;flex-shrink:0;"></div>
                <div>
                    <div style="font-weight:700;font-size:16px;" id="modal-donor-name">—</div>
                    <div style="font-size:12px;color:var(--text-muted);" id="modal-donor-email">—</div>
                </div>
            </div>
            <div style="display:flex;align-items:center;gap:10px;">
                <span id="modal-status-badge" class="don-status-badge"></span>
                <button class="modal-btn-cancel" id="modal-close" style="margin:0;padding:8px 16px;font-size:13px;">✕ Close</button>
            </div>
        </div>

        {{-- Loading state --}}
        <div id="modal-loading" style="padding:56px;text-align:center;color:var(--text-muted);">
            <div style="width:28px;height:28px;border:3px solid var(--border);border-top-color:var(--brand-bright);border-radius:50%;animation:don-spin .7s linear infinite;margin:0 auto 14px;"></div>
            Loading donation details…
        </div>

        {{-- Content --}}
        <div id="modal-content" style="display:none;">

            {{-- Amount hero --}}
            <div id="modal-amount-hero" style="padding:20px 24px;background:linear-gradient(135deg,#0f172a,#1e293b);text-align:center;">
                <div style="font-size:13px;color:rgba(255,255,255,.55);letter-spacing:.08em;text-transform:uppercase;margin-bottom:6px;">Donation Amount</div>
                <div style="font-size:36px;font-weight:800;color:#4ade80;" id="modal-amount"></div>
                <div style="margin-top:8px;" id="modal-freq-badge"></div>
            </div>

            {{-- Detail grid --}}
            <div style="padding:24px;display:grid;grid-template-columns:1fr 1fr;gap:14px;">

                <div class="don-detail-block">
                    <div class="don-detail-label">Campaign</div>
                    <div class="don-detail-value" id="modal-campaign"></div>
                </div>
                <div class="don-detail-block">
                    <div class="don-detail-label">Transaction Reference</div>
                    <div class="don-detail-value" id="modal-txn" style="font-family:monospace;font-size:12px;word-break:break-all;"></div>
                </div>
                <div class="don-detail-block">
                    <div class="don-detail-label">Payment Method</div>
                    <div class="don-detail-value" id="modal-method"></div>
                </div>
                <div class="don-detail-block">
                    <div class="don-detail-label">Card Details</div>
                    <div class="don-detail-value" id="modal-card"></div>
                </div>
                <div class="don-detail-block">
                    <div class="don-detail-label">Submitted</div>
                    <div class="don-detail-value" id="modal-created"></div>
                </div>
                <div class="don-detail-block">
                    <div class="don-detail-label">Last Updated</div>
                    <div class="don-detail-value" id="modal-updated"></div>
                </div>

            </div>
        </div>

    </div>
</div>

@endsection

@push('scripts')
<style>
/* ── Donation-specific styles ── */
.don-avatar {
    width: 36px; height: 36px; border-radius: 12px; flex-shrink: 0;
    background: linear-gradient(135deg, #14b8a6, #0f766e);
    color: #fff; display: grid; place-items: center;
    font-weight: 700; font-size: 14px;
}
.don-status-badge {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 4px 10px; border-radius: 8px; font-size: 12px; font-weight: 600; white-space: nowrap;
}
.don-status-completed { background: rgba(16,185,129,.1); color: #059669; }
.don-status-pending   { background: rgba(245,158,11,.1);  color: #d97706; }
.don-status-failed    { background: rgba(239,68,68,.1);   color: #dc2626; }

.don-freq-badge {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 3px 10px; border-radius: 8px; font-size: 12px; font-weight: 600;
}
.don-freq-monthly { background: rgba(99,102,241,.1); color: #4f46e5; }
.don-freq-once    { background: rgba(20,184,166,.1);  color: #0f766e; }

.don-method-badge {
    display: inline-flex; align-items: center; gap: 5px;
    font-size: 12px; font-weight: 600; color: var(--text-dark);
    background: var(--surface-strong); padding: 4px 10px; border-radius: 8px;
}

.don-detail-block {
    background: var(--surface-strong); border-radius: 12px; padding: 14px 16px;
}
.don-detail-label {
    font-size: 11px; text-transform: uppercase; letter-spacing: .08em;
    color: var(--text-muted); margin-bottom: 5px;
}
.don-detail-value {
    font-size: 14px; font-weight: 600; color: var(--text-dark); word-break: break-word;
}

@keyframes don-spin { to { transform: rotate(360deg); } }
</style>
<script>
(function () {

/* ── Stat counters ── */
document.querySelectorAll('.stat-value[data-target]').forEach(function (el) {
    var target = parseFloat(el.dataset.target);
    var isCurrency = el.hasAttribute('data-currency');
    var startTime  = null;
    function step(ts) {
        if (!startTime) startTime = ts;
        var p = Math.min((ts - startTime) / 700, 1);
        var val = p * target;
        el.textContent = isCurrency
            ? '$' + val.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })
            : Math.floor(val);
        if (p < 1) requestAnimationFrame(step);
        else el.textContent = isCurrency
            ? '$' + target.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })
            : Math.floor(target);
    }
    setTimeout(function () { requestAnimationFrame(step); }, 150);
});

/* ── Row entrance animation ── */
document.querySelectorAll('.page-row').forEach(function (row, i) {
    setTimeout(function () {
        row.style.transition = 'opacity .28s ease, transform .28s ease';
        row.style.opacity = '1'; row.style.transform = 'translateY(0)';
    }, 50 + i * 30);
});

/* ── Search with debounce ── */
var searchInput = document.getElementById('page-search');
var clearBtn    = document.getElementById('search-clear');
var filterForm  = document.getElementById('filter-form');
var timer;
if (searchInput) {
    searchInput.addEventListener('keydown', function (e) {
        if (e.key === 'Enter') { e.preventDefault(); filterForm.submit(); }
    });
    searchInput.addEventListener('input', function () {
        clearBtn.style.display = this.value ? 'flex' : 'none';
        clearTimeout(timer);
        timer = setTimeout(function () { filterForm.submit(); }, 600);
    });
}
if (clearBtn) {
    clearBtn.addEventListener('click', function () {
        searchInput.value = ''; clearBtn.style.display = 'none'; filterForm.submit();
    });
}

/* ── Detail modal via AJAX ── */
var modal         = document.getElementById('detail-modal');
var modalLoading  = document.getElementById('modal-loading');
var modalContent  = document.getElementById('modal-content');

function openModal(url) {
    modal.classList.add('modal-open');
    modalLoading.style.display = 'block';
    modalContent.style.display = 'none';

    fetch(url, { headers: { 'Accept': 'application/json' } })
        .then(function (r) { return r.json(); })
        .then(function (d) {
            /* Header */
            document.getElementById('modal-avatar').textContent = (d.first_name || '?')[0].toUpperCase();
            document.getElementById('modal-donor-name').textContent  = d.full_name  || '—';
            document.getElementById('modal-donor-email').textContent = d.email       || '—';

            /* Status badge */
            var badge = document.getElementById('modal-status-badge');
            badge.className = 'don-status-badge don-status-' + d.status;
            badge.textContent = d.status === 'completed' ? '✅ Completed'
                              : d.status === 'pending'   ? '⏳ Pending'
                              :                            '❌ Failed';

            /* Amount hero */
            document.getElementById('modal-amount').textContent = '$' + d.amount;
            var fBadge = document.getElementById('modal-freq-badge');
            fBadge.className = 'don-freq-badge ' + (d.frequency === 'monthly' ? 'don-freq-monthly' : 'don-freq-once');
            fBadge.textContent = d.frequency === 'monthly' ? '🔄 Monthly Recurring' : '⚡ One-time Gift';

            /* Detail grid */
            document.getElementById('modal-campaign').textContent  = d.campaign_name  || '—';
            document.getElementById('modal-txn').textContent       = d.transaction_id || '—';
            document.getElementById('modal-method').textContent    = d.payment_method ? d.payment_method.charAt(0).toUpperCase() + d.payment_method.slice(1) : '—';
            document.getElementById('modal-created').textContent   = d.created_at     || '—';
            document.getElementById('modal-updated').textContent   = d.updated_at     || '—';

            var cardEl = document.getElementById('modal-card');
            if (d.card_brand && d.card_last_four) {
                cardEl.textContent = d.card_brand + ' •••• ' + d.card_last_four
                    + (d.card_exp_month && d.card_exp_year ? '  (exp ' + d.card_exp_month + '/' + d.card_exp_year + ')' : '');
            } else {
                cardEl.textContent = '—';
            }

            modalLoading.style.display = 'none';
            modalContent.style.display = 'block';
        })
        .catch(function () {
            modalLoading.innerHTML = '<span style="color:#ef4444;">Failed to load details.</span>';
        });
}

document.querySelectorAll('.row-btn-view').forEach(function (btn) {
    btn.addEventListener('click', function () { openModal(btn.dataset.url); });
});

document.getElementById('modal-close').addEventListener('click', function () {
    modal.classList.remove('modal-open');
});
modal.addEventListener('click', function (e) {
    if (e.target === modal) modal.classList.remove('modal-open');
});
document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') modal.classList.remove('modal-open');
});

})();
</script>
@endpush
