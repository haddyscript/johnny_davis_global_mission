@extends('layouts.admin')

@section('title', 'Create Page')
@section('page-title', 'Create Page')

@section('content')

<div class="page-form-shell">

    {{-- Back link --}}
    <a href="{{ route('admin.pages.index') }}" class="form-back-link">
        ← Back to Pages
    </a>

    <form
        id="page-form"
        method="POST"
        action="{{ route('admin.pages.store') }}"
        novalidate
    >
        @csrf

        <div class="page-form-grid">

            {{-- ── Main fields ── --}}
            <div class="form-section">
                <div class="form-section-header">
                    <span class="form-section-icon">📄</span>
                    <div>
                        <div class="form-section-title">Page Details</div>
                        <div class="form-section-sub">Basic information about this page.</div>
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
                        value="{{ old('name') }}"
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
                        <span class="pf-hint">Auto-generated from name, or enter manually.</span>
                    </label>
                    <div class="slug-input-wrap">
                        <span class="slug-prefix">/</span>
                        <input
                            id="slug"
                            name="slug"
                            type="text"
                            class="pf-input pf-input-slug"
                            value="{{ old('slug') }}"
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
                    >{{ old('description') }}</textarea>
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
                                    {{ old('is_active', '1') ? 'checked' : '' }}
                                >
                                <span class="toggle-slider"></span>
                            </div>
                            <div>
                                <div class="toggle-status-text" id="status-text">Active</div>
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
                            value="{{ old('sort_order', 0) }}"
                            min="0"
                            placeholder="0"
                        >
                        <div class="pf-hint-text">Lower numbers appear first.</div>
                        @error('sort_order')
                            <div class="pf-error">⚠ {{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- Form actions --}}
                <div class="form-actions-card">
                    <button type="submit" class="admin-btn pf-submit-btn" id="submit-btn">
                        <span class="btn-text">Create Page</span>
                        <span class="btn-spinner"></span>
                    </button>
                    <a href="{{ route('admin.pages.index') }}" class="admin-btn-secondary pf-cancel-btn">
                        Cancel
                    </a>
                </div>

            </div>
        </div>
    </form>
</div>

@endsection

@push('scripts')
<script>
(function () {
'use strict';

/* ── Auto-slug from name ── */
var nameInput = document.getElementById('name');
var slugInput = document.getElementById('slug');
var slugManual = false;

slugInput.addEventListener('input', function () {
    slugManual = slugInput.value.trim() !== '';
});

nameInput.addEventListener('input', function () {
    if (!slugManual) {
        slugInput.value = nameInput.value
            .toLowerCase()
            .replace(/[^a-z0-9\s-]/g, '')
            .trim()
            .replace(/\s+/g, '-')
            .replace(/-+/g, '-');
    }
});

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

})();
</script>
@endpush
