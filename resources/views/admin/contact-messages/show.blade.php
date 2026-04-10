@extends('layouts.admin')

@section('title', 'View Message')
@section('page-title', 'View Message')

@section('content')
<div class="page-form-shell">

    <a href="{{ route('admin.contact-messages.index') }}" class="form-back-link">← Back to Messages</a>

    <div class="page-form-grid">

        {{-- ── Message body ── --}}
        <div class="form-section">
            <div class="form-section-header">
                <div class="cm-avatar-lg">{{ strtoupper(substr($contactMessage->first_name, 0, 1)) }}</div>
                <div>
                    <div class="form-section-title">{{ $contactMessage->full_name }}</div>
                    <div class="form-section-sub">
                        <a href="mailto:{{ $contactMessage->email }}" style="color:var(--brand-dark);">
                            {{ $contactMessage->email }}
                        </a>
                    </div>
                </div>
                <span class="type-badge {{ $contactMessage->is_read ? 'type-link' : 'type-image' }}" style="margin-left:auto;">
                    {{ $contactMessage->is_read ? 'Read' : 'Unread' }}
                </span>
            </div>

            {{-- Subject --}}
            @if($contactMessage->subject)
            <div class="cm-detail-row">
                <span class="cm-detail-label">Subject</span>
                <span class="type-badge type-{{ $contactMessage->subject }}">{{ $contactMessage->subject_label }}</span>
            </div>
            @endif

            {{-- Message --}}
            <div class="cm-message-body">
                <div class="cm-message-label">Message</div>
                <div class="cm-message-text">{{ $contactMessage->message }}</div>
            </div>

            {{-- Reply button + replied status --}}
            <div style="margin-top:20px;display:flex;align-items:center;gap:14px;flex-wrap:wrap;">
                <button type="button" id="open-reply-btn" class="admin-btn pages-new-btn" style="display:inline-flex;align-items:center;gap:6px;">
                    ✉️ &nbsp;Reply by Email
                </button>
                <span id="replied-badge" style="display:{{ $contactMessage->replied_at ? 'inline-flex' : 'none' }};align-items:center;gap:5px;font-size:13px;color:#059669;font-weight:600;">
                    ✅ Replied
                    <span id="replied-time" style="font-weight:400;color:var(--text-muted);">
                        {{ $contactMessage->replied_at?->diffForHumans() }}
                    </span>
                </span>
            </div>

            {{-- Previous reply (if exists) --}}
            @if($contactMessage->replied_at)
            <div id="prev-reply-card" style="margin-top:20px;background:rgba(15,118,110,.05);border:1px solid rgba(15,118,110,.18);border-radius:14px;padding:18px 20px;">
                <div style="display:flex;align-items:center;gap:8px;margin-bottom:10px;">
                    <span style="font-size:13px;font-weight:700;color:#0f766e;text-transform:uppercase;letter-spacing:.04em;">Previous Reply</span>
                    <span style="font-size:12px;color:var(--text-muted);">by {{ $contactMessage->replied_by }} · {{ $contactMessage->replied_at->format('M j, Y g:i A') }}</span>
                </div>
                <div style="font-size:14px;color:var(--text-body);line-height:1.75;white-space:pre-wrap;">{{ $contactMessage->reply_message }}</div>
            </div>
            @else
            <div id="prev-reply-card" style="display:none;margin-top:20px;background:rgba(15,118,110,.05);border:1px solid rgba(15,118,110,.18);border-radius:14px;padding:18px 20px;">
                <div style="display:flex;align-items:center;gap:8px;margin-bottom:10px;">
                    <span style="font-size:13px;font-weight:700;color:#0f766e;text-transform:uppercase;letter-spacing:.04em;">Reply Sent</span>
                    <span id="prev-reply-meta" style="font-size:12px;color:var(--text-muted);"></span>
                </div>
                <div id="prev-reply-text" style="font-size:14px;color:var(--text-body);line-height:1.75;white-space:pre-wrap;"></div>
            </div>
            @endif
        </div>

        {{-- ── Sidebar ── --}}
        <div class="form-sidebar">

            {{-- Message metadata --}}
            <div class="form-meta-card">
                <div class="form-meta-row">
                    <span class="form-meta-label">Received</span>
                    <span class="form-meta-value">{{ $contactMessage->created_at->format('M j, Y') }}</span>
                </div>
                <div class="form-meta-row">
                    <span class="form-meta-label">Time</span>
                    <span class="form-meta-value">{{ $contactMessage->created_at->format('g:i A') }}</span>
                </div>
                <div class="form-meta-row">
                    <span class="form-meta-label">Age</span>
                    <span class="form-meta-value">{{ $contactMessage->created_at->diffForHumans() }}</span>
                </div>
                @if($contactMessage->ip_address)
                <div class="form-meta-row">
                    <span class="form-meta-label">IP Address</span>
                    <span class="form-meta-value" style="font-family:monospace;font-size:12px;">{{ $contactMessage->ip_address }}</span>
                </div>
                @endif
            </div>

            {{-- Reply history card --}}
            <div id="reply-history-card" style="{{ $contactMessage->replied_at ? '' : 'display:none;' }}margin-top:0;">
                <div style="background:rgba(5,150,105,.06);border:1px solid rgba(5,150,105,.2);border-radius:14px;padding:16px;">
                    <div style="font-size:12px;font-weight:700;color:#059669;text-transform:uppercase;letter-spacing:.05em;margin-bottom:10px;">Reply Log</div>
                    <div class="form-meta-row" style="border:none;padding:0 0 6px;">
                        <span class="form-meta-label">Sent</span>
                        <span class="form-meta-value" id="sidebar-replied-at" style="color:#059669;font-weight:600;">
                            {{ $contactMessage->replied_at?->format('M j, Y') }}
                        </span>
                    </div>
                    <div class="form-meta-row" style="border:none;padding:0;">
                        <span class="form-meta-label">By</span>
                        <span class="form-meta-value" id="sidebar-replied-by">{{ $contactMessage->replied_by }}</span>
                    </div>
                </div>
            </div>

            {{-- Actions --}}
            <div class="form-actions-card">
                <form method="POST" action="{{ route('admin.contact-messages.toggle-read', $contactMessage) }}">
                    @csrf @method('PATCH')
                    <button type="submit" class="admin-btn-secondary pf-cancel-btn" style="width:100%;border:1.5px solid var(--border);">
                        {{ $contactMessage->is_read ? '🔵 Mark as Unread' : '✅ Mark as Read' }}
                    </button>
                </form>
                <a href="{{ route('admin.contact-messages.index') }}" class="admin-btn-secondary pf-cancel-btn">
                    ← Back to Inbox
                </a>
            </div>

            <div class="danger-zone">
                <div class="danger-zone-title">Danger Zone</div>
                <p class="danger-zone-sub">Permanently deletes this message.</p>
                <button type="button" class="danger-delete-btn" id="danger-btn">Delete message</button>
            </div>

        </div>
    </div>
