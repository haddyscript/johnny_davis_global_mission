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
            <div class="pages-toolbar-left" style="flex-wrap:wrap;gap:10px;">
                {{-- Send Email button --}}
                @if($templates->isNotEmpty())
                <button type="button" id="open-bulk-email-btn"
                    style="display:inline-flex;align-items:center;gap:6px;padding:8px 16px;border-radius:8px;font-size:13px;font-weight:600;background:linear-gradient(135deg,#0f766e,#14b8a6);color:#fff;border:none;cursor:pointer;">
                    ✉️ Send Email
                </button>
                @endif

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
                <div class="filter-select-wrap">
                    <select name="type" class="filter-select" onchange="this.form.submit()">
                        <option value="">All Types</option>
                        <option value="monthly"  {{ request('type') === 'monthly'  ? 'selected' : '' }}>Monthly Donor</option>
                        <option value="one_time" {{ request('type') === 'one_time' ? 'selected' : '' }}>One-time Donor</option>
                        <option value="normal"   {{ request('type') === 'normal'   ? 'selected' : '' }}>Normal Subscriber</option>
                    </select>
                </div>
                @if(request()->hasAny(['search','status','type']))
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
                    <th>Type</th>
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
                        @if($sub->subscriber_type === 'monthly')
                            <span style="display:inline-flex;align-items:center;gap:4px;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:700;background:rgba(139,92,246,0.12);color:#7c3aed;">
                                🔁 Monthly Donor
                            </span>
                        @elseif($sub->subscriber_type === 'one_time')
                            <span style="display:inline-flex;align-items:center;gap:4px;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:700;background:rgba(20,184,166,0.12);color:#0f766e;">
                                💛 One-time Donor
                            </span>
                        @else
                            <span style="display:inline-flex;align-items:center;gap:4px;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:700;background:rgba(148,163,184,0.15);color:#64748b;">
                                📧 Subscriber
                            </span>
                        @endif
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

