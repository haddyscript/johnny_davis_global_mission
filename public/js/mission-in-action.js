/* ============================================================
   OUR MISSION IN ACTION — click-to-play TikTok videos
   Each video starts as a thumbnail. TikTok's player is only loaded
   when a visitor presses play, and the thumbnail stays on top (with
   a spinner) until the player has finished loading. Starting a new
   video unloads the previous one so two never play at once.
============================================================ */
(function () {
  'use strict';

  const facades     = document.querySelectorAll('[data-mia-play]');
  const REVEAL_MS   = 700;   // TikTok shows its own logo briefly after the frame loads
  const FALLBACK_MS = 8000;  // reveal anyway if the load event never fires

  function reset(facade) {
    const frame = facade.parentElement.querySelector('iframe');
    if (frame) frame.remove();
    facade.hidden = false;
    facade.classList.remove('is-loading', 'is-revealed');
  }

  function reveal(facade) {
    if (facade.classList.contains('is-revealed')) return;
    facade.classList.add('is-revealed');
    setTimeout(() => { facade.hidden = true; }, 300);
  }

  function play(facade) {
    if (facade.classList.contains('is-loading')) return;

    facades.forEach(other => { if (other !== facade) reset(other); });

    const frame = document.createElement('iframe');
    frame.src   = facade.dataset.src;
    frame.title = facade.dataset.title;
    frame.allow = 'autoplay; encrypted-media; fullscreen; picture-in-picture';
    frame.setAttribute('allowfullscreen', '');

    facade.classList.add('is-loading');
    frame.addEventListener('load', () => setTimeout(() => reveal(facade), REVEAL_MS));
    setTimeout(() => reveal(facade), FALLBACK_MS);

    facade.parentElement.insertBefore(frame, facade);
  }

  facades.forEach(facade => facade.addEventListener('click', () => play(facade)));

  /* Videos 1–6: picking one from the row swaps it into the large spot,
     and the video it replaces goes back into the row. */
  const stage  = document.querySelector('[data-mia-stage]');
  const panels = document.querySelectorAll('[data-mia-panel]');
  const picks  = document.querySelectorAll('[data-mia-pick]');
  const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)');

  function show(number) {
    facades.forEach(reset);
    if (stage) stage.querySelectorAll('video').forEach(video => video.pause());

    panels.forEach(panel => {
      const isActive = panel.dataset.miaPanel === number;
      panel.hidden = !isActive;
      panel.classList.remove('is-entering');
      if (isActive) {
        void panel.offsetWidth; // restart the entrance animation
        panel.classList.add('is-entering');
      }
    });

    picks.forEach(pick => { pick.parentElement.hidden = pick.dataset.miaPick === number; });

    if (stage && stage.getBoundingClientRect().top < 80) {
      stage.scrollIntoView({ behavior: reducedMotion.matches ? 'auto' : 'smooth', block: 'start' });
    }

    const title = document.getElementById('mia-video-' + number);
    if (title) title.focus({ preventScroll: true });
  }

  picks.forEach(pick => pick.addEventListener('click', () => show(pick.dataset.miaPick)));
})();
