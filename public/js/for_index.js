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

  // ─── Hero stats counting animation ──────────────────────────
  (function () {
    const statEls = document.querySelectorAll('.hero-stat strong');
    if (!statEls.length) return;

    function parseStatValue(text) {
      // Extract leading number (supports commas and decimals), keep rest as suffix
      const match = text.match(/^([\d,]+)/);
      if (!match) return { num: null, suffix: text };
      const num    = parseInt(match[1].replace(/,/g, ''), 10);
      const suffix = text.slice(match[1].length); // e.g. "+", "K+", " Countries"
      return { num, suffix };
    }

    function formatNum(n) {
      return n.toLocaleString();
    }

    function animateStat(el) {
      const original      = el.textContent.trim();
      const { num, suffix } = parseStatValue(original);
      if (num === null || num === 0) return;

      const duration = 2000;
      const steps    = 60;
      const interval = duration / steps;
      let current    = 0;

      const timer = setInterval(() => {
        current++;
        const value = Math.round((current / steps) * num);
        el.textContent = formatNum(value) + suffix;
        if (current >= steps) {
          el.textContent = formatNum(num) + suffix;
          clearInterval(timer);
        }
      }, interval);
    }

    /* Stats are in the hero — visible on load, so animate after a short delay */
    window.addEventListener('load', () => {
      setTimeout(() => statEls.forEach(el => animateStat(el)), 400);
    });
  }());

  // ─── Scroll To Top ──────────────────────────────────────────
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
      const scrolled = window.scrollY;
      const total    = document.documentElement.scrollHeight - window.innerHeight;
      const show     = total > 0 && scrolled / total >= THRESHOLD;
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

  // ─── Donate CTA + Hero buttons: ripple + magnetic cursor ────
  (function () {
    const heroBtn      = document.querySelector('.donate-hero-btn');
    const monthlyBtn   = document.querySelector('.donate-monthly-btn');
    const heroDonateBtn= document.querySelector('.hero-donate-btn');
    const heroWatchBtn = document.querySelector('.hero-watch-btn');
    const ctaBtns      = [heroBtn, monthlyBtn, heroDonateBtn, heroWatchBtn].filter(Boolean);

    /* Ripple on click */
    ctaBtns.forEach(btn => {
      btn.addEventListener('click', function (e) {
        const rect   = this.getBoundingClientRect();
        const ripple = document.createElement('span');
        ripple.className  = 'btn-ripple';
        ripple.style.left = (e.clientX - rect.left)  + 'px';
        ripple.style.top  = (e.clientY - rect.top)   + 'px';
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
        const cx   = rect.left + rect.width  / 2;
        const cy   = rect.top  + rect.height / 2;
        const dx   = (e.clientX - cx) * STRENGTH;
        const dy   = (e.clientY - cy) * STRENGTH;
        this.style.transform = `translateY(-7px) scale(1.07) translate(${dx}px,${dy}px)`;
      });
    });
  }());

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

  // ─── Confetti burst from badge number ───────────────────────
  (function () {
    const badge       = document.querySelector('.mission-badge');
    const badgeNumber = badge && badge.querySelector('strong');
    if (!badgeNumber) return;

    const COLORS    = ['#f97316','#fbbf24','#34d399','#60a5fa','#a78bfa','#f472b6','#ffffff'];
    const DURATION  = 2000;
    const INTERVAL  = 40;
    const PER_BURST = 8;

    let canvas, ctx, particles = [], animId, intervalId, startTime, originX, originY;

    function createCanvas() {
      canvas = document.createElement('canvas');
      canvas.style.cssText = [
        'position:fixed', 'inset:0', 'width:100%', 'height:100%',
        'pointer-events:none', 'z-index:9999'
      ].join(';');
      document.body.appendChild(canvas);
      resize();
      window.addEventListener('resize', resize, { passive: true });
    }

    function resize() {
      canvas.width  = window.innerWidth;
      canvas.height = window.innerHeight;
    }

    function getOrigin() {
      const rect = badgeNumber.getBoundingClientRect();
      originX = rect.left + rect.width  / 2;
      originY = rect.top  + rect.height / 2;
    }

    function spawnParticles() {
      getOrigin();
      for (let i = 0; i < PER_BURST; i++) {
        const angle = Math.random() * Math.PI * 2;
        const speed = Math.random() * 6 + 2;
        particles.push({
          x:     originX,
          y:     originY,
          r:     Math.random() * 7 + 4,
          color: COLORS[Math.floor(Math.random() * COLORS.length)],
          vx:    Math.cos(angle) * speed,
          vy:    Math.sin(angle) * speed - 2,
          angle: Math.random() * Math.PI * 2,
          spin:  (Math.random() - 0.5) * 0.25,
          shape: Math.random() < 0.5 ? 'rect' : 'circle',
          life:  1,
          decay: Math.random() * 0.015 + 0.008,
        });
      }
    }

    function draw() {
      ctx.clearRect(0, 0, canvas.width, canvas.height);
      particles.forEach(p => {
        ctx.save();
        ctx.translate(p.x, p.y);
        ctx.rotate(p.angle);
        ctx.fillStyle = p.color;
        ctx.globalAlpha = Math.max(0, p.life);
        if (p.shape === 'rect') {
          ctx.fillRect(-p.r, -p.r / 2, p.r * 2, p.r);
        } else {
          ctx.beginPath();
          ctx.arc(0, 0, p.r / 2, 0, Math.PI * 2);
          ctx.fill();
        }
        ctx.restore();

        p.x     += p.vx;
        p.y     += p.vy;
        p.vy    += 0.12; // gravity
        p.angle += p.spin;
        p.life  -= p.decay;
      });

      particles = particles.filter(p => p.life > 0);
      animId = requestAnimationFrame(draw);
    }

    function stopConfetti() {
      clearInterval(intervalId);
      setTimeout(() => {
        cancelAnimationFrame(animId);
        window.removeEventListener('resize', resize);
        canvas.remove();
        particles = [];
      }, 3000);
    }

    function launchConfetti() {
      createCanvas();
      ctx = canvas.getContext('2d');
      startTime = Date.now();
      spawnParticles();
      draw();
      intervalId = setInterval(() => {
        if (Date.now() - startTime >= DURATION) { stopConfetti(); return; }
        spawnParticles();
      }, INTERVAL);
    }

    if ('IntersectionObserver' in window) {
      const badgeObserver = new IntersectionObserver(entries => {
        entries.forEach(entry => {
          if (entry.isIntersecting) {
            badgeObserver.unobserve(badge);
            launchConfetti();
          }
        });
      }, { threshold: 0.6 });
      badgeObserver.observe(badge);
    }
  }());

  // ─── CTA Buttons: ripple + magnetic cursor ───────────────────
  (function () {
    const primaryBtns = Array.from(document.querySelectorAll('.news-read-btn'));
    const outlineBtn  = document.querySelector('.news-email-btn');
    const ctaBtns     = [...primaryBtns, outlineBtn].filter(Boolean);

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

      const STRENGTH = 0.2;
      let isHovered = false;
      btn.addEventListener('mouseenter', () => { isHovered = true; });
      btn.addEventListener('mouseleave', () => {
        isHovered = false;
        btn.style.transform = '';
      });
      btn.addEventListener('mousemove', function (e) {
        if (!isHovered) return;
        const rect  = this.getBoundingClientRect();
        const cx    = rect.left + rect.width  / 2;
        const cy    = rect.top  + rect.height / 2;
        const dx    = (e.clientX - cx) * STRENGTH;
        const dy    = (e.clientY - cy) * STRENGTH;
        const scale = btn === outlineBtn ? 1.06 : 1.08;
        this.style.transform = `translateY(-8px) scale(${scale}) translate(${dx}px,${dy}px)`;
      });
    });
  }());

  // ─── Sticky Donate: ripple + magnetic cursor ─────────────────
  (function () {
    const stickyBtn = document.getElementById('stickyDonate');
    if (!stickyBtn) return;

    stickyBtn.addEventListener('click', function (e) {
      const rect   = this.getBoundingClientRect();
      const ripple = document.createElement('span');
      ripple.className  = 'wwa-btn-ripple';
      ripple.style.left = (e.clientX - rect.left) + 'px';
      ripple.style.top  = (e.clientY - rect.top)  + 'px';
      this.appendChild(ripple);
      setTimeout(() => ripple.remove(), 680);
    });

    const STRENGTH = 0.22;
    let isHovered = false;
    stickyBtn.addEventListener('mouseenter', () => { isHovered = true; });
    stickyBtn.addEventListener('mouseleave', () => {
      isHovered = false;
      stickyBtn.style.transform = '';
    });
    stickyBtn.addEventListener('mousemove', function (e) {
      if (!isHovered) return;
      const rect = this.getBoundingClientRect();
      const cx = rect.left + rect.width  / 2;
      const cy = rect.top  + rect.height / 2;
      const dx = (e.clientX - cx) * STRENGTH;
      const dy = (e.clientY - cy) * STRENGTH;
      this.style.transform = `translateY(-8px) scale(1.08) translate(${dx}px,${dy}px)`;
    });
  }());