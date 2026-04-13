@extends('layouts.admin')

@section('title', 'Pages')
@section('page-title', 'Pages')

@section('content')

{{-- ── Stats row ── --}}
<div class="pages-stats cols-3" id="stats-row">
    <div class="stat-card" style="--accent:#14b8a6;">
        <div class="stat-icon" style="background:rgba(20,184,166,0.12);color:#0f766e;">📄</div>
        <div>
            <div class="stat-value" data-target="{{ $pages->count() }}">0</div>
            <div class="stat-label">Total Pages</div>
        </div>
    </div>
    <div class="stat-card" style="--accent:#10b981;">
        <div class="stat-icon" style="background:rgba(16,185,129,0.12);color:#059669;">✅</div>
        <div>
            <div class="stat-value" data-target="{{ $pages->where('is_active', true)->count() }}">0</div>
            <div class="stat-label">Active</div>
        </div>
    </div>
    <div class="stat-card" style="--accent:#94a3b8;">
        <div class="stat-icon" style="background:rgba(148,163,184,0.12);color:#64748b;">⏸️</div>
        <div>
            <div class="stat-value" data-target="{{ $pages->where('is_active', false)->count() }}">0</div>
            <div class="stat-label">Inactive</div>
        </div>
    </div>
</div>

{{-- ── Main card ── --}}
<div class="admin-card pages-card">

    {{-- Toolbar --}}
    <div class="pages-toolbar">
        <div class="pages-toolbar-left">
            <div class="table-search-wrap">
                <span class="table-search-icon">🔍</span>
                <input
                    type="text"
                    id="page-search"
                    class="table-search"
                    placeholder="Search pages…"
                    autocomplete="off"
                >
                <button class="table-search-clear" id="search-clear" title="Clear" style="display:none;">✕</button>
            </div>
            <div class="filter-select-wrap">
                <select id="status-filter" class="filter-select">
                    <option value="all">All Status</option>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>
        </div>
        <a href="{{ route('admin.pages.create') }}" class="admin-btn pages-new-btn">
            <span>＋</span> New Page
        </a>
    </div>

    {{-- Empty state --}}
    <div class="empty-state" id="empty-state" style="display:none;">
        <div class="empty-icon">🔍</div>
        <div class="empty-title">No pages found</div>
        <div class="empty-sub">Try adjusting your search or filter.</div>
    </div>

    {{-- Table --}}
    <div class="table-wrap">
        <table class="admin-table pages-table" id="pages-table">
            <thead>
                <tr>
                    <th class="sortable" data-col="slug">
                        Slug <span class="sort-icon">↕</span>
                    </th>
                    <th class="sortable" data-col="name">
                        Name <span class="sort-icon">↕</span>
                    </th>
                    <th>URL</th>
                    <th>Description</th>
                    <th class="sortable" data-col="status">
                        Status <span class="sort-icon">↕</span>
                    </th>
                    <th class="sortable" data-col="order" style="width:110px;">
                        Order <span class="sort-icon">↕</span>
                    </th>
                    <th style="width:160px;">Actions</th>
                </tr>
            </thead>
            <tbody id="pages-tbody">
                @forelse($pages as $page)
                <tr
                    class="page-row"
                    data-slug="{{ strtolower($page->slug) }}"
                    data-name="{{ strtolower($page->name) }}"
                    data-status="{{ $page->is_active ? 'active' : 'inactive' }}"
                    data-order="{{ $page->sort_order }}"
                    style="--row-delay:{{ $loop->index * 40 }}ms"
                >
                    <td>
                        <code class="slug-chip">{{ $page->slug }}</code>
                    </td>
                    <td>
                        <span class="page-name">{{ $page->name }}</span>
                    </td>
                    <td>
                        <div style="display:flex;flex-direction:column;gap:4px;">
                            <a href="{{ $page->resolved_url }}"
                               target="_blank"
                               rel="noopener"
                               style="font-size:13px;font-weight:600;color:var(--brand-dark);text-decoration:none;display:inline-flex;align-items:center;gap:4px;"
                               title="Open {{ $page->resolved_url }}">
                                {{ $page->resolved_url }}
                                <span style="font-size:10px;opacity:.6;">↗</span>
                            </a>
                            @if($page->navItem)
                                <span style="display:inline-flex;align-items:center;gap:4px;font-size:11px;font-weight:600;color:#7c3aed;background:rgba(124,58,237,.08);padding:2px 7px;border-radius:20px;width:fit-content;">
                                    🔗 {{ $page->navItem->label }}
                                </span>
                            @else
                                <span style="font-size:11px;color:var(--text-muted);">manual slug</span>
                            @endif
                        </div>
                    </td>
                    <td class="desc-cell">
                        {{ \Illuminate\Support\Str::limit($page->description, 65) }}
                    </td>
                    <td>
                        <button
                            class="status-toggle {{ $page->is_active ? 'is-active' : 'is-inactive' }}"
                            data-id="{{ $page->id }}"
                            data-url="{{ route('admin.pages.toggle', $page) }}"
                            data-csrf="{{ csrf_token() }}"
                            title="Click to toggle status"
                        >
                            <span class="toggle-dot"></span>
                            <span class="toggle-label">{{ $page->is_active ? 'Active' : 'Inactive' }}</span>
                        </button>
                    </td>
                    <td>
                        <span class="order-badge">{{ $page->sort_order }}</span>
                    </td>
                    <td>
                        <div class="row-actions">
                            <button
                                class="row-btn row-btn-preview preview-trigger"
                                title="Preview"
                                data-slug="{{ $page->slug }}"
                                data-name="{{ $page->name }}"
                            >👁️</button>
                            <a
                                href="{{ route('admin.pages.edit', $page) }}"
                                class="row-btn row-btn-edit"
                                title="Edit"
                            >✏️</a>
                            <button
                                class="row-btn row-btn-delete"
                                title="Delete"
                                data-id="{{ $page->id }}"
                                data-name="{{ $page->name }}"
                                data-action="{{ route('admin.pages.destroy', $page) }}"
                            >🗑️</button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr id="no-data-row">
                    <td colspan="7" style="text-align:center;padding:48px;color:var(--text-muted);">
                        No pages yet. <a href="{{ route('admin.pages.create') }}" style="color:var(--brand-dark);font-weight:600;">Create your first page →</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($pages->count() > 0)
    <div class="table-footer">
        <span id="visible-count">Showing {{ $pages->count() }} page{{ $pages->count() !== 1 ? 's' : '' }}</span>
    </div>
    @endif
