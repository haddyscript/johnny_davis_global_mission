@extends('layouts.admin')

@section('title', 'Email Templates')
@section('page-title', 'Email Templates')

@section('content')

{{-- Stats --}}
<div class="pages-stats">
    <div class="stat-card" style="--accent:#14b8a6;">
        <div class="stat-icon" style="background:rgba(20,184,166,0.12);color:#0f766e;">📨</div>
        <div>
            <div class="stat-value" data-target="{{ $stats['total'] }}">0</div>
            <div class="stat-label">Total Templates</div>
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

{{-- Main card --}}
<div class="admin-card pages-card">

    <form method="GET" action="{{ route('admin.email-templates.index') }}" id="filter-form">
        <div class="pages-toolbar">
            <div class="pages-toolbar-left">
                <div class="table-search-wrap">
                    <span class="table-search-icon">🔍</span>
                    <input type="text" name="search" id="page-search" class="table-search"
                        placeholder="Search templates…"
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
                @if(request()->hasAny(['search','status']))
                    <a href="{{ route('admin.email-templates.index') }}" class="filter-clear-link">Clear</a>
                @endif
            </div>
            <div class="pages-toolbar-right">
                <a href="{{ route('admin.email-templates.create') }}" class="admin-btn" style="display:inline-flex;align-items:center;gap:8px;text-decoration:none;font-size:14px;">
                    <span>＋</span> New Template
                </a>
            </div>
        </div>
    </form>

    @if($templates->isEmpty())
        <div class="empty-state" style="display:flex;">
            <div class="empty-icon">📨</div>
            <div class="empty-title">No templates found</div>
            <div class="empty-sub">
                @if(request()->hasAny(['search','status']))
                    <a href="{{ route('admin.email-templates.index') }}" style="color:var(--brand-dark);">Clear filters</a>
                @else
                    <a href="{{ route('admin.email-templates.create') }}" style="color:var(--brand-dark);">Create your first email template</a>
                @endif
            </div>
        </div>
    @else
    <div class="table-wrap">
        <table class="admin-table pages-table">
            <thead>
                <tr>
                    <th>Template</th>
                    <th>Subject</th>
                    <th>Variables</th>
                    <th>Status</th>
                    <th>Created</th>
                    <th style="width:140px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($templates as $template)
                <tr class="page-row" data-id="{{ $template->id }}" style="opacity:0;transform:translateY(8px);">
                    <td>
                        <div style="display:flex;align-items:center;gap:12px;">
                            <div class="et-icon-badge">📧</div>
                            <div>
                                <div class="page-name">{{ $template->name }}</div>
                                <div style="font-size:12px;color:var(--text-muted);margin-top:2px;">
                                    ID #{{ $template->id }}
                                </div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span class="slug-chip" style="font-family:inherit;font-size:13px;max-width:240px;display:inline-block;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                            {{ $template->subject }}
                        </span>
                    </td>
                    <td>
                        @if($template->variables)
                            @php $ob = '{{'; $cb = '}}'; @endphp
                            <div style="display:flex;flex-wrap:wrap;gap:4px;">
                                @foreach(array_keys($template->variables) as $var)
                                    <code class="et-var-chip">{{ $ob . $var . $cb }}</code>
                                @endforeach
                            </div>
                        @else
                            <span style="color:var(--text-muted);font-size:13px;">—</span>
                        @endif
                    </td>
                    <td>
                        <button
                            class="status-toggle {{ $template->is_active ? 'is-active' : 'is-inactive' }}"
                            data-url="{{ route('admin.email-templates.toggle', $template) }}"
                            data-csrf="{{ csrf_token() }}"
                            title="Click to toggle"
                        >
                            <span class="toggle-dot"></span>
                            <span class="toggle-label">{{ $template->is_active ? 'Active' : 'Inactive' }}</span>
                        </button>
                    </td>
                    <td>
                        <span class="cm-date" title="{{ $template->created_at->format('M j, Y g:i A') }}">
                            {{ $template->created_at->format('M j, Y') }}
                        </span>
                    </td>
                    <td>
                        <div class="row-actions">
                            <button class="row-btn row-btn-preview" title="Preview"
                                data-id="{{ $template->id }}"
                                data-url="{{ route('admin.email-templates.preview', $template) }}">👁️</button>
                            <a href="{{ route('admin.email-templates.edit', $template) }}"
                               class="row-btn" title="Edit" style="display:inline-grid;place-items:center;">✏️</a>
                            <button class="row-btn row-btn-delete" title="Delete"
                                data-name="{{ $template->name }}"
                                data-action="{{ route('admin.email-templates.destroy', $template) }}">🗑️</button>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="table-footer" style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:12px;">
        <span>Showing {{ $templates->firstItem() }}–{{ $templates->lastItem() }} of {{ $templates->total() }} template{{ $templates->total() !== 1 ? 's' : '' }}</span>
        @if($templates->hasPages())
            <div class="pagination-wrap">{{ $templates->links('vendor.pagination.admin') }}</div>
        @endif
    </div>
    @endif

