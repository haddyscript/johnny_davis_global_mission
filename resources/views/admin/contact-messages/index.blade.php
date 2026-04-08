@extends('layouts.admin')

@section('title', 'Contact Messages')
@section('page-title', 'Contact Messages')

@section('content')

{{-- Stats --}}
<div class="pages-stats cols-3">
    <div class="stat-card" style="--accent:#14b8a6;">
        <div class="stat-icon" style="background:rgba(20,184,166,0.12);color:#0f766e;">✉️</div>
        <div>
            <div class="stat-value" data-target="{{ $stats['total'] }}">0</div>
            <div class="stat-label">Total Messages</div>
        </div>
    </div>
    <div class="stat-card" style="--accent:#ef4444;">
        <div class="stat-icon" style="background:rgba(239,68,68,0.12);color:#dc2626;">🔴</div>
        <div>
            <div class="stat-value" data-target="{{ $stats['unread'] }}">0</div>
            <div class="stat-label">Unread</div>
        </div>
    </div>
    <div class="stat-card" style="--accent:#10b981;">
        <div class="stat-icon" style="background:rgba(16,185,129,0.12);color:#059669;">✅</div>
        <div>
            <div class="stat-value" data-target="{{ $stats['read'] }}">0</div>
            <div class="stat-label">Read</div>
        </div>
    </div>
</div>

{{-- Main card --}}
<div class="admin-card pages-card">

    {{-- Filters --}}
    <form method="GET" action="{{ route('admin.contact-messages.index') }}" id="filter-form">
        <div class="pages-toolbar">
            <div class="pages-toolbar-left">
                <div class="table-search-wrap">
                    <span class="table-search-icon">🔍</span>
                    <input type="text" name="search" id="page-search" class="table-search"
                        placeholder="Search name, email, message…"
                        value="{{ request('search') }}" autocomplete="off">
                    <button type="button" class="table-search-clear" id="search-clear"
                        style="display:{{ request('search') ? 'flex' : 'none' }};">✕</button>
                </div>
                <div class="filter-select-wrap">
                    <select name="status" class="filter-select" onchange="this.form.submit()">
                        <option value="">All Status</option>
                        <option value="unread" {{ request('status') === 'unread' ? 'selected' : '' }}>Unread</option>
                        <option value="read"   {{ request('status') === 'read'   ? 'selected' : '' }}>Read</option>
                    </select>
                </div>
                <div class="filter-select-wrap">
                    <select name="subject" class="filter-select" onchange="this.form.submit()">
                        <option value="">All Subjects</option>
                        <option value="general"     {{ request('subject') === 'general'     ? 'selected' : '' }}>General Inquiry</option>
                        <option value="donation"    {{ request('subject') === 'donation'    ? 'selected' : '' }}>Donation</option>
                        <option value="volunteer"   {{ request('subject') === 'volunteer'   ? 'selected' : '' }}>Volunteer</option>
                        <option value="partnership" {{ request('subject') === 'partnership' ? 'selected' : '' }}>Partnership</option>
                        <option value="disaster"    {{ request('subject') === 'disaster'    ? 'selected' : '' }}>Disaster Relief</option>
                        <option value="other"       {{ request('subject') === 'other'       ? 'selected' : '' }}>Other</option>
                    </select>
                </div>
                @if(request()->hasAny(['search','status','subject']))
                    <a href="{{ route('admin.contact-messages.index') }}" class="filter-clear-link">Clear</a>
                @endif
            </div>
        </div>
    </form>

    {{-- Table --}}
    @if($messages->isEmpty())
        <div class="empty-state" style="display:flex;">
            <div class="empty-icon">✉️</div>
            <div class="empty-title">No messages found</div>
            <div class="empty-sub">
                @if(request()->hasAny(['search','status','subject']))
                    <a href="{{ route('admin.contact-messages.index') }}" style="color:var(--brand-dark);">Clear filters</a>
                @else
                    Contact form submissions will appear here.
                @endif
            </div>
        </div>
    @else
    <div class="table-wrap">
        <table class="admin-table pages-table">
            <thead>
                <tr>
                    <th style="width:16px;"></th>
                    <th>From</th>
                    <th>Subject</th>
                    <th>Preview</th>
                    <th>Received</th>
                    <th style="width:110px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($messages as $msg)
                <tr class="page-row cm-row {{ !$msg->is_read ? 'cm-unread' : '' }}"
                    style="opacity:0;transform:translateY(8px);">
                    <td>
                        <span class="cm-dot {{ $msg->is_read ? 'cm-dot-read' : 'cm-dot-unread' }}"
                            data-id="{{ $msg->id }}"
                            data-url="{{ route('admin.contact-messages.toggle-read', $msg) }}"
                            data-csrf="{{ csrf_token() }}"
                            title="{{ $msg->is_read ? 'Mark as unread' : 'Mark as read' }}">
                        </span>
                    </td>
                    <td>
                        <div class="cm-sender">
                            <div class="cm-avatar">{{ strtoupper(substr($msg->first_name, 0, 1)) }}</div>
                            <div>
                                <div class="cm-name {{ !$msg->is_read ? 'cm-name-bold' : '' }}">
                                    {{ $msg->full_name }}
                                </div>
                                <div class="cm-email">{{ $msg->email }}</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        @if($msg->subject)
                            <span class="type-badge type-{{ $msg->subject }}">{{ $msg->subject_label }}</span>
                        @else
                            <span style="color:var(--text-muted);font-size:12px;">—</span>
                        @endif
                    </td>
                    <td class="desc-cell">
                        {{ \Illuminate\Support\Str::limit($msg->message, 70) }}
                    </td>
                    <td>
                        <span class="cm-date" title="{{ $msg->created_at->format('M j, Y g:i A') }}">
                            {{ $msg->created_at->diffForHumans() }}
                        </span>
                    </td>
                    <td>
                        <div class="row-actions">
                            <a href="{{ route('admin.contact-messages.show', $msg) }}"
                               class="row-btn row-btn-edit" title="View message">👁️</a>
                            <button class="row-btn row-btn-delete" title="Delete"
                                data-name="{{ $msg->full_name }}"
                                data-action="{{ route('admin.contact-messages.destroy', $msg) }}">🗑️</button>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="table-footer" style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:12px;">
        <span>Showing {{ $messages->firstItem() }}–{{ $messages->lastItem() }} of {{ $messages->total() }}</span>
        @if($messages->hasPages())
            <div class="pagination-wrap">{{ $messages->links('vendor.pagination.admin') }}</div>
        @endif
    </div>
    @endif

