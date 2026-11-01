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
})();
