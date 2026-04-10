@extends('layouts.admin')

@section('title', 'Admin Management')
@section('page-title', 'Admin Management')

@section('content')

{{-- Stats --}}
<div class="pages-stats cols-3">
    <div class="stat-card" style="--accent:#6366f1;">
        <div class="stat-icon" style="background:rgba(99,102,241,0.12);color:#4f46e5;">👥</div>
        <div>
            <div class="stat-value" data-target="{{ $stats['total'] }}">0</div>
            <div class="stat-label">Total Admins</div>
        </div>
    </div>
    <div class="stat-card" style="--accent:#10b981;">
        <div class="stat-icon" style="background:rgba(16,185,129,0.12);color:#059669;">✅</div>
        <div>
            <div class="stat-value" data-target="{{ $stats['verified'] }}">0</div>
            <div class="stat-label">Email Verified</div>
        </div>
    </div>
    <div class="stat-card" style="--accent:#f59e0b;">
        <div class="stat-icon" style="background:rgba(245,158,11,0.12);color:#d97706;">🆕</div>
        <div>
            <div class="stat-value" data-target="{{ $stats['this_month'] }}">0</div>
            <div class="stat-label">Joined This Month</div>
        </div>
    </div>
</div>

{{-- Main card --}}
<div class="admin-card pages-card">

    <form method="GET" action="{{ route('admin.admins.index') }}" id="filter-form">
        <div class="pages-toolbar">
            <div class="pages-toolbar-left">
                <div class="table-search-wrap">
                    <span class="table-search-icon">🔍</span>
                    <input type="text" name="search" id="page-search" class="table-search"
                        placeholder="Search name or email…"
                        value="{{ $search }}" autocomplete="off">
                    <button type="button" class="table-search-clear" id="search-clear"
                        style="display:{{ $search ? 'flex' : 'none' }};">✕</button>
                </div>
                @if($search)
                    <a href="{{ route('admin.admins.index') }}" class="filter-clear-link">Clear</a>
                @endif
            </div>
            <div class="pages-toolbar-right">
                <button type="button" class="btn-add-page" id="btn-create-admin">
                    + New Admin
                </button>
            </div>
        </div>
    </form>

    @if($admins->isEmpty())
        <div class="empty-state" style="display:flex;">
            <div class="empty-icon">👥</div>
            <div class="empty-title">No admins found</div>
            <div class="empty-sub">
                @if($search)
                    No results for "{{ $search }}". <a href="{{ route('admin.admins.index') }}" style="color:var(--brand-dark);">Clear search</a>
                @else
                    No administrators have been added yet.
                @endif
            </div>
        </div>
    @else
    <div class="table-wrap">
        <table class="admin-table pages-table">
            <thead>
                <tr>
                    <th>Admin</th>
                    <th>Email</th>
                    <th>Verified</th>
                    <th>Joined</th>
                    <th style="width:110px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($admins as $admin)
                @php
                    $initials = collect(explode(' ', $admin->name))
                        ->map(fn($w) => strtoupper($w[0] ?? ''))
                        ->take(2)
                        ->implode('');
                    $avatarColors = ['#6366f1','#8b5cf6','#ec4899','#f97316','#14b8a6','#0ea5e9','#10b981'];
                    $color = $avatarColors[$admin->id % count($avatarColors)];
                    $isSelf = $admin->id === auth()->id();
                @endphp
                <tr class="page-row" style="opacity:0;transform:translateY(8px);"
                    data-id="{{ $admin->id }}"
                    data-name="{{ $admin->name }}"
                    data-email="{{ $admin->email }}">
                    <td>
                        <div class="cm-sender">
                            @if($admin->avatar)
                                <img src="{{ asset('storage/' . $admin->avatar) }}"
                                     alt="{{ $admin->name }}"
                                     class="cm-avatar"
                                     style="object-fit:cover;border-radius:10px;">
                            @else
                                <div class="cm-avatar" style="background:linear-gradient(135deg,{{ $color }},{{ $color }}cc);">
                                    {{ $initials }}
                                </div>
                            @endif
                            <div>
                                <span class="page-name">{{ $admin->name }}</span>
                                @if($isSelf)
                                    <span style="font-size:11px;background:rgba(99,102,241,0.1);color:#4f46e5;padding:1px 7px;border-radius:20px;margin-left:6px;font-weight:600;">You</span>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td>
                        <code class="slug-chip" style="font-family:inherit;font-size:13px;">{{ $admin->email }}</code>
                    </td>
                    <td>
                        @if($admin->email_verified_at)
                            <span style="display:inline-flex;align-items:center;gap:4px;font-size:12px;font-weight:600;padding:3px 10px;border-radius:20px;background:rgba(16,185,129,0.12);color:#059669;" title="{{ $admin->email_verified_at->format('M j, Y') }}">✓ Verified</span>
                        @else
                            <span style="display:inline-flex;align-items:center;gap:4px;font-size:12px;font-weight:600;padding:3px 10px;border-radius:20px;background:rgba(148,163,184,0.12);color:#64748b;">Unverified</span>
                        @endif
                    </td>
                    <td>
                        <span class="cm-date" title="{{ $admin->created_at->format('M j, Y g:i A') }}">
                            {{ $admin->created_at->format('M j, Y') }}
                        </span>
                    </td>
                    <td>
                        <div class="row-actions">
                            <button class="row-btn row-btn-edit" title="Edit"
                                data-id="{{ $admin->id }}"
                                data-name="{{ $admin->name }}"
                                data-email="{{ $admin->email }}">✏️</button>
                            <button class="row-btn row-btn-delete" title="Delete"
                                data-name="{{ $admin->name }} ({{ $admin->email }})"
                                data-action="{{ route('admin.admins.destroy', $admin) }}"
                                {{ $isSelf ? 'data-self=true' : '' }}>🗑️</button>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="table-footer" style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:12px;">
        <span>Showing {{ $admins->firstItem() }}–{{ $admins->lastItem() }} of {{ $admins->total() }} admin{{ $admins->total() !== 1 ? 's' : '' }}</span>
        @if($admins->hasPages())
            <div class="pagination-wrap">{{ $admins->links('vendor.pagination.admin') }}</div>
        @endif
    </div>
    @endif