</div>

{{-- ── Reply Modal ── --}}
<div class="modal-overlay" id="reply-modal">
    <div class="modal-card" style="max-width:520px;width:100%;">
        <div class="modal-icon">✉️</div>
        <h3 class="modal-title">Reply to {{ $contactMessage->first_name }}</h3>
        <p class="modal-body" style="margin-bottom:20px;">
            Sending to: <strong>{{ $contactMessage->email }}</strong>
        </p>

        <div style="text-align:left;">
            <div class="pf-group" style="margin-bottom:14px;">
                <label class="pf-label" style="text-align:left;margin-bottom:6px;">
                    Your Reply <span class="pf-required">*</span>
                </label>
                <textarea
                    id="reply-message-input"
                    class="pf-input pf-textarea"
                    rows="6"
                    placeholder="Write your reply here…"
                    style="resize:vertical;min-height:120px;"></textarea>
                <div id="reply-char-count" style="font-size:11px;color:var(--text-muted);text-align:right;margin-top:4px;">0 / 5000</div>
                <div id="reply-error" style="display:none;color:#dc2626;font-size:13px;margin-top:6px;padding:8px 10px;background:rgba(239,68,68,.07);border-radius:8px;border:1px solid rgba(239,68,68,.2);"></div>
            </div>

            <label style="display:flex;align-items:center;gap:8px;font-size:13px;color:var(--text-muted);cursor:pointer;margin-bottom:4px;user-select:none;">
                <input type="checkbox" id="include-original" checked
                    style="width:15px;height:15px;accent-color:#0f766e;cursor:pointer;">
                Include original message in the email
            </label>
        </div>

        <div class="modal-actions" style="margin-top:20px;">
            <button class="modal-btn-cancel" id="reply-cancel">Cancel</button>
            <button type="button" id="reply-submit" class="modal-btn-confirm" style="background:#0f766e;min-width:120px;display:inline-flex;align-items:center;justify-content:center;gap:8px;">
                <span id="reply-btn-text">Send Reply</span>
                <span class="modal-spinner" id="reply-spinner" style="display:none;"></span>
            </button>
        </div>
    </div>
</div>

{{-- ── Delete Modal ── --}}
<div class="modal-overlay" id="delete-modal">
    <div class="modal-card">
        <div class="modal-icon">🗑️</div>
        <h3 class="modal-title">Delete Message?</h3>
        <p class="modal-body">Permanently delete this message from <strong>{{ $contactMessage->full_name }}</strong>?</p>
        <div class="modal-actions">
            <button class="modal-btn-cancel" id="modal-cancel">Cancel</button>
            <form method="POST" action="{{ route('admin.contact-messages.destroy', $contactMessage) }}">
                @csrf @method('DELETE')
                <button type="submit" class="modal-btn-confirm">Delete</button>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
