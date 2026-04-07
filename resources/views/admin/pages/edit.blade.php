@extends('layouts.admin')

@section('title', 'Edit Page')
@section('page-title', 'Edit Page')

@section('content')

<div class="page-form-shell">

    {{-- Back link --}}
    <a href="{{ route('admin.pages.index') }}" class="form-back-link">
        ← Back to Pages
    </a>

    <form
        id="page-form"
        method="POST"
        action="{{ route('admin.pages.update', $page) }}"
        novalidate
    >
        @csrf
        @method('PUT')

        <div class="page-form-grid">

            {{-- ── Main fields ── --}}
            <div class="form-section">
                <div class="form-section-header">
                    <span class="form-section-icon">✏️</span>
                    <div>
                        <div class="form-section-title">Page Details</div>
                        <div class="form-section-sub">Update the information for this page.</div>
                    </div>
                </div>

                {{-- Name --}}
                <div class="pf-group {{ $errors->has('name') ? 'has-error' : '' }}">
                    <label class="pf-label" for="name">
                        Page Name <span class="pf-required">*</span>
                    </label>
                    <input
                        id="name"
                        name="name"
                        type="text"
                        class="pf-input"
                        value="{{ old('name', $page->name) }}"
                        placeholder="e.g. About Us"
                        autocomplete="off"
                        required
                    >
                    @error('name')
                        <div class="pf-error">⚠ {{ $message }}</div>
                    @enderror
                </div>

                {{-- Slug --}}
                <div class="pf-group {{ $errors->has('slug') ? 'has-error' : '' }}">
                    <label class="pf-label" for="slug">
                        Slug <span class="pf-required">*</span>
                        <span class="pf-hint">Changing the slug may break existing links.</span>
                    </label>
                    <div class="slug-input-wrap">
                        <span class="slug-prefix">/</span>
                        <input
                            id="slug"
                            name="slug"
                            type="text"
                            class="pf-input pf-input-slug"
                            value="{{ old('slug', $page->slug) }}"
                            placeholder="about-us"
                            autocomplete="off"
                            required
                        >
                    </div>
                    @error('slug')
                        <div class="pf-error">⚠ {{ $message }}</div>
                    @enderror
                </div>

                {{-- Description --}}
                <div class="pf-group {{ $errors->has('description') ? 'has-error' : '' }}">
                    <label class="pf-label" for="description">
                        Description
                        <span class="char-counter" id="desc-counter">0 / 300</span>
                    </label>
                    <textarea
                        id="description"
                        name="description"
                        class="pf-input pf-textarea"
                        rows="4"
                        maxlength="300"
                        placeholder="Brief description of this page's purpose…"
                    >{{ old('description', $page->description) }}</textarea>
                    @error('description')
                        <div class="pf-error">⚠ {{ $message }}</div>
                    @enderror
                </div>
            </div>

            {{-- ── Settings sidebar ── --}}
            <div class="form-sidebar">

                <div class="form-section">
                    <div class="form-section-header">
                        <span class="form-section-icon">⚙️</span>
                        <div>
                            <div class="form-section-title">Settings</div>
                            <div class="form-section-sub">Visibility and ordering.</div>
                        </div>
                    </div>

                    {{-- Status toggle --}}
                    <div class="pf-group">
                        <label class="pf-label">Status</label>
                        <label class="status-toggle-row" for="is_active">
                            <div class="toggle-switch-wrap">
                                <input
                                    type="checkbox"
                                    id="is_active"
                                    name="is_active"
                                    value="1"
                                    class="toggle-checkbox"
                                    {{ old('is_active', $page->is_active) ? 'checked' : '' }}
                                >
                                <span class="toggle-slider"></span>
                            </div>
                            <div>
                                <div class="toggle-status-text" id="status-text">
                                    {{ $page->is_active ? 'Active' : 'Inactive' }}
                                </div>
                                <div style="font-size:12px;color:var(--text-muted);margin-top:2px;">
                                    Visible on the website
                                </div>
                            </div>
                        </label>
                    </div>

                    {{-- Sort Order --}}
                    <div class="pf-group {{ $errors->has('sort_order') ? 'has-error' : '' }}">
                        <label class="pf-label" for="sort_order">Sort Order</label>
                        <input
                            id="sort_order"
                            name="sort_order"
                            type="number"
                            class="pf-input"
                            value="{{ old('sort_order', $page->sort_order) }}"
                            min="0"
                            placeholder="0"
                        >
                        <div class="pf-hint-text">Lower numbers appear first.</div>
                        @error('sort_order')
                            <div class="pf-error">⚠ {{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- Meta --}}
                <div class="form-meta-card">
                    <div class="form-meta-row">
                        <span class="form-meta-label">Created</span>
                        <span class="form-meta-value">{{ $page->created_at->format('M j, Y') }}</span>
                    </div>
                    <div class="form-meta-row">
                        <span class="form-meta-label">Updated</span>
                        <span class="form-meta-value">{{ $page->updated_at->format('M j, Y') }}</span>
                    </div>
                    <div class="form-meta-row">
                        <span class="form-meta-label">ID</span>
                        <span class="form-meta-value">#{{ $page->id }}</span>
                    </div>
                </div>

                {{-- Form actions --}}
                <div class="form-actions-card">
                    <button type="submit" class="admin-btn pf-submit-btn" id="submit-btn">
                        <span class="btn-text">Save Changes</span>
                        <span class="btn-spinner"></span>
                    </button>
                    <a href="{{ route('admin.pages.index') }}" class="admin-btn-secondary pf-cancel-btn">
                        Cancel
                    </a>
                </div>

                {{-- Danger zone --}}
                <div class="danger-zone">
                    <div class="danger-zone-title">Danger Zone</div>
                    <p class="danger-zone-sub">Permanently deletes this page and all associated content.</p>
                    <button
                        type="button"
                        class="danger-delete-btn"
                        id="danger-delete-btn"
                        data-name="{{ $page->name }}"
                        data-action="{{ route('admin.pages.destroy', $page) }}"
                    >
                        Delete this page
                    </button>
                </div>

            </div>
        </div>
    </form>