{{-- ══════════════════════════════════════════════
     BULK EMAIL MODAL
══════════════════════════════════════════════ --}}
<div class="modal-overlay" id="bulk-email-modal" style="align-items:flex-start;padding:32px 16px;overflow-y:auto;">
    <div class="modal-card" role="dialog" aria-modal="true" aria-labelledby="be-modal-title"
         style="max-width:820px;width:95%;padding:0;overflow:hidden;text-align:left;margin:auto;">

        {{-- Modal header --}}
        <div style="display:flex;align-items:center;justify-content:space-between;padding:20px 24px;border-bottom:1px solid var(--border);">
            <div>
                <h3 id="be-modal-title" style="margin:0;font-size:17px;font-weight:700;color:var(--text-dark);">✉️ Send Email to Subscribers</h3>
                <p style="margin:4px 0 0;font-size:13px;color:var(--text-muted);" id="be-header-sub">Select recipients and a template below.</p>
            </div>
            <button id="be-close" style="background:none;border:none;font-size:20px;cursor:pointer;color:var(--text-muted);padding:4px 8px;border-radius:6px;">✕</button>
        </div>

        {{-- Step indicators --}}
        <div id="be-steps" style="display:flex;padding:16px 24px;gap:0;border-bottom:1px solid var(--border);background:var(--surface-strong);">
            <div class="be-step be-step-active" data-step="1">
                <span class="be-step-num">1</span>
                <span class="be-step-label">Select Recipients</span>
            </div>
            <div class="be-step-arrow">›</div>
            <div class="be-step" data-step="2">
                <span class="be-step-num">2</span>
                <span class="be-step-label">Choose Template</span>
            </div>
            <div class="be-step-arrow">›</div>
            <div class="be-step" data-step="3">
                <span class="be-step-num">3</span>
                <span class="be-step-label">Confirm & Send</span>
            </div>
        </div>

        {{-- ── STEP 1: Recipient selection ── --}}
        <div id="be-panel-1" class="be-panel" style="padding:20px 24px;">

            {{-- Type filter pills --}}
            <div style="display:flex;flex-wrap:wrap;gap:6px;margin-bottom:12px;">
                <button type="button" class="be-type-filter be-type-active" data-type="">All</button>
                <button type="button" class="be-type-filter" data-type="monthly">🔁 Monthly Donors</button>
                <button type="button" class="be-type-filter" data-type="one_time">💛 One-time Donors</button>
                <button type="button" class="be-type-filter" data-type="normal">📧 Normal Subscribers</button>
            </div>

            <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:10px;margin-bottom:14px;">
                <div style="display:flex;align-items:center;gap:10px;">
                    <label style="display:flex;align-items:center;gap:6px;font-size:13px;font-weight:600;cursor:pointer;">
                        <input type="checkbox" id="be-select-all" style="width:16px;height:16px;cursor:pointer;accent-color:#0f766e;">
                        Select all active subscribers
                    </label>
                    <span id="be-selected-count" style="font-size:12px;color:var(--text-muted);">0 selected</span>
                </div>
                <div class="table-search-wrap" style="max-width:260px;">
                    <span class="table-search-icon">🔍</span>
                    <input type="text" id="be-sub-search" class="table-search" placeholder="Search subscribers…" autocomplete="off">
                </div>
            </div>

            <div id="be-sub-list-wrap" style="border:1px solid var(--border);border-radius:10px;overflow:hidden;max-height:320px;overflow-y:auto;">
                <div id="be-sub-loading" style="padding:40px;text-align:center;color:var(--text-muted);">
                    <div class="be-spinner" style="margin:0 auto 10px;"></div>
                    Loading subscribers…
                </div>
                <div id="be-sub-list" style="display:none;"></div>
                <div id="be-sub-empty" style="display:none;padding:32px;text-align:center;color:var(--text-muted);">
                    No active subscribers found.
                </div>
            </div>

            <div id="be-load-more-wrap" style="display:none;text-align:center;padding:12px;">
                <button id="be-load-more" style="background:var(--surface-strong);border:1px solid var(--border);border-radius:8px;padding:7px 20px;font-size:13px;cursor:pointer;color:var(--text-dark);">
                    Load more…
                </button>
            </div>

            <div style="display:flex;justify-content:flex-end;margin-top:16px;">
                <button id="be-next-1" class="be-btn-primary" disabled>
                    Next: Choose Template →
                </button>
            </div>
        </div>

        {{-- ── STEP 2: Template selection ── --}}
        <div id="be-panel-2" class="be-panel" style="display:none;padding:20px 24px;">

            <div style="margin-bottom:16px;">
                <label style="display:block;font-size:13px;font-weight:600;color:var(--text-dark);margin-bottom:6px;">Email Template</label>
                <select id="be-template-select"
                    style="width:100%;padding:10px 14px;border:1px solid var(--border);border-radius:8px;font-size:14px;color:var(--text-dark);background:var(--surface);outline:none;">
                    <option value="">— Select a template —</option>
                    @foreach($templates as $tpl)
                        <option value="{{ $tpl->id }}" data-subject="{{ $tpl->subject }}">{{ $tpl->name }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Dynamic variable inputs --}}
            <div id="be-var-fields" style="display:none;margin-bottom:16px;">
                <div style="font-size:13px;font-weight:600;color:var(--text-dark);margin-bottom:10px;">
                    Template Variables
                    <span style="font-size:12px;font-weight:400;color:var(--text-muted);">— fill in the dynamic content for this email</span>
                </div>
                <div id="be-var-fields-inner"></div>
            </div>

            <div id="be-preview-wrap" style="display:none;">
                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:8px;">
                    <span style="font-size:13px;font-weight:600;color:var(--text-dark);">Template Preview</span>
                    <span id="be-preview-subject" style="font-size:12px;color:var(--text-muted);font-style:italic;"></span>
                </div>
                <div style="border:1px solid var(--border);border-radius:10px;overflow:hidden;">
                    <div id="be-preview-loading" style="padding:32px;text-align:center;color:var(--text-muted);">
                        <div class="be-spinner" style="margin:0 auto 8px;"></div> Loading preview…
                    </div>
                    <iframe id="be-preview-iframe" style="width:100%;border:none;min-height:340px;display:none;" title="Template preview"></iframe>
                </div>
            </div>

            <div style="display:flex;justify-content:space-between;margin-top:20px;">
                <button id="be-back-2" class="be-btn-secondary">← Back</button>
                <button id="be-next-2" class="be-btn-primary" disabled>Next: Confirm →</button>
            </div>
        </div>

        {{-- ── STEP 3: Confirm & send ── --}}
        <div id="be-panel-3" class="be-panel" style="display:none;padding:20px 24px;">

            <div style="background:var(--surface-strong);border-radius:12px;padding:18px 20px;margin-bottom:20px;">
                <div style="font-size:13px;font-weight:700;color:var(--text-dark);margin-bottom:10px;">Summary</div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;">
                    <div>
                        <div style="font-size:11px;text-transform:uppercase;letter-spacing:.07em;color:var(--text-muted);margin-bottom:3px;">Recipients</div>
                        <div style="font-size:20px;font-weight:800;color:var(--text-dark);" id="be-confirm-count">0</div>
                    </div>
                    <div>
                        <div style="font-size:11px;text-transform:uppercase;letter-spacing:.07em;color:var(--text-muted);margin-bottom:3px;">Template</div>
                        <div style="font-size:14px;font-weight:600;color:var(--text-dark);" id="be-confirm-template">—</div>
                    </div>
                </div>
            </div>

            <div style="background:#fefce8;border:1px solid #fde68a;border-radius:10px;padding:12px 16px;font-size:13px;color:#92400e;margin-bottom:20px;">
                ⚠️ Emails will be sent immediately to all selected active subscribers. This action cannot be undone.
            </div>

            {{-- Sending progress --}}
            <div id="be-sending-wrap" style="display:none;text-align:center;padding:24px 0;">
                <div class="be-spinner be-spinner-lg" style="margin:0 auto 14px;"></div>
                <div style="font-size:14px;font-weight:600;color:var(--text-dark);">Sending emails…</div>
                <div style="font-size:12px;color:var(--text-muted);margin-top:4px;">Please wait, do not close this window.</div>
            </div>

            {{-- Result summary --}}
            <div id="be-result-wrap" style="display:none;"></div>

            <div id="be-confirm-actions" style="display:flex;justify-content:space-between;">
                <button id="be-back-3" class="be-btn-secondary">← Back</button>
                <button id="be-send-btn" class="be-btn-primary" style="background:linear-gradient(135deg,#0f766e,#14b8a6);">
                    ✉️ Send Emails
                </button>
            </div>
            <div id="be-done-actions" style="display:none;justify-content:center;margin-top:4px;">
                <button id="be-done-close" class="be-btn-primary">Done</button>
            </div>
        </div>

    </div>
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
<style>
/* ── Bulk email modal steps ── */
.be-step {
    display: flex; align-items: center; gap: 7px; font-size: 13px;
    color: var(--text-muted); font-weight: 500;
}
.be-step-active { color: #0f766e; font-weight: 700; }
.be-step-done   { color: #10b981; }
.be-step-num {
    width: 24px; height: 24px; border-radius: 50%; background: var(--border);
    color: var(--text-muted); display: grid; place-items: center;
    font-size: 12px; font-weight: 700; flex-shrink: 0;
}
.be-step-active .be-step-num { background: #0f766e; color: #fff; }
.be-step-done   .be-step-num { background: #10b981; color: #fff; }
.be-step-arrow { font-size: 18px; color: var(--border); padding: 0 10px; }

/* ── Buttons ── */
.be-btn-primary {
    padding: 9px 22px; border-radius: 8px; font-size: 13px; font-weight: 600;
    background: linear-gradient(135deg,#1e40af,#3b82f6); color: #fff;
    border: none; cursor: pointer; transition: opacity .15s;
}
.be-btn-primary:disabled { opacity: .45; cursor: not-allowed; }
.be-btn-primary:not(:disabled):hover { opacity: .88; }
.be-btn-secondary {
    padding: 9px 18px; border-radius: 8px; font-size: 13px; font-weight: 600;
    background: var(--surface-strong); color: var(--text-dark);
    border: 1px solid var(--border); cursor: pointer;
}
.be-btn-secondary:hover { background: var(--border); }

/* ── Type filter pills ── */
.be-type-filter {
    padding: 5px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;
    border: 1px solid var(--border); background: var(--surface-strong);
    color: var(--text-muted); cursor: pointer; transition: all .15s;
}
.be-type-filter:hover { border-color: #0f766e; color: #0f766e; }
.be-type-filter.be-type-active {
    background: #0f766e; color: #fff; border-color: #0f766e;
}

/* ── Subscriber type badge in modal ── */
.be-sub-type {
    display: inline-block; padding: 2px 8px; border-radius: 10px;
    font-size: 10px; font-weight: 700; margin-top: 2px;
}
.be-sub-type-monthly  { background: rgba(139,92,246,.12); color: #7c3aed; }
.be-sub-type-one_time { background: rgba(20,184,166,.12);  color: #0f766e; }
.be-sub-type-normal   { background: rgba(148,163,184,.15); color: #64748b; }

/* ── Subscriber list items ── */
.be-sub-item {
    display: flex; align-items: center; gap: 12px; padding: 10px 14px;
    border-bottom: 1px solid var(--border); cursor: pointer;
    transition: background .1s;
}
.be-sub-item:last-child { border-bottom: none; }
.be-sub-item:hover { background: var(--surface-strong); }
.be-sub-item.be-selected { background: rgba(20,184,166,0.07); }
.be-sub-avatar {
    width: 32px; height: 32px; border-radius: 8px; flex-shrink: 0;
    background: linear-gradient(135deg,#6366f1,#4f46e5);
    color: #fff; display: grid; place-items: center;
    font-weight: 700; font-size: 13px;
}

/* ── Spinner ── */
.be-spinner {
    width: 24px; height: 24px; border: 3px solid var(--border);
    border-top-color: #0f766e; border-radius: 50%;
    animation: be-spin .7s linear infinite; display: block;
}
.be-spinner-lg { width: 36px; height: 36px; border-width: 4px; }
@keyframes be-spin { to { transform: rotate(360deg); } }

/* ── Result badges ── */
.be-result-card {
    border-radius: 12px; padding: 16px 20px; margin-bottom: 12px;
    display: flex; align-items: center; gap: 14px;
}
.be-result-sent   { background: rgba(16,185,129,.08); border: 1px solid rgba(16,185,129,.25); }
.be-result-failed { background: rgba(239,68,68,.07);  border: 1px solid rgba(239,68,68,.2);  }
</style>

<script>
(function () {

/* ════════════════════════════════════════
   Stat counters + row entrance
════════════════════════════════════════ */
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
    }, 50 + i * 30);
});

/* ════════════════════════════════════════
   Subscribers index search / filter
════════════════════════════════════════ */
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

/* ════════════════════════════════════════
   Status toggle
════════════════════════════════════════ */
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

/* ════════════════════════════════════════
   Delete modal
════════════════════════════════════════ */
var deleteModal  = document.getElementById('delete-modal');
var itemName     = document.getElementById('modal-item-name');
var deleteForm   = document.getElementById('delete-form');
var confirmBtn   = document.getElementById('modal-confirm');
document.querySelectorAll('.row-btn-delete').forEach(function (btn) {
    btn.addEventListener('click', function () {
        itemName.textContent  = btn.dataset.name;
        deleteForm.action     = btn.dataset.action;
        deleteModal.classList.add('modal-open');
        document.getElementById('modal-cancel').focus();
    });
});
document.getElementById('modal-cancel').addEventListener('click', function () { deleteModal.classList.remove('modal-open'); });
deleteModal.addEventListener('click', function (e) { if (e.target === deleteModal) deleteModal.classList.remove('modal-open'); });
deleteForm.addEventListener('submit', function () {
    confirmBtn.disabled = true;
    confirmBtn.querySelector('.modal-spinner').style.display = 'block';
});

/* ════════════════════════════════════════
   BULK EMAIL MODAL
════════════════════════════════════════ */
var CSRF        = '{{ csrf_token() }}';
var FETCH_URL   = '{{ route("admin.subscribers.fetch") }}';
var SEND_URL    = '{{ route("admin.subscribers.bulk-email") }}';
var PREVIEW_BASE = '{{ url("admin/email-templates") }}';

// Template variables map: { templateId: { varKey: description, ... } }
// Auto-filled vars (name, email, unsubscribe_link) are excluded from the UI
var AUTO_VARS   = ['name', 'email', 'unsubscribe_link'];
var TPL_VARS    = {!! $templates->mapWithKeys(fn($t) => [
    $t->id => collect($t->variables ?? [])->except(['name','email','unsubscribe_link'])
])->toJson() !!};

var beModal        = document.getElementById('bulk-email-modal');
var openBtn        = document.getElementById('open-bulk-email-btn');
var closeBtn       = document.getElementById('be-close');

/* State */
var selectedIds    = new Set();
var allIds         = [];          /* ids of all loaded subscribers */
var currentPage    = 1;
var lastPage       = 1;
var searchTimer;
var activeType     = '';          /* '', 'monthly', 'one_time', 'normal' */

/* ── Type filter pills ── */
document.querySelectorAll('.be-type-filter').forEach(function (btn) {
    btn.addEventListener('click', function () {
        document.querySelectorAll('.be-type-filter').forEach(function (b) { b.classList.remove('be-type-active'); });
        this.classList.add('be-type-active');
        activeType = this.dataset.type;
        loadSubscribers(1, true, document.getElementById('be-sub-search').value);
    });
});

/* ── Open / close ── */
if (openBtn) {
    openBtn.addEventListener('click', function () {
        beModal.classList.add('modal-open');
        goToStep(1);
        loadSubscribers(1, true);
    });
}
closeBtn.addEventListener('click', closeModal);
beModal.addEventListener('click', function (e) { if (e.target === beModal) closeModal(); });
document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape' && beModal.classList.contains('modal-open')) closeModal();
});
function closeModal() {
    beModal.classList.remove('modal-open');
    resetModal();
}

/* ── Step navigation ── */
function goToStep(n) {
    [1,2,3].forEach(function (i) {
        document.getElementById('be-panel-' + i).style.display = (i === n) ? 'block' : 'none';
        var stepEl = document.querySelector('.be-step[data-step="' + i + '"]');
        stepEl.classList.remove('be-step-active','be-step-done');
        if (i === n) stepEl.classList.add('be-step-active');
        else if (i < n) stepEl.classList.add('be-step-done');
    });
    document.getElementById('be-header-sub').textContent =
        n === 1 ? 'Select recipients and a template below.'
        : n === 2 ? 'Choose an email template for this campaign.'
        : 'Review and confirm before sending.';
}

document.getElementById('be-next-1').addEventListener('click', function () { goToStep(2); });
document.getElementById('be-back-2').addEventListener('click', function () { goToStep(1); });
document.getElementById('be-next-2').addEventListener('click', function () {
    populateConfirm();
    goToStep(3);
});
document.getElementById('be-back-3').addEventListener('click', function () { goToStep(2); });
document.getElementById('be-done-close').addEventListener('click', function () { closeModal(); location.reload(); });

/* ── Load subscribers (AJAX) ── */
function loadSubscribers(page, reset, search) {
    var loading = document.getElementById('be-sub-loading');
    var listEl  = document.getElementById('be-sub-list');
    var emptyEl = document.getElementById('be-sub-empty');
    var moreEl  = document.getElementById('be-load-more-wrap');

    if (reset) {
        currentPage = 1;
        allIds = [];
        listEl.innerHTML = '';
        listEl.style.display = 'none';
        emptyEl.style.display = 'none';
        loading.style.display = 'block';
        moreEl.style.display  = 'none';
    }

    var url = FETCH_URL + '?page=' + (page || 1);
    if (search) url += '&search=' + encodeURIComponent(search);
    if (activeType) url += '&type=' + encodeURIComponent(activeType);

    fetch(url, { headers: { 'Accept': 'application/json' } })
        .then(function (r) { return r.json(); })
        .then(function (res) {
            loading.style.display = 'none';

            if (res.data.length === 0 && page === 1) {
                emptyEl.style.display = 'block';
                moreEl.style.display  = 'none';
                return;
            }

            listEl.style.display = 'block';
            lastPage = res.last_page;
            currentPage = res.current_page;

            res.data.forEach(function (sub) {
                allIds.push(sub.id);
                var item = buildSubItem(sub);
                listEl.appendChild(item);
            });

            moreEl.style.display = (currentPage < lastPage) ? 'block' : 'none';
            syncSelectAll();
            updateCount();
        });
}

function buildSubItem(sub) {
    var div = document.createElement('div');
    div.className = 'be-sub-item' + (selectedIds.has(sub.id) ? ' be-selected' : '');
    div.dataset.id = sub.id;
    var typeLabels = { monthly: '🔁 Monthly', one_time: '💛 One-time', normal: '📧 Subscriber' };
    var typeClass  = 'be-sub-type be-sub-type-' + (sub.subscriber_type || 'normal');
    var typeLabel  = typeLabels[sub.subscriber_type] || typeLabels['normal'];

    div.innerHTML =
        '<input type="checkbox" style="width:16px;height:16px;flex-shrink:0;accent-color:#0f766e;" ' +
            (selectedIds.has(sub.id) ? 'checked' : '') + '>' +
        '<div class="be-sub-avatar">' + sub.first_name.charAt(0).toUpperCase() + '</div>' +
        '<div style="flex:1;min-width:0;">' +
            '<div style="font-size:13px;font-weight:600;color:var(--text-dark);">' + escHtml(sub.first_name) + '</div>' +
            '<div style="font-size:12px;color:var(--text-muted);">' + escHtml(sub.email) + '</div>' +
            '<span class="' + typeClass + '">' + typeLabel + '</span>' +
        '</div>';

    div.addEventListener('click', function (e) {
        if (e.target.tagName === 'INPUT') return; /* checkbox handles itself */
        var cb = div.querySelector('input[type=checkbox]');
        cb.checked = !cb.checked;
        toggleSub(sub.id, cb.checked, div);
    });
    div.querySelector('input').addEventListener('change', function () {
        toggleSub(sub.id, this.checked, div);
    });
    return div;
}

function toggleSub(id, checked, itemEl) {
    if (checked) selectedIds.add(id);
    else selectedIds.delete(id);
    itemEl.classList.toggle('be-selected', checked);
    syncSelectAll();
    updateCount();
    updateNext1();
}

/* ── Select all ── */
var selectAll = document.getElementById('be-select-all');
selectAll.addEventListener('change', function () {
    var checked = this.checked;
    document.querySelectorAll('#be-sub-list .be-sub-item').forEach(function (item) {
        var id = parseInt(item.dataset.id, 10);
        var cb = item.querySelector('input[type=checkbox]');
        cb.checked = checked;
        if (checked) { selectedIds.add(id); item.classList.add('be-selected'); }
        else          { selectedIds.delete(id); item.classList.remove('be-selected'); }
    });
    updateCount();
    updateNext1();
});

function syncSelectAll() {
    var items = document.querySelectorAll('#be-sub-list .be-sub-item');
    if (!items.length) { selectAll.checked = false; selectAll.indeterminate = false; return; }
    var numChecked = 0;
    items.forEach(function (item) { if (selectedIds.has(parseInt(item.dataset.id, 10))) numChecked++; });
    selectAll.checked       = numChecked === items.length;
    selectAll.indeterminate = numChecked > 0 && numChecked < items.length;
}

/* ── Load more ── */
document.getElementById('be-load-more').addEventListener('click', function () {
    loadSubscribers(currentPage + 1, false, document.getElementById('be-sub-search').value);
});

/* ── In-modal search ── */
document.getElementById('be-sub-search').addEventListener('input', function () {
    clearTimeout(searchTimer);
    var val = this.value;
    searchTimer = setTimeout(function () { loadSubscribers(1, true, val); }, 400);
});

function updateCount() {
    document.getElementById('be-selected-count').textContent = selectedIds.size + ' selected';
}
function updateNext1() {
    document.getElementById('be-next-1').disabled = selectedIds.size === 0;
}

/* ── Template select & preview ── */
var templateSelect = document.getElementById('be-template-select');
var next2Btn       = document.getElementById('be-next-2');

templateSelect.addEventListener('change', function () {
    next2Btn.disabled = !this.value;
    if (this.value) {
        renderVarFields(parseInt(this.value, 10));
        loadTemplatePreview(this.value);
    } else {
        document.getElementById('be-preview-wrap').style.display = 'none';
        document.getElementById('be-var-fields').style.display = 'none';
    }
});

function renderVarFields(tplId) {
    var container = document.getElementById('be-var-fields');
    var inner     = document.getElementById('be-var-fields-inner');
    var vars      = TPL_VARS[tplId] || {};
    var keys      = Object.keys(vars);

    if (!keys.length) { container.style.display = 'none'; return; }

    inner.innerHTML = '';
    keys.forEach(function (key) {
        var desc  = vars[key];
        var label = document.createElement('label');
        label.style.cssText = 'display:block;margin-bottom:12px;';
        var isLong = key === 'message' || key.indexOf('story') !== -1
                  || key.indexOf('update') !== -1 || key.indexOf('message') !== -1
                  || key.indexOf('summary') !== -1;
        label.innerHTML =
            '<span style="display:block;font-size:12px;font-weight:600;color:var(--text-dark);margin-bottom:4px;">' +
                '{{' + escHtml(key) + '}}' +
                (desc ? '<span style="font-weight:400;color:var(--text-muted);margin-left:6px;">— ' + escHtml(desc) + '</span>' : '') +
            '</span>' +
            (isLong
                ? '<textarea name="custom_data[' + escHtml(key) + ']" data-var="' + escHtml(key) + '" rows="4" placeholder="Enter ' + escHtml(key.replace(/_/g,' ')) + '…" style="width:100%;padding:9px 12px;border:1px solid var(--border);border-radius:8px;font-size:13px;resize:vertical;font-family:inherit;color:var(--text-dark);background:var(--surface);"></textarea>'
                : '<input type="text" name="custom_data[' + escHtml(key) + ']" data-var="' + escHtml(key) + '" placeholder="Enter ' + escHtml(key.replace(/_/g,' ')) + '…" style="width:100%;padding:9px 12px;border:1px solid var(--border);border-radius:8px;font-size:13px;color:var(--text-dark);background:var(--surface);">'
            );
        inner.appendChild(label);
    });

    container.style.display = 'block';
}

function loadTemplatePreview(templateId) {
    var selected = templateSelect.options[templateSelect.selectedIndex];
    var subject  = selected ? selected.dataset.subject : '';
    var wrap     = document.getElementById('be-preview-wrap');
    var loading  = document.getElementById('be-preview-loading');
    var iframe   = document.getElementById('be-preview-iframe');

    wrap.style.display    = 'block';
    loading.style.display = 'block';
    iframe.style.display  = 'none';
    document.getElementById('be-preview-subject').textContent = subject ? '"' + subject + '"' : '';

    fetch(PREVIEW_BASE + '/' + templateId + '/preview', {
        headers: { 'Accept': 'application/json' },
    })
    .then(function (r) { return r.json(); })
    .catch(function () { return null; })
    .then(function (d) {
        loading.style.display = 'none';
        if (!d || !d.branded_html) { wrap.style.display = 'none'; return; }
        iframe.style.display = 'block';
        var doc = iframe.contentDocument || iframe.contentWindow.document;
        doc.open(); doc.write(d.branded_html); doc.close();
    });
}

/* ── Confirm panel ── */
function populateConfirm() {
    document.getElementById('be-confirm-count').textContent = selectedIds.size;
    var opt = templateSelect.options[templateSelect.selectedIndex];
    document.getElementById('be-confirm-template').textContent = opt ? opt.textContent.trim() : '—';

    /* Reset state */
    document.getElementById('be-sending-wrap').style.display = 'none';
    document.getElementById('be-result-wrap').style.display  = 'none';
    document.getElementById('be-result-wrap').innerHTML      = '';
    document.getElementById('be-confirm-actions').style.display = 'flex';
    document.getElementById('be-done-actions').style.display    = 'none';
    document.getElementById('be-send-btn').disabled = false;
}

/* ── Send ── */
document.getElementById('be-send-btn').addEventListener('click', function () {
    var btn = this;
    btn.disabled = true;

    document.getElementById('be-confirm-actions').style.display = 'none';
    document.getElementById('be-sending-wrap').style.display    = 'block';
    document.getElementById('be-result-wrap').style.display     = 'none';

    fetch(SEND_URL, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': CSRF,
        },
        body: JSON.stringify({
            template_id:    parseInt(templateSelect.value, 10),
            subscriber_ids: Array.from(selectedIds),
            custom_data:    collectCustomData(),
        }),
    })
    .then(function (r) { return r.json(); })
    .then(function (d) {
        document.getElementById('be-sending-wrap').style.display = 'none';
        var resultWrap = document.getElementById('be-result-wrap');
        resultWrap.style.display = 'block';

        if (d.message) {
            /* validation error */
            resultWrap.innerHTML = '<div class="be-result-card be-result-failed"><span style="font-size:22px;">⚠️</span><div><strong style="color:#dc2626;">Error</strong><div style="font-size:13px;color:#991b1b;margin-top:3px;">' + escHtml(d.message) + '</div></div></div>';
            document.getElementById('be-confirm-actions').style.display = 'flex';
            btn.disabled = false;
            return;
        }

        var html = '';
        html += '<div class="be-result-card be-result-sent"><span style="font-size:28px;">✅</span>';
        html += '<div><div style="font-size:22px;font-weight:800;color:#059669;">' + d.sent + '</div>';
        html += '<div style="font-size:13px;color:#065f46;">Emails sent successfully</div></div></div>';

        if (d.failed > 0) {
            html += '<div class="be-result-card be-result-failed"><span style="font-size:28px;">❌</span>';
            html += '<div><div style="font-size:22px;font-weight:800;color:#dc2626;">' + d.failed + '</div>';
            html += '<div style="font-size:13px;color:#991b1b;">Failed to send</div>';
            if (d.errors && d.errors.length) {
                html += '<ul style="margin:6px 0 0;padding-left:18px;font-size:12px;color:#b91c1c;">';
                d.errors.slice(0,5).forEach(function (e) { html += '<li>' + escHtml(e) + '</li>'; });
                if (d.errors.length > 5) html += '<li>…and ' + (d.errors.length - 5) + ' more</li>';
                html += '</ul>';
            }
            html += '</div></div>';
        }
        resultWrap.innerHTML = html;

        document.getElementById('be-done-actions').style.display    = 'flex';
        document.getElementById('be-confirm-actions').style.display = 'none';

        if (window.showAdminToast) {
            window.showAdminToast('Sent ' + d.sent + ' email' + (d.sent !== 1 ? 's' : '') + '.', d.failed ? 'warning' : 'success');
        }
    })
    .catch(function () {
        document.getElementById('be-sending-wrap').style.display = 'none';
        document.getElementById('be-confirm-actions').style.display = 'flex';
        btn.disabled = false;
        if (window.showAdminToast) window.showAdminToast('An error occurred. Please try again.', 'error');
    });
});

/* ── Reset ── */
function resetModal() {
    selectedIds.clear();
    allIds = [];
    activeType = '';
    goToStep(1);
    document.getElementById('be-sub-list').innerHTML = '';
    document.getElementById('be-sub-list').style.display = 'none';
    document.getElementById('be-sub-loading').style.display = 'block';
    document.getElementById('be-sub-empty').style.display = 'none';
    document.getElementById('be-load-more-wrap').style.display = 'none';
    document.getElementById('be-sub-search').value = '';
    document.getElementById('be-select-all').checked = false;
    document.getElementById('be-select-all').indeterminate = false;
    document.getElementById('be-selected-count').textContent = '0 selected';
    document.getElementById('be-next-1').disabled = true;
    document.getElementById('be-template-select').value = '';
    document.getElementById('be-preview-wrap').style.display = 'none';
    document.getElementById('be-var-fields').style.display = 'none';
    document.getElementById('be-var-fields-inner').innerHTML = '';
    document.getElementById('be-next-2').disabled = true;
    /* Reset type filter pills */
    document.querySelectorAll('.be-type-filter').forEach(function (b) { b.classList.remove('be-type-active'); });
    var allPill = document.querySelector('.be-type-filter[data-type=""]');
    if (allPill) allPill.classList.add('be-type-active');
}

/* ── Collect custom var fields ── */
function collectCustomData() {
    var data = {};
    document.querySelectorAll('#be-var-fields-inner [data-var]').forEach(function (el) {
        data[el.dataset.var] = el.value;
    });
    return data;
}

/* ── Utility ── */
function escHtml(str) {
    return String(str)
        .replace(/&/g,'&amp;').replace(/</g,'&lt;')
        .replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}

})();
</script>
@endpush