</div>

{{-- Preview modal --}}
<div class="modal-overlay" id="preview-modal">
    <div class="modal-card" role="dialog" aria-modal="true" style="max-width:760px;width:95%;padding:0;overflow:hidden;">
        <div style="display:flex;align-items:center;justify-content:space-between;padding:18px 24px;border-bottom:1px solid var(--border);background:#fff;border-radius:28px 28px 0 0;">
            <div style="display:flex;align-items:center;gap:12px;">
                <div style="width:36px;height:36px;border-radius:10px;background:rgba(20,184,166,0.1);color:#0f766e;display:grid;place-items:center;font-size:16px;">📧</div>
                <div>
                    <div style="font-weight:700;font-size:15px;" id="preview-name">Template Preview</div>
                    <div style="font-size:12px;color:var(--text-muted);margin-top:2px;">Subject: <span id="preview-subject" style="color:var(--text-dark);font-weight:500;"></span></div>
                </div>
            </div>
            <button class="modal-btn-cancel" id="preview-close" style="margin:0;padding:8px 16px;font-size:13px;">✕ Close</button>
        </div>
        <div id="preview-loading" style="padding:56px;text-align:center;color:var(--text-muted);">
            <div class="et-spinner" style="margin:0 auto 14px;"></div>
            Rendering branded email…
        </div>
        <div id="preview-frame-wrap" style="display:none;background:#e8ecf0;">
            <iframe
                id="preview-iframe"
                title="Email Preview"
                style="width:100%;min-height:560px;border:none;display:block;"
                sandbox="allow-same-origin"
            ></iframe>
        </div>
    </div>
</div>

{{-- Delete modal --}}
<div class="modal-overlay" id="delete-modal">
    <div class="modal-card" role="dialog" aria-modal="true">
        <div class="modal-icon">🗑️</div>
        <h3 class="modal-title">Delete Template?</h3>
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
<style>
.et-icon-badge {
    width: 36px; height: 36px; border-radius: 12px;
    background: rgba(20,184,166,0.1); color: #0f766e;
    display: grid; place-items: center; font-size: 16px; flex-shrink: 0;
}
.et-var-chip {
    background: rgba(99,102,241,0.08); color: #4f46e5;
    border: 1px solid rgba(99,102,241,0.15);
    padding: 2px 8px; border-radius: 6px; font-size: 11px; font-family: monospace;
}
.et-spinner {
    width: 28px; height: 28px; border: 3px solid var(--border);
    border-top-color: var(--brand-bright); border-radius: 50%;
    animation: et-spin 0.7s linear infinite;
}
@keyframes et-spin { to { transform: rotate(360deg); } }
</style>
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

