/* Faith in Action — Philippines mission gallery lightbox (previous/next, keyboard, swipe). */
(function () {
  var data = document.getElementById('pmgPhotos');
  var lightbox = document.getElementById('pmgLightbox');
  if (!data || !lightbox) { return; }

  var photos = JSON.parse(data.textContent);
  var img = document.getElementById('pmgLightboxImg');
  var caption = document.getElementById('pmgLightboxCaption');
  var count = document.getElementById('pmgLightboxCount');
  var closeBtn = lightbox.querySelector('.pmg-lightbox-close');
  var current = 0;
  var opener = null;
  var touchStartX = null;

  function show(index) {
    current = (index + photos.length) % photos.length;
    img.src = photos[current].src;
    img.alt = photos[current].alt;
    caption.textContent = photos[current].caption;
    count.textContent = (current + 1) + ' / ' + photos.length;
  }

  function open(index, trigger) {
    opener = trigger;
    show(index);
    lightbox.hidden = false;
    requestAnimationFrame(function () { lightbox.classList.add('open'); });
    document.body.style.overflow = 'hidden';
    closeBtn.focus();
  }

  function close() {
    lightbox.classList.remove('open');
    document.body.style.overflow = '';
    setTimeout(function () { lightbox.hidden = true; img.src = ''; }, 250);
    if (opener) { opener.focus(); }
  }

  document.querySelectorAll('.pmg-open').forEach(function (button) {
    button.addEventListener('click', function () {
      open(parseInt(button.getAttribute('data-pmg-index'), 10), button);
    });
  });

  lightbox.querySelectorAll('[data-pmg-close]').forEach(function (el) {
    el.addEventListener('click', close);
  });
  document.getElementById('pmgLightboxPrev').addEventListener('click', function () { show(current - 1); });
  document.getElementById('pmgLightboxNext').addEventListener('click', function () { show(current + 1); });

  document.addEventListener('keydown', function (e) {
    if (lightbox.hidden) { return; }
    if (e.key === 'Escape') { close(); }
    if (e.key === 'ArrowLeft') { show(current - 1); }
    if (e.key === 'ArrowRight') { show(current + 1); }
  });

  lightbox.addEventListener('touchstart', function (e) { touchStartX = e.touches[0].clientX; }, { passive: true });
  lightbox.addEventListener('touchend', function (e) {
    if (touchStartX === null) { return; }
    var dx = e.changedTouches[0].clientX - touchStartX;
    touchStartX = null;
    if (Math.abs(dx) > 50) { show(current + (dx < 0 ? 1 : -1)); }
  });
}());
