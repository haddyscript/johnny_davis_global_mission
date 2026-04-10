@extends('layouts.admin')

@section('title', 'Email Logs')
@section('page-title', 'Email Logs')

@section('content')

{{-- Stats --}}
<div class="pages-stats cols-3">
    <div class="stat-card" style="--accent:#6366f1;">
        <div class="stat-icon" style="background:rgba(99,102,241,0.12);color:#4f46e5;">📧</div>
        <div>
            <div class="stat-value" data-target="{{ $stats['total'] }}">0</div>
            <div class="stat-label">Total Sent</div>
        </div>
    </div>
    <div class="stat-card" style="--accent:#10b981;">
        <div class="stat-icon" style="background:rgba(16,185,129,0.12);color:#059669;">✅</div>
        <div>
            <div class="stat-value" data-target="{{ $stats['sent'] }}">0</div>
            <div class="stat-label">Delivered</div>
        </div>
    </div>
    <div class="stat-card" style="--accent:#ef4444;">
        <div class="stat-icon" style="background:rgba(239,68,68,0.12);color:#dc2626;">❌</div>
        <div>
            <div class="stat-value" data-target="{{ $stats['failed'] }}">0</div>
            <div class="stat-label">Failed</div>
        </div>
    </div>
</div>

{{-- Main card --}}
<div class="admin-card pages-card">

    <form method="GET" action="{{ route('admin.email-logs.index') }}" id="filter-form">
        <div class="pages-toolbar">
            <div class="pages-toolbar-left" style="flex-wrap:wrap;gap:10px;">
                <div class="table-search-wrap">
                    <span class="table-search-icon">🔍</span>
                    <input type="text" name="search" id="page-search" class="table-search"
                        placeholder="Search recipient, subject…"
                        value="{{ request('search') }}" autocomplete="off">
                    <button type="button" class="table-search-clear" id="search-clear"
                        style="display:{{ request('search') ? 'flex' : 'none' }};">✕</button>
                </div>
                <div class="filter-select-wrap">
                    <select name="status" class="filter-select" onchange="this.form.submit()">
                        <option value="">All Status</option>
                        <option value="sent"   {{ request('status') === 'sent'   ? 'selected' : '' }}>Sent</option>
                        <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }}>Failed</option>
                    </select>
                </div>
                <div class="filter-select-wrap">
                    <select name="template" class="filter-select" onchange="this.form.submit()">
                        <option value="">All Templates</option>
                        @foreach($templates as $tpl)
                            <option value="{{ $tpl->id }}" {{ request('template') == $tpl->id ? 'selected' : '' }}>
                                {{ $tpl->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                @if(request()->hasAny(['search', 'status', 'template']))
                    <a href="{{ route('admin.email-logs.index') }}" class="filter-clear-link">Clear</a>
                @endif
            </div>
        </div>
    </form>

    @if($logs->isEmpty())
        <div class="empty-state" style="display:flex;">
            <div class="empty-icon">📭</div>
            <div class="empty-title">No email logs found</div>
            <div class="empty-sub">
                @if(request()->hasAny(['search', 'status', 'template']))
                    <a href="{{ route('admin.email-logs.index') }}" style="color:var(--brand-dark);">Clear filters</a>
                @else
                    Email logs will appear here after emails are sent through the system.
                @endif
            </div>
        </div>
    @else
    <div class="table-wrap">
        <table class="admin-table pages-table" id="logs-table">
            <thead>
                <tr>
                    <th style="width:48px;">#</th>
                    <th>Recipient</th>
                    <th>Subject</th>
                    <th>Template</th>
                    <th>Status</th>
                    <th>Sent At</th>
                    <th style="width:80px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($logs as $log)
                <tr class="page-row" style="opacity:0;transform:translateY(8px);">
                    <td style="color:var(--text-muted);font-size:12px;">{{ $log->id }}</td>
                    <td>
                        <div>
                            <div class="page-name">{{ $log->recipient_name ?: '—' }}</div>
                            <div style="font-size:12px;color:var(--text-muted);">{{ $log->recipient_email }}</div>
                        </div>
                    </td>
                    <td>
                        <span style="font-size:13px;color:var(--text-dark);max-width:200px;display:inline-block;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;"
                              title="{{ $log->subject }}">{{ $log->subject }}</span>
                    </td>
                    <td>
                        @if($log->emailTemplate)
                            <span style="font-size:12px;background:var(--surface-strong);padding:3px 8px;border-radius:6px;color:var(--text-dark);">
                                {{ $log->emailTemplate->name }}
                            </span>
                        @else
                            <span style="color:var(--text-muted);font-size:13px;">—</span>
                        @endif
                    </td>
                    <td>
                        @if($log->status === 'sent')
                            <span class="el-status-badge el-status-sent">✅ Sent</span>
                        @else
                            <span class="el-status-badge el-status-failed" title="{{ $log->error_message }}">❌ Failed</span>
                        @endif
                    </td>
                    <td>
                        <span class="cm-date" title="{{ $log->created_at->format('M j, Y g:i A') }}">
                            {{ $log->created_at->format('M j, Y') }}
                        </span>
                    </td>
                    <td>
                        <div class="row-actions">
                            <button class="row-btn row-btn-view" title="View email content"
                                data-url="{{ route('admin.email-logs.show', $log) }}">👁️</button>
                            <form method="POST" action="{{ route('admin.email-logs.destroy', $log) }}"
                                  onsubmit="return confirm('Delete this log entry?')" style="display:inline;">
                                @csrf @method('DELETE')
                                <button type="submit" class="row-btn row-btn-delete" title="Delete">🗑️</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="table-footer" style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:12px;">
        <span>Showing {{ $logs->firstItem() }}–{{ $logs->lastItem() }} of {{ $logs->total() }} log{{ $logs->total() !== 1 ? 's' : '' }}</span>
        @if($logs->hasPages())
            <div class="pagination-wrap">{{ $logs->links('vendor.pagination.admin') }}</div>
        @endif
    </div>
    @endif

</div>

{{-- Detail modal --}}
<div class="modal-overlay" id="detail-modal">
    <div class="modal-card" role="dialog" aria-modal="true"
         style="max-width:780px;width:95%;padding:0;overflow:hidden;text-align:left;">

        <div style="display:flex;align-items:center;justify-content:space-between;padding:20px 24px;border-bottom:1px solid var(--border);">
            <div>
                <div style="font-weight:700;font-size:16px;" id="modal-subject">—</div>
                <div style="font-size:12px;color:var(--text-muted);margin-top:4px;" id="modal-meta">—</div>
            </div>
            <div style="display:flex;align-items:center;gap:10px;">
                <span id="modal-status-badge" class="el-status-badge"></span>
                <button class="modal-btn-cancel" id="modal-close" style="margin:0;padding:8px 16px;font-size:13px;">✕ Close</button>
            </div>
        </div>

        <div id="modal-loading" style="padding:56px;text-align:center;color:var(--text-muted);">
            <div style="width:28px;height:28px;border:3px solid var(--border);border-top-color:var(--brand-bright);border-radius:50%;animation:el-spin .7s linear infinite;margin:0 auto 14px;"></div>
            Loading email…
        </div>

        <div id="modal-content" style="display:none;">
            <div id="modal-error-bar" style="display:none;padding:12px 24px;background:#fef2f2;border-bottom:1px solid #fca5a5;">
                <strong style="font-size:13px;color:#dc2626;">Error:</strong>
                <span id="modal-error-msg" style="font-size:13px;color:#dc2626;margin-left:6px;"></span>
            </div>
            <div style="padding:4px 0;">
                <iframe id="modal-iframe"
                    style="width:100%;border:none;min-height:480px;display:block;"
                    title="Email preview"></iframe>
            </div>
            <div style="padding:14px 24px;border-top:1px solid var(--border);font-size:12px;color:var(--text-muted);">
                Template: <span id="modal-template" style="font-weight:600;color:var(--text-dark);"></span>
                &nbsp;·&nbsp;
                Sent: <span id="modal-sent-at"></span>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<style>
.el-status-badge {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 4px 10px; border-radius: 8px; font-size: 12px; font-weight: 600; white-space: nowrap;
}
.el-status-sent   { background: rgba(16,185,129,.1); color: #059669; }
.el-status-failed { background: rgba(239,68,68,.1);  color: #dc2626; }

@keyframes el-spin { to { transform: rotate(360deg); } }
</style>
<script>
(function () {

/* ── Stat counters ── */
document.querySelectorAll('.stat-value[data-target]').forEach(function (el) {
    var target = parseInt(el.dataset.target, 10);
    var startTime = null;
    function step(ts) {
        if (!startTime) startTime = ts;
        var p = Math.min((ts - startTime) / 700, 1);
        el.textContent = Math.floor(p * target);
        if (p < 1) requestAnimationFrame(step);
        else el.textContent = target;
    }
    setTimeout(function () { requestAnimationFrame(step); }, 150);
});

/* ── Row entrance animation ── */
document.querySelectorAll('.page-row').forEach(function (row, i) {
    setTimeout(function () {
        row.style.transition = 'opacity .28s ease, transform .28s ease';
        row.style.opacity = '1'; row.style.transform = 'translateY(0)';
    }, 50 + i * 30);
});

/* ── Search with debounce ── */
var searchInput = document.getElementById('page-search');
var clearBtn    = document.getElementById('search-clear');
var filterForm  = document.getElementById('filter-form');
var timer;
if (searchInput) {
    searchInput.addEventListener('keydown', function (e) {
        if (e.key === 'Enter') { e.preventDefault(); filterForm.submit(); }
    });
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

/* ── Detail modal ── */
var modal        = document.getElementById('detail-modal');
var modalLoading = document.getElementById('modal-loading');
var modalContent = document.getElementById('modal-content');

function openModal(url) {
    modal.classList.add('modal-open');
    modalLoading.style.display = 'block';
    modalContent.style.display = 'none';

    fetch(url, { headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' } })
        .then(function (r) { return r.json(); })
        .then(function (d) {
            document.getElementById('modal-subject').textContent = d.subject || '—';
            document.getElementById('modal-meta').textContent =
                (d.recipient_name ? d.recipient_name + ' · ' : '') + (d.recipient_email || '');

            var badge = document.getElementById('modal-status-badge');
            badge.className = 'el-status-badge el-status-' + d.status;
            badge.textContent = d.status === 'sent' ? '✅ Sent' : '❌ Failed';

            var errorBar = document.getElementById('modal-error-bar');
            if (d.status === 'failed' && d.error_message) {
                errorBar.style.display = 'block';
                document.getElementById('modal-error-msg').textContent = d.error_message;
            } else {
                errorBar.style.display = 'none';
            }

            /* Write rendered HTML into the iframe */
            var iframe = document.getElementById('modal-iframe');
            var doc = iframe.contentDocument || iframe.contentWindow.document;
            doc.open();
            doc.write(d.rendered_body || '');
            doc.close();

            document.getElementById('modal-template').textContent = d.template_name || '—';
            document.getElementById('modal-sent-at').textContent  = d.created_at   || '—';

            modalLoading.style.display = 'none';
            modalContent.style.display = 'block';
        })
        .catch(function () {
            modalLoading.innerHTML = '<span style="color:#ef4444;">Failed to load email content.</span>';
        });
}

document.querySelectorAll('.row-btn-view').forEach(function (btn) {
    btn.addEventListener('click', function () { openModal(btn.dataset.url); });
});

document.getElementById('modal-close').addEventListener('click', function () {
    modal.classList.remove('modal-open');
});
modal.addEventListener('click', function (e) {
    if (e.target === modal) modal.classList.remove('modal-open');
});
document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') modal.classList.remove('modal-open');
});

})();
</script>
@endpush
