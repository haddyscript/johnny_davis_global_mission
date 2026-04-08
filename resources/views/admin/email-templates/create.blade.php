@extends('layouts.admin')

@section('title', 'Create Email Template')
@section('page-title', 'Create Email Template')

@section('content')

{{-- Quill editor styles --}}
<link href="https://cdn.quilljs.com/1.3.7/quill.snow.css" rel="stylesheet">

<div class="page-form-shell">

    <a href="{{ route('admin.email-templates.index') }}" class="form-back-link">
        ← Back to Email Templates
    </a>

    <form id="et-form" method="POST" action="{{ route('admin.email-templates.store') }}" novalidate>
        @csrf

        <div class="page-form-grid et-form-grid">

            {{-- ── Main fields ── --}}
            <div class="form-section">
                <div class="form-section-header">
                    <span class="form-section-icon">📧</span>
                    <div>
                        <div class="form-section-title">Template Details</div>
                        <div class="form-section-sub">Define the name, subject, and body of this email template.</div>
                    </div>
                </div>

                {{-- Name --}}
                <div class="pf-group {{ $errors->has('name') ? 'has-error' : '' }}">
                    <label class="pf-label" for="name">
                        Template Name <span class="pf-required">*</span>
                        <span class="pf-hint">Internal reference name for admins.</span>
                    </label>
                    <input id="name" name="name" type="text" class="pf-input"
                        value="{{ old('name') }}" placeholder="e.g. Welcome Email"
                        autocomplete="off" required>
                    @error('name')
                        <div class="pf-error">⚠ {{ $message }}</div>
                    @enderror
                </div>

                {{-- Subject --}}
                <div class="pf-group {{ $errors->has('subject') ? 'has-error' : '' }}">
                    <label class="pf-label" for="subject">
                        Email Subject <span class="pf-required">*</span>
                        <span class="pf-hint">Supports placeholders like @{{name}}.</span>
                    </label>
                    <input id="subject" name="subject" type="text" class="pf-input"
                        value="{{ old('subject') }}" placeholder="e.g. Welcome, @{{name}}! 🎉"
                        autocomplete="off" required>
                    @error('subject')
                        <div class="pf-error">⚠ {{ $message }}</div>
                    @enderror
                </div>

                {{-- Body editor --}}
                <div class="pf-group {{ $errors->has('body') ? 'has-error' : '' }}">
                    <label class="pf-label">
                        Email Body <span class="pf-required">*</span>
                    </label>
                    {{-- Hidden input that receives Quill HTML --}}
                    <input type="hidden" name="body" id="body-input" value="{{ old('body') }}">
                    <div id="quill-editor" style="min-height:320px;border-radius:0 0 12px 12px;font-size:14px;"></div>
                    @error('body')
                        <div class="pf-error">⚠ {{ $message }}</div>
                    @enderror
                </div>

                {{-- Variables --}}
                <div class="pf-group {{ $errors->has('variables') ? 'has-error' : '' }}">
                    <label class="pf-label" for="variables">
                        Custom Variables
                        <span class="pf-hint">One per line. Format: <code>key: description</code></span>
                    </label>
                    <textarea id="variables" name="variables" class="pf-input pf-textarea" rows="4"
                        placeholder="name: Recipient's full name&#10;email: Recipient's email address&#10;date: Current date&#10;message: Custom message">{{ old('variables') }}</textarea>
                    @error('variables')
                        <div class="pf-error">⚠ {{ $message }}</div>
                    @enderror
                </div>
            </div>

            {{-- ── Sidebar ── --}}
            <div class="form-sidebar">

                {{-- Settings --}}
                <div class="form-section">
                    <div class="form-section-header">
                        <span class="form-section-icon">⚙️</span>
                        <div>
                            <div class="form-section-title">Settings</div>
                            <div class="form-section-sub">Availability of this template.</div>
                        </div>
                    </div>

                    <div class="pf-group">
                        <label class="pf-label">Status</label>
                        <label class="status-toggle-row" for="is_active">
                            <div class="toggle-switch-wrap">
                                <input type="checkbox" id="is_active" name="is_active" value="1"
                                    class="toggle-checkbox" {{ old('is_active', '1') ? 'checked' : '' }}>
                                <span class="toggle-slider"></span>
                            </div>
                            <div>
                                <div class="toggle-status-text" id="status-text">Active</div>
                                <div style="font-size:12px;color:var(--text-muted);margin-top:2px;">
                                    Available for sending emails
                                </div>
                            </div>
                        </label>
                    </div>
                </div>

                {{-- Variable helper panel --}}
                <div class="form-section et-var-panel">
                    <div class="form-section-header">
                        <span class="form-section-icon">🔖</span>
                        <div>
                            <div class="form-section-title">Available Placeholders</div>
                            <div class="form-section-sub">Click to insert into subject or body.</div>
                        </div>
                    </div>
                    <div class="et-var-list">
                        <button type="button" class="et-var-btn" data-var="@{{name}}">
                            <code>@{{name}}</code>
                            <span>Recipient's name</span>
                        </button>
                        <button type="button" class="et-var-btn" data-var="@{{email}}">
                            <code>@{{email}}</code>
                            <span>Email address</span>
                        </button>
                        <button type="button" class="et-var-btn" data-var="@{{date}}">
                            <code>@{{date}}</code>
                            <span>Current date</span>
                        </button>
                        <button type="button" class="et-var-btn" data-var="@{{message}}">
                            <code>@{{message}}</code>
                            <span>Custom message</span>
                        </button>
                    </div>
                    <div class="et-var-tip">
                        You can also define custom variables in the field on the left.
                    </div>
                </div>

                {{-- Live preview card --}}
                <div class="form-section et-preview-card" id="preview-card" style="display:none;">
                    <div class="form-section-header">
                        <span class="form-section-icon">👁️</span>
                        <div>
                            <div class="form-section-title">Live Preview</div>
                            <div class="form-section-sub">Sample data applied.</div>
                        </div>
                    </div>
                    <div id="live-preview-body" style="font-size:13px;line-height:1.6;color:var(--text-dark);background:#f8fafc;border-radius:12px;padding:16px;max-height:200px;overflow-y:auto;"></div>
                </div>

                {{-- Form actions --}}
                <div class="form-actions-card">
                    <button type="button" class="admin-btn-secondary" id="preview-btn"
                        style="display:flex;align-items:center;gap:8px;justify-content:center;width:100%;margin-bottom:10px;">
                        👁️ Preview Template
                    </button>
                    <button type="submit" class="admin-btn pf-submit-btn" id="submit-btn">
                        <span class="btn-text">Create Template</span>
                        <span class="btn-spinner"></span>
                    </button>
                    <a href="{{ route('admin.email-templates.index') }}" class="admin-btn-secondary pf-cancel-btn">
                        Cancel
                    </a>
                </div>

            </div>
        </div>
    </form>
