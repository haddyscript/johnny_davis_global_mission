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

  /* ── Contact Form Handling ───────────────────────────────── */
  const contactForm = document.getElementById('contactForm');
  const submitBtn = document.getElementById('submitBtn');
  const formSuccess = document.getElementById('formSuccess');
  const charCounter = document.getElementById('charCounter');
  const messageTextarea = document.getElementById('message');

  // Character counter
  messageTextarea.addEventListener('input', () => {
    const count = messageTextarea.value.length;
    charCounter.textContent = `${count} / 1000`;
    charCounter.style.color = count > 900 ? '#ef4444' : count > 800 ? '#f59e0b' : 'var(--gray-400)';
  });

  // Form validation and submission
  contactForm.addEventListener('submit', async e => {
    e.preventDefault();

    // Client-side validation
    const requiredFields = ['firstName', 'lastName', 'email', 'subject', 'message'];
    let isValid = true;

    requiredFields.forEach(fieldId => {
      const field = document.getElementById(fieldId);
      const label = field.previousElementSibling;
      if (!field.value.trim()) {
        field.style.borderColor = '#ef4444';
        field.style.animation = 'shake 0.5s ease';
        if (label) label.style.color = '#ef4444';
        isValid = false;
      } else {
        field.style.borderColor = 'var(--gray-100)';
        if (label) label.style.color = 'var(--gray-600)';
      }
      setTimeout(() => field.style.animation = '', 500);
    });

    const emailField = document.getElementById('email');
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (emailField.value && !emailRegex.test(emailField.value)) {
      emailField.style.borderColor = '#ef4444';
      if (emailField.previousElementSibling) emailField.previousElementSibling.style.color = '#ef4444';
      isValid = false;
    }

    if (!isValid) return;

    // Show loading state
    submitBtn.classList.add('loading');
    submitBtn.disabled = true;
    submitBtn.textContent = 'Sending...';

    // Build form data including CSRF token
    const formData = new FormData(contactForm);

    try {
      const response = await fetch(contactForm.action, {
        method: 'POST',
        headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
        body: formData,
      });

      if (response.ok || response.status === 302) {
        // Success — hide form, show success panel
        contactForm.style.display = 'none';
        formSuccess.style.display  = 'block';
        formSuccess.style.opacity  = '0';
        formSuccess.style.transform = 'translateY(16px)';
        formSuccess.style.width    = '100%';
        formSuccess.style.boxSizing = 'border-box';
        requestAnimationFrame(() => {
          formSuccess.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
          formSuccess.style.opacity    = '1';
          formSuccess.style.transform  = 'translateY(0)';
        });
        contactForm.reset();
        if (charCounter) { charCounter.textContent = '0 / 1000'; charCounter.style.color = 'var(--gray-400)'; }
        // Scroll to success message so it's visible on mobile
        const card = document.querySelector('.contact-form-card');
        if (card) card.scrollIntoView({ behavior: 'smooth', block: 'center' });
        return;
      }

      // Laravel validation errors (422)
      if (response.status === 422) {
        const data = await response.json();
        const errors = data.errors || {};
        Object.keys(errors).forEach(key => {
          // Map camelCase field names to element IDs
          const fieldId = key === 'firstName' ? 'firstName'
                        : key === 'lastName'  ? 'lastName'
                        : key;
          const field = document.getElementById(fieldId);
          if (field) {
            field.style.borderColor = '#ef4444';
            if (field.previousElementSibling) field.previousElementSibling.style.color = '#ef4444';
          }
        });
        submitBtn.textContent = 'Please fix the errors above';
        setTimeout(() => { submitBtn.textContent = 'Send Message'; submitBtn.disabled = false; }, 3000);
        return;
      }

      throw new Error('Server error: ' + response.status);

    } catch (error) {
      console.error('Form submission error:', error);
      submitBtn.textContent = 'Something went wrong — try again';
      setTimeout(() => { submitBtn.textContent = 'Send Message'; submitBtn.disabled = false; }, 3000);
    } finally {
      submitBtn.classList.remove('loading');
      submitBtn.disabled = false;
    }
  });

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

})();