(function () {

// ── Delete modal ──
var deleteModal = document.getElementById('delete-modal');
document.getElementById('danger-btn').addEventListener('click', function () { deleteModal.classList.add('modal-open'); });
document.getElementById('modal-cancel').addEventListener('click', function () { deleteModal.classList.remove('modal-open'); });
deleteModal.addEventListener('click', function (e) { if (e.target === deleteModal) deleteModal.classList.remove('modal-open'); });

// ── Reply modal ──
var replyModal    = document.getElementById('reply-modal');
var replyInput    = document.getElementById('reply-message-input');
var replyError    = document.getElementById('reply-error');
var charCount     = document.getElementById('reply-char-count');
var replySubmit   = document.getElementById('reply-submit');
var replyBtnText  = document.getElementById('reply-btn-text');
var replySpinner  = document.getElementById('reply-spinner');

document.getElementById('open-reply-btn').addEventListener('click', function () {
    replyModal.classList.add('modal-open');
    setTimeout(function () { replyInput.focus(); }, 120);
});

document.getElementById('reply-cancel').addEventListener('click', function () {
    replyModal.classList.remove('modal-open');
});

replyModal.addEventListener('click', function (e) {
    if (e.target === replyModal) replyModal.classList.remove('modal-open');
});

// Escape closes whichever modal is open
document.addEventListener('keydown', function (e) {
    if (e.key !== 'Escape') return;
    replyModal.classList.remove('modal-open');
    deleteModal.classList.remove('modal-open');
});

// Character counter
replyInput.addEventListener('input', function () {
    var len = replyInput.value.length;
    charCount.textContent = len + ' / 5000';
    charCount.style.color = len > 4800 ? '#dc2626' : 'var(--text-muted)';
    if (len >= 10) { replyError.style.display = 'none'; }
});

// Submit
replySubmit.addEventListener('click', function () {
    var message = replyInput.value.trim();
    replyError.style.display = 'none';

    if (message.length < 10) {
        replyError.textContent = 'Reply must be at least 10 characters.';
        replyError.style.display = 'block';
        replyInput.focus();
        return;
    }

    replySubmit.disabled = true;
    replyBtnText.textContent = 'Sending…';
    replySpinner.style.display = 'inline-block';

    var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    fetch('{{ route("admin.contact-messages.reply", $contactMessage) }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN':  csrfToken,
            'Accept':        'application/json',
        },
        body: JSON.stringify({
            reply_message:    message,
            include_original: document.getElementById('include-original').checked,
        }),
    })
    .then(function (r) { return r.json(); })
    .then(function (data) {
        replySubmit.disabled = false;
        replyBtnText.textContent = 'Send Reply';
        replySpinner.style.display = 'none';

        if (data.success) {
            replyModal.classList.remove('modal-open');
            replyInput.value = '';
            charCount.textContent = '0 / 5000';

            // Update replied badge
            var badge = document.getElementById('replied-badge');
            badge.style.display = 'inline-flex';
            document.getElementById('replied-time').textContent = 'just now';

            // Show inline reply card (for messages that had no prior reply)
            var card = document.getElementById('prev-reply-card');
            if (card) {
                var metaEl = document.getElementById('prev-reply-meta');
                var textEl = document.getElementById('prev-reply-text');
                if (metaEl) metaEl.textContent = 'by ' + data.replied_by + ' · ' + data.replied_at;
                if (textEl) textEl.textContent = message;
                card.style.display = 'block';
            }

            // Update sidebar reply log
            var historyCard = document.getElementById('reply-history-card');
            if (historyCard) {
                historyCard.style.display = 'block';
                var atEl = document.getElementById('sidebar-replied-at');
                var byEl = document.getElementById('sidebar-replied-by');
                if (atEl) atEl.textContent = data.replied_at;
                if (byEl) byEl.textContent = data.replied_by;
            }

            if (window.showAdminToast) {
                window.showAdminToast(data.message, 'success');
            }
        } else {
            replyError.textContent = data.message;
            replyError.style.display = 'block';
            if (window.showAdminToast) {
                window.showAdminToast(data.message, 'error');
            }
        }
    })
    .catch(function () {
        replySubmit.disabled = false;
        replyBtnText.textContent = 'Send Reply';
        replySpinner.style.display = 'none';
        replyError.textContent = 'An unexpected error occurred. Please try again.';
        replyError.style.display = 'block';
        if (window.showAdminToast) {
            window.showAdminToast('An unexpected error occurred. Please try again.', 'error');
        }
    });
});

})();
</script>
@endpush
