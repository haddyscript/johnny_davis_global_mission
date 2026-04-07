(function () {
  'use strict';

  /* ── Navbar scroll shadow ─────────────────────────────────── */
  const navbar = document.getElementById('navbar');
  window.addEventListener('scroll', () => {
    navbar.classList.toggle('scrolled', window.scrollY > 20);
  }, { passive:true });

  /* ── Mobile nav toggle ───────────────────────────────────── */
  const toggle = document.getElementById('navToggle');
  const mobileNav = document.getElementById('navMobile');
  toggle.addEventListener('click', () => {
    const open = mobileNav.classList.toggle('open');
    toggle.classList.toggle('open', open);
    toggle.setAttribute('aria-expanded', String(open));
  });
  mobileNav.querySelectorAll('a').forEach(a =>
    a.addEventListener('click', () => {
      mobileNav.classList.remove('open');
      toggle.classList.remove('open');
      toggle.setAttribute('aria-expanded', 'false');
    })
  );

  /* ── Scroll Reveal ───────────────────────────────────────── */
  const revealEls = document.querySelectorAll('.reveal, .reveal-left, .reveal-right');
  if ('IntersectionObserver' in window) {
    const io = new IntersectionObserver(entries => {
      entries.forEach(e => {
        if (e.isIntersecting) { e.target.classList.add('visible'); io.unobserve(e.target); }
      });
    }, { threshold:.1, rootMargin:'0px 0px -40px 0px' });
    revealEls.forEach(el => io.observe(el));
  } else {
    revealEls.forEach(el => el.classList.add('visible'));
  }

  /* ── Category Filter ─────────────────────────────────────── */
  const filterBtns  = document.querySelectorAll('.filter-btn');
  const posts       = document.querySelectorAll('.post-card');
  const noResults   = document.getElementById('noResults');
  const resultsCount = document.getElementById('resultsCount');

  filterBtns.forEach(btn => {
    btn.addEventListener('click', () => {
      const cat = btn.dataset.filter;

      /* update active state */
      filterBtns.forEach(b => b.classList.remove('active'));
      btn.classList.add('active');

      let visible = 0;
      posts.forEach(card => {
        const cats = card.dataset.categories || '';
        const country = card.dataset.country || '';
        const show = cat === 'all' ||
                    cats.includes(cat) ||
                    (cat === 'uganda' && country.toLowerCase() === 'uganda') ||
                    (cat === 'philippines' && country.toLowerCase() === 'philippines');

        if (show) {
          card.style.display = '';
          /* re-trigger reveal animation */
          card.classList.remove('visible');
          requestAnimationFrame(() => requestAnimationFrame(() => card.classList.add('visible')));
          visible++;
        } else {
          card.style.display = 'none';
        }
      });

      /* no results */
      noResults.classList.toggle('show', visible === 0);

      /* update counter */
      resultsCount.innerHTML = `Showing <strong>${visible}</strong> post${visible !== 1 ? 's' : ''}`;
    });
  });

  /* ── Newsletter Subscribe ────────────────────────────────── */
  const subscribeForm = document.getElementById('subscribeForm');
  const nlFormDiv     = document.getElementById('nlForm');
  const nlSuccess     = document.getElementById('nlSuccess');
  const nlSubmitBtn   = document.getElementById('nlSubmitBtn');

  if (subscribeForm) {
    subscribeForm.addEventListener('submit', async e => {
      e.preventDefault();

      const emailInp = document.getElementById('nlEmail');
      const nameInp  = document.getElementById('nlFirstName');
      const email    = emailInp.value.trim();
      const name     = nameInp.value.trim();

      /* Client-side validation */
      let valid = true;
      [emailInp, nameInp].forEach(inp => {
        if (!inp.value.trim()) {
          inp.style.borderColor = '#f87171';
          inp.style.animation   = 'shakeInput .4s ease';
          setTimeout(() => { inp.style.animation = ''; inp.style.borderColor = ''; }, 400);
          valid = false;
        }
      });
      if (email && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
        emailInp.style.borderColor = '#f87171';
        valid = false;
      }
      if (!valid) return;

      /* Loading state */
      nlSubmitBtn.textContent = 'Subscribing\u2026';
      nlSubmitBtn.disabled    = true;

      try {
        const formData = new FormData(subscribeForm);
        const url      = subscribeForm.dataset.action;

        const response = await fetch(url, {
          method:  'POST',
          headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
          body:    formData,
        });

        const data = await response.json();

        if (response.ok) {
          if (data.already_subscribed) {
            /* Already subscribed — show friendly message */
            nlFormDiv.style.display = 'none';
            nlSuccess.classList.add('show');
            const h4 = nlSuccess.querySelector('h4');
            const p  = nlSuccess.querySelector('p');
            if (h4) h4.textContent = 'Already Subscribed!';
            if (p)  p.textContent  = data.message;
          } else {
            nlFormDiv.style.display = 'none';
            nlSuccess.classList.add('show');
          }
          subscribeForm.reset();
          return;
        }

        /* Validation errors (422) */
        if (response.status === 422 && data.errors) {
          if (data.errors.email)      emailInp.style.borderColor = '#f87171';
          if (data.errors.first_name) nameInp.style.borderColor  = '#f87171';
          nlSubmitBtn.textContent = 'Please fix the fields above';
          setTimeout(() => {
            nlSubmitBtn.textContent = '\u2665 Subscribe \u2014 It\'s Free';
            nlSubmitBtn.disabled    = false;
            emailInp.style.borderColor = '';
            nameInp.style.borderColor  = '';
          }, 3000);
          return;
        }

        throw new Error('Server error');

      } catch (err) {
        console.error('Subscribe error:', err);
        nlSubmitBtn.textContent = 'Something went wrong \u2014 try again';
        setTimeout(() => {
          nlSubmitBtn.textContent = '\u2665 Subscribe \u2014 It\'s Free';
          nlSubmitBtn.disabled    = false;
        }, 3000);
      }
    });
  }

  /* ── Smooth anchor scrolling ─────────────────────────────── */
  document.querySelectorAll('a[href^="#"]').forEach(a => {
    a.addEventListener('click', e => {
      const target = document.querySelector(a.getAttribute('href'));
      if (target) {
        e.preventDefault();
        window.scrollTo({ top: target.getBoundingClientRect().top + window.scrollY - 80, behavior:'smooth' });
      }
    });
  });

  /* ── Trending item keyboard nav ──────────────────────────── */
  document.querySelectorAll('.trending-item').forEach(item => {
    item.addEventListener('keydown', e => { if (e.key === 'Enter' || e.key === ' ') item.click(); });
  });

  /* ── Share dot micro-interaction ────────────────────────── */
  document.querySelectorAll('.share-dot').forEach(dot => {
    dot.addEventListener('click', function() {
      const orig = this.textContent;
      this.textContent = '\u2713';
      this.style.background = 'var(--orange)';
      this.style.color = '#fff';
      setTimeout(() => {
        this.textContent = orig;
        this.style.background = '';
        this.style.color = '';
      }, 1200);
    });
  });

  /* ── Animated Counters ──────────────────────────────────── */
  function animateCounters() {
    const counters = document.querySelectorAll('.stat-number');
    counters.forEach(counter => {
      const target = parseInt(counter.dataset.target);
      const duration = 2000; // 2 seconds
      const step = target / (duration / 16); // 60fps
      let current = 0;

      const timer = setInterval(() => {
        current += step;
        if (current >= target) {
          counter.textContent = target.toLocaleString();
          clearInterval(timer);
        } else {
          counter.textContent = Math.floor(current).toLocaleString();
        }
      }, 16);
    });
  }

  /* Trigger counters when stats section comes into view */
  if ('IntersectionObserver' in window) {
    const statsObserver = new IntersectionObserver(entries => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          animateCounters();
          statsObserver.unobserve(entry.target);
        }
      });
    }, { threshold: 0.3 });
    const statsSection = document.getElementById('mission-stats');
    if (statsSection) statsObserver.observe(statsSection);
  }

  /* ── Location Filter Buttons ────────────────────────────── */
  document.querySelectorAll('.location-btn').forEach(btn => {
    btn.addEventListener('click', () => {
      const location = btn.dataset.location;
      const filterBtn = document.querySelector(`.filter-btn[data-filter="${location}"]`);
      if (filterBtn) {
        filterBtn.click();
        // Smooth scroll to posts section
        const postsSection = document.getElementById('posts-section');
        if (postsSection) {
          window.scrollTo({
            top: postsSection.getBoundingClientRect().top + window.scrollY - 100,
            behavior: 'smooth'
          });
        }
      }
    });
  });

  /* ── Testimonials Carousel ──────────────────────────────── */
  let currentTestimonial = 0;
  const testimonialCards = document.querySelectorAll('.testimonial-card');
  const testimonialDots = document.querySelectorAll('.dot');

  function showTestimonial(index) {
    testimonialCards.forEach(card => card.classList.remove('active'));
    testimonialDots.forEach(dot => dot.classList.remove('active'));
    testimonialCards[index].classList.add('active');
    testimonialDots[index].classList.add('active');
    currentTestimonial = index;
  }

  testimonialDots.forEach((dot, index) => {
    dot.addEventListener('click', () => showTestimonial(index));
  });

  // Auto-rotate testimonials
  setInterval(() => {
    const nextIndex = (currentTestimonial + 1) % testimonialCards.length;
    showTestimonial(nextIndex);
  }, 5000);

})();
