@extends('layouts.admin')

@section('title', 'My Profile')
@section('page-title', 'Profile')

@section('content')

{{-- Profile Header Card --}}
<div class="profile-header-card">
    <div class="profile-hero">
        <div class="profile-avatar-wrap" id="avatar-wrap">
            @if($user->avatar)
                <img src="{{ asset('storage/' . $user->avatar) }}"
                     alt="{{ $user->name }}"
                     class="profile-avatar-img"
                     id="avatar-preview">
            @else
                <div class="profile-avatar-initials" id="avatar-initials">
                    {{ collect(explode(' ', $user->name))->map(fn($w) => strtoupper($w[0] ?? ''))->take(2)->implode('') }}
                </div>
                <img src="" alt="" class="profile-avatar-img" id="avatar-preview" style="display:none;">
            @endif
            <label class="profile-avatar-overlay" for="avatar-input" title="Change photo">
                <span class="profile-avatar-camera">📷</span>
            </label>
        </div>
        <div class="profile-hero-info">
            <div class="profile-hero-name">{{ $user->name }}</div>
            <div class="profile-hero-email">{{ $user->email }}</div>
            <div class="profile-hero-role">
                <span class="profile-role-badge">Super Admin</span>
                <span class="profile-joined">Member since {{ $user->created_at->format('M Y') }}</span>
            </div>
        </div>
    </div>
</div>

