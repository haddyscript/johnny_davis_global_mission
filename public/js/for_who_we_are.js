
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