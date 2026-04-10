@extends('layouts.admin')

@section('title', 'Campaigns')
@section('page-title', 'Campaigns')

@section('content')

{{-- Stats --}}
<div class="pages-stats cols-3">
    <div class="stat-card" style="--accent:#14b8a6;">
        <div class="stat-icon" style="background:rgba(20,184,166,0.12);color:#0f766e;">🎯</div>
        <div>
            <div class="stat-value" data-target="{{ $stats['total'] }}">0</div>
            <div class="stat-label">Total Campaigns</div>
        </div>
    </div>
    <div class="stat-card" style="--accent:#10b981;">
        <div class="stat-icon" style="background:rgba(16,185,129,0.12);color:#059669;">✅</div>
        <div>
            <div class="stat-value" data-target="{{ $stats['active'] }}">0</div>
            <div class="stat-label">Active</div>
        </div>
    </div>
    <div class="stat-card" style="--accent:#94a3b8;">
        <div class="stat-icon" style="background:rgba(148,163,184,0.12);color:#64748b;">⏸️</div>
        <div>
            <div class="stat-value" data-target="{{ $stats['inactive'] }}">0</div>
            <div class="stat-label">Inactive</div>
        </div>
    </div>
</div>

{{-- Flash --}}
@if(session('success'))
    <div class="flash-success" style="display:flex;align-items:center;gap:10px;background:rgba(16,185,129,.08);border:1px solid rgba(16,185,129,.25);color:#065f46;padding:14px 18px;border-radius:12px;font-size:14px;margin-bottom:18px;">
        ✅ {{ session('success') }}
    </div>
@endif