</div>

{{-- Delete modal --}}
<div class="modal-overlay" id="delete-modal">
    <div class="modal-card" role="dialog" aria-modal="true">
        <div class="modal-icon">🗑️</div>
        <h3 class="modal-title">Delete Message?</h3>
        <p class="modal-body">Permanently delete the message from <strong id="modal-item-name"></strong>?<br>This cannot be undone.</p>
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
        row.style.transition = 'opacity 0.28s ease, transform 0.28s ease';
        row.style.opacity = '1'; row.style.transform = 'translateY(0)';
    }, 50 + i * 30);
});

/* Search */
var searchInput = document.getElementById('page-search');
var clearBtn    = document.getElementById('search-clear');
var filterForm  = document.getElementById('filter-form');
var searchTimer;
if (searchInput) {
    searchInput.addEventListener('keydown', function (e) {
        if (e.key === 'Enter') { e.preventDefault(); filterForm.submit(); }
    });
    searchInput.addEventListener('input', function () {
        clearBtn.style.display = this.value ? 'flex' : 'none';
        clearTimeout(searchTimer);
        searchTimer = setTimeout(function () { filterForm.submit(); }, 600);
    });
}
if (clearBtn) {
    clearBtn.addEventListener('click', function () {
        searchInput.value = ''; clearBtn.style.display = 'none'; filterForm.submit();
    });
}

/* Read/unread dot toggle */
document.querySelectorAll('.cm-dot').forEach(function (dot) {
    dot.addEventListener('click', function () {
        if (dot.classList.contains('toggling')) return;
        dot.classList.add('toggling');
        fetch(dot.dataset.url, {
            method: 'PATCH',
            headers: { 'X-CSRF-TOKEN': dot.dataset.csrf, 'Accept': 'application/json' },
        })
        .then(function (r) { return r.json(); })
        .then(function (data) {
            var row = dot.closest('tr');
            dot.classList.toggle('cm-dot-unread', !data.is_read);
            dot.classList.toggle('cm-dot-read',   data.is_read);
            dot.title = data.is_read ? 'Mark as unread' : 'Mark as read';
            if (row) row.classList.toggle('cm-unread', !data.is_read);
            var name = row ? row.querySelector('.cm-name') : null;
            if (name) name.classList.toggle('cm-name-bold', !data.is_read);
            if (window.showAdminToast) {
                window.showAdminToast('Marked as ' + (data.is_read ? 'read' : 'unread') + '.', 'success');
            }
        })
        .catch(function () {
            if (window.showAdminToast) window.showAdminToast('Failed to update.', 'error');
        })
        .finally(function () { dot.classList.remove('toggling'); });
    });
});

/* Delete modal */
var modal      = document.getElementById('delete-modal');
var itemName   = document.getElementById('modal-item-name');
var deleteForm = document.getElementById('delete-form');
var confirm    = document.getElementById('modal-confirm');

document.querySelectorAll('.row-btn-delete').forEach(function (btn) {
    btn.addEventListener('click', function () {
        itemName.textContent = btn.dataset.name;
        deleteForm.action    = btn.dataset.action;
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
