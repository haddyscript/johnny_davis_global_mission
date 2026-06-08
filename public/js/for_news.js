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
  const revealEls = document.querySelectorAll(
    '.reveal, .reveal-left, .reveal-right, .reveal-scale, .reveal-pop, .reveal-flip, .reveal-blur'
  );
  if ('IntersectionObserver' in window) {
    const io = new IntersectionObserver(entries => {
      entries.forEach(e => {
        if (e.isIntersecting) { e.target.classList.add('visible'); io.unobserve(e.target); }
      });
    }, { threshold:.08, rootMargin:'0px 0px -32px 0px' });
    revealEls.forEach(el => io.observe(el));
  } else {
    revealEls.forEach(el => el.classList.add('visible'));
  }

  /* ── Category Filter + Pagination ───────────────────────── */
  const filterBtns   = document.querySelectorAll('.filter-btn');
  const allPosts     = Array.from(document.querySelectorAll('.post-card'));
  const noResults    = document.getElementById('noResults');
  const resultsCount = document.getElementById('resultsCount');
  const pagination   = document.getElementById('pagination');

  const POSTS_PER_PAGE = 3;
  let currentFilter = 'all';
  let currentPage   = 1;
  let filteredPosts = allPosts.slice();

  function getFilteredPosts(cat) {
    return allPosts.filter(card => {
      const cats    = card.dataset.categories || '';
      const country = (card.dataset.country || '').toLowerCase();
      return cat === 'all' ||
             cats.includes(cat) ||
             (cat === 'philippines' && country === 'philippines') ||
             (cat === 'uganda'      && country === 'uganda');
    });
  }

  function renderPage(page) {
    const start = (page - 1) * POSTS_PER_PAGE;
    const end   = start + POSTS_PER_PAGE;

    allPosts.forEach(card => { card.style.display = 'none'; });

    const pageItems = filteredPosts.slice(start, end);
    pageItems.forEach(card => {
      card.style.display = '';
      card.classList.remove('visible');
      requestAnimationFrame(() => requestAnimationFrame(() => card.classList.add('visible')));
    });

    noResults.classList.toggle('show', filteredPosts.length === 0);
    resultsCount.innerHTML = `Showing <strong>${filteredPosts.length}</strong> post${filteredPosts.length !== 1 ? 's' : ''}`;
  }

  function renderPagination() {
    if (!pagination) return;
    const totalPages = Math.max(1, Math.ceil(filteredPosts.length / POSTS_PER_PAGE));

    /* rebuild inner buttons */
    pagination.innerHTML = '';

    const prevBtn = document.createElement('button');
    prevBtn.className  = 'pg-btn arrow';
    prevBtn.textContent = '← Prev';
    prevBtn.setAttribute('aria-label', 'Previous page');
    prevBtn.disabled = currentPage === 1;
    prevBtn.addEventListener('click', () => goToPage(currentPage - 1));
    pagination.appendChild(prevBtn);

    for (let i = 1; i <= totalPages; i++) {
      const btn = document.createElement('button');
      btn.className  = 'pg-btn' + (i === currentPage ? ' active' : '');
      btn.textContent = i;
      btn.setAttribute('aria-label', `Page ${i}${i === currentPage ? ', current' : ''}`);
      btn.addEventListener('click', () => goToPage(i));
      pagination.appendChild(btn);
    }

    const nextBtn = document.createElement('button');
    nextBtn.className  = 'pg-btn arrow';
    nextBtn.textContent = 'Next →';
    nextBtn.setAttribute('aria-label', 'Next page');
    nextBtn.disabled = currentPage === totalPages;
    nextBtn.addEventListener('click', () => goToPage(currentPage + 1));
    pagination.appendChild(nextBtn);
  }

  function goToPage(page) {
    const totalPages = Math.ceil(filteredPosts.length / POSTS_PER_PAGE);
    if (page < 1 || page > totalPages) return;
    currentPage = page;
    renderPage(currentPage);
    renderPagination();
    /* scroll to top of posts grid */
    const postsSection = document.getElementById('posts-section');
    if (postsSection) {
      window.scrollTo({ top: postsSection.getBoundingClientRect().top + window.scrollY - 100, behavior: 'smooth' });
    }
  }

  filterBtns.forEach(btn => {
    btn.addEventListener('click', () => {
      currentFilter = btn.dataset.filter;
      currentPage   = 1;

      filterBtns.forEach(b => b.classList.remove('active'));
      btn.classList.add('active');

      filteredPosts = getFilteredPosts(currentFilter);
      renderPage(currentPage);
      renderPagination();
    });
  });

  /* initial render */
  filteredPosts = getFilteredPosts(currentFilter);
  renderPage(currentPage);
  renderPagination();

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

  /* ── Scroll To Top ───────────────────────────────────────── */
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

  /* ── Story Modal ─────────────────────────────────────────── */
  const storyModal         = document.getElementById('storyModal');
  const storyModalClose    = document.getElementById('storyModalClose');
  const storyModalCloseBtn = document.getElementById('storyModalCloseBtn');
  const featuredCta        = document.querySelector('.featured-cta');

  if (storyModal) {
    // Snapshot the hardcoded Marco's-story content so the featured-section CTA can restore it
    const _mImg     = storyModal.querySelector('.story-modal-img');
    const _origSrc  = _mImg ? _mImg.src : '';
    const _origAlt  = _mImg ? _mImg.alt : '';
    const _origCat  = (storyModal.querySelector('.story-modal-cat')      || {}).innerHTML || '';
    const _origLoc  = (storyModal.querySelector('.story-modal-location') || {}).innerHTML || '';
    const _origTtl  = (document.getElementById('storyModalTitle')        || {}).textContent || '';
    const _origBody = (storyModal.querySelector('.story-modal-content')  || {}).innerHTML || '';

    const openStoryModal = () => {
      storyModal.removeAttribute('hidden');
      requestAnimationFrame(() => storyModal.classList.add('open'));
      document.body.style.overflow = 'hidden';
      if (storyModalClose) storyModalClose.focus();
    };
    const closeStoryModal = () => {
      storyModal.classList.remove('open');
      document.body.style.overflow = '';
      setTimeout(() => storyModal.setAttribute('hidden', ''), 380);
    };

    // Featured story section CTA → always restores full hardcoded Marco's story
    if (featuredCta) {
      featuredCta.addEventListener('click', e => {
        e.preventDefault();
        if (_mImg) { _mImg.src = _origSrc; _mImg.alt = _origAlt; }
        const c = storyModal.querySelector('.story-modal-cat');      if (c) c.innerHTML = _origCat;
        const l = storyModal.querySelector('.story-modal-location'); if (l) l.innerHTML = _origLoc;
        const t = document.getElementById('storyModalTitle');        if (t) t.textContent = _origTtl;
        const b = storyModal.querySelector('.story-modal-content');  if (b) b.innerHTML = _origBody;
        openStoryModal();
      });
    }

    // Post card CTAs → populate modal dynamically from the card's data attributes
    document.querySelectorAll('.post-story-cta').forEach(cta => {
      cta.addEventListener('click', e => {
        e.preventDefault();
        const title    = cta.dataset.title    || '';
        const image    = cta.dataset.image    || '';
        const imgalt   = cta.dataset.imgalt   || '';
        const category = cta.dataset.category || '';
        const excerpt  = cta.dataset.excerpt  || '';
        const flag     = cta.dataset.flag     || '';
        const country  = cta.dataset.country  || '';

        const modalImg = storyModal.querySelector('.story-modal-img');
        if (modalImg) { modalImg.src = image; modalImg.alt = imgalt; }

        const modalCat = storyModal.querySelector('.story-modal-cat');
        if (modalCat) modalCat.textContent = category;

        const modalLoc = storyModal.querySelector('.story-modal-location');
        if (modalLoc) modalLoc.innerHTML = (flag ? '<span aria-hidden="true">' + flag + '</span> ' : '') + country;

        const modalTitle = document.getElementById('storyModalTitle');
        if (modalTitle) modalTitle.textContent = title;

        const modalContent = storyModal.querySelector('.story-modal-content');
        if (modalContent) modalContent.innerHTML = '<p>' + excerpt + '</p>';

        openStoryModal();
      });
    });

    if (storyModalClose)    storyModalClose.addEventListener('click', closeStoryModal);
    if (storyModalCloseBtn) storyModalCloseBtn.addEventListener('click', closeStoryModal);
    storyModal.addEventListener('click', e => { if (e.target === storyModal) closeStoryModal(); });
    document.addEventListener('keydown', e => {
      if (e.key === 'Escape' && storyModal.classList.contains('open')) closeStoryModal();
    });
  }

  /* ── News Hero Buttons: ripple + magnetic cursor ─────────── */
  (function () {
    const readBtns = Array.from(document.querySelectorAll('.news-read-btn'));
    const emailBtn = document.querySelector('.news-email-btn');
    const heroBtns = [...readBtns, emailBtn].filter(Boolean);

    heroBtns.forEach(btn => {
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
        const rect = this.getBoundingClientRect();
        const cx = rect.left + rect.width  / 2;
        const cy = rect.top  + rect.height / 2;
        const dx = (e.clientX - cx) * STRENGTH;
        const dy = (e.clientY - cy) * STRENGTH;
        const scale = this === emailBtn ? 1.06 : 1.08;
        this.style.transform = `translateY(-8px) scale(${scale}) translate(${dx}px,${dy}px)`;
      });
    });
  }());

  /* ── Sticky Donate: ripple + magnetic cursor ─────────────── */
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

})();

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

/* ─── MOBILE FILTER BAR: scroll active chip into center ─────── */
(function initMobileFilterScroll() {
  'use strict';
  if (window.innerWidth > 768) return;

  document.querySelectorAll('.filter-btn').forEach(function(btn) {
    btn.addEventListener('click', function() {
      this.scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'center' });
    }, { passive: true });
  });
}());
