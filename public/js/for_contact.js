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

    // Basic validation
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

    // Email validation
    const emailField = document.getElementById('email');
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (emailField.value && !emailRegex.test(emailField.value)) {
      emailField.style.borderColor = '#ef4444';
      emailField.previousElementSibling.style.color = '#ef4444';
      isValid = false;
    }

    if (!isValid) return;

    // Show loading state
    submitBtn.classList.add('loading');
    submitBtn.textContent = 'Sending...';

    // Simulate form submission (replace with actual API call)
    try {
      await new Promise(resolve => setTimeout(resolve, 2000)); // Simulate delay

      // Hide form, show success
      contactForm.style.display = 'none';
      formSuccess.classList.add('show');

      // Reset form after success
      setTimeout(() => {
        contactForm.reset();
        contactForm.style.display = 'block';
        formSuccess.classList.remove('show');
        charCounter.textContent = '0 / 1000';
        charCounter.style.color = 'var(--gray-400)';
      }, 5000);

    } catch (error) {
      console.error('Form submission error:', error);
      submitBtn.textContent = 'Error - Try Again';
      setTimeout(() => submitBtn.textContent = 'Send Message', 2000);
    } finally {
      submitBtn.classList.remove('loading');
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

})();