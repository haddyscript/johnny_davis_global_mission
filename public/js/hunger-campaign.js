/* ============================================================
   HUNGER CAN'T WAIT! — monthly partner tracker
   Refreshes the tracker while the page is open. The Stripe-verified
   partner count is only shown once it reaches the public threshold
   (100 partners); below it, or if the count can't be read, the
   "Every Partner Makes a Difference" message is shown instead.
============================================================ */
(function () {
  'use strict';

  const tracker = document.querySelector('[data-hcw-tracker]');
  if (!tracker || !window.fetch) return;

  const REFRESH_MS     = 2 * 60 * 1000;
  const messageEl      = tracker.querySelector('[data-hcw-message]');
  const liveEl         = tracker.querySelector('[data-hcw-live]');
  const countEl        = tracker.querySelector('[data-hcw-count]');
  const barEl          = tracker.querySelector('[data-hcw-bar]');
  const barWrap        = tracker.querySelector('.hcw-bar');
  const milestoneLabel = tracker.querySelector('[data-hcw-milestone-label]');
  const milestoneEl    = tracker.querySelector('[data-hcw-milestone]');
  const fmt            = n => Number(n).toLocaleString('en-US');

  function showCount(visible) {
    liveEl.hidden    = !visible;
    messageEl.hidden = visible;
    milestoneLabel.textContent = visible ? 'Next milestone' : 'First milestone';
  }

  function render(data) {
    milestoneEl.textContent = fmt(data.next_milestone);

    if (!data.show_count) {
      showCount(false);
      return;
    }

    const partners = Number(data.active_partners) || 0;

    countEl.textContent = fmt(partners);
    barEl.style.width   = data.percent + '%';
    barWrap.setAttribute('aria-valuenow', String(partners));
    showCount(true);
  }

  async function refresh() {
    if (document.hidden) return;

    try {
      const res  = await fetch(tracker.dataset.progressUrl, { headers: { Accept: 'application/json' } });
      const data = await res.json();

      if (res.ok && data.available) {
        render(data);
      } else {
        showCount(false);
      }
    } catch (e) {
      // Network hiccup: keep what is on screen
    }
  }

  setInterval(refresh, REFRESH_MS);
  document.addEventListener('visibilitychange', () => {
    if (!document.hidden) refresh();
  });
})();
