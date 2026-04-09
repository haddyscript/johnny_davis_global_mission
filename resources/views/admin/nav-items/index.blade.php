@extends('layouts.admin')

@section('title', 'Navigation Items')
@section('page-title', 'Navigation Items')

@section('content')

<div class="pages-stats cols-3">
    <div class="stat-card" style="--accent:#14b8a6;">
        <div class="stat-icon" style="background:rgba(20,184,166,0.12);color:#0f766e;">🔗</div>
        <div>
            <div class="stat-value" data-target="{{ $items->count() }}">0</div>
            <div class="stat-label">Total Items</div>
        </div>
    </div>
    <div class="stat-card" style="--accent:#10b981;">
        <div class="stat-icon" style="background:rgba(16,185,129,0.12);color:#059669;">✅</div>
        <div>
            <div class="stat-value" data-target="{{ $items->where('is_active', true)->count() }}">0</div>
            <div class="stat-label">Visible</div>
        </div>
    </div>
    <div class="stat-card" style="--accent:#94a3b8;">
        <div class="stat-icon" style="background:rgba(148,163,184,0.12);color:#64748b;">⏸️</div>
        <div>
            <div class="stat-value" data-target="{{ $items->where('is_active', false)->count() }}">0</div>
            <div class="stat-label">Hidden</div>
        </div>
    </div>
</div>

<div class="admin-card pages-card">

    <div class="pages-toolbar">
        <div class="pages-toolbar-left">
            <div style="font-size:13px;color:var(--text-muted);">
                Drag rows to reorder. Changes apply immediately to the website.
            </div>
        </div>
        <div class="pages-toolbar-right">
            <button type="button" class="admin-btn" id="add-item-btn" style="display:inline-flex;align-items:center;gap:8px;font-size:14px;">
                <span>＋</span> Add Item
            </button>
        </div>
    </div>

    @if($items->isEmpty())
        <div class="empty-state" style="display:flex;">
            <div class="empty-icon">🔗</div>
            <div class="empty-title">No navigation items yet</div>
            <div class="empty-sub">Add your first item to build the site navigation.</div>
        </div>
    @else
    <div class="table-wrap">
        <table class="admin-table pages-table">
            <thead>
                <tr>
                    <th style="width:36px;"></th>
                    <th>Label</th>
                    <th>URL</th>
                    <th>CSS Class</th>
                    <th>External</th>
                    <th>Status</th>
                    <th style="width:120px;">Actions</th>
                </tr>
            </thead>
            <tbody id="nav-sortable">
                @foreach($items as $item)
                <tr class="nav-row" data-id="{{ $item->id }}" style="opacity:0;transform:translateY(8px);">
                    <td class="drag-handle" title="Drag to reorder">⠿</td>
                    <td>
                        <div style="font-weight:600;font-size:13.5px;color:var(--text-dark);">{{ $item->label }}</div>
                    </td>
                    <td>
                        <code class="slug-chip" style="font-family:monospace;font-size:12px;">{{ $item->url }}</code>
                    </td>
                    <td>
                        @if($item->nav_class)
                            <code class="et-var-chip">{{ $item->nav_class }}</code>
                        @else
                            <span style="color:var(--text-muted);font-size:13px;">—</span>
                        @endif
                    </td>
                    <td>
                        <span style="font-size:13px;color:var(--text-muted);">{{ $item->is_external ? 'Yes ↗' : 'No' }}</span>
                    </td>
                    <td>
                        <button
                            class="status-toggle {{ $item->is_active ? 'is-active' : 'is-inactive' }}"
                            data-url="{{ route('admin.nav-items.toggle', $item) }}"
                            data-csrf="{{ csrf_token() }}"
                            title="Click to toggle visibility"
                        >
                            <span class="toggle-dot"></span>
                            <span class="toggle-label">{{ $item->is_active ? 'Visible' : 'Hidden' }}</span>
                        </button>
                    </td>
                    <td>
                        <div class="row-actions">
                            <button class="row-btn row-btn-edit" title="Edit"
                                data-id="{{ $item->id }}"
                                data-label="{{ $item->label }}"
                                data-url-value="{{ $item->url }}"
                                data-nav-class="{{ $item->nav_class }}"
                                data-is-external="{{ $item->is_external ? '1' : '0' }}">✏️</button>
                            <button class="row-btn row-btn-delete" title="Delete"
                                data-name="{{ $item->label }}"
                                data-action="{{ route('admin.nav-items.destroy', $item) }}">🗑️</button>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

