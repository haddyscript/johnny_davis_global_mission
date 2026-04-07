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

                <div class="pf-group {{ $errors->has('key') ? 'has-error' : '' }}" id="key-group">
                    <label class="pf-label" for="key-select">
                        Key <span class="pf-required">*</span>
                    </label>

                    {{-- Dropdown: populated by JS when a section is chosen --}}
                    <select id="key-select" class="pf-input" style="margin-bottom:6px;">
                        <option value="">— Select a section first —</option>
                    </select>

                    {{-- Shown only when "Custom key…" is picked --}}
                    <div id="key-custom-wrap" style="display:none;">
                        <input id="key-custom-input" type="text" class="pf-input"
                            placeholder="Type your custom key…" autocomplete="off">
                    </div>

                    {{-- Hint shown below the dropdown --}}
                    <div id="key-hint" style="font-size:12px;color:var(--text-muted);margin-top:4px;min-height:18px;"></div>

                    {{-- Actual submitted field (always present, kept in sync) --}}
                    <input id="key" name="key" type="hidden" value="{{ old('key') }}" required>

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
'use strict';

/* ── Key map (section_id → key definitions) passed from controller ── */
var KEY_MAP = @json($keyMap);
var CUSTOM_VALUE = '__custom__';
var oldKey = @json(old('key', ''));

/* ── Elements ── */
var sectionSelect   = document.getElementById('section_id');
var keySelect       = document.getElementById('key-select');
var keyHidden       = document.getElementById('key');
var keyCustomWrap   = document.getElementById('key-custom-wrap');
var keyCustomInput  = document.getElementById('key-custom-input');
var keyHint         = document.getElementById('key-hint');

/* ── Populate key dropdown when section changes ── */
function populateKeyDropdown(sectionId, preselectKey) {
    keySelect.innerHTML = '';

    var defs = KEY_MAP[sectionId] || [];

    if (defs.length === 0) {
        keySelect.add(new Option('— No predefined keys for this section —', ''));
        showCustom(preselectKey || '');
        return;
    }

    // Placeholder
    keySelect.add(new Option('— Select a key —', ''));

    // Known keys
    defs.forEach(function (def) {
        var label = def.label + '  ·  ' + def.type;
        var opt   = new Option(label, def.key);
        opt.dataset.type = def.type;
        opt.dataset.hint = def.hint;
        keySelect.add(opt);
    });

    // Separator + custom option
    var sep = new Option('──────────────', '', false, false);
    sep.disabled = true;
    keySelect.add(sep);
    keySelect.add(new Option('✏️  Custom key…', CUSTOM_VALUE));

    // Restore previous value if available
    if (preselectKey) {
        var knownOpt = Array.from(keySelect.options).find(function (o) {
            return o.value === preselectKey;
        });

        if (knownOpt) {
            keySelect.value = preselectKey;
            applyKeySelection(preselectKey, false);
        } else {
            keySelect.value = CUSTOM_VALUE;
            keyCustomInput.value = preselectKey;
            showCustom(preselectKey);
        }
    }
}

/* ── Apply a key selection: update hidden field, hint, type radio ── */
function applyKeySelection(keyValue, autoSelectType) {
    if (keyValue === CUSTOM_VALUE || keyValue === '') return;

    keyHidden.value = keyValue;
    keyCustomWrap.style.display = 'none';
    keyCustomInput.value = '';

    // Show hint
    var selected = keySelect.options[keySelect.selectedIndex];
    keyHint.textContent = selected.dataset.hint || '';

    // Auto-select block type
    if (autoSelectType !== false) {
        var blockType = selected.dataset.type || '';
        if (blockType) {
            var radio = document.querySelector('input[name="type"][value="' + blockType + '"]');
            if (radio) {
                radio.checked = true;
                radio.dispatchEvent(new Event('change', { bubbles: true }));
            }
        }
    }
}

function showCustom(currentValue) {
    keyCustomWrap.style.display = 'block';
    keyCustomInput.value = currentValue || '';
    keyHidden.value = currentValue || '';
    keyHint.textContent = 'Enter any key that your Blade template uses.';
}

/* ── Events ── */
sectionSelect.addEventListener('change', function () {
    populateKeyDropdown(this.value, '');
    keyHidden.value = '';
    keyHint.textContent = '';
});

keySelect.addEventListener('change', function () {
    var val = this.value;
    if (val === CUSTOM_VALUE) {
        showCustom('');
        keyCustomInput.focus();
    } else if (val) {
        applyKeySelection(val, true);
    } else {
        keyHidden.value = '';
        keyHint.textContent = '';
        keyCustomWrap.style.display = 'none';
    }
});

keyCustomInput.addEventListener('input', function () {
    keyHidden.value = this.value.trim();
});

/* ── Type picker ── */
function updateUrlVisibility() {
    var checked = document.querySelector('input[name="type"]:checked');
    var urlGroup = document.getElementById('url-group');
    if (!checked || !urlGroup) return;
    var show = checked.value === 'image' || checked.value === 'link';
    urlGroup.style.display = show ? 'block' : 'none';
}

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

/* ── Init: restore state after validation failure ── */
var initSectionId = sectionSelect.value;
if (initSectionId) {
    populateKeyDropdown(initSectionId, oldKey);
}

/* ── Form submit ── */
document.getElementById('cb-form').addEventListener('submit', function (e) {
    // Ensure we have a key value
    if (!keyHidden.value.trim()) {
        e.preventDefault();
        keyHint.textContent = '⚠ Please select or enter a key.';
        keyHint.style.color = '#ef4444';
        keySelect.focus();
        return;
    }
    var btn = document.getElementById('submit-btn');
    btn.classList.add('submitting');
    btn.disabled = true;
});

})();
</script>
@endpush
