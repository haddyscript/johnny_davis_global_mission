@extends('layouts.admin')

@section('title', 'Edit Content Block')
@section('page-title', 'Edit Content Block')

@section('content')
<div class="page-form-shell">

    <a href="{{ route('admin.content-blocks.index') }}" class="form-back-link">← Back to Content Blocks</a>

    <form id="cb-form" method="POST" action="{{ route('admin.content-blocks.update', $contentBlock) }}" novalidate>
        @csrf
        @method('PUT')
        <div class="page-form-grid">

            {{-- Main fields --}}
            <div class="form-section">
                <div class="form-section-header">
                    <span class="form-section-icon">✏️</span>
                    <div>
                        <div class="form-section-title">Block Details</div>
                        <div class="form-section-sub">Update this block's content and configuration.</div>
                    </div>
                </div>

                <div class="pf-group {{ $errors->has('section_id') ? 'has-error' : '' }}">
                    <label class="pf-label" for="section_id">Section <span class="pf-required">*</span></label>
                    <select id="section_id" name="section_id" class="pf-input" required>
                        <option value="">— Select a section —</option>
                        @foreach($sections as $section)
                            <option value="{{ $section->id }}" {{ old('section_id', $contentBlock->section_id) == $section->id ? 'selected' : '' }}>
                                {{ $section->page->name }} — {{ $section->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('section_id')<div class="pf-error">⚠ {{ $message }}</div>@enderror
                </div>

                <div class="pf-group {{ $errors->has('key') ? 'has-error' : '' }}">
                    <label class="pf-label" for="key">Key <span class="pf-required">*</span></label>
                    <input id="key" name="key" type="text" class="pf-input"
                        value="{{ old('key', $contentBlock->key) }}" placeholder="e.g. headline" autocomplete="off" required>
                    @error('key')<div class="pf-error">⚠ {{ $message }}</div>@enderror
                </div>

                <div class="pf-group {{ $errors->has('content') ? 'has-error' : '' }}">
                    <label class="pf-label" for="content">Content</label>
                    <textarea id="content" name="content" class="pf-input pf-textarea" rows="5"
                        placeholder="Enter text, alt text, or list items…">{{ old('content', $contentBlock->content) }}</textarea>
                    @error('content')<div class="pf-error">⚠ {{ $message }}</div>@enderror
                </div>

                <div class="pf-group {{ $errors->has('url') ? 'has-error' : '' }}" id="url-group"
                    style="display:{{ in_array(old('type', $contentBlock->type), ['image','link']) ? 'block' : 'none' }};">
                    <label class="pf-label" for="url">URL</label>
                    <input id="url" name="url" type="url" class="pf-input"
                        value="{{ old('url', $contentBlock->url) }}" placeholder="https://…">
                    @error('url')<div class="pf-error">⚠ {{ $message }}</div>@enderror
                </div>
            </div>

            {{-- Sidebar --}}
            <div class="form-sidebar">
                <div class="form-section">
                    <div class="form-section-header">
                        <span class="form-section-icon">⚙️</span>
                        <div>
                            <div class="form-section-title">Type & Order</div>
                            <div class="form-section-sub">Block type determines rendering.</div>
                        </div>
                    </div>

                    <div class="pf-group {{ $errors->has('type') ? 'has-error' : '' }}">
                        <label class="pf-label" for="type">Block Type <span class="pf-required">*</span></label>
                        <div class="type-picker" id="type-picker">
                            @foreach(['text','image','link','list'] as $t)
                            <label class="type-pick-option {{ old('type', $contentBlock->type) === $t ? 'selected' : '' }}">
                                <input type="radio" name="type" value="{{ $t }}"
                                    {{ old('type', $contentBlock->type) === $t ? 'checked' : '' }} required>
                                <span class="type-pick-icon">{{ ['text'=>'📝','image'=>'🖼️','link'=>'🔗','list'=>'📋'][$t] }}</span>
                                <span class="type-pick-label">{{ ucfirst($t) }}</span>
                            </label>
                            @endforeach
                        </div>
                        @error('type')<div class="pf-error">⚠ {{ $message }}</div>@enderror
                    </div>

                    <div class="pf-group {{ $errors->has('sort_order') ? 'has-error' : '' }}">
                        <label class="pf-label" for="sort_order">Sort Order</label>
                        <input id="sort_order" name="sort_order" type="number" class="pf-input"
                            value="{{ old('sort_order', $contentBlock->sort_order) }}" min="0" placeholder="0">
                        <div class="pf-hint-text">Lower numbers appear first.</div>
                        @error('sort_order')<div class="pf-error">⚠ {{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="form-meta-card">
                    <div class="form-meta-row">
                        <span class="form-meta-label">Block ID</span>
                        <span class="form-meta-value">#{{ $contentBlock->id }}</span>
                    </div>
                    <div class="form-meta-row">
                        <span class="form-meta-label">Created</span>
                        <span class="form-meta-value">{{ $contentBlock->created_at->format('M j, Y') }}</span>
                    </div>
                    <div class="form-meta-row">
                        <span class="form-meta-label">Updated</span>
                        <span class="form-meta-value">{{ $contentBlock->updated_at->format('M j, Y') }}</span>
                    </div>
                </div>

                <div class="form-actions-card">
                    <button type="submit" class="admin-btn pf-submit-btn" id="submit-btn">
                        <span class="btn-text">Save Changes</span>
                        <span class="btn-spinner"></span>
                    </button>
                    <a href="{{ route('admin.content-blocks.index') }}" class="admin-btn-secondary pf-cancel-btn">Cancel</a>
                </div>

                <div class="danger-zone">
                    <div class="danger-zone-title">Danger Zone</div>
                    <p class="danger-zone-sub">Permanently deletes this content block.</p>
                    <button type="button" class="danger-delete-btn" id="danger-btn">Delete this block</button>
                </div>
            </div>

        </div>
    </form>
</div>

<div class="modal-overlay" id="delete-modal">
    <div class="modal-card">
        <div class="modal-icon">🗑️</div>
        <h3 class="modal-title">Delete Block?</h3>
        <p class="modal-body">Permanently deletes block <strong>{{ $contentBlock->key }}</strong>.</p>
        <div class="modal-actions">
            <button class="modal-btn-cancel" id="modal-cancel">Cancel</button>
            <form method="POST" action="{{ route('admin.content-blocks.destroy', $contentBlock) }}">
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
function updateUrlVisibility() {
    var checked = document.querySelector('input[name="type"]:checked');
    var urlGroup = document.getElementById('url-group');
    if (!checked || !urlGroup) return;
    var show = checked.value === 'image' || checked.value === 'link';
    urlGroup.style.display = show ? 'block' : 'none';
}

document.querySelectorAll('.type-pick-option input').forEach(function (radio) {
    radio.addEventListener('change', function () {
        document.querySelectorAll('.type-pick-option').forEach(function (opt) { opt.classList.remove('selected'); });
        radio.closest('.type-pick-option').classList.add('selected');
        updateUrlVisibility();
    });
});

updateUrlVisibility();

document.getElementById('cb-form').addEventListener('submit', function () {
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