</div>

{{-- Create / Edit Modal --}}
<div class="modal-overlay" id="admin-modal">
    <div class="modal-card" role="dialog" aria-modal="true" style="max-width:480px;width:100%;">
        <div class="modal-icon" id="modal-icon">👤</div>
        <h3 class="modal-title" id="modal-title">Create New Admin</h3>

        <div id="modal-form-error"
             style="display:none;background:rgba(220,38,38,.07);border:1px solid rgba(220,38,38,.25);
                    color:#dc2626;font-size:13px;padding:10px 14px;border-radius:8px;margin-bottom:12px;text-align:left;">
        </div>

        <form id="admin-form" novalidate style="text-align:left;">
            @csrf
            <input type="hidden" id="form-method" name="_method" value="POST">
            <input type="hidden" id="form-admin-id" name="admin_id" value="">

            <div class="form-group" style="margin-bottom:14px;">
                <label class="form-label" for="field-name">Full Name <span style="color:#dc2626;">*</span></label>
                <input type="text" id="field-name" name="name" class="form-control"
                    placeholder="e.g. John Doe" autocomplete="off" required>
                <div class="field-error" id="err-name" style="display:none;color:#dc2626;font-size:12px;margin-top:4px;"></div>
            </div>

            <div class="form-group" style="margin-bottom:14px;">
                <label class="form-label" for="field-email">Email Address <span style="color:#dc2626;">*</span></label>
                <input type="email" id="field-email" name="email" class="form-control"
                    placeholder="admin@example.com" autocomplete="off" required>
                <div class="field-error" id="err-email" style="display:none;color:#dc2626;font-size:12px;margin-top:4px;"></div>
            </div>

            <div class="form-group" style="margin-bottom:14px;">
                <label class="form-label" for="field-password">
                    Password <span id="pw-hint" style="font-size:11px;color:var(--text-muted);font-weight:400;">(leave blank to keep current)</span>
                    <span id="pw-required" style="color:#dc2626;display:none;">*</span>
                </label>
                <div style="position:relative;">
                    <input type="password" id="field-password" name="password" class="form-control"
                        placeholder="Min 8 chars, upper, lower, number" autocomplete="new-password">
                    <button type="button" id="toggle-pw" style="position:absolute;right:10px;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;font-size:15px;opacity:.6;" title="Show/hide">👁️</button>
                </div>
                <div class="field-error" id="err-password" style="display:none;color:#dc2626;font-size:12px;margin-top:4px;"></div>
            </div>

            <div class="form-group" style="margin-bottom:20px;">
                <label class="form-label" for="field-password-confirm">Confirm Password</label>
                <input type="password" id="field-password-confirm" name="password_confirmation" class="form-control"
                    placeholder="Repeat password" autocomplete="new-password">
                <div class="field-error" id="err-password-confirm" style="display:none;color:#dc2626;font-size:12px;margin-top:4px;"></div>
            </div>

            <div class="modal-actions" style="margin-top:4px;">
                <button type="button" class="modal-btn-cancel" id="admin-modal-cancel">Cancel</button>
                <button type="submit" class="modal-btn-confirm" id="admin-modal-submit">
                    <span id="submit-label">Create Admin</span>
                    <span class="modal-spinner" id="submit-spinner"></span>
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Delete modal --}}
<div class="modal-overlay" id="delete-modal">
    <div class="modal-card" role="dialog" aria-modal="true">
        <div class="modal-icon">🗑️</div>
        <h3 class="modal-title">Remove Admin?</h3>
        <p class="modal-body">Permanently remove <strong id="modal-item-name"></strong>?<br>This cannot be undone.</p>
        <div id="delete-error-msg"
             style="display:none;background:rgba(220,38,38,.07);border:1px solid rgba(220,38,38,.25);
                    color:#dc2626;font-size:13px;padding:10px 14px;border-radius:8px;margin-bottom:4px;">
        </div>
        <div class="modal-actions">
            <button class="modal-btn-cancel" id="delete-modal-cancel">Cancel</button>
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

