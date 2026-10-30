/* ============================================================
   HUNGER CAN'T WAIT! — live monthly partner tracker
   Refreshes the Stripe-verified partner count while the page is
   open. If the count can't be read, the tracker is replaced by a
   plain call-to-action rather than showing a stale or made-up number.
============================================================ */
(function () {
  'use strict';

  const tracker  = document.querySelector('[data-hcw-tracker]');
  const fallback = document.querySelector('[data-hcw-fallback]');
  if (!tracker || !window.fetch) return;

  const REFRESH_MS   = 2 * 60 * 1000;
  const countEl      = tracker.querySelector('[data-hcw-count]');
  const countLabelEl = tracker.querySelector('.hcw-count-label');
  const hasPartners  = tracker.querySelector('[data-hcw-has-partners]');
  const noPartners   = tracker.querySelector('[data-hcw-no-partners]');
  const barEl        = tracker.querySelector('[data-hcw-bar]');
  const barWrap      = tracker.querySelector('.hcw-bar');
  const milestoneEl  = tracker.querySelector('[data-hcw-milestone]');
  const fmt          = n => Number(n).toLocaleString('en-US');

  function showTracker(available) {
    tracker.hidden = !available;
    if (fallback) fallback.hidden = available;
  }

  function render(data) {
    const partners = Number(data.active_partners) || 0;

    countEl.textContent      = fmt(partners);
    countLabelEl.textContent = partners === 1 ? 'active monthly partner' : 'active monthly partners';
    hasPartners.hidden       = partners < 1;
    noPartners.hidden        = partners > 0;
    barEl.style.width        = data.percent + '%';
    barEl.classList.toggle('hcw-bar-fill--started', partners > 0);
    barWrap.setAttribute('aria-valuenow', String(partners));
    milestoneEl.textContent  = fmt(data.next_milestone);
    showTracker(true);
  }

  async function refresh() {
    if (document.hidden) return;

    try {
      const res  = await fetch(tracker.dataset.progressUrl, { headers: { Accept: 'application/json' } });
      const data = await res.json();

      if (res.ok && data.available) {
        render(data);
      } else {
        showTracker(false);
      }
    } catch (e) {
      // Network hiccup: keep the last verified figure on screen
    }
  }

  setInterval(refresh, REFRESH_MS);
  document.addEventListener('visibilitychange', () => {
    if (!document.hidden) refresh();
  });
})();
