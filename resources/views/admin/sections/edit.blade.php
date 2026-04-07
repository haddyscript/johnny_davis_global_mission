@extends('layouts.admin')

@section('title', 'Edit Section')
@section('page-title', 'Edit Section')

@section('content')
<div class="page-form-shell">

    <a href="{{ route('admin.sections.index') }}" class="form-back-link">← Back to Sections</a>

    <form id="section-form" method="POST" action="{{ route('admin.sections.update', $section) }}" novalidate>
        @csrf
        @method('PUT')
        <div class="page-form-grid">

            {{-- Main fields --}}
            <div class="form-section">
                <div class="form-section-header">
                    <span class="form-section-icon">✏️</span>
                    <div>
                        <div class="form-section-title">Section Details</div>
                        <div class="form-section-sub">Update this section's identity and structure.</div>
                    </div>
                </div>

                <div class="pf-group {{ $errors->has('page_id') ? 'has-error' : '' }}">
                    <label class="pf-label" for="page_id">Page <span class="pf-required">*</span></label>
                    <select id="page_id" name="page_id" class="pf-input" required>
                        <option value="">— Select a page —</option>
                        @foreach($pages as $page)
                            <option value="{{ $page->id }}" {{ old('page_id', $section->page_id) == $page->id ? 'selected' : '' }}>
                                {{ $page->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('page_id')<div class="pf-error">⚠ {{ $message }}</div>@enderror
                </div>

                <div class="pf-group {{ $errors->has('name') ? 'has-error' : '' }}">
                    <label class="pf-label" for="name">Section Name <span class="pf-required">*</span></label>
                    <input id="name" name="name" type="text" class="pf-input"
                        value="{{ old('name', $section->name) }}" placeholder="e.g. Hero Banner" autocomplete="off" required>
                    @error('name')<div class="pf-error">⚠ {{ $message }}</div>@enderror
                </div>

                <div class="pf-group {{ $errors->has('slug') ? 'has-error' : '' }}">
                    <label class="pf-label" for="slug">
                        Slug <span class="pf-required">*</span>
                        <span class="pf-hint">Changing this may break content references.</span>
                    </label>
                    <div class="slug-input-wrap">
                        <span class="slug-prefix">/</span>
                        <input id="slug" name="slug" type="text" class="pf-input pf-input-slug"
                            value="{{ old('slug', $section->slug) }}" placeholder="hero-banner" autocomplete="off" required>
                    </div>
                    @error('slug')<div class="pf-error">⚠ {{ $message }}</div>@enderror
                </div>

                <div class="pf-group {{ $errors->has('type') ? 'has-error' : '' }}">
                    <label class="pf-label" for="type">Type <span class="pf-hint">Optional</span></label>
                    <input id="type" name="type" type="text" class="pf-input"
                        value="{{ old('type', $section->type) }}" placeholder="hero" autocomplete="off">
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
                            value="{{ old('sort_order', $section->sort_order) }}" min="0" placeholder="0">
                        <div class="pf-hint-text">Lower numbers appear first.</div>
                        @error('sort_order')<div class="pf-error">⚠ {{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="form-meta-card">
                    <div class="form-meta-row">
                        <span class="form-meta-label">Content blocks</span>
                        <span class="form-meta-value">{{ $section->contentBlocks()->count() }}</span>
                    </div>
                    <div class="form-meta-row">
                        <span class="form-meta-label">Created</span>
                        <span class="form-meta-value">{{ $section->created_at->format('M j, Y') }}</span>
                    </div>
                    <div class="form-meta-row">
                        <span class="form-meta-label">Updated</span>
                        <span class="form-meta-value">{{ $section->updated_at->format('M j, Y') }}</span>
                    </div>
                </div>

                <div class="form-actions-card">
                    <button type="submit" class="admin-btn pf-submit-btn" id="submit-btn">
                        <span class="btn-text">Save Changes</span>
                        <span class="btn-spinner"></span>
                    </button>
                    <a href="{{ route('admin.sections.index') }}" class="admin-btn-secondary pf-cancel-btn">Cancel</a>
                </div>

                <div class="danger-zone">
                    <div class="danger-zone-title">Danger Zone</div>
                    <p class="danger-zone-sub">Permanently deletes this section and all its content blocks.</p>
                    <button type="button" class="danger-delete-btn" id="danger-btn">Delete this section</button>
                </div>
            </div>

        </div>
    </form>
</div>

<div class="modal-overlay" id="delete-modal">
    <div class="modal-card">
        <div class="modal-icon">🗑️</div>
        <h3 class="modal-title">Delete Section?</h3>
        <p class="modal-body">Permanently deletes <strong>{{ $section->name }}</strong> and all its content blocks.</p>
        <div class="modal-actions">
            <button class="modal-btn-cancel" id="modal-cancel">Cancel</button>
            <form method="POST" action="{{ route('admin.sections.destroy', $section) }}">
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
    document.getElementById('section-form').addEventListener('submit', function () {
        var btn = document.getElementById('submit-btn');
        btn.classList.add('submitting'); btn.disabled = true;
    });

    var modal = document.getElementById('delete-modal');
    document.getElementById('danger-btn').addEventListener('click', function () { modal.classList.add('modal-open'); });
    document.getElementById('modal-cancel').addEventListener('click', function () { modal.classList.remove('modal-open'); });
    modal.addEventListener('click', function (e) { if (e.target === modal) modal.classList.remove('modal-open'); });
    document.addEventListener('keydown', function (e) { if (e.key === 'Escape') modal.classList.remove('modal-open'); });
})();
</script>
@endpush