{{-- Main card --}}
<div class="admin-card pages-card">

    <form method="GET" action="{{ route('admin.campaigns.index') }}" id="filter-form">
        <div class="pages-toolbar">
            <div class="pages-toolbar-left" style="flex-wrap:wrap;gap:10px;">
                <a href="{{ route('admin.campaigns.create') }}"
                   style="display:inline-flex;align-items:center;gap:6px;padding:8px 16px;border-radius:8px;font-size:13px;font-weight:600;background:linear-gradient(135deg,#0f766e,#14b8a6);color:#fff;text-decoration:none;">
                    + New Campaign
                </a>
                <div class="table-search-wrap">
                    <span class="table-search-icon">🔍</span>
                    <input type="text" name="search" id="page-search" class="table-search"
                        placeholder="Search campaigns…"
                        value="{{ request('search') }}" autocomplete="off">
                    <button type="button" class="table-search-clear" id="search-clear"
                        style="display:{{ request('search') ? 'flex' : 'none' }};">✕</button>
                </div>
                <div class="filter-select-wrap">
                    <select name="status" class="filter-select" onchange="this.form.submit()">
                        <option value="">All Status</option>
                        <option value="active"   {{ request('status') === 'active'   ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
                @if(request()->hasAny(['search', 'status']))
                    <a href="{{ route('admin.campaigns.index') }}" class="filter-clear-link">Clear</a>
                @endif
            </div>
        </div>
    </form>

    @if($campaigns->isEmpty())
        <div class="empty-state" style="display:flex;">
            <div class="empty-icon">🎯</div>
            <div class="empty-title">No campaigns found</div>
            <div class="empty-sub">
                @if(request()->hasAny(['search', 'status']))
                    <a href="{{ route('admin.campaigns.index') }}" style="color:var(--brand-dark);">Clear filters</a>
                @else
                    <a href="{{ route('admin.campaigns.create') }}" style="color:var(--brand-dark);">Create your first campaign →</a>
                @endif
            </div>
        </div>
    @else
    <div class="table-wrap">
        <table class="admin-table pages-table">
            <thead>
                <tr>
                    <th style="width:48px;">Order</th>
                    <th>Campaign</th>
                    <th>Progress (Live)</th>
                    <th>Label</th>
                    <th>Status</th>
                    <th>Updated</th>
                    <th style="width:110px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($campaigns as $campaign)
                <tr class="page-row" style="opacity:0;transform:translateY(8px);">
                    <td>
                        <span style="display:inline-flex;align-items:center;justify-content:center;width:28px;height:28px;background:var(--surface-strong);border-radius:8px;font-size:13px;font-weight:700;color:var(--text-muted);">
                            {{ $campaign->sort_order }}
                        </span>
                    </td>
                    <td>
                        <div style="display:flex;align-items:center;gap:10px;">
                            <div style="width:36px;height:36px;border-radius:10px;background:linear-gradient(135deg,#0f766e,#14b8a6);display:grid;place-items:center;font-size:18px;flex-shrink:0;">
                                {{ $campaign->icon }}
                            </div>
                            <div>
                                <div class="page-name">{{ $campaign->title }}</div>
                                <div style="font-size:12px;color:var(--text-muted);margin-top:2px;max-width:280px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                                    {{ $campaign->snippet }}
                                </div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div style="min-width:150px;">
                            <div style="display:flex;justify-content:space-between;font-size:12px;margin-bottom:4px;">
                                <span style="color:var(--text-muted);">
                                    ${{ number_format($campaign->total_donated, 0) }} raised
                                </span>
                                <span style="font-weight:700;color:#0f766e;">{{ $campaign->computed_pct }}%</span>
                            </div>
                            <div style="height:6px;background:var(--surface-strong);border-radius:99px;overflow:hidden;">
                                <div style="height:100%;width:{{ $campaign->computed_pct }}%;border-radius:99px;{{ $campaign->bar_style ?: 'background:linear-gradient(90deg,#0f766e,#14b8a6);' }}transition:width .3s;"></div>
                            </div>
                            <div style="font-size:11px;color:var(--text-muted);margin-top:3px;display:flex;justify-content:space-between;">
                                <span>Goal: {{ $campaign->goal_amount ?: '—' }}</span>
                                @if($campaign->remaining !== null)
                                    <span style="color:{{ $campaign->remaining > 0 ? '#94a3b8' : '#16a34a' }};">
                                        {{ $campaign->remaining > 0 ? '$'.number_format($campaign->remaining, 0).' left' : '✓ Funded' }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td>
                        <span style="font-size:12px;padding:3px 10px;border-radius:8px;background:var(--surface-strong);color:var(--text-dark);font-weight:600;">
                            {{ $campaign->label }}
                        </span>
                    </td>
                    <td>
                        <button
                            class="status-toggle {{ $campaign->is_active ? 'is-active' : 'is-inactive' }}"
                            data-url="{{ route('admin.campaigns.toggle', $campaign) }}"
                            data-csrf="{{ csrf_token() }}"
                            title="Click to toggle">
                            <span class="toggle-dot"></span>
                            <span class="toggle-label">{{ $campaign->is_active ? 'Active' : 'Inactive' }}</span>
                        </button>
                    </td>
                    <td>
                        <span class="cm-date" title="{{ $campaign->updated_at->format('M j, Y g:i A') }}">
                            {{ $campaign->updated_at->format('M j, Y') }}
                        </span>
                    </td>
                    <td>
                        <div class="row-actions">
                            <a href="{{ route('admin.campaigns.edit', $campaign) }}"
                               class="row-btn" title="Edit" style="text-decoration:none;">✏️</a>
                            <button class="row-btn row-btn-delete" title="Delete"
                                data-name="{{ $campaign->title }}"
                                data-action="{{ route('admin.campaigns.destroy', $campaign) }}">🗑️</button>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="table-footer" style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:12px;">
        <span>Showing {{ $campaigns->firstItem() }}–{{ $campaigns->lastItem() }} of {{ $campaigns->total() }} campaign{{ $campaigns->total() !== 1 ? 's' : '' }}</span>
        @if($campaigns->hasPages())
            <div class="pagination-wrap">{{ $campaigns->links('vendor.pagination.admin') }}</div>
        @endif
    </div>
    @endif

</div>

{{-- Delete modal --}}
<div class="modal-overlay" id="delete-modal">
    <div class="modal-card" role="dialog" aria-modal="true">
        <div class="modal-icon">🗑️</div>
        <h3 class="modal-title">Delete Campaign?</h3>
        <p class="modal-body">Permanently delete <strong id="modal-item-name"></strong>?<br>This cannot be undone.</p>
        <div class="modal-actions">
            <button class="modal-btn-cancel" id="modal-cancel">Cancel</button>
            <form id="delete-form" method="POST" style="display:inline;">
                @csrf @method('DELETE')
                <button type="submit" class="modal-btn-confirm" id="modal-confirm">
                    <span>Delete</span><span class="modal-spinner"></span>
                </button>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
(function () {

/* Stat counters */
document.querySelectorAll('.stat-value[data-target]').forEach(function (el) {
    var target = parseInt(el.dataset.target, 10), startTime = null;
    function step(ts) {
        if (!startTime) startTime = ts;
        var p = Math.min((ts - startTime) / 600, 1);
        el.textContent = Math.floor(p * target);
        if (p < 1) requestAnimationFrame(step); else el.textContent = target;
    }
    setTimeout(function () { requestAnimationFrame(step); }, 150);
});

/* Row entrance */
document.querySelectorAll('.page-row').forEach(function (row, i) {
    setTimeout(function () {
        row.style.transition = 'opacity .28s ease, transform .28s ease';
        row.style.opacity = '1'; row.style.transform = 'translateY(0)';
    }, 50 + i * 40);
});

/* Search */
var searchInput = document.getElementById('page-search');
var clearBtn    = document.getElementById('search-clear');
var filterForm  = document.getElementById('filter-form');
var timer;
if (searchInput) {
    searchInput.addEventListener('keydown', function (e) { if (e.key === 'Enter') { e.preventDefault(); filterForm.submit(); } });
    searchInput.addEventListener('input', function () {
        clearBtn.style.display = this.value ? 'flex' : 'none';
        clearTimeout(timer); timer = setTimeout(function () { filterForm.submit(); }, 600);
    });
}
if (clearBtn) { clearBtn.addEventListener('click', function () { searchInput.value = ''; clearBtn.style.display = 'none'; filterForm.submit(); }); }

/* Status toggle */
document.querySelectorAll('.status-toggle').forEach(function (btn) {
    btn.addEventListener('click', function () {
        if (btn.classList.contains('toggling')) return;
        btn.classList.add('toggling');
        fetch(btn.dataset.url, {
            method: 'PATCH',
            headers: { 'X-CSRF-TOKEN': btn.dataset.csrf, 'Accept': 'application/json' },
        })
        .then(function (r) { return r.json(); })
        .then(function (data) {
            btn.classList.toggle('is-active',   data.is_active);
            btn.classList.toggle('is-inactive', !data.is_active);
            btn.querySelector('.toggle-label').textContent = data.is_active ? 'Active' : 'Inactive';
            if (window.showAdminToast) window.showAdminToast('Status updated.', 'success');
        })
        .catch(function () { if (window.showAdminToast) window.showAdminToast('Failed to update.', 'error'); })
        .finally(function () { btn.classList.remove('toggling'); });
    });
});

/* Delete modal */
var modal      = document.getElementById('delete-modal');
var itemName   = document.getElementById('modal-item-name');
var deleteForm = document.getElementById('delete-form');
var confirmBtn = document.getElementById('modal-confirm');
document.querySelectorAll('.row-btn-delete').forEach(function (btn) {
    btn.addEventListener('click', function () {
        itemName.textContent  = btn.dataset.name;
        deleteForm.action     = btn.dataset.action;
        modal.classList.add('modal-open');
        document.getElementById('modal-cancel').focus();
    });
});
document.getElementById('modal-cancel').addEventListener('click', function () { modal.classList.remove('modal-open'); });
modal.addEventListener('click', function (e) { if (e.target === modal) modal.classList.remove('modal-open'); });
document.addEventListener('keydown', function (e) { if (e.key === 'Escape') modal.classList.remove('modal-open'); });
deleteForm.addEventListener('submit', function () {
    confirmBtn.disabled = true;
    confirmBtn.querySelector('.modal-spinner').style.display = 'block';
});

})();
</script>
@endpush
