@extends('layouts.admin')

@section('title', 'Create Section')
@section('page-title', 'Create Section')

@section('content')
<div class="page-form-shell">

    <a href="{{ route('admin.sections.index') }}" class="form-back-link">← Back to Sections</a>

    <form id="section-form" method="POST" action="{{ route('admin.sections.store') }}" novalidate>
        @csrf
        <div class="page-form-grid">

            {{-- Main fields --}}
            <div class="form-section">
                <div class="form-section-header">
                    <span class="form-section-icon">✍️</span>
                    <div>
                        <div class="form-section-title">Section Details</div>
                        <div class="form-section-sub">Define the structure and identity of this section.</div>
                    </div>
                </div>

                <div class="pf-group {{ $errors->has('page_id') ? 'has-error' : '' }}">
                    <label class="pf-label" for="page_id">Page <span class="pf-required">*</span></label>
                    <select id="page_id" name="page_id" class="pf-input" required>
                        <option value="">— Select a page —</option>
                        @foreach($pages as $page)
                            <option value="{{ $page->id }}" {{ old('page_id') == $page->id ? 'selected' : '' }}>
                                {{ $page->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('page_id')<div class="pf-error">⚠ {{ $message }}</div>@enderror
                </div>

                <div class="pf-group {{ $errors->has('name') ? 'has-error' : '' }}">
                    <label class="pf-label" for="name">Section Name <span class="pf-required">*</span></label>
                    <input id="name" name="name" type="text" class="pf-input"
                        value="{{ old('name') }}" placeholder="e.g. Hero Banner" autocomplete="off" required>
                    @error('name')<div class="pf-error">⚠ {{ $message }}</div>@enderror
                </div>

                <div class="pf-group {{ $errors->has('slug') ? 'has-error' : '' }}">
                    <label class="pf-label" for="slug">
                        Slug <span class="pf-required">*</span>
                        <span class="pf-hint">Auto-generated from name.</span>
                    </label>
                    <div class="slug-input-wrap">
                        <span class="slug-prefix">/</span>
                        <input id="slug" name="slug" type="text" class="pf-input pf-input-slug"
                            value="{{ old('slug') }}" placeholder="hero-banner" autocomplete="off" required>
                    </div>
                    @error('slug')<div class="pf-error">⚠ {{ $message }}</div>@enderror
                </div>

                <div class="pf-group {{ $errors->has('type') ? 'has-error' : '' }}">
                    <label class="pf-label" for="type">Type <span class="pf-hint">Optional — e.g. hero, gallery, cta</span></label>
                    <input id="type" name="type" type="text" class="pf-input"
                        value="{{ old('type') }}" placeholder="hero" autocomplete="off">
                    @error('type')<div class="pf-error">⚠ {{ $message }}</div>@enderror
                </div>
            </div>

            {{-- Sidebar --}}
            <div class="form-sidebar">
                <div class="form-section">
                    <div class="form-section-header">
                        <span class="form-section-icon">⚙️</span>
                        <div>
                            <div class="form-section-title">Settings</div>
                            <div class="form-section-sub">Ordering within the page.</div>
                        </div>
                    </div>
                    <div class="pf-group {{ $errors->has('sort_order') ? 'has-error' : '' }}">
                        <label class="pf-label" for="sort_order">Sort Order</label>
                        <input id="sort_order" name="sort_order" type="number" class="pf-input"
                            value="{{ old('sort_order', 0) }}" min="0" placeholder="0">
                        <div class="pf-hint-text">Lower numbers appear first.</div>
                        @error('sort_order')<div class="pf-error">⚠ {{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="form-actions-card">
                    <button type="submit" class="admin-btn pf-submit-btn" id="submit-btn">
                        <span class="btn-text">Create Section</span>
                        <span class="btn-spinner"></span>
                    </button>
                    <a href="{{ route('admin.sections.index') }}" class="admin-btn-secondary pf-cancel-btn">Cancel</a>
                </div>
            </div>

        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
(function () {
    var nameInput = document.getElementById('name');
    var slugInput = document.getElementById('slug');
    var slugManual = !!slugInput.value.trim();

    slugInput.addEventListener('input', function () { slugManual = !!slugInput.value.trim(); });

    nameInput.addEventListener('input', function () {
        if (!slugManual) {
            slugInput.value = nameInput.value.toLowerCase()
                .replace(/[^a-z0-9\s-]/g, '').trim().replace(/\s+/g, '-').replace(/-+/g, '-');
        }
    });

    document.getElementById('section-form').addEventListener('submit', function () {
        var btn = document.getElementById('submit-btn');
        btn.classList.add('submitting');
        btn.disabled = true;
    });
})();
</script>
@endpush