</div>

{{-- Add / Edit Modal --}}
<div class="modal-overlay" id="item-modal">
    <div class="modal-card" role="dialog" aria-modal="true" style="max-width:480px;width:95%;padding:0;overflow:hidden;">
        <div style="display:flex;align-items:center;justify-content:space-between;padding:18px 24px;border-bottom:1px solid var(--border);background:#fff;border-radius:16px 16px 0 0;flex-shrink:0;">
            <div style="font-weight:700;font-size:15px;" id="modal-title">Add Navigation Item</div>
            <button class="modal-btn-cancel" id="modal-close" style="margin:0;padding:8px 16px;font-size:13px;">✕</button>
        </div>
        <div style="padding:24px;">
            <form id="item-form">
                <input type="hidden" id="item-id" value="">

                <div class="pf-group">
                    <label class="pf-label" for="f-label">Label <span class="pf-required">*</span>
                        <span class="pf-hint">Text shown in the navigation bar.</span>
                    </label>
                    <input id="f-label" name="label" type="text" class="pf-input" placeholder="e.g. About Us" required autocomplete="off">
                </div>

                <div class="pf-group">
                    <label class="pf-label" for="f-url">URL <span class="pf-required">*</span>
                        <span class="pf-hint">Use <code>/path</code>, <code>/#anchor</code>, or a full URL.</span>
                    </label>
                    <input id="f-url" name="url" type="text" class="pf-input" placeholder="e.g. /contact or /#mission" required autocomplete="off">
                </div>

                <div class="pf-group">
                    <label class="pf-label" for="f-nav-class">CSS Class
                        <span class="pf-hint">Optional. Add <code>btn-nav-donate</code> for the donate button style.</span>
                    </label>
                    <input id="f-nav-class" name="nav_class" type="text" class="pf-input" placeholder="e.g. btn-nav-donate" autocomplete="off">
                </div>

                <div class="pf-group">
                    <label class="status-toggle-row" for="f-external" style="gap:12px;">
                        <div class="toggle-switch-wrap">
                            <input type="checkbox" id="f-external" name="is_external" value="1" class="toggle-checkbox">
                            <span class="toggle-slider"></span>
                        </div>
                        <div>
                            <div style="font-weight:600;font-size:13px;color:var(--text-dark);">Open in new tab</div>
                            <div style="font-size:12px;color:var(--text-muted);margin-top:2px;">For external links.</div>
                        </div>
                    </label>
                </div>

                <div style="display:flex;gap:10px;margin-top:8px;">
                    <button type="submit" class="admin-btn pf-submit-btn" id="modal-save" style="flex:1;">
                        <span class="btn-text">Save</span>
                        <span class="btn-spinner"></span>
                    </button>
                    <button type="button" class="admin-btn-secondary" id="modal-cancel-btn" style="flex:1;">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Delete Modal --}}
<div class="modal-overlay" id="delete-modal">
    <div class="modal-card" role="dialog" aria-modal="true">
        <div class="modal-icon">🗑️</div>
        <h3 class="modal-title">Remove Item?</h3>
        <p class="modal-body">Remove <strong id="modal-item-name"></strong> from the navigation?<br>This cannot be undone.</p>
        <div class="modal-actions">
            <button class="modal-btn-cancel" id="delete-cancel">Cancel</button>
            <button class="modal-btn-confirm" id="delete-confirm"><span>Remove</span><span class="modal-spinner"></span></button>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<style>
