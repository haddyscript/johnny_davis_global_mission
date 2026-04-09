@extends('layouts.admin')

@section('title', 'Notifications')
@section('page-title', 'Notifications')

@section('content')

{{-- Stats --}}
<div class="pages-stats cols-3">
    <div class="stat-card" style="--accent:#22c55e;">
        <div class="stat-icon" style="background:rgba(34,197,94,0.1);color:#16a34a;">🔔</div>
        <div>
            <div class="stat-value" data-target="{{ $notifications->total() }}">0</div>
            <div class="stat-label">Total</div>
        </div>
    </div>
    <div class="stat-card" style="--accent:#ef4444;">
        <div class="stat-icon" style="background:rgba(239,68,68,0.1);color:#dc2626;">🔴</div>
        <div>
            <div class="stat-value" data-target="{{ $unreadCount }}">0</div>
            <div class="stat-label">Unread</div>
        </div>
    </div>
    <div class="stat-card" style="--accent:#94a3b8;">
        <div class="stat-icon" style="background:rgba(148,163,184,0.1);color:#64748b;">✅</div>
        <div>
            <div class="stat-value" data-target="{{ $notifications->total() - $unreadCount }}">0</div>
            <div class="stat-label">Read</div>
        </div>
    </div>
</div>

{{-- Main card --}}
<div class="admin-card pages-card">

    <form method="GET" action="{{ route('admin.notifications.index') }}" id="filter-form">
        <div class="pages-toolbar">
            <div class="pages-toolbar-left">
                <div class="filter-select-wrap">
                    <select name="type" class="filter-select" onchange="this.form.submit()">
                        <option value="">All Types</option>
                        <option value="new_donation"   {{ request('type') === 'new_donation'   ? 'selected' : '' }}>💰 New Donation</option>
                        <option value="failed_donation"{{ request('type') === 'failed_donation'? 'selected' : '' }}>❌ Failed Donation</option>
                        <option value="pending_donation"{{ request('type') === 'pending_donation'? 'selected' : '' }}>⏳ Pending Donation</option>
                        <option value="new_subscriber" {{ request('type') === 'new_subscriber' ? 'selected' : '' }}>📧 New Subscriber</option>
                        <option value="new_contact"    {{ request('type') === 'new_contact'    ? 'selected' : '' }}>✉️ Contact Message</option>
                    </select>
                </div>
                <div class="filter-select-wrap">
                    <select name="unread" class="filter-select" onchange="this.form.submit()">
                        <option value="">All Status</option>
                        <option value="1" {{ request('unread') === '1' ? 'selected' : '' }}>Unread only</option>
                    </select>
                </div>
                @if(request()->hasAny(['type','unread']))
                    <a href="{{ route('admin.notifications.index') }}" class="filter-clear-link">Clear</a>
                @endif
            </div>
            <div class="pages-toolbar-right">
                @if($unreadCount > 0)
                <button type="button" class="admin-btn" id="mark-all-page-btn"
                    style="display:inline-flex;align-items:center;gap:8px;font-size:14px;">
                    ✓ Mark all as read
                </button>
                @endif
            </div>
        </div>
    </form>

    @if($notifications->isEmpty())
        <div class="empty-state" style="display:flex;">
            <div class="empty-icon">🔕</div>
            <div class="empty-title">No notifications found</div>
            <div class="empty-sub">
                @if(request()->hasAny(['type','unread']))
                    <a href="{{ route('admin.notifications.index') }}" style="color:var(--brand-dark);">Clear filters</a>
                @else
                    Notifications will appear here when activity occurs.
                @endif
            </div>
        </div>
    @else

    <div class="table-wrap" style="padding:0;">
        @foreach($notifications as $notif)
        <div class="notif-page-item {{ $notif->is_read ? '' : 'unread' }}" id="notif-row-{{ $notif->id }}">
            <div class="notif-page-icon" style="background:{{ $notif->icon_bg }};color:{{ $notif->icon_color }};">
                {{ $notif->icon }}
            </div>
            <div class="notif-page-body">
                <div class="notif-page-title">{{ $notif->title }}</div>
                <div class="notif-page-msg">{{ $notif->message }}</div>
                <div class="notif-page-time" title="{{ $notif->created_at->format('M j, Y g:i A') }}">
                    {{ $notif->created_at->diffForHumans() }}
                    &nbsp;·&nbsp;
                    <span class="notif-type-chip notif-type-{{ $notif->type }}">{{ str_replace('_', ' ', $notif->type) }}</span>
                </div>
            </div>
            <div class="notif-page-actions">
                @if(!$notif->is_read)
                    <div class="notif-unread-dot" id="dot-{{ $notif->id }}" title="Unread"></div>
                @endif
                @if(!$notif->is_read)
                <button class="notif-mark-btn" data-id="{{ $notif->id }}"
                    data-url="{{ route('admin.notifications.mark-read', $notif) }}">
                    Mark read
                </button>
                @endif
                @if(isset($notif->data['action_url']))
                <a href="{{ $notif->data['action_url'] }}"
                   class="notif-mark-btn" style="text-decoration:none;">
                    View →
                </a>
                @endif
            </div>
        </div>
        @endforeach
    </div>

    <div class="table-footer" style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:12px;">
        <span>Showing {{ $notifications->firstItem() }}–{{ $notifications->lastItem() }} of {{ $notifications->total() }} notification{{ $notifications->total() !== 1 ? 's' : '' }}</span>
        @if($notifications->hasPages())
            <div class="pagination-wrap">{{ $notifications->links('vendor.pagination.admin') }}</div>
        @endif
    </div>

    @endif

