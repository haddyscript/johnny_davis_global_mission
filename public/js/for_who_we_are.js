
  // ─── Navbar scroll shadow ────────────────────────────────────
  const navbar = document.getElementById('navbar');
  window.addEventListener('scroll', () => {
    navbar.classList.toggle('scrolled', window.scrollY > 20);
  }, { passive: true });

  // ─── Hero background Ken Burns ───────────────────────────────
  window.addEventListener('load', () => {
    document.getElementById('heroBg').classList.add('loaded');
  });

  // ─── Mobile nav toggle ───────────────────────────────────────
  const navToggle = document.getElementById('navToggle');
  const navMobile = document.getElementById('navMobile');
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

  // ─── Hero parallax ───────────────────────────────────────────
  const heroBg = document.getElementById('heroBg');
  window.addEventListener('scroll', () => {
    if (heroBg) heroBg.style.transform = `translateY(${window.scrollY * 0.25}px) scale(1.06)`;
  }, { passive: true });

  // ─── Scroll reveal (IntersectionObserver) ────────────────────
  const revealEls = document.querySelectorAll('.reveal, .reveal-left, .reveal-right');
  const revealObs = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.classList.add('visible');
        revealObs.unobserve(entry.target);
      }
    });
  }, { threshold: 0.12, rootMargin: '0px 0px -40px 0px' });

  revealEls.forEach(el => revealObs.observe(el));

  // ─── Hero CTA buttons: ripple + magnetic cursor ──────────────
  (function () {
    const donateBtn = document.querySelector('.wwa-donate-btn');
    const storyBtn  = document.querySelector('.wwa-story-btn');
    const ctaBtns   = [donateBtn, storyBtn].filter(Boolean);

    /* Ripple on click */
    ctaBtns.forEach(btn => {
      btn.addEventListener('click', function (e) {
        const rect   = this.getBoundingClientRect();
        const ripple = document.createElement('span');
        ripple.className  = 'wwa-btn-ripple';
        ripple.style.left = (e.clientX - rect.left) + 'px';
        ripple.style.top  = (e.clientY - rect.top)  + 'px';
        this.appendChild(ripple);
        setTimeout(() => ripple.remove(), 680);
      });
    });

    /* Magnetic cursor follow */
    ctaBtns.forEach(btn => {
      const STRENGTH = 0.22;
      let isHovered  = false;
      btn.addEventListener('mouseenter', () => { isHovered = true; });
      btn.addEventListener('mouseleave', () => {
        isHovered = false;
        btn.style.transform = '';
      });
      btn.addEventListener('mousemove', function (e) {
        if (!isHovered) return;
        const rect = this.getBoundingClientRect();
        const cx = rect.left + rect.width  / 2;
        const cy = rect.top  + rect.height / 2;
        const dx = (e.clientX - cx) * STRENGTH;
        const dy = (e.clientY - cy) * STRENGTH;
        this.style.transform = `translateY(-8px) scale(1.08) translate(${dx}px,${dy}px)`;
      });
    });
  }());

  // ─── Scroll To Top ───────────────────────────────────────────
  (function () {
    const btn = document.createElement('button');
    btn.id = 'scrollToTop';
    btn.setAttribute('aria-label', 'Scroll to top');
    btn.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="18 15 12 9 6 15"/></svg>';
    document.body.appendChild(btn);

    const THRESHOLD = 0.65;
    let ticking = false;
    let isVisible = false;

    function checkScroll() {
      const scrolled   = window.scrollY;
      const total      = document.documentElement.scrollHeight - window.innerHeight;
      const nearBottom = window.innerWidth <= 768 && total > 0 && scrolled / total >= 0.94;
      if (nearBottom) {
        if (isVisible) { isVisible = false; btn.classList.remove('visible'); }
        ticking = false;
        return;
      }
      const show = total > 0 && scrolled / total >= THRESHOLD;
      if (show !== isVisible) {
        isVisible = show;
        btn.classList.toggle('visible', show);
      }
      ticking = false;
    }

    window.addEventListener('scroll', () => {
      if (!ticking) { requestAnimationFrame(checkScroll); ticking = true; }
    }, { passive: true });

    btn.addEventListener('click', () => window.scrollTo({ top: 0, behavior: 'smooth' }));
  }());

/* ─── MOBILE FOOTER ACCORDION ──────────────────────────────── */
(function initFooterAccordion() {
  'use strict';
  if (window.innerWidth > 768) return;

  document.querySelectorAll('.footer-nav-accordion').forEach(function(nav) {
    var trigger = nav.querySelector('.footer-accordion-btn');
    var content = nav.querySelector('.footer-accordion-content');
    if (!trigger || !content) return;

    trigger.addEventListener('click', function() {
      var isOpen = nav.classList.toggle('open');
      trigger.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
    });
  });
}());