<div class="profile-layout">

    {{-- ── Left column ── --}}
    <div class="profile-col-main">

        {{-- Profile Information --}}
        <div class="admin-card profile-section-card" id="card-info">
            <div class="profile-section-head">
                <div class="profile-section-icon">👤</div>
                <div>
                    <div class="profile-section-title">Profile Information</div>
                    <div class="profile-section-sub">Update your name, email and profile photo</div>
                </div>
            </div>

            <form method="POST" action="{{ route('profile.update') }}"
                  enctype="multipart/form-data"
                  id="profile-info-form"
                  class="profile-form"
                  novalidate>
                @csrf
                @method('PATCH')

                {{-- Hidden avatar input --}}
                <input type="file" id="avatar-input" name="avatar"
                       accept="image/jpeg,image/png,image/gif,image/webp"
                       style="display:none;">

                {{-- Avatar preview row --}}
                <div class="profile-avatar-row" id="avatar-change-row" style="display:none;">
                    <div class="pf-label">New Photo</div>
                    <div class="avatar-change-preview" id="avatar-change-preview"></div>
                    <button type="button" class="avatar-cancel-btn" id="avatar-cancel-btn">✕ Cancel</button>
                </div>

                <div class="profile-form-grid">
                    <div class="pf-group">
                        <label class="pf-label" for="name">Full Name</label>
                        <input type="text" id="name" name="name"
                               class="pf-input {{ $errors->get('name') ? 'pf-input-error' : '' }}"
                               value="{{ old('name', $user->name) }}"
                               required autocomplete="name"
                               placeholder="Your full name">
                        @error('name')
                            <div class="pf-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="pf-group">
                        <label class="pf-label" for="email">Email Address</label>
                        <input type="email" id="email" name="email"
                               class="pf-input {{ $errors->get('email') ? 'pf-input-error' : '' }}"
                               value="{{ old('email', $user->email) }}"
                               required autocomplete="email"
                               placeholder="your@email.com">
                        @error('email')
                            <div class="pf-error">{{ $message }}</div>
                        @enderror
                        @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                            <div class="pf-hint" style="color:#f59e0b;">
                                ⚠ Email unverified.
                                <form id="send-verification" method="post" action="{{ route('verification.send') }}" style="display:inline;">@csrf</form>
                                <button form="send-verification" class="pf-link-btn">Re-send verification</button>
                            </div>
                        @endif
                    </div>
                </div>

                @if($user->avatar)
                <div class="pf-group">
                    <label class="pf-label">Current Photo</label>
                    <div class="current-avatar-row">
                        <img src="{{ asset('storage/' . $user->avatar) }}" class="current-avatar-thumb" alt="Current avatar">
                        <form method="POST" action="{{ route('profile.avatar.remove') }}" style="display:inline;" id="remove-avatar-form">
                            @csrf @method('DELETE')
                            <button type="button" class="avatar-remove-btn" id="remove-avatar-btn">Remove photo</button>
                        </form>
                    </div>
                </div>
                @endif

                <div class="profile-form-actions">
                    <button type="submit" class="pf-submit-btn" id="info-submit-btn">
                        <span class="btn-label">Save Changes</span>
                        <span class="btn-spinner" style="display:none;"></span>
                    </button>
                    @if (session('status') === 'profile-updated')
                        <div class="pf-success-inline" id="profile-saved-msg">✓ Profile updated</div>
                    @endif
                    @if (session('status') === 'avatar-removed')
                        <div class="pf-success-inline" id="profile-saved-msg">✓ Photo removed</div>
                    @endif
                </div>
            </form>
        </div>

        {{-- Change Password --}}
        <div class="admin-card profile-section-card" id="card-password">
            <div class="profile-section-head">
                <div class="profile-section-icon">🔒</div>
                <div>
                    <div class="profile-section-title">Change Password</div>
                    <div class="profile-section-sub">Use a strong, unique password to keep your account secure</div>
                </div>
            </div>

            <form method="POST" action="{{ route('password.update') }}"
                  id="password-form"
                  class="profile-form"
                  novalidate>
                @csrf
                @method('PUT')

                <div class="pf-group">
                    <label class="pf-label" for="current_password">Current Password</label>
                    <div class="pf-password-wrap">
                        <input type="password" id="current_password" name="current_password"
                               class="pf-input {{ $errors->updatePassword?->get('current_password') ? 'pf-input-error' : '' }}"
                               autocomplete="current-password"
                               placeholder="Enter current password">
                        <button type="button" class="pf-eye-btn" data-target="current_password">👁</button>
                    </div>
                    @if($errors->updatePassword?->get('current_password'))
                        <div class="pf-error">{{ $errors->updatePassword->first('current_password') }}</div>
                    @endif
                </div>

                <div class="pf-group">
                    <label class="pf-label" for="password">New Password</label>
                    <div class="pf-password-wrap">
                        <input type="password" id="password" name="password"
                               class="pf-input {{ $errors->updatePassword?->get('password') ? 'pf-input-error' : '' }}"
                               autocomplete="new-password"
                               placeholder="At least 8 characters">
                        <button type="button" class="pf-eye-btn" data-target="password">👁</button>
                    </div>
                    @if($errors->updatePassword?->get('password'))
                        <div class="pf-error">{{ $errors->updatePassword->first('password') }}</div>
                    @endif
                    <div class="password-strength-bar" id="pw-strength-bar">
                        <div class="pw-bar-fill" id="pw-bar-fill"></div>
                    </div>
                    <div class="pw-strength-label" id="pw-strength-label"></div>
                </div>

                <div class="pf-group">
                    <label class="pf-label" for="password_confirmation">Confirm New Password</label>
                    <div class="pf-password-wrap">
                        <input type="password" id="password_confirmation" name="password_confirmation"
                               class="pf-input"
                               autocomplete="new-password"
                               placeholder="Repeat new password">
                        <button type="button" class="pf-eye-btn" data-target="password_confirmation">👁</button>
                    </div>
                    <div class="pf-error" id="pw-match-error" style="display:none;">Passwords do not match</div>
                    @if($errors->updatePassword?->get('password_confirmation'))
                        <div class="pf-error">{{ $errors->updatePassword->first('password_confirmation') }}</div>
                    @endif
                </div>

                <div class="profile-form-actions">
                    <button type="submit" class="pf-submit-btn" id="pw-submit-btn">
                        <span class="btn-label">Update Password</span>
                        <span class="btn-spinner" style="display:none;"></span>
                    </button>
                    @if (session('status') === 'password-updated')
                        <div class="pf-success-inline">✓ Password updated</div>
                    @endif
                </div>
            </form>
        </div>

    </div>

    {{-- ── Right column ── --}}
    <div class="profile-col-side">

        {{-- Account Info card --}}
        <div class="admin-card profile-section-card profile-meta-card">
            <div class="profile-meta-title">Account Details</div>
            <div class="profile-meta-row">
                <span class="profile-meta-label">User ID</span>
                <span class="profile-meta-value">#{{ $user->id }}</span>
            </div>
            <div class="profile-meta-row">
                <span class="profile-meta-label">Role</span>
                <span class="profile-meta-value">Super Admin</span>
            </div>
            <div class="profile-meta-row">
                <span class="profile-meta-label">Joined</span>
                <span class="profile-meta-value">{{ $user->created_at->format('M j, Y') }}</span>
            </div>
            <div class="profile-meta-row">
                <span class="profile-meta-label">Email Status</span>
                <span class="profile-meta-value">
                    @if($user->email_verified_at)
                        <span style="color:#10b981;">✓ Verified</span>
                    @else
                        <span style="color:#f59e0b;">⚠ Unverified</span>
                    @endif
                </span>
            </div>
            <div class="profile-meta-row" style="border-bottom:none;">
                <span class="profile-meta-label">Last Update</span>
                <span class="profile-meta-value">{{ $user->updated_at->diffForHumans() }}</span>
            </div>
        </div>

        {{-- Quick links --}}
        <div class="admin-card profile-section-card profile-meta-card">
            <div class="profile-meta-title">Quick Links</div>
            <a href="{{ route('admin.pages.index') }}" class="profile-quick-link">📄 Manage Pages</a>
            <a href="{{ route('admin.subscribers.index') }}" class="profile-quick-link">📧 Subscribers</a>
            <a href="{{ route('admin.contact-messages.index') }}" class="profile-quick-link">✉️ Messages</a>
        </div>

        {{-- Danger Zone --}}
        <div class="admin-card profile-section-card danger-zone" id="card-danger">
            <div class="profile-section-head" style="margin-bottom:12px;">
                <div class="profile-section-icon" style="background:rgba(239,68,68,0.1);color:#ef4444;">⚠️</div>
                <div>
                    <div class="profile-section-title" style="color:#ef4444;">Danger Zone</div>
                    <div class="profile-section-sub">Irreversible account actions</div>
                </div>
            </div>
            <p style="font-size:13px;color:var(--text-muted);margin-bottom:16px;line-height:1.5;">
                Permanently deletes your account and all associated data. This action cannot be undone.
            </p>
            <button class="danger-delete-btn" id="open-delete-modal" style="width:100%;">
                Delete My Account
            </button>
        </div>

    </div>
