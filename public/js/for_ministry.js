 const navbar = document.getElementById('navbar');
  window.addEventListener('scroll', () => {
    navbar.classList.toggle('scrolled', window.scrollY > 20);
  }, { passive: true });

  // ─── Mobile nav toggle ───────────────────────────────────────
  const navToggle = document.getElementById('navToggle');
  const navMobile = document.getElementById('navMobile');
  if (navToggle && navMobile) {
    navToggle.addEventListener('click', () => {
      const open = navMobile.classList.toggle('open');
      navToggle.classList.toggle('open', open);
      navToggle.setAttribute('aria-expanded', String(open));
    });
    navMobile.querySelectorAll('a').forEach(link => {
      link.addEventListener('click', () => {
        navMobile.classList.remove('open');
        navToggle.classList.remove('open');
        navToggle.setAttribute('aria-expanded', 'false');
      });
    });
  }

  // ─── Hero Slider ─────────────────────────────────────────────
  const slides = document.querySelectorAll('.slide');
  const dots   = document.querySelectorAll('.slider-dot');
  let current  = 0;
  let autoSlide;

  function goToSlide(index) {
    slides[current].classList.remove('active');
    dots[current].classList.remove('active');
    current = (index + slides.length) % slides.length;
    slides[current].classList.add('active');
    dots[current].classList.add('active');
  }

  function startAuto() {
    autoSlide = setInterval(() => goToSlide(current + 1), 5500);
  }
  function stopAuto() { clearInterval(autoSlide); }

  document.getElementById('sliderNext').addEventListener('click', () => {
    stopAuto(); goToSlide(current + 1); startAuto();
  });
  document.getElementById('sliderPrev').addEventListener('click', () => {
    stopAuto(); goToSlide(current - 1); startAuto();
  });
  dots.forEach(dot => {
    dot.addEventListener('click', () => {
      stopAuto(); goToSlide(Number(dot.dataset.index)); startAuto();
    });
  });

  startAuto();

  // Pause on hover
  const sliderTrack = document.getElementById('sliderTrack');
  sliderTrack.addEventListener('mouseenter', stopAuto);
  sliderTrack.addEventListener('mouseleave', startAuto);

  // ─── Scroll reveal ───────────────────────────────────────────
  const revealEls = document.querySelectorAll('.reveal, .reveal-left, .reveal-right');
  const revealObs = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.classList.add('visible');
        revealObs.unobserve(entry.target);
      }
    });
  }, { threshold: 0.10, rootMargin: '0px 0px -40px 0px' });
  revealEls.forEach(el => revealObs.observe(el));

  // ─── Video modal (Daily Push) ────────────────────────────────
  const videoModal = document.getElementById('videoModal');
  document.getElementById('modalClose').addEventListener('click', () => {
    videoModal.classList.remove('open');
    document.body.style.overflow = '';
  });
  videoModal.addEventListener('click', e => {
    if (e.target === videoModal) {
      videoModal.classList.remove('open');
      document.body.style.overflow = '';
    }
  });
  document.addEventListener('keydown', e => {
    if (e.key === 'Escape') {
      videoModal.classList.remove('open');
      document.body.style.overflow = '';
    }
  });

  function openVideoModal(topic) {
    videoModal.classList.add('open');
    document.body.style.overflow = 'hidden';
  }

  // ─── Hunger parallax ─────────────────────────────────────────
  const hungerBg = document.querySelector('.hunger-bg');
  const inspireBg = document.querySelector('.inspire-bg');
  window.addEventListener('scroll', () => {
    if (hungerBg) {
      const rect = hungerBg.parentElement.getBoundingClientRect();
      if (rect.top < window.innerHeight && rect.bottom > 0) {
        const ratio = (window.innerHeight - rect.top) / (window.innerHeight + rect.height);
        hungerBg.style.transform = `translateY(${(ratio - 0.5) * 40}px)`;
      }
    }
    if (inspireBg) {
      const rect = inspireBg.parentElement.getBoundingClientRect();
      if (rect.top < window.innerHeight && rect.bottom > 0) {
        const ratio = (window.innerHeight - rect.top) / (window.innerHeight + rect.height);
        inspireBg.style.transform = `translateY(${(ratio - 0.5) * 40}px)`;
      }
    }
  }, { passive: true });

  // ─── Mobile Enhancements (touch devices / narrow viewports) ─────────
  (function initMobileEnhancements() {
    if (window.innerWidth > 768) return;

    /* ── Daily Push: inject swipe hint + progress dots ── */
    var dpSection  = document.getElementById('daily-push');
    var dpGrid     = dpSection  && dpSection.querySelector('.dp-grid');
    var dpCards    = dpGrid     && dpGrid.querySelectorAll('.dp-card');
    var dpFooter   = dpSection  && dpSection.querySelector('.dp-footer');

    if (dpGrid && dpCards && dpCards.length && dpFooter) {
      var dpHint = document.createElement('p');
      dpHint.className = 'dp-swipe-hint';
      dpHint.setAttribute('aria-hidden', 'true');
      dpHint.innerHTML =
        '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="currentColor">' +
        '<path d="M8.59 16.59L13.17 12 8.59 7.41 10 6l6 6-6 6z"/></svg>' +
        'Swipe to explore';
      dpGrid.parentNode.insertBefore(dpHint, dpGrid);

      var dpProg = document.createElement('div');
      dpProg.className = 'dp-progress';
      dpProg.setAttribute('aria-hidden', 'true');
      for (var i = 0; i < dpCards.length; i++) {
        var dot = document.createElement('div');
        dot.className = 'dp-progress-dot' + (i === 0 ? ' active' : '');
        dpProg.appendChild(dot);
      }
      dpGrid.parentNode.insertBefore(dpProg, dpFooter);

      var progDots = dpProg.querySelectorAll('.dp-progress-dot');
      dpGrid.addEventListener('scroll', function () {
        var gap   = 14;
        var cardW = dpCards[0].offsetWidth + gap;
        var idx   = Math.min(Math.round(dpGrid.scrollLeft / cardW), dpCards.length - 1);
        progDots.forEach(function (d, i) { d.classList.toggle('active', i === idx); });
      }, { passive: true });

      dpGrid.addEventListener('scroll', function hideHint() {
        dpHint.style.opacity = '0';
        dpHint.style.transition = 'opacity .4s ease';
        dpGrid.removeEventListener('scroll', hideHint);
      }, { passive: true, once: true });
    }

    /* ── Events: inject swipe hint ── */
    var eventsSection = document.getElementById('events');
    var eventsGrid    = eventsSection && eventsSection.querySelector('.events-grid');
    if (eventsGrid) {
      var evHint = document.createElement('p');
      evHint.className = 'events-swipe-hint';
      evHint.setAttribute('aria-hidden', 'true');
      evHint.innerHTML =
        '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="currentColor">' +
        '<path d="M8.59 16.59L13.17 12 8.59 7.41 10 6l6 6-6 6z"/></svg>' +
        'Swipe to see all events';
      eventsGrid.parentNode.insertBefore(evHint, eventsGrid.nextSibling);

      eventsGrid.addEventListener('scroll', function hideEvHint() {
        evHint.style.opacity = '0';
        evHint.style.transition = 'opacity .4s ease';
        eventsGrid.removeEventListener('scroll', hideEvHint);
      }, { passive: true, once: true });
    }

    /* ── Sticky Mobile Bottom Action Bar ── */
    var bar = document.createElement('div');
    bar.id = 'mobileActionBar';
    bar.setAttribute('role', 'navigation');
    bar.setAttribute('aria-label', 'Quick actions');
    bar.innerHTML =
      '<a href="https://www.youtube.com/@johnnydavisministries" target="_blank" rel="noopener noreferrer"' +
      '   class="mob-bar-btn btn-watch" aria-label="Watch Daily Push on YouTube">' +
      '  <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="currentColor">' +
      '    <path d="M8 5v14l11-7z"/></svg>' +
      '  <span>Watch</span>' +
      '</a>' +
      '<button class="mob-bar-btn btn-pray" aria-label="Go to prayer section">' +
      '  <span class="mob-bar-icon" aria-hidden="true">🙏</span>' +
      '  <span>Pray</span>' +
      '</button>' +
      '<a href="/donate" class="mob-bar-btn btn-give" aria-label="Donate now">' +
      '  <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="currentColor">' +
      '    <path d="M12 21.593c-5.63-5.539-11-10.297-11-14.402 0-3.791 3.068-5.191 5.281-5.191 ' +
      '    1.312 0 4.151.501 5.719 4.457 1.59-3.968 4.464-4.447 5.726-4.447 2.54 0 5.274 1.621 ' +
      '    5.274 5.181 0 4.069-5.136 8.625-11 14.402z"/></svg>' +
      '  <span>Give Now</span>' +
      '</a>';
    document.body.appendChild(bar);

    bar.querySelector('.btn-pray').addEventListener('click', function () {
      var target = document.getElementById('daily-push');
      if (target) target.scrollIntoView({ behavior: 'smooth' });
    });

    var heroEl   = document.getElementById('hero');
    var footerEl = document.getElementById('footer');
    var barVisible = false;

    function updateActionBar() {
      var scrollY    = window.scrollY;
      var threshold  = heroEl ? heroEl.offsetHeight * 0.45 : 280;

      if (footerEl) {
        var fRect = footerEl.getBoundingClientRect();
        if (fRect.top < window.innerHeight + 60) {
          if (barVisible) { bar.classList.remove('bar-visible'); barVisible = false; }
          return;
        }
      }

      if (scrollY > threshold) {
        if (!barVisible) { bar.classList.add('bar-visible'); barVisible = true; }
      } else {
        if (barVisible)  { bar.classList.remove('bar-visible'); barVisible = false; }
      }
    }

    window.addEventListener('scroll', updateActionBar, { passive: true });
    updateActionBar();
  })();