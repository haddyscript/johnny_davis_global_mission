@extends('layouts.admin')

@section('title', 'Create Content Block')
@section('page-title', 'Create Content Block')

@section('content')
<div class="page-form-shell">

    <a href="{{ route('admin.content-blocks.index') }}" class="form-back-link">← Back to Content Blocks</a>

    <form id="cb-form" method="POST" action="{{ route('admin.content-blocks.store') }}" novalidate>
        @csrf
        <div class="page-form-grid">

            {{-- Main fields --}}
            <div class="form-section">
                <div class="form-section-header">
                    <span class="form-section-icon">🧩</span>
                    <div>
                        <div class="form-section-title">Block Details</div>
                        <div class="form-section-sub">Define the content and identity of this block.</div>
                    </div>
                </div>

                <div class="pf-group {{ $errors->has('section_id') ? 'has-error' : '' }}">
                    <label class="pf-label" for="section_id">Section <span class="pf-required">*</span></label>
                    <select id="section_id" name="section_id" class="pf-input" required>
                        <option value="">— Select a section —</option>
                        @foreach($sections as $section)
                            <option value="{{ $section->id }}" {{ old('section_id') == $section->id ? 'selected' : '' }}>
                                {{ $section->page->name }} — {{ $section->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('section_id')<div class="pf-error">⚠ {{ $message }}</div>@enderror
                </div>

                <div class="pf-group {{ $errors->has('key') ? 'has-error' : '' }}">
                    <label class="pf-label" for="key">
                        Key <span class="pf-required">*</span>
                        <span class="pf-hint">Unique identifier within the section.</span>
                    </label>
                    <input id="key" name="key" type="text" class="pf-input"
                        value="{{ old('key') }}" placeholder="e.g. headline" autocomplete="off" required>
                    @error('key')<div class="pf-error">⚠ {{ $message }}</div>@enderror
                </div>

                <div class="pf-group {{ $errors->has('content') ? 'has-error' : '' }}">
                    <label class="pf-label" for="content">
                        Content
                        <span class="char-counter" id="desc-counter"></span>
                    </label>
                    <textarea id="content" name="content" class="pf-input pf-textarea" rows="5"
                        placeholder="Enter text content, image alt text, list items…">{{ old('content') }}</textarea>
                    @error('content')<div class="pf-error">⚠ {{ $message }}</div>@enderror
                </div>

                <div class="pf-group {{ $errors->has('url') ? 'has-error' : '' }}" id="url-group"
                    style="display:{{ in_array(old('type'), ['image','link']) ? 'block' : 'none' }};">
                    <label class="pf-label" for="url">URL</label>
                    <input id="url" name="url" type="url" class="pf-input"
                        value="{{ old('url') }}" placeholder="https://…">
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
                            <label class="type-pick-option {{ old('type') === $t ? 'selected' : '' }}">
                                <input type="radio" name="type" value="{{ $t }}"
                                    {{ old('type', 'text') === $t ? 'checked' : '' }} required>
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
                            value="{{ old('sort_order', 0) }}" min="0" placeholder="0">
                        <div class="pf-hint-text">Lower numbers appear first.</div>
                        @error('sort_order')<div class="pf-error">⚠ {{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="form-actions-card">
                    <button type="submit" class="admin-btn pf-submit-btn" id="submit-btn">
                        <span class="btn-text">Create Block</span>
                        <span class="btn-spinner"></span>
                    </button>
                    <a href="{{ route('admin.content-blocks.index') }}" class="admin-btn-secondary pf-cancel-btn">Cancel</a>
                </div>
            </div>

        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
(function () {
/* Show/hide URL field based on type */
function updateUrlVisibility() {
    var checked = document.querySelector('input[name="type"]:checked');
    var urlGroup = document.getElementById('url-group');
    if (!checked || !urlGroup) return;
    urlGroup.style.display = (checked.value === 'image' || checked.value === 'link') ? 'block' : 'none';
    if (urlGroup.style.display === 'block') {
        urlGroup.style.animation = 'fadeSlideUp 0.2s ease both';
    }
}

/* Type picker highlight */
document.querySelectorAll('.type-pick-option input').forEach(function (radio) {
    radio.addEventListener('change', function () {
        document.querySelectorAll('.type-pick-option').forEach(function (opt) {
            opt.classList.remove('selected');
        });
        radio.closest('.type-pick-option').classList.add('selected');
        updateUrlVisibility();
    });
});

updateUrlVisibility();

/* Loading state */
document.getElementById('cb-form').addEventListener('submit', function () {
    var btn = document.getElementById('submit-btn');
    btn.classList.add('submitting'); btn.disabled = true;
});
})();
</script>
@endpush
