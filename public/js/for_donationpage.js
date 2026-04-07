
(function () {
  'use strict';

  /* ── Element refs ─────────────────────────────────────── */
  const overviewScreen    = document.getElementById('campaign-overview-screen');
  const donateScreen      = document.getElementById('donate-body-screen');
  const thankScreen       = document.getElementById('thank-you-screen');
  const loadingScreen     = document.getElementById('loading-screen');
  const campaignCards     = document.querySelectorAll('.campaign-page-card');
  const campaignOpts      = document.querySelectorAll('.campaign-opt');
  const amountBtns        = document.querySelectorAll('.amount-btn');
  const dtabs             = document.querySelectorAll('.dtab');
  const customInput       = document.getElementById('customAmountInput');
  const ctaBtn            = document.getElementById('completeDonationBtn');
  const backBtn           = document.getElementById('back-to-campaigns-btn');
  const backFromThankBtn  = document.getElementById('back-to-campaigns-from-thank');
  const impactHeading     = document.getElementById('donate-impact-heading');
  const orderCampaign     = document.getElementById('donate-order-campaign');
  const orderType         = document.getElementById('donate-order-type');
  const orderAmount       = document.getElementById('donate-order-amount');
  const totalLabel        = document.getElementById('donate-total-label');
  const orderTotal        = document.getElementById('donate-order-total');
  const storyText         = document.getElementById('campaign-story-text');
  const goalText          = document.getElementById('campaign-goal-text');
  const thankCampaign     = document.getElementById('thank-campaign-name');
  const thankAmt          = document.getElementById('thank-amount');
  const tellEmail         = document.getElementById('tell-friend-email');
  const tellBtn           = document.getElementById('tell-friend-btn');
  const payTabCard        = document.getElementById('pay-tab-card');
  const payTabGcash       = document.getElementById('pay-tab-gcash');
  const payTabPaypal      = document.getElementById('pay-tab-paypal');
  const panelCard         = document.getElementById('pay-panel-card');
  const panelGcash        = document.getElementById('pay-panel-gcash');
  const panelPaypal       = document.getElementById('pay-panel-paypal');

  /* ── State ─────────────────────────────────────────────── */
  let selectedAmt      = 29.99;
  let isMonthly        = true;
  let selectedCampaign = 'Feed Filipino Children';

  /* ── Campaign data ──────────────────────────────────────── */
  const campaigns = {
    'Feed Filipino Children': {
      story: 'Hot meal support plus school supplies for 100+ vulnerable children in Cebu. Regular sponsorship removes hunger and increases school attendance.',
      goalText: 'Goal: $45,000 to feed and educate 100+ children for 12 months',
    },
    'Cebu Earthquake Relief': {
      story: 'Rapid relief for families displaced by the recent quake: shelters, clean water, emergency kits, and safe rebuilding support.',
      goalText: 'Goal: $30,000 for emergency shelter & community recovery',
    },
    'Uganda Water Wells': {
      story: 'Build clean water wells that serve 200 people each for 25 years, ending waterborne disease and enabling local growth in Soroti.',
      goalText: 'Goal: $22,000 for 5 wells in Soroti region',
    },
    "Where it's needed most": {
      story: 'JDGM monitors all active programs and allocates unrestricted gifts to wherever the need is most critical at that moment.',
      goalText: 'Flexible fund · currently supporting all three active campaigns',
    }
  };

  /* ── Helpers ────────────────────────────────────────────── */
  function fmt(n) {
    return '$' + (Number.isInteger(n) ? n : n.toFixed(2));
  }

  function showScreen(screen) {
    overviewScreen.style.display = 'none';
    donateScreen.style.display   = 'none';
    thankScreen.style.display    = 'none';
    screen.style.display = 'block';
    window.scrollTo({ top: 0, behavior: 'smooth' });
  }

  /* ── Loading screen functions ───────────────────────────── */
  function showLoadingScreen() {
    loadingScreen.classList.add('show');
    loadingScreen.hidden = false;
  }

  function hideLoadingScreen() {
    loadingScreen.classList.remove('show');
    setTimeout(() => {
      loadingScreen.hidden = true;
    }, 300);
  }

  /* ── Sync UI with state ─────────────────────────────────── */
  function sync() {
    const amtStr  = fmt(selectedAmt);
    const typeStr = isMonthly ? 'Monthly' : 'One-time';

    ctaBtn.textContent        = '♡ Complete Gift — ' + amtStr + (isMonthly ? ' / Month' : ' One-time');
    impactHeading.textContent = 'Your ' + amtStr + (isMonthly ? '/mo' : '') + ' Impact';
    orderCampaign.textContent = selectedCampaign;
    orderType.textContent     = typeStr;
    orderAmount.textContent   = amtStr;
    totalLabel.textContent    = typeStr + ' total';
    orderTotal.textContent    = amtStr;
  }

  /* ── Set campaign ───────────────────────────────────────── */
  function setCampaign(name) {
    selectedCampaign = name;
    const data = campaigns[name];

    campaignOpts.forEach(opt => {
      const title = opt.dataset.campaignOpt;
      const radio = opt.querySelector('.camp-radio');
      const isActive = title === name;
      opt.classList.toggle('active', isActive);
      opt.setAttribute('aria-checked', isActive ? 'true' : 'false');
      if (radio) {
        radio.classList.toggle('on', isActive);
        radio.innerHTML = '';
      }
    });

    if (data) {
      storyText.textContent = data.story;
      goalText.textContent  = data.goalText;
    }

    backBtn.style.display = 'inline-flex';
    sync();
  }

  /* ── Campaign overview cards ────────────────────────────── */
  campaignCards.forEach(card => {
    const activate = () => {
      const name = card.dataset.campaign;
      setCampaign(name);

      // Scroll to where donation section will appear
      const scrollTarget = donateScreen.offsetTop - 100; // Small offset from top
      window.scrollTo({ top: scrollTarget, behavior: 'smooth' });

      // Show loading screen after scroll starts
      setTimeout(() => {
        showLoadingScreen();

        // After loading animation, show donation screen
        setTimeout(() => {
          hideLoadingScreen();
          showScreen(donateScreen);
        }, 2500); // Match the progress bar animation duration
      }, 600); // Wait for scroll to start
    };
    card.addEventListener('click', activate);
    card.addEventListener('keydown', e => { if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); activate(); } });
  });

  /* ── Campaign opts (in form) ────────────────────────────── */
  campaignOpts.forEach(opt => {
    const activate = () => { const name = opt.dataset.campaignOpt; if (name) setCampaign(name); };
    opt.addEventListener('click', activate);
    opt.addEventListener('keydown', e => { if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); activate(); } });
  });

  /* ── Back to campaigns ──────────────────────────────────── */
  backBtn.addEventListener('click', () => {
    backBtn.style.display = 'none';
    showScreen(overviewScreen);
  });
  backFromThankBtn.addEventListener('click', () => {
    backBtn.style.display = 'none';
    showScreen(overviewScreen);
  });

  /* ── Amount buttons ─────────────────────────────────────── */
  amountBtns.forEach(btn => {
    btn.addEventListener('click', () => {
      amountBtns.forEach(b => { b.classList.remove('selected'); b.removeAttribute('aria-pressed'); });
      btn.classList.add('selected');
      btn.setAttribute('aria-pressed', 'true');
      if (customInput) customInput.value = '';
      selectedAmt = parseFloat(btn.querySelector('.amount-price').textContent.replace('$', ''));
      sync();
    });
  });

  /* ── Custom amount ──────────────────────────────────────── */
  customInput.addEventListener('input', () => {
    const val = parseFloat(customInput.value);
    if (!isNaN(val) && val > 0) {
      amountBtns.forEach(b => { b.classList.remove('selected'); b.removeAttribute('aria-pressed'); });
      selectedAmt = val;
      sync();
    }
  });

  /* ── Frequency tabs ─────────────────────────────────────── */
  dtabs.forEach(tab => {
    tab.addEventListener('click', () => {
      dtabs.forEach(t => { t.classList.remove('active'); t.setAttribute('aria-pressed', 'false'); });
      tab.classList.add('active');
      tab.setAttribute('aria-pressed', 'true');
      isMonthly = tab.textContent.includes('Monthly');
      sync();
    });
  });

  /* ── Payment method switch ──────────────────────────────── */
  function switchPay(method) {
    payTabCard.classList.toggle('active', method === 'card');
    payTabCard.setAttribute('aria-selected', method === 'card');
    payTabGcash.classList.toggle('active', method === 'gcash');
    payTabGcash.setAttribute('aria-selected', method === 'gcash');
    payTabPaypal.classList.toggle('active', method === 'paypal');
    payTabPaypal.setAttribute('aria-selected', method === 'paypal');
    panelCard.style.display   = method === 'card'   ? '' : 'none';
    panelGcash.style.display  = method === 'gcash'  ? '' : 'none';
    panelPaypal.style.display = method === 'paypal' ? '' : 'none';
  }
  payTabCard.addEventListener('click',   () => switchPay('card'));
  payTabGcash.addEventListener('click',  () => switchPay('gcash'));
  payTabPaypal.addEventListener('click', () => switchPay('paypal'));

  /* ── Complete donation → Thank You ──────────────────────── */
  ctaBtn.addEventListener('click', () => {
    thankCampaign.textContent = selectedCampaign;
    thankAmt.textContent      = fmt(selectedAmt) + (isMonthly ? ' / month' : ' one-time');

    const message = `I just donated to ${selectedCampaign} via JDGM — join me!`;
    const url     = encodeURIComponent(window.location.href);
    const text    = encodeURIComponent(message);
    document.getElementById('share-twitter').href  = `https://twitter.com/intent/tweet?text=${text}&url=${url}`;
    document.getElementById('share-facebook').href = `https://www.facebook.com/sharer/sharer.php?u=${url}`;
    document.getElementById('share-whatsapp').href = `https://wa.me/?text=${text}%20${url}`;

    showScreen(thankScreen);
  });

  /* ── Tell a friend ──────────────────────────────────────── */
  tellBtn.addEventListener('click', () => {
    const email = tellEmail.value.trim();
    if (!email) { tellEmail.focus(); return; }
    const subject = encodeURIComponent('Join me in supporting a life-changing campaign');
    const body    = encodeURIComponent(`Hi,\n\nI just supported ${selectedCampaign} at Johnny Davis Global Missions. Would you consider giving too?\n\nLearn more: ${window.location.href}\n\nThank you!`);
    window.location = `mailto:${email}?subject=${subject}&body=${body}`;
  });

  /* ── Sticky nav shadow ──────────────────────────────────── */
  const nav = document.getElementById('mainNav');
  window.addEventListener('scroll', () => { nav.classList.toggle('scrolled', window.scrollY > 10); }, { passive:true });

  /* ── Mobile nav toggle ──────────────────────────────────── */
  const toggle   = document.getElementById('navToggle');
  const mobileNav = document.getElementById('navMobile');
  toggle.addEventListener('click', () => {
    const open = mobileNav.classList.toggle('open');
    toggle.classList.toggle('open', open);
    toggle.setAttribute('aria-expanded', String(open));
  });

  /* ── Reveal animations ─────────────────────────────────── */
  const revealEls = document.querySelectorAll('.reveal');
  if ('IntersectionObserver' in window) {
    const io = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.classList.add('visible');
          io.unobserve(entry.target);
        }
      });
    }, { threshold: 0.12, rootMargin: '0px 0px -40px 0px' });
    revealEls.forEach(el => io.observe(el));
  } else {
    revealEls.forEach(el => el.classList.add('visible'));
  }

  /* ── Campaign Tour ──────────────────────────────────────── */
  const tourOverlay = document.getElementById('campaign-tour-overlay');
  const tourSteps = document.querySelectorAll('.tour-step');
  const tourNextBtns = document.querySelectorAll('.tour-next');
  const tourPrevBtns = document.querySelectorAll('.tour-prev');
  const tourCtaBtn = document.querySelector('.tour-cta');
  const tourCloseBtn = document.querySelector('.tour-close');
  const tourSkipBtn = document.querySelector('.tour-skip');
  const tourDots = document.querySelectorAll('.tour-dot');
  let currentTourStep = 0;
  let tourShown = false;
  let autoAdvanceTimer = null;

  const overviewScreenEl = document.getElementById('campaign-overview-screen');
  const overviewObserver = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting && !tourShown) {
        tourShown = true;
        setTimeout(() => {
          showTourStep(0);
          // Scroll to first campaign card when tour starts
          const firstCard = document.querySelector('.campaign-page-card');
          if (firstCard) {
            setTimeout(() => {
              firstCard.scrollIntoView({
                behavior: 'smooth',
                block: 'center',
                inline: 'center'
              });
            }, 600); // Delay to allow tour overlay to appear first
          }
        }, 500); // Small delay for smooth entry
        tourOverlay.hidden = false;
        document.body.style.overflow = 'hidden';
        tourOverlay.focus(); // Focus for accessibility
      }
    });
  }, { threshold: 0.5 });

  overviewObserver.observe(overviewScreenEl);

  function showTourStep(step) {
    // Clear any existing auto-advance timer
    if (autoAdvanceTimer) {
      clearTimeout(autoAdvanceTimer);
      autoAdvanceTimer = null;
    }

    // Update step visibility
    tourSteps.forEach((s, i) => s.classList.toggle('active', i === step));
    currentTourStep = step;

    // Update progress dots
    tourDots.forEach((dot, i) => dot.classList.toggle('active', i === step));

    // Highlight corresponding card with animation
    const cards = document.querySelectorAll('.campaign-page-card');
    cards.forEach((card, i) => {
      card.classList.toggle('tour-highlight', i === step);
      if (i === step) {
        // Scroll to the highlighted card
        setTimeout(() => {
          card.scrollIntoView({
            behavior: 'smooth',
            block: 'center',
            inline: 'center'
          });
        }, 300); // Small delay to allow highlight animation to start
      }
    });

    // Update ARIA labels
    tourOverlay.setAttribute('aria-labelledby', `tour-title-${step}`);

    // Set auto-advance timer for non-final steps
    if (step < tourSteps.length - 1) {
      autoAdvanceTimer = setTimeout(() => {
        showTourStep(step + 1);
      }, 8000); // Auto-advance after 8 seconds
    }
  }

  // Reset auto-advance on user interaction
  function resetAutoAdvance() {
    if (autoAdvanceTimer) {
      clearTimeout(autoAdvanceTimer);
      if (currentTourStep < tourSteps.length - 1) {
        autoAdvanceTimer = setTimeout(() => {
          showTourStep(currentTourStep + 1);
        }, 8000);
      }
    }
  }

  // Navigation buttons
  tourNextBtns.forEach(btn => {
    btn.addEventListener('click', () => {
      resetAutoAdvance();
      if (currentTourStep < tourSteps.length - 1) {
        showTourStep(currentTourStep + 1);
      }
    });
  });

  tourPrevBtns.forEach(btn => {
    btn.addEventListener('click', () => {
      resetAutoAdvance();
      if (currentTourStep > 0) {
        showTourStep(currentTourStep - 1);
      }
    });
  });

  // CTA button - close tour and scroll to campaigns
  tourCtaBtn.addEventListener('click', () => {
    if (autoAdvanceTimer) clearTimeout(autoAdvanceTimer);
    closeTour();
    setTimeout(() => {
      overviewScreenEl.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }, 300);
  });

  // Skip and close buttons
  tourSkipBtn.addEventListener('click', () => {
    if (autoAdvanceTimer) clearTimeout(autoAdvanceTimer);
    closeTour();
  });
  tourCloseBtn.addEventListener('click', () => {
    if (autoAdvanceTimer) clearTimeout(autoAdvanceTimer);
    closeTour();
  });

  // Keyboard navigation
  tourOverlay.addEventListener('keydown', (e) => {
    resetAutoAdvance();
    if (e.key === 'Escape') {
      closeTour();
    } else if (e.key === 'ArrowRight' && currentTourStep < tourSteps.length - 1) {
      showTourStep(currentTourStep + 1);
    } else if (e.key === 'ArrowLeft' && currentTourStep > 0) {
      showTourStep(currentTourStep - 1);
    } else if (e.key === 'Enter' && e.target === tourCtaBtn) {
      tourCtaBtn.click();
    }
  });

  // Click outside to close
  tourOverlay.addEventListener('click', (e) => {
    if (e.target === tourOverlay || e.target === tourOverlay.querySelector('.tour-backdrop')) {
      if (autoAdvanceTimer) clearTimeout(autoAdvanceTimer);
      closeTour();
    }
  });

  function closeTour() {
    tourOverlay.hidden = true;
    document.body.style.overflow = '';
    const cards = document.querySelectorAll('.campaign-page-card');
    cards.forEach(card => card.classList.remove('tour-highlight'));
  }

  /* ── Initial state ──────────────────────────────────────── */
  overviewScreen.style.display = 'block';
  donateScreen.style.display   = 'none';
  thankScreen.style.display    = 'none';
  backBtn.style.display        = 'none';
  setCampaign('Feed Filipino Children');

})();