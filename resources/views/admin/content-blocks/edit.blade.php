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

                <div class="pf-group {{ $errors->has('key') ? 'has-error' : '' }}" id="key-group">
                    <label class="pf-label" for="key-select">
                        Key <span class="pf-required">*</span>
                    </label>

                    <select id="key-select" class="pf-input" style="margin-bottom:6px;">
                        <option value="">— Loading keys… —</option>
                    </select>

                    <div id="key-custom-wrap" style="display:none;">
                        <input id="key-custom-input" type="text" class="pf-input"
                            placeholder="Type your custom key…" autocomplete="off">
                    </div>

                    <div id="key-hint" style="font-size:12px;color:var(--text-muted);margin-top:4px;min-height:18px;"></div>

                    <input id="key" name="key" type="hidden"
                        value="{{ old('key', $contentBlock->key) }}" required>

                    @error('key')<div class="pf-error">⚠ {{ $message }}</div>@enderror
                </div>

                {{-- Plain content field (text / image / link) --}}
                <div class="pf-group {{ $errors->has('content') ? 'has-error' : '' }}" id="content-group">
                    <label class="pf-label" for="content">Content</label>
                    <textarea id="content" name="content" class="pf-input pf-textarea" rows="5"
                        placeholder="Enter text, alt text, or list items…">{{ old('content', $contentBlock->content) }}</textarea>
                    @error('content')<div class="pf-error">⚠ {{ $message }}</div>@enderror
                </div>

                {{-- List item editor (list type only) --}}
                <div class="pf-group" id="list-editor-group" style="display:none;">
                    <label class="pf-label">List Items</label>
                    <div id="list-items-wrap"></div>
                    <button type="button" id="add-list-item" class="admin-btn-secondary" style="margin-top:10px;font-size:.85rem;padding:7px 16px;">
                        + Add Item
                    </button>
                    <input type="hidden" id="extra-hidden" name="extra">
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
'use strict';

/* ── Key map ── */
var KEY_MAP       = @json($keyMap);
var CUSTOM_VALUE  = '__custom__';
var currentKey    = @json(old('key', $contentBlock->key));

/* ── Elements ── */
var sectionSelect  = document.getElementById('section_id');
var keySelect      = document.getElementById('key-select');
var keyHidden      = document.getElementById('key');
var keyCustomWrap  = document.getElementById('key-custom-wrap');
var keyCustomInput = document.getElementById('key-custom-input');
var keyHint        = document.getElementById('key-hint');

function populateKeyDropdown(sectionId, preselectKey) {
    keySelect.innerHTML = '';
    var defs = KEY_MAP[sectionId] || [];

    if (defs.length === 0) {
        keySelect.add(new Option('— No predefined keys for this section —', ''));
        showCustom(preselectKey || '');
        return;
    }

    keySelect.add(new Option('— Select a key —', ''));

    defs.forEach(function (def) {
        var opt = new Option(def.label + '  ·  ' + def.type, def.key);
        opt.dataset.type = def.type;
        opt.dataset.hint = def.hint;
        keySelect.add(opt);
    });

    var sep = new Option('──────────────', '', false, false);
    sep.disabled = true;
    keySelect.add(sep);
    keySelect.add(new Option('✏️  Custom key…', CUSTOM_VALUE));

    if (preselectKey) {
        var knownOpt = Array.from(keySelect.options).find(function (o) {
            return o.value === preselectKey;
        });
        if (knownOpt) {
            keySelect.value = preselectKey;
            applyKeySelection(preselectKey, false); // don't override type on edit load
        } else {
            keySelect.value = CUSTOM_VALUE;
            showCustom(preselectKey);
        }
    }
}