</div>

{{-- ── Page Preview Modal ── --}}
@include('admin.preview._modal')

{{-- ── Delete confirmation modal ── --}}
<div class="modal-overlay" id="delete-modal">
    <div class="modal-card" role="dialog" aria-modal="true">
        <div class="modal-icon">🗑️</div>
        <h3 class="modal-title">Delete Page?</h3>
        <p class="modal-body">
            You are about to delete <strong id="modal-page-name"></strong>.<br>
            This action cannot be undone.
        </p>
        <div class="modal-actions">
            <button class="modal-btn-cancel" id="modal-cancel">Cancel</button>
            <form id="delete-form" method="POST" style="display:inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="modal-btn-confirm" id="modal-confirm">
                    <span class="modal-btn-text">Delete</span>
                    <span class="modal-spinner"></span>
                </button>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
(function () {
'use strict';

/* ── Animated stat counters ── */
document.querySelectorAll('.stat-value[data-target]').forEach(function (el) {
    var target = parseInt(el.dataset.target, 10);
    var start = 0;
    var duration = 700;
    var startTime = null;
    function step(ts) {
        if (!startTime) startTime = ts;
        var progress = Math.min((ts - startTime) / duration, 1);
        el.textContent = Math.floor(progress * target);
        if (progress < 1) requestAnimationFrame(step);
        else el.textContent = target;
    }
    setTimeout(function () { requestAnimationFrame(step); }, 200);
});

/* ── Toast (delegates to global handler) ── */
var showToast = window.showAdminToast || function(){};

/* ── Search + filter ── */
var searchInput = document.getElementById('page-search');
var searchClear = document.getElementById('search-clear');
var statusFilter = document.getElementById('status-filter');
var emptyState = document.getElementById('empty-state');
var visibleCount = document.getElementById('visible-count');
var rows = Array.from(document.querySelectorAll('.page-row'));

function filterTable() {
    var q = searchInput.value.toLowerCase().trim();
    var status = statusFilter.value;
    var visible = 0;

    rows.forEach(function (row) {
        var matchesSearch = !q ||
            row.dataset.slug.includes(q) ||
            row.dataset.name.includes(q);
        var matchesStatus = status === 'all' || row.dataset.status === status;
        var show = matchesSearch && matchesStatus;
        row.style.display = show ? '' : 'none';
        if (show) visible++;
    });

    searchClear.style.display = q ? 'flex' : 'none';
    if (emptyState) emptyState.style.display = (visible === 0 && rows.length > 0) ? 'flex' : 'none';
    if (visibleCount) {
        visibleCount.textContent = 'Showing ' + visible + ' page' + (visible !== 1 ? 's' : '');
    }
}

searchInput.addEventListener('input', filterTable);
statusFilter.addEventListener('change', filterTable);
searchClear.addEventListener('click', function () {
    searchInput.value = '';
    filterTable();
    searchInput.focus();
});

/* ── Column sort ── */
var sortState = { col: null, dir: 1 };

document.querySelectorAll('th.sortable').forEach(function (th) {
    th.addEventListener('click', function () {
        var col = th.dataset.col;
        if (sortState.col === col) sortState.dir *= -1;
        else { sortState.col = col; sortState.dir = 1; }

        document.querySelectorAll('th.sortable').forEach(function (t) {
            t.classList.remove('sort-asc', 'sort-desc');
        });
        th.classList.add(sortState.dir === 1 ? 'sort-asc' : 'sort-desc');

        var tbody = document.getElementById('pages-tbody');
        var sorted = rows.slice().sort(function (a, b) {
            var av = a.dataset[col === 'order' ? 'order' : col] || '';
            var bv = b.dataset[col === 'order' ? 'order' : col] || '';
            if (col === 'order') return (parseFloat(av) - parseFloat(bv)) * sortState.dir;
            return av.localeCompare(bv) * sortState.dir;
        });
        sorted.forEach(function (row) { tbody.appendChild(row); });
    });
});

/* ── Status AJAX toggle ── */
document.querySelectorAll('.status-toggle').forEach(function (btn) {
    btn.addEventListener('click', function () {
        if (btn.classList.contains('toggling')) return;
        btn.classList.add('toggling');

        fetch(btn.dataset.url, {
            method: 'PATCH',
            headers: {
                'X-CSRF-TOKEN': btn.dataset.csrf,
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            },
        })
        .then(function (r) { return r.json(); })
        .then(function (data) {
            var active = data.is_active;
            btn.classList.toggle('is-active', active);
            btn.classList.toggle('is-inactive', !active);
            btn.querySelector('.toggle-label').textContent = active ? 'Active' : 'Inactive';
            var row = btn.closest('tr');
            if (row) row.dataset.status = active ? 'active' : 'inactive';
            showToast('Status updated to ' + (active ? 'Active' : 'Inactive') + '.', 'success');
            filterTable();
        })
        .catch(function () {
            showToast('Failed to update status. Please try again.', 'error');
        })
        .finally(function () {
            btn.classList.remove('toggling');
        });
    });
});

/* ── Delete modal ── */
var modal = document.getElementById('delete-modal');
var modalPageName = document.getElementById('modal-page-name');
var deleteForm = document.getElementById('delete-form');
var modalConfirm = document.getElementById('modal-confirm');

document.querySelectorAll('.row-btn-delete').forEach(function (btn) {
    btn.addEventListener('click', function () {
        modalPageName.textContent = '"' + btn.dataset.name + '"';
        deleteForm.action = btn.dataset.action;
        modal.classList.add('modal-open');
        document.getElementById('modal-cancel').focus();
    });
});

document.getElementById('modal-cancel').addEventListener('click', closeModal);

modal.addEventListener('click', function (e) {
    if (e.target === modal) closeModal();
});

document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') closeModal();
});

deleteForm.addEventListener('submit', function () {
    modalConfirm.classList.add('confirming');
    modalConfirm.disabled = true;
});

function closeModal() {
    modal.classList.remove('modal-open');
}

/* ── Row entrance animation ── */
rows.forEach(function (row, i) {
    row.style.opacity = '0';
    row.style.transform = 'translateY(12px)';
    setTimeout(function () {
        row.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
        row.style.opacity = '';
        row.style.transform = '';
    }, 80 + i * 40);
});

})();
</script>
@endpush