</div>

{{-- Brand config passed to JS via data attributes (keeps script block Blade-free) --}}
<div id="et-brand-data"
     data-preview-url="{{ route('admin.email-templates.preview-render') }}"
     data-csrf="{{ csrf_token() }}"
     style="display:none;"></div>

{{-- Full branded preview modal --}}
<div class="modal-overlay" id="preview-modal">
    <div class="modal-card" role="dialog" aria-modal="true" style="max-width:760px;width:95%;padding:0;overflow:hidden;">
        <div style="display:flex;align-items:center;justify-content:space-between;padding:18px 24px;border-bottom:1px solid var(--border);background:#fff;border-radius:28px 28px 0 0;">
            <div style="display:flex;align-items:center;gap:12px;">
                <div style="width:36px;height:36px;border-radius:10px;background:rgba(20,184,166,0.1);color:#0f766e;display:grid;place-items:center;font-size:16px;">📧</div>
                <div>
                    <div style="font-weight:700;font-size:15px;">Branded Email Preview</div>
                    <div style="font-size:12px;color:var(--text-muted);margin-top:2px;">
                        Subject: <span id="modal-preview-subject" style="color:var(--text-dark);font-weight:500;"></span>
                    </div>
                </div>
            </div>
            <button class="modal-btn-cancel" id="preview-close" style="margin:0;padding:8px 16px;font-size:13px;">✕ Close</button>
        </div>
        <div style="background:#e8ecf0;">
            <iframe
                id="preview-iframe"
                title="Branded Email Preview"
                style="width:100%;min-height:500px;border:none;display:block;"
                sandbox="allow-same-origin"
            ></iframe>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.quilljs.com/1.3.7/quill.min.js"></script>
