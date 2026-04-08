@extends('layouts.admin')

@section('title', 'Sections')
@section('page-title', 'Sections')

@section('content')

{{-- Stats --}}
<div class="pages-stats cols-3">
    <div class="stat-card" style="--accent:#14b8a6;">
        <div class="stat-icon" style="background:rgba(20,184,166,0.12);color:#0f766e;">✍️</div>
        <div>
            <div class="stat-value" data-target="{{ $stats['total'] }}">0</div>
            <div class="stat-label">Total Sections</div>
        </div>
    </div>
    <div class="stat-card" style="--accent:#6366f1;">
        <div class="stat-icon" style="background:rgba(99,102,241,0.12);color:#4f46e5;">📄</div>
        <div>
            <div class="stat-value" data-target="{{ $stats['pages_count'] }}">0</div>
            <div class="stat-label">Pages Covered</div>
        </div>
    </div>
    <div class="stat-card" style="--accent:#f59e0b;">
        <div class="stat-icon" style="background:rgba(245,158,11,0.12);color:#d97706;">🏷️</div>
        <div>
            <div class="stat-value" data-target="{{ $stats['types_count'] }}">0</div>
            <div class="stat-label">Distinct Types</div>
        </div>
    </div>
</div>

{{-- Main card --}}
<div class="admin-card pages-card">

    {{-- Filters --}}
    <form method="GET" action="{{ route('admin.sections.index') }}" id="filter-form">
        <div class="pages-toolbar">
            <div class="pages-toolbar-left">
                <div class="table-search-wrap">
                    <span class="table-search-icon">🔍</span>
                    <input
                        type="text"
                        name="search"
                        id="page-search"
                        class="table-search"
                        placeholder="Search name or slug…"
                        value="{{ request('search') }}"
                        autocomplete="off"
                    >
                    @if(request('search'))
                        <button type="button" class="table-search-clear" id="search-clear" style="display:flex;">✕</button>
                    @else
                        <button type="button" class="table-search-clear" id="search-clear" style="display:none;">✕</button>
                    @endif
                </div>
                <div class="filter-select-wrap">
                    <select name="page_id" class="filter-select" onchange="this.form.submit()">
                        <option value="">All Pages</option>
                        @foreach($pages as $page)
                            <option value="{{ $page->id }}" {{ request('page_id') == $page->id ? 'selected' : '' }}>
                                {{ $page->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                @if($types->count())
                <div class="filter-select-wrap">
                    <select name="type" class="filter-select" onchange="this.form.submit()">
                        <option value="">All Types</option>
                        @foreach($types as $t)
                            <option value="{{ $t }}" {{ request('type') == $t ? 'selected' : '' }}>
                                {{ ucfirst($t) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                @endif
                @if(request()->hasAny(['search','page_id','type']))
                    <a href="{{ route('admin.sections.index') }}" class="filter-clear-link">Clear filters</a>
                @endif
            </div>
            <a href="{{ route('admin.sections.create') }}" class="admin-btn pages-new-btn">
                <span>＋</span> New Section
            </a>
        </div>
    </form>

    {{-- Table --}}
    @if($sections->isEmpty())
        <div class="empty-state" style="display:flex;">
            <div class="empty-icon">✍️</div>
            <div class="empty-title">No sections found</div>
            <div class="empty-sub">
                @if(request()->hasAny(['search','page_id','type']))
                    Try adjusting your filters. <a href="{{ route('admin.sections.index') }}" style="color:var(--brand-dark);">Clear all</a>
                @else
                    <a href="{{ route('admin.sections.create') }}" style="color:var(--brand-dark);font-weight:600;">Create your first section →</a>
                @endif
            </div>
        </div>
    @else
    <div class="table-wrap">
        <table class="admin-table pages-table">
            <thead>
                <tr>
                    <th>Page</th>
                    <th>Slug</th>
                    <th>Name</th>
                    <th>Type</th>
                    <th style="width:80px;">Order</th>
                    <th style="width:140px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($sections as $i => $section)
                <tr class="page-row" style="opacity:0;transform:translateY(8px);">
                    <td>
                        <span class="page-chip">{{ $section->page->name }}</span>
                    </td>
                    <td>
                        <code class="slug-chip">{{ $section->slug }}</code>
                    </td>
                    <td>
                        <span class="page-name">{{ $section->name }}</span>
                    </td>
                    <td>
                        @if($section->type)
                            <span class="type-badge type-{{ strtolower($section->type) }}">{{ $section->type }}</span>
                        @else
                            <span style="color:var(--text-muted);font-size:12px;">—</span>
                        @endif
                    </td>
                    <td>
                        <span class="order-badge">{{ $section->sort_order }}</span>
                    </td>
                    <td>
                        <div class="row-actions">
                            <button
                                class="row-btn row-btn-preview preview-trigger"
                                title="Preview page"
                                data-slug="{{ $section->page->slug }}"
                                data-name="{{ $section->page->name }}"
                            >👁️</button>
                            <a href="{{ route('admin.sections.edit', $section) }}" class="row-btn row-btn-edit" title="Edit">✏️</a>
                            <button
                                class="row-btn row-btn-delete"
                                title="Delete"
                                data-name="{{ $section->name }}"
                                data-action="{{ route('admin.sections.destroy', $section) }}"
                            >🗑️</button>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Pagination + count footer --}}
    <div class="table-footer" style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:12px;">
        <span>Showing {{ $sections->firstItem() }}–{{ $sections->lastItem() }} of {{ $sections->total() }} section{{ $sections->total() !== 1 ? 's' : '' }}</span>
        @if($sections->hasPages())
            <div class="pagination-wrap">{{ $sections->links('vendor.pagination.admin') }}</div>
        @endif
    </div>
    @endif

</div>

{{-- ── Page Preview Modal ── --}}
@include('admin.preview._modal')

{{-- Delete modal --}}
<div class="modal-overlay" id="delete-modal">
    <div class="modal-card" role="dialog" aria-modal="true">
        <div class="modal-icon">🗑️</div>
        <h3 class="modal-title">Delete Section?</h3>
        <p class="modal-body">
            You are about to delete <strong id="modal-item-name"></strong>.<br>
            All associated content blocks will also be removed.
        </p>
        <div class="modal-actions">
            <button class="modal-btn-cancel" id="modal-cancel">Cancel</button>
            <form id="delete-form" method="POST" style="display:inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="modal-btn-confirm" id="modal-confirm">
                    <span>Delete</span>
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
        row.style.opacity = '1';
        row.style.transform = 'translateY(0)';
    }, 60 + i * 35);
});

