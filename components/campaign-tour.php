<?php
// Variables available: $campaigns (array)
?>

<!-- ============================================================
     CAMPAIGN TOUR OVERLAY
============================================================ -->
<div id="campaign-tour-overlay" class="tour-overlay" hidden aria-live="assertive" role="dialog" aria-labelledby="tour-title">
  <div class="tour-backdrop"></div>
  <div class="tour-content">

    <div class="tour-progress">
      <div class="tour-progress-dots">
        <?php foreach ($campaigns as $i => $c): ?>
        <span class="tour-dot <?= $i === 0 ? 'active' : '' ?>" data-step="<?= $i ?>"></span>
        <?php endforeach; ?>
      </div>
      <button class="tour-skip" aria-label="Skip tour">Skip Tour</button>
    </div>

    <?php foreach ($campaigns as $i => $c): ?>
    <div class="tour-step <?= $i === 0 ? 'active' : '' ?>" data-step="<?= $i ?>">
      <div class="tour-header">
        <span class="tour-icon"><?= $c['tour_icon'] ?></span>
        <h3 <?= $i === 0 ? 'id="tour-title"' : '' ?>><?= htmlspecialchars($c['tour_num']) ?>: <?= htmlspecialchars($c['key']) ?></h3>
      </div>
      <div class="tour-meta">
        <span class="tour-status <?= $c['status'] ?>"><?= htmlspecialchars($c['label']) ?></span>
        <span class="tour-goal">Goal <?= htmlspecialchars($c['goal']) ?> · <?= $c['pct'] ?>% reached</span>
      </div>
      <p class="tour-description"><?= htmlspecialchars($c['snippet']) ?></p>
      <p class="tour-story"><?= htmlspecialchars($c['story']) ?></p>
      <div class="tour-actions">
        <?php if ($i > 0): ?>
        <button class="tour-btn tour-prev" aria-label="Go to previous campaign">
          <span class="btn-icon">←</span> Previous
        </button>
        <?php endif; ?>
        <?php if ($i < count($campaigns) - 1): ?>
        <button class="tour-btn tour-next" aria-label="Go to next campaign">
          Next Campaign → <span class="btn-icon"><?= $campaigns[$i + 1]['tour_icon'] ?></span>
        </button>
        <?php else: ?>
        <button class="tour-btn tour-cta" aria-label="Choose a campaign to support">
          Choose a Campaign to Support <span class="btn-icon">🎯</span>
        </button>
        <?php endif; ?>
      </div>
    </div>
    <?php endforeach; ?>

    <div class="tour-footer">
      <p class="tour-instruction">Use arrow keys to navigate • Press Escape or click outside to close</p>
    </div>

    <button class="tour-close" aria-label="Close tour">✕</button>
  </div>
</div>