.drag-handle {
    cursor: grab;
    color: var(--text-muted);
    font-size: 18px;
    padding: 0 8px;
    user-select: none;
}
.drag-handle:active { cursor: grabbing; }
.nav-row.drag-over { background: rgba(34,197,94,0.08); outline: 2px dashed var(--brand-bright); }
.nav-row.dragging  { opacity: 0.4; }
.et-var-chip {
    background: rgba(99,102,241,0.08); color: #4f46e5;
    border: 1px solid rgba(99,102,241,0.15);
    padding: 2px 8px; border-radius: 6px; font-size: 11px; font-family: monospace;
}
</style>
<script>
(function () {
'use strict';

var CSRF = '{{ csrf_token() }}';
var REORDER_URL = '{{ route("admin.nav-items.reorder") }}';
var STORE_URL   = '{{ route("admin.nav-items.store") }}';

/* ── Stat counters ── */
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

/* ── Row entrance animation ── */
document.querySelectorAll('.nav-row').forEach(function (row, i) {
    setTimeout(function () {
        row.style.transition = 'opacity 0.28s ease, transform 0.28s ease';
        row.style.opacity = '1'; row.style.transform = 'translateY(0)';
    }, 50 + i * 40);
});

/* ── Drag-to-reorder ── */
var tbody = document.getElementById('nav-sortable');
var dragged = null;

if (tbody) {
    tbody.addEventListener('dragstart', function (e) {
        dragged = e.target.closest('tr');
        if (!dragged) return;
        dragged.classList.add('dragging');
        e.dataTransfer.effectAllowed = 'move';
    });
    tbody.addEventListener('dragend', function () {
        if (dragged) dragged.classList.remove('dragging');
        tbody.querySelectorAll('.nav-row').forEach(function (r) { r.classList.remove('drag-over'); });
        saveOrder();
    });
    tbody.addEventListener('dragover', function (e) {
        e.preventDefault();
        var target = e.target.closest('tr');
        if (!target || target === dragged) return;
        tbody.querySelectorAll('.nav-row').forEach(function (r) { r.classList.remove('drag-over'); });
        target.classList.add('drag-over');
        var rect = target.getBoundingClientRect();
        var mid  = rect.top + rect.height / 2;
        if (e.clientY < mid) {
            tbody.insertBefore(dragged, target);
        } else {
            tbody.insertBefore(dragged, target.nextSibling);
        }
    });

    /* Make rows draggable */
    tbody.querySelectorAll('.nav-row').forEach(function (row) {
        row.setAttribute('draggable', 'true');
    });
}

function saveOrder() {
    var ids = Array.from(tbody.querySelectorAll('.nav-row')).map(function (r) {
        return parseInt(r.dataset.id, 10);
    });
    fetch(REORDER_URL, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
        body: JSON.stringify({ ids: ids }),
    }).then(function () {
        if (window.showAdminToast) window.showAdminToast('Navigation order saved.', 'success');
    }).catch(function () {
        if (window.showAdminToast) window.showAdminToast('Failed to save order.', 'error');
    });
}

/* ── Status toggle ── */
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
            btn.querySelector('.toggle-label').textContent = data.is_active ? 'Visible' : 'Hidden';
            if (window.showAdminToast) window.showAdminToast('Visibility updated.', 'success');
        })
        .catch(function () { if (window.showAdminToast) window.showAdminToast('Failed to update.', 'error'); })
        .finally(function () { btn.classList.remove('toggling'); });
    });
});

/* ── Add / Edit modal ── */
var itemModal    = document.getElementById('item-modal');
var modalTitle   = document.getElementById('modal-title');
var itemForm     = document.getElementById('item-form');
var fId          = document.getElementById('item-id');
var fLabel       = document.getElementById('f-label');
var fUrl         = document.getElementById('f-url');
var fNavClass    = document.getElementById('f-nav-class');
var fExternal    = document.getElementById('f-external');
var modalSaveBtn = document.getElementById('modal-save');