/* ── Helpers ── */
function csrfToken() {
    return document.querySelector('meta[name="csrf-token"]')?.content
        || '{{ csrf_token() }}';
}

function showError(id, msg) {
    var el = document.getElementById(id);
    if (!el) return;
    el.textContent = msg;
    el.style.display = msg ? 'block' : 'none';
}

function clearErrors() {
    ['err-name','err-email','err-password','err-password-confirm'].forEach(function(id) {
        showError(id, '');
    });
    var formErr = document.getElementById('modal-form-error');
    if (formErr) { formErr.textContent = ''; formErr.style.display = 'none'; }
}

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

/* ── Row entrance ── */
document.querySelectorAll('.page-row').forEach(function (row, i) {
    setTimeout(function () {
        row.style.transition = 'opacity 0.28s ease, transform 0.28s ease';
        row.style.opacity = '1'; row.style.transform = 'translateY(0)';
    }, 50 + i * 30);
});

/* ── Search ── */
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

/* ── Create/Edit Modal ── */
var adminModal      = document.getElementById('admin-modal');
var adminForm       = document.getElementById('admin-form');
var modalTitle      = document.getElementById('modal-title');
var modalIcon       = document.getElementById('modal-icon');
var submitLabel     = document.getElementById('submit-label');
var submitBtn       = document.getElementById('admin-modal-submit');
var submitSpinner   = document.getElementById('submit-spinner');
var formMethodInput = document.getElementById('form-method');
var formAdminId     = document.getElementById('form-admin-id');
var pwHint          = document.getElementById('pw-hint');
var pwRequired      = document.getElementById('pw-required');
var isEditMode      = false;
var currentAdminUrl = '';

function openCreateModal() {
    isEditMode = false;
    clearErrors();
    modalTitle.textContent  = 'Create New Admin';
    modalIcon.textContent   = '👤';
    submitLabel.textContent = 'Create Admin';
    formMethodInput.value   = 'POST';
    formAdminId.value       = '';
    currentAdminUrl         = '{{ route("admin.admins.store") }}';
    document.getElementById('field-name').value            = '';
    document.getElementById('field-email').value           = '';
    document.getElementById('field-password').value        = '';
    document.getElementById('field-password-confirm').value = '';
    pwHint.style.display    = 'none';
    pwRequired.style.display = 'inline';
    adminModal.classList.add('modal-open');
    document.getElementById('field-name').focus();
}

function openEditModal(id, name, email) {
    isEditMode = true;
    clearErrors();
    modalTitle.textContent  = 'Edit Admin';
    modalIcon.textContent   = '✏️';
    submitLabel.textContent = 'Save Changes';
    formMethodInput.value   = 'PUT';
    formAdminId.value       = id;
    currentAdminUrl         = '{{ url("admin/admins") }}/' + id;
    document.getElementById('field-name').value            = name;
    document.getElementById('field-email').value           = email;
    document.getElementById('field-password').value        = '';
    document.getElementById('field-password-confirm').value = '';
    pwHint.style.display    = 'inline';
    pwRequired.style.display = 'none';
    adminModal.classList.add('modal-open');
    document.getElementById('field-name').focus();
}

document.getElementById('btn-create-admin').addEventListener('click', openCreateModal);

document.querySelectorAll('.row-btn-edit').forEach(function (btn) {
    btn.addEventListener('click', function () {
        openEditModal(btn.dataset.id, btn.dataset.name, btn.dataset.email);
    });
});