function applyKeySelection(keyValue, autoSelectType) {
    if (keyValue === CUSTOM_VALUE || keyValue === '') return;
    keyHidden.value = keyValue;
    keyCustomWrap.style.display = 'none';
    keyCustomInput.value = '';

    var selected = keySelect.options[keySelect.selectedIndex];
    keyHint.textContent = selected.dataset.hint || '';

    if (autoSelectType) {
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
    var checked    = document.querySelector('input[name="type"]:checked');
    var urlGroup   = document.getElementById('url-group');
    var contentGrp = document.getElementById('content-group');
    var listGrp    = document.getElementById('list-editor-group');
    if (!checked) return;
    var isList = checked.value === 'list';
    if (urlGroup)   urlGroup.style.display   = (checked.value === 'image' || checked.value === 'link') ? 'block' : 'none';
    if (contentGrp) contentGrp.style.display = isList ? 'none' : 'block';
    if (listGrp)    listGrp.style.display    = isList ? 'block' : 'none';
}

document.querySelectorAll('.type-pick-option input').forEach(function (radio) {
    radio.addEventListener('change', function () {
        document.querySelectorAll('.type-pick-option').forEach(function (opt) { opt.classList.remove('selected'); });
        radio.closest('.type-pick-option').classList.add('selected');
        updateUrlVisibility();
    });
});

/* ── List item editor ── */
var EXISTING_EXTRA = @json($contentBlock->extra ?? []);
var listWrap       = document.getElementById('list-items-wrap');
var extraHidden    = document.getElementById('extra-hidden');

function escHtml(s) {
    return String(s || '').replace(/&/g,'&amp;').replace(/"/g,'&quot;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
}
function makeRow(value, label) {
    var row = document.createElement('div');
    row.className = 'list-item-row';
    row.style.cssText = 'display:flex;gap:10px;align-items:center;margin-bottom:8px;';
    row.innerHTML =
        '<input type="text" class="pf-input item-value" placeholder="Value (e.g. 5,000+)" value="' + escHtml(value) + '" style="flex:1;">' +
        '<input type="text" class="pf-input item-label" placeholder="Label (e.g. Lives Touched)" value="' + escHtml(label) + '" style="flex:2;">' +
        '<button type="button" title="Remove" style="background:none;border:none;cursor:pointer;font-size:1.1rem;color:#ef4444;padding:0 6px;flex-shrink:0;">✕</button>';
    row.querySelector('button').addEventListener('click', function () { row.remove(); });
    return row;
}
function populateList(items) {
    listWrap.innerHTML = '';
    (items || []).forEach(function (it) { listWrap.appendChild(makeRow(it.value || '', it.label || '')); });
    if (!items || !items.length) listWrap.appendChild(makeRow('', ''));
}

document.getElementById('add-list-item').addEventListener('click', function () {
    listWrap.appendChild(makeRow('', ''));
});

populateList(EXISTING_EXTRA);

/* ── Init ── */
populateKeyDropdown(sectionSelect.value, currentKey);
updateUrlVisibility();

/* ── Form submit guard ── */
document.getElementById('cb-form').addEventListener('submit', function (e) {
    if (!keyHidden.value.trim()) {
        e.preventDefault();
        keyHint.textContent = '⚠ Please select or enter a key.';
        keyHint.style.color = '#ef4444';
        keySelect.focus();
        return;
    }
    var checked = document.querySelector('input[name="type"]:checked');
    if (checked && checked.value === 'list') {
        var items = [];
        listWrap.querySelectorAll('.list-item-row').forEach(function (row) {
            var v = row.querySelector('.item-value').value.trim();
            var l = row.querySelector('.item-label').value.trim();
            if (v || l) items.push({ value: v, label: l });
        });
        extraHidden.value = JSON.stringify(items);
    }
    var btn = document.getElementById('submit-btn');
    btn.classList.add('submitting');
    btn.disabled = true;
});

/* ── Delete modal ── */
var modal = document.getElementById('delete-modal');
document.getElementById('danger-btn').addEventListener('click', function () { modal.classList.add('modal-open'); });
document.getElementById('modal-cancel').addEventListener('click', function () { modal.classList.remove('modal-open'); });
modal.addEventListener('click', function (e) { if (e.target === modal) modal.classList.remove('modal-open'); });
document.addEventListener('keydown', function (e) { if (e.key === 'Escape') modal.classList.remove('modal-open'); });

})();
</script>
@endpush
