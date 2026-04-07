@extends('layouts.admin')

@section('title', 'Content Blocks')
@section('page-title', 'Content Blocks')

@section('content')

{{-- Stats --}}
<div class="pages-stats cb-stats">
    <div class="stat-card" style="--accent:#14b8a6;">
        <div class="stat-icon" style="background:rgba(20,184,166,0.12);color:#0f766e;">🧩</div>
        <div>
            <div class="stat-value" data-target="{{ $stats['total'] }}">0</div>
            <div class="stat-label">Total Blocks</div>
        </div>
    </div>
    <div class="stat-card" style="--accent:#6366f1;">
        <div class="stat-icon" style="background:rgba(99,102,241,0.12);color:#4f46e5;">📝</div>
        <div>
            <div class="stat-value" data-target="{{ $stats['text'] }}">0</div>
            <div class="stat-label">Text</div>
        </div>
    </div>
    <div class="stat-card" style="--accent:#ec4899;">
        <div class="stat-icon" style="background:rgba(236,72,153,0.12);color:#db2777;">🖼️</div>
        <div>
            <div class="stat-value" data-target="{{ $stats['image'] }}">0</div>
            <div class="stat-label">Image</div>
        </div>
    </div>
    <div class="stat-card" style="--accent:#f59e0b;">
        <div class="stat-icon" style="background:rgba(245,158,11,0.12);color:#d97706;">🔗</div>
        <div>
            <div class="stat-value" data-target="{{ $stats['link'] }}">0</div>
            <div class="stat-label">Link</div>
        </div>
    </div>
</div>

{{-- Main card --}}
<div class="admin-card pages-card">

    {{-- Filters --}}
    <form method="GET" action="{{ route('admin.content-blocks.index') }}" id="filter-form">
        <div class="pages-toolbar">
            <div class="pages-toolbar-left">
                <div class="table-search-wrap">
                    <span class="table-search-icon">🔍</span>
                    <input
                        type="text"
                        name="search"
                        id="page-search"
                        class="table-search"
                        placeholder="Search key or content…"
                        value="{{ request('search') }}"
                        autocomplete="off"
                    >
                    <button type="button" class="table-search-clear" id="search-clear"
                        style="display:{{ request('search') ? 'flex' : 'none' }};">✕</button>
                </div>
                <div class="filter-select-wrap">
                    <select name="section_id" class="filter-select" onchange="this.form.submit()">
                        <option value="">All Sections</option>
                        @foreach($sections as $section)
                            <option value="{{ $section->id }}" {{ request('section_id') == $section->id ? 'selected' : '' }}>
                                {{ $section->page->name }} — {{ $section->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="filter-select-wrap">
                    <select name="type" class="filter-select" onchange="this.form.submit()">
                        <option value="">All Types</option>
                        <option value="text"  {{ request('type') == 'text'  ? 'selected' : '' }}>Text</option>
                        <option value="image" {{ request('type') == 'image' ? 'selected' : '' }}>Image</option>
                        <option value="link"  {{ request('type') == 'link'  ? 'selected' : '' }}>Link</option>
                        <option value="list"  {{ request('type') == 'list'  ? 'selected' : '' }}>List</option>
                    </select>
                </div>
                @if(request()->hasAny(['search','section_id','type']))
                    <a href="{{ route('admin.content-blocks.index') }}" class="filter-clear-link">Clear filters</a>
                @endif
            </div>
            <a href="{{ route('admin.content-blocks.create') }}" class="admin-btn pages-new-btn">
                <span>＋</span> New Block
            </a>
        </div>
    </form>

    {{-- Table --}}
    @if($contentBlocks->isEmpty())
        <div class="empty-state" style="display:flex;">
            <div class="empty-icon">🧩</div>
            <div class="empty-title">No content blocks found</div>
            <div class="empty-sub">
                @if(request()->hasAny(['search','section_id','type']))
                    <a href="{{ route('admin.content-blocks.index') }}" style="color:var(--brand-dark);">Clear filters</a>
                @else
                    <a href="{{ route('admin.content-blocks.create') }}" style="color:var(--brand-dark);font-weight:600;">Create your first block →</a>
                @endif
            </div>
        </div>
    @else
    <div class="table-wrap">
        <table class="admin-table pages-table">
            <thead>
                <tr>
                    <th>Section</th>
                    <th>Key</th>
                    <th>Type</th>
                    <th>Content</th>
                    <th style="width:80px;">Order</th>
                    <th style="width:110px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($contentBlocks as $block)
                <tr class="page-row" style="opacity:0;transform:translateY(8px);">
                    <td>
                        <div class="cb-section-cell">
                            <span class="page-chip">{{ $block->section->page->name }}</span>
                            <span class="cb-section-name">{{ $block->section->name }}</span>
                        </div>
                    </td>
                    <td>
                        <code class="slug-chip">{{ $block->key }}</code>
                    </td>
                    <td>
                        <span class="type-badge type-{{ $block->type }}">{{ $block->type }}</span>
                    </td>
                    <td class="desc-cell">
                        @if($block->type === 'image' && $block->url)
                            <span style="color:var(--text-muted);font-style:italic;font-size:12px;">{{ \Illuminate\Support\Str::limit($block->url, 40) }}</span>
                        @elseif($block->content)
                            {{ \Illuminate\Support\Str::limit($block->content, 55) }}
                        @else
                            <span style="color:var(--text-muted);font-size:12px;">—</span>
                        @endif
                    </td>
                    <td>
                        <span class="order-badge">{{ $block->sort_order }}</span>
                    </td>
                    <td>
                        <div class="row-actions">
                            <a href="{{ route('admin.content-blocks.edit', $block) }}" class="row-btn row-btn-edit" title="Edit">✏️</a>
                            <button
                                class="row-btn row-btn-delete"
                                title="Delete"
                                data-name="{{ $block->key }}"
                                data-action="{{ route('admin.content-blocks.destroy', $block) }}"
                            >🗑️</button>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="table-footer" style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:12px;">
        <span>Showing {{ $contentBlocks->firstItem() }}–{{ $contentBlocks->lastItem() }} of {{ $contentBlocks->total() }} block{{ $contentBlocks->total() !== 1 ? 's' : '' }}</span>
        @if($contentBlocks->hasPages())
            <div class="pagination-wrap">{{ $contentBlocks->links('vendor.pagination.admin') }}</div>
        @endif
    </div>
    @endif

</div>

{{-- Delete modal --}}
<div class="modal-overlay" id="delete-modal">
    <div class="modal-card" role="dialog" aria-modal="true">
        <div class="modal-icon">🗑️</div>
        <h3 class="modal-title">Delete Block?</h3>
        <p class="modal-body">
            You are about to delete block <strong id="modal-item-name"></strong>.<br>
            This action cannot be undone.
        </p>
        <div class="modal-actions">
            <button class="modal-btn-cancel" id="modal-cancel">Cancel</button>
            <form id="delete-form" method="POST" style="display:inline;">
                @csrf @method('DELETE')
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

document.querySelectorAll('.page-row').forEach(function (row, i) {
    setTimeout(function () {
        row.style.transition = 'opacity 0.28s ease, transform 0.28s ease';
        row.style.opacity = '1'; row.style.transform = 'translateY(0)';
    }, 60 + i * 30);
});

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
