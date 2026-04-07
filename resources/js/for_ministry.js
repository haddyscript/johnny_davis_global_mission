 const navbar = document.getElementById('navbar');
  window.addEventListener('scroll', () => {
    navbar.classList.toggle('scrolled', window.scrollY > 20);
  }, { passive: true });

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