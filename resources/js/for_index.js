 // ─── Navbar scroll shadow ───────────────────────────────────
  const navbar = document.getElementById('navbar');
  window.addEventListener('scroll', () => {
    navbar.classList.toggle('scrolled', window.scrollY > 20);
  }, { passive: true });

  // ─── Hero background ken burns ──────────────────────────────
  window.addEventListener('load', () => {
    document.getElementById('heroBg').classList.add('loaded');
  });

  // ─── Mobile nav toggle ──────────────────────────────────────
  const navToggle  = document.getElementById('navToggle');
  const navMobile  = document.getElementById('navMobile');
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

  // ─── Video modal ────────────────────────────────────────────
  const modal          = document.getElementById('videoModal');
  const frame          = document.getElementById('youtubeFrame');
  const fallback       = document.getElementById('videoFallback');
  const YT_SRC         = 'https://www.youtube.com/embed/s3DO45gx0KY?si=PBMQmiZyq3YD1IIa&autoplay=1&rel=0';
  const YT_FALLBACK_URL = 'https://www.youtube.com/watch?v=s3DO45gx0KY';
  let fallbackTimeout;

  document.getElementById('watchMissionBtn').addEventListener('click', () => {
    frame.src = YT_SRC;
    fallback.hidden = true;
    clearTimeout(fallbackTimeout);

    modal.classList.add('open');
    document.body.style.overflow = 'hidden';

    fallbackTimeout = setTimeout(() => {
      if (modal.classList.contains('open')) {
        fallback.hidden = false;
      }
    }, 2200);
  });

  frame.addEventListener('load', () => {
    fallback.hidden = true;
    clearTimeout(fallbackTimeout);
  });

  function closeModal() {
    modal.classList.remove('open');
    frame.src = '';
    fallback.hidden = true;
    clearTimeout(fallbackTimeout);
    document.body.style.overflow = '';
  }

  document.getElementById('modalClose').addEventListener('click', closeModal);
  modal.addEventListener('click', e => { if (e.target === modal) closeModal(); });
  document.addEventListener('keydown', e => { if (e.key === 'Escape') closeModal(); });

  // ─── Hero parallax and counters ─────────────────────────────
  const heroBg = document.getElementById('heroBg');
  window.addEventListener('scroll', () => {
    if (heroBg) heroBg.style.transform = `translateY(${window.scrollY * 0.25}px) scale(1.05)`;
  }, { passive: true });

  const counterEls = document.querySelectorAll('.counter-value');
  let countersStarted = false;

  function animateCount(el) {
    const target = Number(el.getAttribute('data-target')) || 0;
    const duration = 1800;
    let start = 0;
    const stepTime = Math.max(Math.floor(duration / target), 20);

    const increment = () => {
      start += Math.ceil(target / (duration / stepTime));
      if (start >= target) {
        el.textContent = target.toLocaleString();
      } else {
        el.textContent = start.toLocaleString();
        setTimeout(increment, stepTime);
      }
    };

    increment();
  }

  const counterObserver = new IntersectionObserver(entries => {
    entries.forEach(entry => {
      if (entry.isIntersecting && !countersStarted) {
        counterEls.forEach(el => animateCount(el));
        countersStarted = true;
      }
    });
  }, { threshold: 0.4 });

  document.querySelectorAll('.counter-item').forEach(item => counterObserver.observe(item));

  // ─── Testimonial carousel auto-scroll ───────────────────────
  const testimonialScroll = document.querySelector('.testimonial-carousel');
  if (testimonialScroll) {
    let carouselInterval = setInterval(() => {
      testimonialScroll.scrollBy({ left: testimonialScroll.clientWidth * 0.9, behavior: 'smooth' });
      if (testimonialScroll.scrollLeft + testimonialScroll.clientWidth >= testimonialScroll.scrollWidth - 10) {
        setTimeout(() => { testimonialScroll.scrollTo({ left: 0, behavior: 'smooth' }); }, 900);
      }
    }, 6000);

    testimonialScroll.addEventListener('mouseenter', () => clearInterval(carouselInterval));
    testimonialScroll.addEventListener('mouseleave', () => {
      carouselInterval = setInterval(() => {
        testimonialScroll.scrollBy({ left: testimonialScroll.clientWidth * 0.9, behavior: 'smooth' });
        if (testimonialScroll.scrollLeft + testimonialScroll.clientWidth >= testimonialScroll.scrollWidth - 10) {
          setTimeout(() => { testimonialScroll.scrollTo({ left: 0, behavior: 'smooth' }); }, 900);
        }
      }, 6000);
    });
  }

  // ─── Donation amount selector ───────────────────────────────
  function selectAmount(btn, amount) {
    document.querySelectorAll('.donate-amount').forEach(b => b.classList.remove('selected'));
    btn.classList.add('selected');
  }

  // ─── Scroll reveal (IntersectionObserver) ───────────────────
  const revealClasses = ['.reveal', '.reveal-left', '.reveal-right'];
  const allReveal = document.querySelectorAll(revealClasses.join(','));

  if ('IntersectionObserver' in window) {
    const io = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.classList.add('visible');
          io.unobserve(entry.target);
        }
      });
    }, { threshold: 0.12, rootMargin: '0px 0px -40px 0px' });

    allReveal.forEach(el => io.observe(el));
  } else {
    // Fallback: show all immediately
    allReveal.forEach(el => el.classList.add('visible'));
  }

  // ─── Smooth scroll for nav links ────────────────────────────
  document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function(e) {
      const target = document.querySelector(this.getAttribute('href'));
      if (target) {
        e.preventDefault();
        const offset = 80; // navbar height
        const top = target.getBoundingClientRect().top + window.scrollY - offset;
        window.scrollTo({ top, behavior: 'smooth' });
      }
    });
  });