<style>
.et-form-grid {
    grid-template-columns: 1fr 380px;
}
.et-var-panel { margin-top: 0; }
.et-var-list { display: flex; flex-direction: column; gap: 6px; margin-top: 4px; }
.et-var-btn {
    display: flex; align-items: center; gap: 10px;
    background: rgba(99,102,241,0.05); border: 1px solid rgba(99,102,241,0.15);
    border-radius: 10px; padding: 9px 12px; cursor: pointer; text-align: left;
    transition: background 0.15s, border-color 0.15s;
}
.et-var-btn:hover { background: rgba(99,102,241,0.1); border-color: rgba(99,102,241,0.3); }
.et-var-btn code {
    font-size: 12px; color: #4f46e5; font-weight: 600; white-space: nowrap;
    background: rgba(99,102,241,0.1); padding: 2px 6px; border-radius: 5px;
}
.et-var-btn span { font-size: 12px; color: var(--text-muted); }
.et-var-tip {
    margin-top: 10px; font-size: 11px; color: var(--text-muted);
    background: var(--surface-strong); border-radius: 8px; padding: 8px 10px;
    line-height: 1.5;
}
.et-preview-card { margin-top: 0; }
/* Quill toolbar rounding */
.ql-toolbar.ql-snow { border-radius: 12px 12px 0 0; border-color: var(--border); background: #f8fafc; }
.ql-container.ql-snow { border-color: var(--border); border-radius: 0 0 12px 12px; }
.ql-editor { min-height: 300px; font-family: Inter, system-ui, sans-serif; font-size: 14px; }
</style>
<script>
(function () {
'use strict';

/* ── Quill editor ── */
var quill = new Quill('#quill-editor', {
    theme: 'snow',
    placeholder: 'Write your email body here\u2026 Use \u007b\u007bname\u007d\u007d, \u007b\u007bemail\u007d\u007d, etc. for dynamic content.',
    modules: {
        toolbar: [
            [{ header: [1, 2, 3, false] }],
            ['bold', 'italic', 'underline', 'strike'],
            [{ color: [] }, { background: [] }],
            [{ align: [] }],
            [{ list: 'ordered' }, { list: 'bullet' }],
            ['link', 'image'],
            ['blockquote', 'code-block'],
            ['clean'],
        ],
    },
});

/* Restore old value on validation failure */
var oldBody = document.getElementById('body-input').value;
if (oldBody) {
    quill.root.innerHTML = oldBody;
}

/* Sync Quill → hidden input on submit */
document.getElementById('et-form').addEventListener('submit', function () {
    document.getElementById('body-input').value = quill.root.innerHTML;
    var btn = document.getElementById('submit-btn');
    btn.classList.add('submitting'); btn.disabled = true;
});

/* ── Status toggle text ── */
var checkbox   = document.getElementById('is_active');
var statusText = document.getElementById('status-text');
function updateStatus() {
    statusText.textContent = checkbox.checked ? 'Active' : 'Inactive';
    statusText.style.color = checkbox.checked ? '#059669' : '#64748b';
}
checkbox.addEventListener('change', updateStatus);
updateStatus();

/* ── Variable buttons → insert into focused field ── */
var lastFocused = null; // 'subject' or 'quill'
var subjectInput = document.getElementById('subject');
subjectInput.addEventListener('focus', function () { lastFocused = 'subject'; });
quill.on('selection-change', function (range) { if (range) lastFocused = 'quill'; });

document.querySelectorAll('.et-var-btn').forEach(function (btn) {
    btn.addEventListener('click', function () {
        var variable = btn.dataset.var;
        if (lastFocused === 'subject') {
            var start = subjectInput.selectionStart;
            var end   = subjectInput.selectionEnd;
            subjectInput.value = subjectInput.value.slice(0, start) + variable + subjectInput.value.slice(end);
            subjectInput.focus();
            subjectInput.setSelectionRange(start + variable.length, start + variable.length);
        } else {
            var range = quill.getSelection(true);
            quill.insertText(range ? range.index : quill.getLength(), variable, 'user');
        }
        if (window.showAdminToast) window.showAdminToast('Placeholder inserted.', 'success');
    });
});

/* ── Config from data attributes ── */
var _bd          = document.getElementById('et-brand-data');
var PREVIEW_URL  = _bd.dataset.previewUrl;
var CSRF         = _bd.dataset.csrf;

/* ── Live preview (sidebar — simple placeholder swap, no branding) ── */
var OB = '{' + '{';
var CB = '}' + '}';
var sampleData = {};
sampleData[OB + 'name'    + CB] = 'John Doe';
sampleData[OB + 'email'   + CB] = 'john.doe@example.com';
sampleData[OB + 'date'    + CB] = new Date().toLocaleDateString('en-US', { year:'numeric', month:'long', day:'numeric' });
sampleData[OB + 'message' + CB] = 'This is a sample message for preview purposes.';

function applyPlaceholders(str) {
    Object.keys(sampleData).forEach(function (k) { str = str.split(k).join(sampleData[k]); });
    return str;
}

var previewCard = document.getElementById('preview-card');
var livePreview = document.getElementById('live-preview-body');
var previewTimer;

function updateLivePreview() {
    var html = quill.root.innerHTML;
    if (html.replace(/<[^>]*>/g, '').trim() === '') {
        previewCard.style.display = 'none';
        return;
    }
    livePreview.innerHTML = applyPlaceholders(html);
    previewCard.style.display = 'block';
}

quill.on('text-change', function () {
    clearTimeout(previewTimer);
    previewTimer = setTimeout(updateLivePreview, 600);
});

/* ── Full branded preview modal (AJAX → server renders brand-template) ── */
var previewModal        = document.getElementById('preview-modal');
var modalPreviewSubject = document.getElementById('modal-preview-subject');
var previewIframe       = document.getElementById('preview-iframe');
var previewBtn          = document.getElementById('preview-btn');

previewBtn.addEventListener('click', function () {
    var body    = quill.root.innerHTML;
    var subject = document.getElementById('subject').value;

    previewModal.classList.add('modal-open');
    modalPreviewSubject.textContent = '…';
    previewIframe.srcdoc = '';
    previewIframe.style.height = '400px';
    previewBtn.disabled = true;
    previewBtn.textContent = 'Loading…';

    fetch(PREVIEW_URL, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': CSRF,
        },
        body: JSON.stringify({ subject: subject, body: body }),
    })
    .then(function (r) { return r.json(); })
    .then(function (data) {
        modalPreviewSubject.textContent = data.subject || subject || '(no subject)';
        previewIframe.srcdoc = data.branded_html;
        previewIframe.onload = function () {
            try {
                var h = previewIframe.contentDocument.body.scrollHeight;
                previewIframe.style.height = Math.max(h + 32, 400) + 'px';
            } catch (e) {}
        };
    })
    .catch(function () {
        if (window.showAdminToast) window.showAdminToast('Failed to load preview.', 'error');
        previewModal.classList.remove('modal-open');
    })
    .finally(function () {
        previewBtn.disabled = false;
        previewBtn.textContent = 'Preview Template';
    });
});

document.getElementById('preview-close').addEventListener('click', function () {
    previewModal.classList.remove('modal-open');
});
previewModal.addEventListener('click', function (e) {
    if (e.target === previewModal) previewModal.classList.remove('modal-open');
});
document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') previewModal.classList.remove('modal-open');
});

})();
</script>
@endpush
