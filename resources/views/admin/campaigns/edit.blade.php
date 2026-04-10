@extends('layouts.admin')

@section('title', 'Edit — ' . $campaign->title)
@section('page-title', 'Edit Campaign')

@section('content')

<div class="page-form-shell">

    <a href="{{ route('admin.campaigns.index') }}" class="form-back-link">← Back to Campaigns</a>

    <form id="campaign-form" method="POST" action="{{ route('admin.campaigns.update', $campaign) }}" novalidate>
        @csrf
        @method('PUT')

        <div class="page-form-grid" style="grid-template-columns:1fr 340px;">

            {{-- ── LEFT: Main fields ── --}}
            <div>

                {{-- Basic Info --}}
                <div class="form-section">
                    <div class="form-section-header">
                        <span class="form-section-icon">✏️</span>
                        <div>
                            <div class="form-section-title">Campaign Details</div>
                            <div class="form-section-sub">Editing: <strong>{{ $campaign->title }}</strong></div>
                        </div>
                    </div>

                    <div style="display:grid;grid-template-columns:1fr 80px;gap:16px;">
                        <div class="pf-group {{ $errors->has('title') ? 'has-error' : '' }}">
                            <label class="pf-label" for="title">Campaign Title <span class="pf-required">*</span></label>
                            <input id="title" name="title" type="text" class="pf-input"
                                value="{{ old('title', $campaign->title) }}" placeholder="e.g. Feed Filipino Children" required autocomplete="off">
                            @error('title') <div class="pf-error">⚠ {{ $message }}</div> @enderror
                        </div>
                        <div class="pf-group {{ $errors->has('icon') ? 'has-error' : '' }}">
                            <label class="pf-label" for="icon">Icon</label>
                            <input id="icon" name="icon" type="text" class="pf-input"
                                value="{{ old('icon', $campaign->icon) }}" placeholder="🎯" style="text-align:center;font-size:20px;" maxlength="10">
                            @error('icon') <div class="pf-error">⚠ {{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
                        <div class="pf-group {{ $errors->has('label') ? 'has-error' : '' }}">
                            <label class="pf-label" for="label">
                                Badge Label
                                <span class="pf-hint">e.g. "Active Campaign", "Urgent Relief"</span>
                            </label>
                            <input id="label" name="label" type="text" class="pf-input"
                                value="{{ old('label', $campaign->label) }}" placeholder="Active Campaign" autocomplete="off">
                            @error('label') <div class="pf-error">⚠ {{ $message }}</div> @enderror
                        </div>
                        <div class="pf-group {{ $errors->has('status_class') ? 'has-error' : '' }}">
                            <label class="pf-label" for="status_class">
                                Status CSS Class
                                <span class="pf-hint">Controls badge colour.</span>
                            </label>
                            <input id="status_class" name="status_class" type="text" class="pf-input"
                                value="{{ old('status_class', $campaign->status_class) }}" placeholder="active-campaign" autocomplete="off">
                            @error('status_class') <div class="pf-error">⚠ {{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="pf-group {{ $errors->has('subtitle') ? 'has-error' : '' }}">
                        <label class="pf-label" for="subtitle">
                            Subtitle
                            <span class="pf-hint">Short tagline shown below the title in the donation form.</span>
                        </label>
                        <input id="subtitle" name="subtitle" type="text" class="pf-input"
                            value="{{ old('subtitle', $campaign->subtitle) }}" placeholder="e.g. $7.99/month feeds one child" autocomplete="off">
                        @error('subtitle') <div class="pf-error">⚠ {{ $message }}</div> @enderror
                    </div>

                    <div class="pf-group {{ $errors->has('snippet') ? 'has-error' : '' }}">
                        <label class="pf-label" for="snippet">
                            Card Description
                            <span class="pf-hint">Paragraph shown on the campaign card. Max 500 chars.</span>
                        </label>
                        <textarea id="snippet" name="snippet" class="pf-input pf-textarea" rows="3"
                            placeholder="Brief description…" maxlength="500">{{ old('snippet', $campaign->snippet) }}</textarea>
                        @error('snippet') <div class="pf-error">⚠ {{ $message }}</div> @enderror
                    </div>

                    <div class="pf-group {{ $errors->has('story') ? 'has-error' : '' }}">
                        <label class="pf-label" for="story">
                            Pull Quote / Story
                            <span class="pf-hint">Short quote shown on the card.</span>
                        </label>
                        <input id="story" name="story" type="text" class="pf-input"
                            value="{{ old('story', $campaign->story) }}" placeholder='"A story from the field."' autocomplete="off">
                        @error('story') <div class="pf-error">⚠ {{ $message }}</div> @enderror
                    </div>
                </div>

                {{-- Progress --}}
                <div class="form-section" style="margin-top:20px;">
                    <div class="form-section-header">
                        <span class="form-section-icon">📊</span>
                        <div>
                            <div class="form-section-title">Fundraising Progress</div>
                            <div class="form-section-sub">Goal, amounts, and progress bar settings.</div>
                        </div>
                    </div>

                    <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:16px;">
                        <div class="pf-group {{ $errors->has('goal_amount') ? 'has-error' : '' }}">
                            <label class="pf-label" for="goal_amount">Goal Amount</label>
                            <input id="goal_amount" name="goal_amount" type="text" class="pf-input"
                                value="{{ old('goal_amount', $campaign->goal_amount) }}" placeholder="$45,000" autocomplete="off">
                            @error('goal_amount') <div class="pf-error">⚠ {{ $message }}</div> @enderror
                        </div>
                        <div class="pf-group {{ $errors->has('raised_amount') ? 'has-error' : '' }}">
                            <label class="pf-label" for="raised_amount">Raised So Far</label>
                            <input id="raised_amount" name="raised_amount" type="text" class="pf-input"
                                value="{{ old('raised_amount', $campaign->raised_amount) }}" placeholder="$32,480" autocomplete="off">
                            @error('raised_amount') <div class="pf-error">⚠ {{ $message }}</div> @enderror
                        </div>
                        <div class="pf-group {{ $errors->has('goal_pct') ? 'has-error' : '' }}">
                            <label class="pf-label" for="goal_pct">Progress % <span class="pf-required">*</span></label>
                            <input id="goal_pct" name="goal_pct" type="number" class="pf-input"
                                value="{{ old('goal_pct', $campaign->goal_pct) }}" min="0" max="100" placeholder="72">
                            @error('goal_pct') <div class="pf-error">⚠ {{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="pf-group {{ $errors->has('meta') ? 'has-error' : '' }}">
                        <label class="pf-label" for="meta">
                            Meta Line
                            <span class="pf-hint">Summary line below the progress bar.</span>
                        </label>
                        <input id="meta" name="meta" type="text" class="pf-input"
                            value="{{ old('meta', $campaign->meta) }}" placeholder="$32,480 raised of $45,000 goal · 72% funded" autocomplete="off">
                        @error('meta') <div class="pf-error">⚠ {{ $message }}</div> @enderror
                    </div>

                    <div class="pf-group {{ $errors->has('bar_style') ? 'has-error' : '' }}">
                        <label class="pf-label" for="bar_style">
                            Progress Bar Style Override
                            <span class="pf-hint">Optional inline CSS. Leave blank for default teal.</span>
                        </label>
                        <input id="bar_style" name="bar_style" type="text" class="pf-input"
                            value="{{ old('bar_style', $campaign->bar_style) }}" placeholder="background:linear-gradient(…);" autocomplete="off">
                        @error('bar_style') <div class="pf-error">⚠ {{ $message }}</div> @enderror
                    </div>
                </div>

                {{-- Story --}}
                <div class="form-section" style="margin-top:20px;">
                    <div class="form-section-header">
                        <span class="form-section-icon">📖</span>
                        <div>
                            <div class="form-section-title">Donation Form Story</div>
                            <div class="form-section-sub">Shown in the "Campaign Story" card when this campaign is selected.</div>
                        </div>
                    </div>

                    <div class="pf-group {{ $errors->has('story_full') ? 'has-error' : '' }}">
                        <label class="pf-label" for="story_full">Full Story</label>
                        <textarea id="story_full" name="story_full" class="pf-input pf-textarea" rows="4"
                            placeholder="Full story paragraph…">{{ old('story_full', $campaign->story_full) }}</textarea>
                        @error('story_full') <div class="pf-error">⚠ {{ $message }}</div> @enderror
                    </div>

                    <div class="pf-group {{ $errors->has('goal_full') ? 'has-error' : '' }}">
                        <label class="pf-label" for="goal_full">Goal Description Line</label>
                        <input id="goal_full" name="goal_full" type="text" class="pf-input"
                            value="{{ old('goal_full', $campaign->goal_full) }}" placeholder="Goal: $45,000 to reach 100+ children for full school year" autocomplete="off">
                        @error('goal_full') <div class="pf-error">⚠ {{ $message }}</div> @enderror
                    </div>
                </div>

            </div>

            {{-- ── RIGHT: Sidebar ── --}}
            <div class="form-sidebar">

                {{-- Settings --}}
                <div class="form-section">
                    <div class="form-section-header">
                        <span class="form-section-icon">⚙️</span>
                        <div>
                            <div class="form-section-title">Settings</div>
                        </div>
                    </div>

                    <div class="pf-group">
                        <label class="pf-label">Status</label>
                        <label class="status-toggle-row" for="is_active">
                            <div class="toggle-switch-wrap">
                                <input type="checkbox" id="is_active" name="is_active" value="1"
                                    class="toggle-checkbox" {{ old('is_active', $campaign->is_active) ? 'checked' : '' }}>
                                <span class="toggle-slider"></span>
                            </div>
                            <div>
                                <div class="toggle-status-text" id="status-text">Active</div>
                                <div style="font-size:12px;color:var(--text-muted);margin-top:2px;">Shown on the donate page</div>
                            </div>
                        </label>
                    </div>

                    <div class="pf-group {{ $errors->has('sort_order') ? 'has-error' : '' }}" style="margin-top:12px;">
                        <label class="pf-label" for="sort_order">
                            Display Order
                            <span class="pf-hint">Lower numbers appear first.</span>
                        </label>
                        <input id="sort_order" name="sort_order" type="number" class="pf-input"
                            value="{{ old('sort_order', $campaign->sort_order) }}" min="0" placeholder="1">
                        @error('sort_order') <div class="pf-error">⚠ {{ $message }}</div> @enderror
                    </div>

                    <div style="margin-top:16px;padding:12px;background:var(--surface-strong);border-radius:12px;font-size:12px;color:var(--text-muted);line-height:1.8;">
                        <div>Created: {{ $campaign->created_at->format('M j, Y g:i A') }}</div>
                        <div>Updated: {{ $campaign->updated_at->format('M j, Y g:i A') }}</div>
                    </div>
                </div>

                {{-- Live progress preview --}}
                <div class="form-section" style="margin-top:0;">
                    <div class="form-section-header">
                        <span class="form-section-icon">📊</span>
                        <div><div class="form-section-title">Progress Preview</div></div>
                    </div>
                    <div>
                        <div style="display:flex;justify-content:space-between;font-size:12px;margin-bottom:6px;">
                            <span id="prev-raised" style="color:var(--text-muted);">{{ $campaign->raised_amount ?? '—' }}</span>
                            <span id="prev-pct" style="font-weight:700;color:#0f766e;">{{ $campaign->goal_pct }}%</span>
                        </div>
                        <div style="height:8px;background:var(--surface-strong);border-radius:99px;overflow:hidden;">
                            <div id="prev-bar" style="height:100%;width:{{ $campaign->goal_pct }}%;border-radius:99px;{{ $campaign->bar_style ?: 'background:linear-gradient(90deg,#0f766e,#14b8a6);' }}transition:width .3s;"></div>
                        </div>
                        <div id="prev-goal" style="font-size:11px;color:var(--text-muted);margin-top:4px;">Goal: {{ $campaign->goal_amount ?? '—' }}</div>
                    </div>
                </div>

                {{-- Form actions --}}
                <div class="form-actions-card">
                    <button type="submit" class="admin-btn pf-submit-btn" id="submit-btn">
                        <span class="btn-text">Save Changes</span>
                        <span class="btn-spinner"></span>
                    </button>
                    <a href="{{ route('admin.campaigns.index') }}" class="admin-btn-secondary pf-cancel-btn">Cancel</a>
                </div>

            </div>
        </div>
    </form>
</div>

@endsection

@push('scripts')
<script>
(function () {
var checkbox   = document.getElementById('is_active');
var statusText = document.getElementById('status-text');
function syncStatus() {
    statusText.textContent = checkbox.checked ? 'Active' : 'Inactive';
    statusText.style.color = checkbox.checked ? '#059669' : '#64748b';
}
checkbox.addEventListener('change', syncStatus);
syncStatus();

var pctInput    = document.getElementById('goal_pct');
var raisedInput = document.getElementById('raised_amount');
var goalInput   = document.getElementById('goal_amount');
var barEl       = document.getElementById('prev-bar');
var pctEl       = document.getElementById('prev-pct');
var raisedEl    = document.getElementById('prev-raised');
var goalEl      = document.getElementById('prev-goal');

function updatePreview() {
    var pct = Math.min(Math.max(parseInt(pctInput.value, 10) || 0, 0), 100);
    barEl.style.width    = pct + '%';
    pctEl.textContent    = pct + '%';
    raisedEl.textContent = raisedInput.value || '—';
    goalEl.textContent   = 'Goal: ' + (goalInput.value || '—');
}

[pctInput, raisedInput, goalInput].forEach(function (el) { el.addEventListener('input', updatePreview); });

document.getElementById('campaign-form').addEventListener('submit', function () {
    var btn = document.getElementById('submit-btn');
    btn.classList.add('submitting'); btn.disabled = true;
});
})();
</script>
@endpush
