<!-- ============================================================
     CAMPAIGN OVERVIEW SCREEN
============================================================ -->
<div id="campaign-overview-screen">
  <p class="screen-label">Make a Difference</p>
  <h1 class="screen-title">Campaigns for Change</h1>
  <p class="screen-sub">Pick one cause to support, read the story, and watch your gift power real impact in the field.</p>

  <div class="campaign-pages-grid">
    @foreach($campaigns as $c)
    <div class="campaign-page-card reveal"
         data-campaign="{{ $c->title }}"
         role="button" tabindex="0"
         aria-label="Support {{ $c->title }} campaign">
      <div class="card-body-inner">
        <p class="card-meta">{{ $c->label }}</p>
        <h3>{{ $c->icon }} {{ $c->title }}</h3>
        <small>Goal {{ $c->goal_amount }} · {{ $c->goal_pct }}% reached</small>
        <div class="progress" style="margin:6px 0 10px;">
          <div class="progress-fill" style="width:{{ $c->goal_pct }}%;{{ $c->bar_style }}"></div>
        </div>
        <p class="story-snippet">{{ $c->snippet }}</p>
        <p class="card-goal">Story: {{ $c->story }}</p>
        <span class="card-cta-link" aria-hidden="true">Support this campaign ›</span>
      </div>
    </div>
    @endforeach
  </div>
</div>