</div>

{{-- Delete modal --}}
<div class="modal-overlay" id="delete-modal">
    <div class="modal-card" role="dialog" aria-modal="true">
        <div class="modal-icon">🗑️</div>
        <h3 class="modal-title">Delete Page?</h3>
        <p class="modal-body">
            You are about to permanently delete <strong>{{ $page->name }}</strong>.<br>
            This cannot be undone.
        </p>
        <div class="modal-actions">
            <button class="modal-btn-cancel" id="modal-cancel">Cancel</button>
            <form id="delete-form" method="POST" action="{{ route('admin.pages.destroy', $page) }}">
                @csrf
                @method('DELETE')
                <button type="submit" class="modal-btn-confirm">Delete</button>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
(function () {
'use strict';

/* ── Character counter ── */
var desc = document.getElementById('description');
var counter = document.getElementById('desc-counter');
function updateCounter() {
    var len = desc.value.length;
    var max = parseInt(desc.maxLength, 10);
    counter.textContent = len + ' / ' + max;
    counter.style.color = len >= max * 0.9 ? '#ef4444' : '';
}
desc.addEventListener('input', updateCounter);
updateCounter();

/* ── Status toggle text ── */
var checkbox = document.getElementById('is_active');
var statusText = document.getElementById('status-text');
function updateStatus() {
    statusText.textContent = checkbox.checked ? 'Active' : 'Inactive';
    statusText.style.color = checkbox.checked ? '#059669' : '#64748b';
}
checkbox.addEventListener('change', updateStatus);
updateStatus();

/* ── Loading state on submit ── */
document.getElementById('page-form').addEventListener('submit', function () {
    var btn = document.getElementById('submit-btn');
    btn.classList.add('submitting');
    btn.disabled = true;
});

/* ── Danger zone delete modal ── */
var modal = document.getElementById('delete-modal');
document.getElementById('danger-delete-btn').addEventListener('click', function () {
    modal.classList.add('modal-open');
    document.getElementById('modal-cancel').focus();
});
document.getElementById('modal-cancel').addEventListener('click', function () {
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
