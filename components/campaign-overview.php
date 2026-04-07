<?php
// Variables available: $base (string), $campaigns (array)
?>

<!-- ============================================================
     CAMPAIGN OVERVIEW SCREEN
============================================================ -->
<div id="campaign-overview-screen">
  <p class="screen-label">Make a Difference</p>
  <h1 class="screen-title">Campaigns for Change</h1>
  <p class="screen-sub">Pick one cause to support, read the story, and watch your gift power real impact in the field.</p>

  <div class="campaign-pages-grid">
    <?php foreach ($campaigns as $c): ?>
    <div class="campaign-page-card reveal"
         data-campaign="<?= htmlspecialchars($c['key']) ?>"
         role="button" tabindex="0"
         aria-label="Support <?= htmlspecialchars($c['key']) ?> campaign">
      <div class="campaign-img-area"
           style="background-image:url('<?= $base . htmlspecialchars($c['image']) ?>');"
           role="img"
           aria-label="<?= htmlspecialchars($c['snippet']) ?>"></div>
      <div class="card-body-inner">
        <p class="card-meta"><?= htmlspecialchars($c['label']) ?></p>
        <h3><?= htmlspecialchars($c['key']) ?></h3>
        <small>Goal <?= htmlspecialchars($c['goal']) ?> · <?= $c['pct'] ?>% reached</small>
        <div class="progress" style="margin:6px 0 10px;">
          <div class="progress-fill" style="width:<?= $c['pct'] ?>%;<?= $c['bar_style'] ?>"></div>
        </div>
        <p class="story-snippet"><?= htmlspecialchars($c['snippet']) ?></p>
        <p class="card-goal">Story: <?= htmlspecialchars($c['story']) ?></p>
        <span class="card-cta-link" aria-hidden="true">Support this campaign ›</span>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
</div>
