@extends('layouts.admin')

@section('title', 'Newsletter Subscribers')
@section('page-title', 'Subscribers')

@section('content')

{{-- Stats --}}
<div class="pages-stats cols-3">
    <div class="stat-card" style="--accent:#14b8a6;">
        <div class="stat-icon" style="background:rgba(20,184,166,0.12);color:#0f766e;">📧</div>
        <div>
            <div class="stat-value" data-target="{{ $stats['total'] }}">0</div>
            <div class="stat-label">Total Subscribers</div>
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
            <div class="stat-label">Unsubscribed</div>
        </div>
    </div>
</div>

{{-- Main card --}}
<div class="admin-card pages-card">

    <form method="GET" action="{{ route('admin.subscribers.index') }}" id="filter-form">
        <div class="pages-toolbar">
            <div class="pages-toolbar-left">
                <div class="table-search-wrap">
                    <span class="table-search-icon">🔍</span>
                    <input type="text" name="search" id="page-search" class="table-search"
                        placeholder="Search name or email…"
                        value="{{ request('search') }}" autocomplete="off">
                    <button type="button" class="table-search-clear" id="search-clear"
                        style="display:{{ request('search') ? 'flex' : 'none' }};">✕</button>
                </div>
                <div class="filter-select-wrap">
                    <select name="status" class="filter-select" onchange="this.form.submit()">
                        <option value="">All Status</option>
                        <option value="active"   {{ request('status') === 'active'   ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Unsubscribed</option>
                    </select>
                </div>
                @if(request()->hasAny(['search','status']))
                    <a href="{{ route('admin.subscribers.index') }}" class="filter-clear-link">Clear</a>
                @endif
            </div>
        </div>
    </form>

    @if($subscribers->isEmpty())
        <div class="empty-state" style="display:flex;">
            <div class="empty-icon">📧</div>
            <div class="empty-title">No subscribers found</div>
            <div class="empty-sub">
                @if(request()->hasAny(['search','status']))
                    <a href="{{ route('admin.subscribers.index') }}" style="color:var(--brand-dark);">Clear filters</a>
                @else
                    Newsletter signups from the News page will appear here.
                @endif
            </div>
        </div>
    @else
    <div class="table-wrap">
        <table class="admin-table pages-table">
            <thead>
                <tr>
                    <th>Subscriber</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>Joined</th>
                    <th style="width:110px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($subscribers as $sub)
                <tr class="page-row" style="opacity:0;transform:translateY(8px);">
                    <td>
                        <div class="cm-sender">
                            <div class="cm-avatar" style="background:linear-gradient(135deg,#6366f1,#4f46e5);">
                                {{ strtoupper(substr($sub->first_name, 0, 1)) }}
                            </div>
                            <span class="page-name">{{ $sub->first_name }}</span>
                        </div>
                    </td>
                    <td>
                        <code class="slug-chip" style="font-family:inherit;font-size:13px;">{{ $sub->email }}</code>
                    </td>
                    <td>
                        <button
                            class="status-toggle {{ $sub->is_active ? 'is-active' : 'is-inactive' }}"
                            data-url="{{ route('admin.subscribers.toggle', $sub) }}"
                            data-csrf="{{ csrf_token() }}"
                            title="Click to toggle"
                        >
                            <span class="toggle-dot"></span>
                            <span class="toggle-label">{{ $sub->is_active ? 'Active' : 'Unsubscribed' }}</span>
                        </button>
                    </td>
                    <td>
                        <span class="cm-date" title="{{ $sub->created_at->format('M j, Y g:i A') }}">
                            {{ $sub->created_at->format('M j, Y') }}
                        </span>
                    </td>
                    <td>
                        <div class="row-actions">
                            <button class="row-btn row-btn-delete" title="Delete"
                                data-name="{{ $sub->first_name }} ({{ $sub->email }})"
                                data-action="{{ route('admin.subscribers.destroy', $sub) }}">🗑️</button>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="table-footer" style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:12px;">
        <span>Showing {{ $subscribers->firstItem() }}–{{ $subscribers->lastItem() }} of {{ $subscribers->total() }} subscriber{{ $subscribers->total() !== 1 ? 's' : '' }}</span>
        @if($subscribers->hasPages())
            <div class="pagination-wrap">{{ $subscribers->links('vendor.pagination.admin') }}</div>
        @endif
    </div>
    @endif

</div>

{{-- Delete modal --}}
<div class="modal-overlay" id="delete-modal">
    <div class="modal-card" role="dialog" aria-modal="true">
        <div class="modal-icon">🗑️</div>
        <h3 class="modal-title">Remove Subscriber?</h3>
        <p class="modal-body">Permanently remove <strong id="modal-item-name"></strong>?<br>This cannot be undone.</p>
        <div class="modal-actions">
            <button class="modal-btn-cancel" id="modal-cancel">Cancel</button>
            <form id="delete-form" method="POST" style="display:inline;">
                @csrf @method('DELETE')
                <button type="submit" class="modal-btn-confirm" id="modal-confirm">
                    <span>Remove</span><span class="modal-spinner"></span>
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
        row.style.transition = 'opacity 0.28s ease, transform 0.28s ease';
        row.style.opacity = '1'; row.style.transform = 'translateY(0)';
    }, 50 + i * 30);
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
        clearTimeout(timer);
        timer = setTimeout(function () { filterForm.submit(); }, 600);
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
            btn.querySelector('.toggle-label').textContent = data.is_active ? 'Active' : 'Unsubscribed';
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
var confirm    = document.getElementById('modal-confirm');

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
    confirm.disabled = true;
    confirm.querySelector('.modal-spinner').style.display = 'block';
});
})();
</script>
@endpush