</div>

{{-- Delete Account Modal --}}
<div class="modal-overlay" id="delete-modal">
    <div class="modal-card" role="dialog" aria-modal="true">
        <div class="modal-icon">⚠️</div>
        <h3 class="modal-title">Delete Account?</h3>
        <p class="modal-body">This will permanently remove your account and all data. Enter your password to confirm.</p>
        <form method="POST" action="{{ route('profile.destroy') }}" id="delete-account-form">
            @csrf @method('DELETE')
            <div class="pf-group" style="margin:16px 0 4px;">
                <div class="pf-password-wrap">
                    <input type="password" name="password" id="delete-password"
                           class="pf-input {{ $errors->userDeletion?->get('password') ? 'pf-input-error' : '' }}"
                           placeholder="Your current password" autocomplete="current-password">
                    <button type="button" class="pf-eye-btn" data-target="delete-password">👁</button>
                </div>
                @if($errors->userDeletion?->get('password'))
                    <div class="pf-error">{{ $errors->userDeletion->first('password') }}</div>
                @endif
            </div>
            <div class="modal-actions">
                <button type="button" class="modal-btn-cancel" id="modal-cancel">Cancel</button>
                <button type="submit" class="modal-btn-confirm">
                    <span>Delete Account</span><span class="modal-spinner"></span>
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
(function () {

/* ── Card entrance animations ─────────────────────────────── */
document.querySelectorAll('.profile-section-card, .profile-header-card').forEach(function (card, i) {
    card.style.opacity = '0';
    card.style.transform = 'translateY(12px)';
    setTimeout(function () {
        card.style.transition = 'opacity .35s ease, transform .35s ease';
        card.style.opacity = '1';
        card.style.transform = 'translateY(0)';
    }, 60 + i * 60);
});

/* ── Avatar upload preview ─────────────────────────────────── */
var avatarInput    = document.getElementById('avatar-input');
var avatarPreview  = document.getElementById('avatar-preview');
var avatarInitials = document.getElementById('avatar-initials');
var changeRow      = document.getElementById('avatar-change-row');
var changePreview  = document.getElementById('avatar-change-preview');
var cancelBtn      = document.getElementById('avatar-cancel-btn');

if (avatarInput) {
    avatarInput.addEventListener('change', function () {
        var file = this.files[0];
        if (!file) return;

        var reader = new FileReader();
        reader.onload = function (e) {
            /* Update big avatar */
            if (avatarInitials) avatarInitials.style.display = 'none';
            avatarPreview.src = e.target.result;
            avatarPreview.style.display = 'block';

            /* Show preview row */
            changePreview.innerHTML = '<img src="' + e.target.result + '" class="avatar-mini-preview"><span class="avatar-filename">' + file.name + '</span>';
            changeRow.style.display = 'flex';
        };
        reader.readAsDataURL(file);
    });
}

if (cancelBtn) {
    cancelBtn.addEventListener('click', function () {
        avatarInput.value = '';
        changeRow.style.display = 'none';
        /* Restore original state */
        @if($user->avatar)
            avatarPreview.src = '{{ asset("storage/" . $user->avatar) }}';
            avatarPreview.style.display = 'block';
        @else
            avatarPreview.style.display = 'none';
            if (avatarInitials) avatarInitials.style.display = 'flex';
        @endif
    });
}

/* ── Remove avatar confirmation ───────────────────────────── */
var removeBtn  = document.getElementById('remove-avatar-btn');
var removeForm = document.getElementById('remove-avatar-form');
if (removeBtn && removeForm) {
    removeBtn.addEventListener('click', function () {
        if (confirm('Remove your profile photo?')) removeForm.submit();
    });
}

/* ── Password toggle (👁 buttons) ─────────────────────────── */
document.querySelectorAll('.pf-eye-btn').forEach(function (btn) {
    btn.addEventListener('click', function () {
        var inp = document.getElementById(btn.dataset.target);
        if (!inp) return;
        inp.type = inp.type === 'password' ? 'text' : 'password';
        btn.textContent = inp.type === 'password' ? '👁' : '🙈';
    });
});

/* ── Password strength meter ──────────────────────────────── */
var pwInput     = document.getElementById('password');
var barFill     = document.getElementById('pw-bar-fill');
var strengthLbl = document.getElementById('pw-strength-label');
var strengthBar = document.getElementById('pw-strength-bar');

function scorePassword(pw) {
    var score = 0;
    if (!pw) return 0;
    if (pw.length >= 8)  score++;
    if (pw.length >= 12) score++;
    if (/[A-Z]/.test(pw)) score++;
    if (/[0-9]/.test(pw)) score++;
    if (/[^A-Za-z0-9]/.test(pw)) score++;
    return score;
}

if (pwInput && barFill) {
    pwInput.addEventListener('input', function () {
        var score = scorePassword(this.value);
        var labels = ['', 'Very Weak', 'Weak', 'Fair', 'Strong', 'Very Strong'];
        var colors = ['', '#ef4444', '#f97316', '#eab308', '#22c55e', '#10b981'];
        var widths = ['0%', '20%', '40%', '60%', '80%', '100%'];

        if (this.value) {
            strengthBar.style.display = 'block';
            barFill.style.width     = widths[score] || '0%';
            barFill.style.background = colors[score] || '#ef4444';
            strengthLbl.textContent = labels[score] || '';
            strengthLbl.style.color = colors[score] || '#ef4444';
        } else {
            strengthBar.style.display = 'none';
            strengthLbl.textContent = '';
        }
    });
}

/* ── Password match check ─────────────────────────────────── */
var pwConfirm  = document.getElementById('password_confirmation');
var matchError = document.getElementById('pw-match-error');

if (pwConfirm && matchError) {
    pwConfirm.addEventListener('input', function () {
        if (pwInput && this.value && this.value !== pwInput.value) {
            matchError.style.display = 'block';
            this.classList.add('pf-input-error');
        } else {
            matchError.style.display = 'none';
            this.classList.remove('pf-input-error');
        }
    });
}

/* ── Loading state on form submit ─────────────────────────── */
document.querySelectorAll('.profile-form').forEach(function (form) {
    form.addEventListener('submit', function () {
        var btn    = form.querySelector('.pf-submit-btn');
        var label  = btn && btn.querySelector('.btn-label');
        var spinner = btn && btn.querySelector('.btn-spinner');
        if (btn) btn.disabled = true;
        if (label) label.style.opacity = '0.5';
        if (spinner) spinner.style.display = 'inline-block';
    });
});

/* ── Auto-hide success messages ──────────────────────────── */
document.querySelectorAll('.pf-success-inline').forEach(function (el) {
    setTimeout(function () {
        el.style.transition = 'opacity .5s';
        el.style.opacity = '0';
    }, 3500);
});

/* ── Delete modal ─────────────────────────────────────────── */
var deleteModal = document.getElementById('delete-modal');
var openBtn     = document.getElementById('open-delete-modal');
var cancelBtn2  = document.getElementById('modal-cancel');

if (openBtn) {
    openBtn.addEventListener('click', function () {
        deleteModal.classList.add('modal-open');
        var pw = document.getElementById('delete-password');
        if (pw) setTimeout(function () { pw.focus(); }, 100);
    });
}
if (cancelBtn2) {
    cancelBtn2.addEventListener('click', function () { deleteModal.classList.remove('modal-open'); });
}
deleteModal && deleteModal.addEventListener('click', function (e) {
    if (e.target === deleteModal) deleteModal.classList.remove('modal-open');
});
document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape' && deleteModal) deleteModal.classList.remove('modal-open');
});

/* ── Open delete modal if validation errors exist ─────────── */
@if($errors->userDeletion->isNotEmpty())
    deleteModal && deleteModal.classList.add('modal-open');
@endif

})();
</script>
@endpush