function closeAdminModal() {
    adminModal.classList.remove('modal-open');
}

document.getElementById('admin-modal-cancel').addEventListener('click', closeAdminModal);
adminModal.addEventListener('click', function (e) {
    if (e.target === adminModal) closeAdminModal();
});

/* Password toggle */
document.getElementById('toggle-pw').addEventListener('click', function () {
    var input = document.getElementById('field-password');
    input.type = input.type === 'password' ? 'text' : 'password';
});

/* Form submit */
adminForm.addEventListener('submit', function (e) {
    e.preventDefault();
    clearErrors();

    var name    = document.getElementById('field-name').value.trim();
    var email   = document.getElementById('field-email').value.trim();
    var pw      = document.getElementById('field-password').value;
    var pwc     = document.getElementById('field-password-confirm').value;

    var hasErr = false;
    if (!name)  { showError('err-name',  'Name is required.'); hasErr = true; }
    if (!email) { showError('err-email', 'Email is required.'); hasErr = true; }
    if (!isEditMode && !pw) { showError('err-password', 'Password is required.'); hasErr = true; }
    if (pw && pw !== pwc)   { showError('err-password-confirm', 'Passwords do not match.'); hasErr = true; }
    if (hasErr) return;

    submitBtn.disabled = true;
    submitSpinner.style.display = 'block';

    var method  = isEditMode ? 'PUT' : 'POST';
    var payload = { name: name, email: email, _token: csrfToken() };
    if (pw) { payload.password = pw; payload.password_confirmation = pwc; }

    fetch(currentAdminUrl, {
        method: method,
        headers: {
            'Content-Type': 'application/json',
            'Accept':       'application/json',
            'X-CSRF-TOKEN': csrfToken(),
        },
        body: JSON.stringify(payload),
    })
    .then(function (r) { return r.json().then(function (d) { return { ok: r.ok, data: d }; }); })
    .then(function (res) {
        if (res.ok && res.data.success) {
            closeAdminModal();
            if (window.showAdminToast) window.showAdminToast(res.data.message, 'success');
            setTimeout(function () { window.location.reload(); }, 800);
        } else {
            /* Validation errors from Laravel */
            var errors = res.data.errors || {};
            if (errors.name)                  showError('err-name',            errors.name[0]);
            if (errors.email)                 showError('err-email',           errors.email[0]);
            if (errors.password)              showError('err-password',        errors.password[0]);
            if (errors.password_confirmation) showError('err-password-confirm',errors.password_confirmation[0]);

            var formErr = document.getElementById('modal-form-error');
            if (!errors.name && !errors.email && !errors.password) {
                formErr.textContent = res.data.message || 'An error occurred. Please try again.';
                formErr.style.display = 'block';
            }
        }
    })
    .catch(function () {
        var formErr = document.getElementById('modal-form-error');
        formErr.textContent = 'Network error. Please try again.';
        formErr.style.display = 'block';
    })
    .finally(function () {
        submitBtn.disabled = false;
        submitSpinner.style.display = 'none';
    });
});

/* ── Delete modal ── */
var deleteModal    = document.getElementById('delete-modal');
var deleteItemName = document.getElementById('modal-item-name');
var deleteForm     = document.getElementById('delete-form');
var deleteConfirm  = document.getElementById('modal-confirm');
var deleteErrMsg   = document.getElementById('delete-error-msg');

document.querySelectorAll('.row-btn-delete').forEach(function (btn) {
    btn.addEventListener('click', function () {
        if (btn.dataset.self) {
            if (window.showAdminToast) window.showAdminToast('You cannot delete your own account.', 'error');
            return;
        }
        deleteItemName.textContent = btn.dataset.name;
        deleteForm.action          = btn.dataset.action;
        deleteErrMsg.style.display = 'none';
        deleteErrMsg.textContent   = '';
        deleteModal.classList.add('modal-open');
        document.getElementById('delete-modal-cancel').focus();
    });
});

document.getElementById('delete-modal-cancel').addEventListener('click', function () {
    deleteModal.classList.remove('modal-open');
});
deleteModal.addEventListener('click', function (e) {
    if (e.target === deleteModal) deleteModal.classList.remove('modal-open');
});
document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') {
        deleteModal.classList.remove('modal-open');
        adminModal.classList.remove('modal-open');
    }
});
deleteForm.addEventListener('submit', function () {
    deleteConfirm.disabled = true;
    deleteConfirm.querySelector('.modal-spinner').style.display = 'block';
});

})();
</script>
@endpush