/* Search clear */
var searchInput = document.getElementById('page-search');
var clearBtn    = document.getElementById('search-clear');
var filterForm  = document.getElementById('filter-form');
if (searchInput) {
    searchInput.addEventListener('input', function () {
        clearBtn.style.display = this.value ? 'flex' : 'none';
    });
}
if (clearBtn) {
    clearBtn.addEventListener('click', function () {
        searchInput.value = '';
        clearBtn.style.display = 'none';
        filterForm.submit();
    });
}

/* Search on Enter/debounce */
var searchTimer;
if (searchInput) {
    searchInput.addEventListener('keydown', function (e) {
        if (e.key === 'Enter') { e.preventDefault(); filterForm.submit(); }
    });
    searchInput.addEventListener('input', function () {
        clearTimeout(searchTimer);
        searchTimer = setTimeout(function () { filterForm.submit(); }, 600);
    });
}

/* Delete modal */
var modal      = document.getElementById('delete-modal');
var itemName   = document.getElementById('modal-item-name');
var deleteForm = document.getElementById('delete-form');
var confirm    = document.getElementById('modal-confirm');

document.querySelectorAll('.row-btn-delete').forEach(function (btn) {
    btn.addEventListener('click', function () {
        itemName.textContent = '"' + btn.dataset.name + '"';
        deleteForm.action = btn.dataset.action;
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