function openModal(mode, data) {
    fId.value        = data ? data.id       : '';
    fLabel.value     = data ? data.label    : '';
    fUrl.value       = data ? data.urlValue : '';
    fNavClass.value  = data ? data.navClass : '';
    fExternal.checked= data ? data.isExternal === '1' : false;
    modalTitle.textContent = mode === 'edit' ? 'Edit Navigation Item' : 'Add Navigation Item';
    modalSaveBtn.querySelector('.btn-text').textContent = mode === 'edit' ? 'Save Changes' : 'Add Item';
    modalSaveBtn.classList.remove('submitting');
    modalSaveBtn.disabled = false;
    itemModal.classList.add('modal-open');
    fLabel.focus();
}

document.getElementById('add-item-btn').addEventListener('click', function () {
    openModal('add', null);
});

document.querySelectorAll('.row-btn-edit').forEach(function (btn) {
    btn.addEventListener('click', function () {
        openModal('edit', {
            id:         btn.dataset.id,
            label:      btn.dataset.label,
            urlValue:   btn.dataset.urlValue,
            navClass:   btn.dataset.navClass,
            isExternal: btn.dataset.isExternal,
        });
    });
});

function closeItemModal() { itemModal.classList.remove('modal-open'); }
document.getElementById('modal-close').addEventListener('click', closeItemModal);
document.getElementById('modal-cancel-btn').addEventListener('click', closeItemModal);
itemModal.addEventListener('click', function (e) { if (e.target === itemModal) closeItemModal(); });

itemForm.addEventListener('submit', function (e) {
    e.preventDefault();
    modalSaveBtn.classList.add('submitting');
    modalSaveBtn.disabled = true;

    var id  = fId.value;
    var url = id ? '{{ url("admin/nav-items") }}/' + id : STORE_URL;
    var method = id ? 'PUT' : 'POST';
    var body = JSON.stringify({
        label:       fLabel.value.trim(),
        url:         fUrl.value.trim(),
        nav_class:   fNavClass.value.trim() || null,
        is_external: fExternal.checked ? 1 : 0,
    });

    fetch(url, {
        method: method,
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
        body: body,
    })
    .then(function (r) { return r.json(); })
    .then(function (data) {
        if (data.ok) {
            closeItemModal();
            if (window.showAdminToast) window.showAdminToast(id ? 'Item updated.' : 'Item added.', 'success');
            setTimeout(function () { window.location.reload(); }, 600);
        }
    })
    .catch(function () {
        modalSaveBtn.classList.remove('submitting');
        modalSaveBtn.disabled = false;
        if (window.showAdminToast) window.showAdminToast('Failed to save item.', 'error');
    });
});

/* ── Delete modal ── */
var deleteModal   = document.getElementById('delete-modal');
var deleteItemName= document.getElementById('modal-item-name');
var deleteConfirm = document.getElementById('delete-confirm');
var currentDeleteUrl = null;

document.querySelectorAll('.row-btn-delete').forEach(function (btn) {
    btn.addEventListener('click', function () {
        deleteItemName.textContent = btn.dataset.name;
        currentDeleteUrl = btn.dataset.action;
        deleteModal.classList.add('modal-open');
        document.getElementById('delete-cancel').focus();
    });
});

document.getElementById('delete-cancel').addEventListener('click', function () {
    deleteModal.classList.remove('modal-open');
});
deleteModal.addEventListener('click', function (e) {
    if (e.target === deleteModal) deleteModal.classList.remove('modal-open');
});

deleteConfirm.addEventListener('click', function () {
    if (!currentDeleteUrl) return;
    deleteConfirm.disabled = true;
    deleteConfirm.querySelector('.modal-spinner').style.display = 'block';
    fetch(currentDeleteUrl, {
        method: 'DELETE',
        headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
    })
    .then(function () {
        deleteModal.classList.remove('modal-open');
        if (window.showAdminToast) window.showAdminToast('Item removed.', 'success');
        setTimeout(function () { window.location.reload(); }, 600);
    })
    .catch(function () {
        deleteConfirm.disabled = false;
        deleteConfirm.querySelector('.modal-spinner').style.display = 'none';
        if (window.showAdminToast) window.showAdminToast('Failed to remove item.', 'error');
    });
});

document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') {
        closeItemModal();
        deleteModal.classList.remove('modal-open');
    }
});

})();
</script>
@endpush