/* Row entrance animation */
document.querySelectorAll('.page-row').forEach(function (row, i) {
    setTimeout(function () {
        row.style.transition = 'opacity 0.28s ease, transform 0.28s ease';
        row.style.opacity = '1'; row.style.transform = 'translateY(0)';
    }, 50 + i * 35);
});

/* Search with debounce */
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
if (clearBtn) {
    clearBtn.addEventListener('click', function () {
        searchInput.value = ''; clearBtn.style.display = 'none'; filterForm.submit();
    });
}

/* Status toggle via AJAX */
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
            if (window.showAdminToast) window.showAdminToast('Status updated to ' + (data.is_active ? 'Active' : 'Inactive') + '.', 'success');
        })
        .catch(function () { if (window.showAdminToast) window.showAdminToast('Failed to update status.', 'error'); })
        .finally(function () { btn.classList.remove('toggling'); });
    });
});

/* Preview modal */
var previewModal     = document.getElementById('preview-modal');
var previewName      = document.getElementById('preview-name');
var previewSubject   = document.getElementById('preview-subject');
var previewLoading   = document.getElementById('preview-loading');
var previewFrameWrap = document.getElementById('preview-frame-wrap');
var previewIframe    = document.getElementById('preview-iframe');

document.querySelectorAll('.row-btn-preview').forEach(function (btn) {
    btn.addEventListener('click', function () {
        previewModal.classList.add('modal-open');
        previewLoading.style.display = 'block';
        previewFrameWrap.style.display = 'none';
        previewName.textContent    = 'Template Preview';
        previewSubject.textContent = '…';

        fetch(btn.dataset.url, { headers: { 'Accept': 'application/json' } })
            .then(function (r) { return r.json(); })
            .then(function (data) {
                previewName.textContent    = data.name;
                previewSubject.textContent = data.subject_raw;
                /* Render full branded HTML in sandboxed iframe */
                previewIframe.srcdoc = data.branded_html;
                previewLoading.style.display = 'none';
                previewFrameWrap.style.display = 'block';
                /* Auto-size iframe to content */
                previewIframe.onload = function () {
                    try {
                        var h = previewIframe.contentDocument.body.scrollHeight;
                        previewIframe.style.height = Math.max(h + 32, 400) + 'px';
                    } catch (e) {}
                };
            })
            .catch(function () {
                previewLoading.innerHTML = '<span style="color:#ef4444;">Failed to load preview.</span>';
            });
    });
});

document.getElementById('preview-close').addEventListener('click', function () {
    previewModal.classList.remove('modal-open');
    previewIframe.srcdoc = '';
});
previewModal.addEventListener('click', function (e) {
    if (e.target === previewModal) { previewModal.classList.remove('modal-open'); previewIframe.srcdoc = ''; }
});

/* Delete modal */
var deleteModal  = document.getElementById('delete-modal');
var itemName     = document.getElementById('modal-item-name');
var deleteForm   = document.getElementById('delete-form');
var confirmBtn   = document.getElementById('modal-confirm');

document.querySelectorAll('.row-btn-delete').forEach(function (btn) {
    btn.addEventListener('click', function () {
        itemName.textContent = btn.dataset.name;
        deleteForm.action    = btn.dataset.action;
        deleteModal.classList.add('modal-open');
        document.getElementById('modal-cancel').focus();
    });
});
document.getElementById('modal-cancel').addEventListener('click', function () {
    deleteModal.classList.remove('modal-open');
});
deleteModal.addEventListener('click', function (e) {
    if (e.target === deleteModal) deleteModal.classList.remove('modal-open');
});
document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') {
        deleteModal.classList.remove('modal-open');
        previewModal.classList.remove('modal-open');
    }
});
deleteForm.addEventListener('submit', function () {
    confirmBtn.disabled = true;
    confirmBtn.querySelector('.modal-spinner').style.display = 'block';
});

})();
</script>
@endpush