</div>

@endsection

@push('scripts')
<style>
.notif-type-chip {
    display: inline-block;
    padding: 1px 8px;
    border-radius: 6px;
    font-size: 11px;
    font-weight: 600;
    text-transform: capitalize;
    background: rgba(99,102,241,0.08);
    color: #4f46e5;
    border: 1px solid rgba(99,102,241,0.15);
}
.notif-type-new_donation    { background:rgba(34,197,94,0.08); color:#16a34a; border-color:rgba(34,197,94,0.2); }
.notif-type-failed_donation { background:rgba(239,68,68,0.08); color:#dc2626; border-color:rgba(239,68,68,0.2); }
.notif-type-pending_donation{ background:rgba(245,158,11,0.08);color:#d97706; border-color:rgba(245,158,11,0.2); }
.notif-type-new_subscriber  { background:rgba(29,78,216,0.08); color:#1d4ed8; border-color:rgba(29,78,216,0.2); }
.notif-type-new_contact     { background:rgba(147,51,234,0.08);color:#9333ea; border-color:rgba(147,51,234,0.2); }
</style>
<script>
(function () {
var CSRF = '{{ csrf_token() }}';

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

/* Mark single notification as read */
document.querySelectorAll('.notif-mark-btn[data-url]').forEach(function (btn) {
    btn.addEventListener('click', function () {
        var id  = btn.dataset.id;
        var url = btn.dataset.url;
        fetch(url, {
            method: 'PATCH',
            headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
        }).then(function () {
            var row = document.getElementById('notif-row-' + id);
            if (row) {
                row.classList.remove('unread');
                var dot = document.getElementById('dot-' + id);
                if (dot) dot.remove();
                btn.remove();
            }
            if (window.showAdminToast) window.showAdminToast('Notification marked as read.', 'success');
        }).catch(function () {
            if (window.showAdminToast) window.showAdminToast('Failed to update notification.', 'error');
        });
    });
});

/* Mark all as read */
var markAllPageBtn = document.getElementById('mark-all-page-btn');
if (markAllPageBtn) {
    markAllPageBtn.addEventListener('click', function () {
        markAllPageBtn.disabled = true;
        fetch('{{ route("admin.notifications.mark-all-read") }}', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
        }).then(function () {
            document.querySelectorAll('.notif-page-item.unread').forEach(function (row) {
                row.classList.remove('unread');
                var dot = row.querySelector('.notif-unread-dot');
                if (dot) dot.remove();
                var markBtn = row.querySelector('.notif-mark-btn[data-url]');
                if (markBtn) markBtn.remove();
            });
            markAllPageBtn.textContent = '✓ All caught up';
            markAllPageBtn.style.opacity = '0.6';
            if (window.showAdminToast) window.showAdminToast('All notifications marked as read.', 'success');
        }).catch(function () {
            markAllPageBtn.disabled = false;
            if (window.showAdminToast) window.showAdminToast('Failed to mark all as read.', 'error');
        });
    });
}

})();
</script>
@endpush
