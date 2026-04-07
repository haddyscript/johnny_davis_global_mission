@extends('layouts.admin')

@section('title', 'View Message')
@section('page-title', 'View Message')

@section('content')
<div class="page-form-shell">

    <a href="{{ route('admin.contact-messages.index') }}" class="form-back-link">← Back to Messages</a>

    <div class="page-form-grid">

        {{-- Message body --}}
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

            {{-- Reply button --}}
            <div style="margin-top:20px;">
                <a href="mailto:{{ $contactMessage->email }}?subject=Re: {{ urlencode($contactMessage->subject_label) }}"
                   class="admin-btn pages-new-btn" style="display:inline-flex;">
                    ✉️ &nbsp;Reply by Email
                </a>
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="form-sidebar">

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
    var modal = document.getElementById('delete-modal');
    document.getElementById('danger-btn').addEventListener('click', function () { modal.classList.add('modal-open'); });
    document.getElementById('modal-cancel').addEventListener('click', function () { modal.classList.remove('modal-open'); });
    modal.addEventListener('click', function (e) { if (e.target === modal) modal.classList.remove('modal-open'); });
    document.addEventListener('keydown', function (e) { if (e.key === 'Escape') modal.classList.remove('modal-open'); });
})();
</script>
@endpush
