
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
  const paymentErrorEl    = document.getElementById('payment-error-msg');

  /* ── State ─────────────────────────────────────────────── */
  let selectedAmt       = 29.99;
  let isMonthly         = true;
  let selectedCampaign  = 'Feed Filipino Children';
  let currentPayMethod  = 'card';

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
    },
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

  function showLoadingScreen() {
    loadingScreen.classList.add('show');
    loadingScreen.hidden = false;
  }

  function hideLoadingScreen() {
    loadingScreen.classList.remove('show');
    setTimeout(() => { loadingScreen.hidden = true; }, 300);
  }

  function showPaymentError(msg) {
    paymentErrorEl.textContent = msg;
    paymentErrorEl.style.display = 'block';
    paymentErrorEl.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
  }

  function clearPaymentError() {
    paymentErrorEl.textContent = '';
    paymentErrorEl.style.display = 'none';
  }

  function resetCtaBtn() {
    ctaBtn.disabled = false;
    sync();
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
      const title   = opt.dataset.campaignOpt;
      const radio   = opt.querySelector('.camp-radio');
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

      const scrollTarget = donateScreen.offsetTop - 100;
      window.scrollTo({ top: scrollTarget, behavior: 'smooth' });

      setTimeout(() => {
        showLoadingScreen();
        setTimeout(() => {
          hideLoadingScreen();
          showScreen(donateScreen);
        }, 2500);
      }, 600);
    };
    card.addEventListener('click', activate);
    card.addEventListener('keydown', e => {
      if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); activate(); }
    });
  });

  /* ── Campaign opts (in form) ────────────────────────────── */
  campaignOpts.forEach(opt => {
    const activate = () => {
      const name = opt.dataset.campaignOpt;
      if (name) setCampaign(name);
    };
    opt.addEventListener('click', activate);
    opt.addEventListener('keydown', e => {
      if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); activate(); }
    });
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
      clearPaymentError();
      sync();
    });
  });

  /* ── Custom amount ──────────────────────────────────────── */
  customInput.addEventListener('input', () => {
    const val = parseFloat(customInput.value);
    if (!isNaN(val) && val > 0) {
      amountBtns.forEach(b => { b.classList.remove('selected'); b.removeAttribute('aria-pressed'); });
      selectedAmt = val;
      clearPaymentError();
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
    currentPayMethod = method;
    payTabCard.classList.toggle('active', method === 'card');
    payTabCard.setAttribute('aria-selected', method === 'card');
    payTabGcash.classList.toggle('active', method === 'gcash');
    payTabGcash.setAttribute('aria-selected', method === 'gcash');
    payTabPaypal.classList.toggle('active', method === 'paypal');
    payTabPaypal.setAttribute('aria-selected', method === 'paypal');
    panelCard.style.display   = method === 'card'   ? '' : 'none';
    panelGcash.style.display  = method === 'gcash'  ? '' : 'none';
    panelPaypal.style.display = method === 'paypal' ? '' : 'none';
    // PayPal has its own Buttons — hide the generic CTA when it's active
    ctaBtn.style.display = method === 'paypal' ? 'none' : '';
    if (method === 'paypal') {
      initPayPalButtons();
      syncPaypalOverlay();
    }
    clearPaymentError();
  }
  payTabCard.addEventListener('click',   () => switchPay('card'));
  payTabGcash.addEventListener('click',  () => switchPay('gcash'));
  payTabPaypal.addEventListener('click', () => switchPay('paypal'));

  /* ── PayPal overlay: disable button until form is filled ── */
  function paypalFormValid() {
    const first = document.getElementById('firstName').value.trim();
    const last  = document.getElementById('lastName').value.trim();
    const email = document.getElementById('emailAddr').value.trim();
    return first && last && /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
  }

  function syncPaypalOverlay() {
    const overlay = document.getElementById('paypal-btn-overlay');
    if (!overlay) return;
    overlay.style.display = paypalFormValid() ? 'none' : 'flex';
  }

  ['firstName', 'lastName', 'emailAddr'].forEach(id => {
    document.getElementById(id)?.addEventListener('input', syncPaypalOverlay);
  });

  /* ─────────────────────────────────────────────────────────
     STRIPE INTEGRATION
  ───────────────────────────────────────────────────────── */

  const stripeKey  = document.querySelector('meta[name="stripe-key"]')?.content ?? '';
  const csrfToken  = document.querySelector('meta[name="csrf-token"]')?.content ?? '';
  const cardErrors = document.getElementById('stripe-card-errors');

  let stripe      = null;
  let cardElement = null;

  if (stripeKey) {
    stripe = Stripe(stripeKey);

    const elements = stripe.elements({
      fonts: [{ cssSrc: 'https://fonts.googleapis.com/css2?family=Inter:wght@400;500&display=swap' }],
    });

    cardElement = elements.create('card', {
      style: {
        base: {
          color:           '#1e293b',
          fontFamily:      'Inter, system-ui, sans-serif',
          fontSize:        '15px',
          fontWeight:      '400',
          lineHeight:      '1.5',
          '::placeholder': { color: '#94a3b8' },
          iconColor:       '#64748b',
        },
        invalid: {
          color:     '#dc2626',
          iconColor: '#dc2626',
        },
      },
      hidePostalCode: true,
    });

    cardElement.mount('#stripe-card-element');

    cardElement.addEventListener('change', event => {
      if (event.error) {
        cardErrors.textContent    = event.error.message;
        cardErrors.style.display  = 'block';
      } else {
        cardErrors.textContent    = '';
        cardErrors.style.display  = 'none';
      }
    });
  }

  /* ─────────────────────────────────────────────────────────
     PAYPAL INTEGRATION
  ───────────────────────────────────────────────────────── */

  const paypalClientId   = document.querySelector('meta[name="paypal-client-id"]')?.content ?? '';
  let   paypalButtonsDone = false;

  function initPayPalButtons() {
    if (paypalButtonsDone) return;

    const container = document.getElementById('paypal-button-container');
    if (!container) return;

    if (!window.paypal) {
      container.innerHTML = '<p style="text-align:center;font-size:13px;color:#64748b;padding:12px 0;">PayPal is not configured. Please use Credit/Debit Card.</p>';
      return;
    }

    // Wrap container so overlay can sit on top of the SDK iframe
    const ppContainer = document.getElementById('paypal-button-container');
    const ppWrapper   = document.createElement('div');
    ppWrapper.style.cssText = 'position:relative;margin-top:16px;';
    ppContainer.style.marginTop = '0';
    ppContainer.parentNode.insertBefore(ppWrapper, ppContainer);
    ppWrapper.appendChild(ppContainer);

    const ppOverlay = document.createElement('div');
    ppOverlay.id = 'paypal-btn-overlay';
    ppOverlay.style.cssText = [
      'position:absolute', 'inset:0', 'z-index:10',
      'background:rgba(255,255,255,0.82)', 'border-radius:8px',
      'display:flex', 'align-items:center', 'justify-content:center',
      'cursor:not-allowed',
    ].join(';');
    ppOverlay.innerHTML = '<p style="margin:0;font-size:12px;color:#64748b;text-align:center;padding:8px 16px;">Please fill in your name and email above to continue.</p>';
    ppWrapper.appendChild(ppOverlay);

    paypal.Buttons({
      style: { layout: 'vertical', color: 'blue', shape: 'rect', label: 'donate', height: 48 },

      /* ── Step 1: create the order on our server ── */
      createOrder: async () => {
        clearPaymentError();

        const firstName = document.getElementById('firstName').value.trim();
        const lastName  = document.getElementById('lastName').value.trim();
        const email     = document.getElementById('emailAddr').value.trim();

        if (!firstName || !lastName) {
          showPaymentError('Please enter your first and last name before continuing.');
          return undefined;
        }
        if (!email || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
          showPaymentError('Please enter a valid email address before continuing.');
          return undefined;
        }
        if (!selectedAmt || selectedAmt < 1) {
          showPaymentError('Please select a donation amount of at least $1.');
          return undefined;
        }

        try {
          const res = await fetch('/donate/paypal/order', {
            method:  'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
            body: JSON.stringify({
              campaign_name: selectedCampaign,
              first_name:    firstName,
              last_name:     lastName,
              email:         email,
              amount:        selectedAmt,
              frequency:     isMonthly ? 'monthly' : 'one-time',
            }),
          });

          const data = await res.json();

          if (!res.ok) {
            showPaymentError(data.message || data.error || 'Failed to initiate PayPal payment.');
            return undefined;
          }

          // Stash donation_id so the capture step can reference it
          window._ppDonationId = data.donation_id;
          return data.orderID;

        } catch (err) {
          showPaymentError('Network error. Please check your connection and try again.');
          return undefined;
        }
      },

      /* ── Step 2: capture after buyer approves ── */
      onApprove: async (data) => {
        try {
          const res = await fetch('/donate/paypal/capture', {
            method:  'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
            body: JSON.stringify({ order_id: data.orderID, donation_id: window._ppDonationId }),
          });

          const result = await res.json();

          if (!res.ok) {
            showPaymentError(result.error || 'Payment capture failed. Please contact support.');
            return;
          }

          // ── Success — show thank-you screen ──
          thankCampaign.textContent = selectedCampaign;
          thankAmt.textContent      = fmt(selectedAmt) + (isMonthly ? ' / month' : ' one-time');

          const message = `I just donated to ${selectedCampaign} via JDGM — join me!`;
          const pageUrl = encodeURIComponent(window.location.href);
          const text    = encodeURIComponent(message);
          document.getElementById('share-twitter').href  = `https://twitter.com/intent/tweet?text=${text}&url=${pageUrl}`;
          document.getElementById('share-facebook').href = `https://www.facebook.com/sharer/sharer.php?u=${pageUrl}`;
          document.getElementById('share-whatsapp').href = `https://wa.me/?text=${text}%20${pageUrl}`;

          showScreen(thankScreen);

        } catch (err) {
          showPaymentError('An unexpected error occurred. Please contact support if payment was taken.');
        }
      },

      onCancel: () => {
        showPaymentError('PayPal payment cancelled. You can try again whenever you\'re ready.');
      },

      onError: (err) => {
        console.error('PayPal SDK error:', err);
        showPaymentError('PayPal encountered an error. Please try again or use Credit/Debit Card.');
      },

    }).render('#paypal-button-container');

    paypalButtonsDone = true;
  }

  /* ── Complete donation ──────────────────────────────────── */
  ctaBtn.addEventListener('click', async () => {
    clearPaymentError();

    // PayPal uses its own Buttons; GCash is not yet live
    if (currentPayMethod !== 'card') {
      if (currentPayMethod === 'gcash') {
        showPaymentError('GCash integration is coming soon. Please use Credit/Debit Card or PayPal.');
      }
      return;
    }

    if (!stripe || !cardElement) {
      showPaymentError('Payment system is not configured. Please contact support.');
      return;
    }

    // Validate donor info
    const firstName = document.getElementById('firstName').value.trim();
    const lastName  = document.getElementById('lastName').value.trim();
    const email     = document.getElementById('emailAddr').value.trim();

    if (!firstName || !lastName) {
      showPaymentError('Please enter your first and last name.');
      return;
    }
    if (!email || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
      showPaymentError('Please enter a valid email address.');
      return;
    }
    if (!selectedAmt || selectedAmt < 1) {
      showPaymentError('Please select or enter a donation amount of at least $1.');
      return;
    }

    // Disable button and show processing state
    ctaBtn.disabled    = true;
    ctaBtn.textContent = '⏳ Processing…';

    try {
      /* Step 1 — Create PaymentIntent on our server */
      const chargeRes = await fetch('/donate/charge', {
        method: 'POST',
        headers: {
          'Content-Type':  'application/json',
          'X-CSRF-TOKEN':  csrfToken,
          'Accept':        'application/json',
        },
        body: JSON.stringify({
          campaign_name:  selectedCampaign,
          first_name:     firstName,
          last_name:      lastName,
          email:          email,
          amount:         selectedAmt,
          frequency:      isMonthly ? 'monthly' : 'one-time',
          payment_method: 'card',
        }),
      });

      const chargeData = await chargeRes.json();

      if (!chargeRes.ok) {
        throw new Error(chargeData.message || chargeData.error || 'Failed to initiate payment.');
      }

      const { client_secret, donation_id } = chargeData;

      /* Step 2 — Confirm payment client-side via Stripe */
      ctaBtn.textContent = '🔒 Verifying with Stripe…';

      const result = await stripe.confirmCardPayment(client_secret, {
        payment_method: {
          card: cardElement,
          billing_details: {
            name:  `${firstName} ${lastName}`,
            email: email,
          },
        },
      });

      if (result.error) {
        throw new Error(result.error.message);
      }

      /* Step 3 — Confirm server-side and update donation record */
      ctaBtn.textContent = '✅ Confirming…';

      const confirmRes = await fetch('/donate/confirm', {
        method: 'POST',
        headers: {
          'Content-Type':  'application/json',
          'X-CSRF-TOKEN':  csrfToken,
          'Accept':        'application/json',
        },
        body: JSON.stringify({
          payment_intent_id: result.paymentIntent.id,
          donation_id:       donation_id,
        }),
      });

      const confirmData = await confirmRes.json();

      if (!confirmRes.ok) {
        // Payment succeeded but record update failed — non-critical, show success anyway
        console.warn('Record update issue:', confirmData.error);
      }

      /* ── Success — show thank-you screen ── */
      thankCampaign.textContent = selectedCampaign;
      thankAmt.textContent      = fmt(selectedAmt) + (isMonthly ? ' / month' : ' one-time');

      const message = `I just donated to ${selectedCampaign} via JDGM — join me!`;
      const pageUrl = encodeURIComponent(window.location.href);
      const text    = encodeURIComponent(message);
      document.getElementById('share-twitter').href   = `https://twitter.com/intent/tweet?text=${text}&url=${pageUrl}`;
      document.getElementById('share-facebook').href  = `https://www.facebook.com/sharer/sharer.php?u=${pageUrl}`;
      document.getElementById('share-whatsapp').href  = `https://wa.me/?text=${text}%20${pageUrl}`;

      showScreen(thankScreen);

    } catch (err) {
      showPaymentError(err.message || 'An unexpected error occurred. Please try again.');
      resetCtaBtn();
    }
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
  const nav = document.getElementById('navbar');
  window.addEventListener('scroll', () => {
    nav.classList.toggle('scrolled', window.scrollY > 10);
  }, { passive: true });

  /* ── Mobile nav toggle ──────────────────────────────────── */
  const toggle    = document.getElementById('navToggle');
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

  /* ── Initial state ──────────────────────────────────────── */
  overviewScreen.style.display = 'block';
  donateScreen.style.display   = 'none';
  thankScreen.style.display    = 'none';
  backBtn.style.display        = 'none';
  setCampaign('Feed Filipino Children');

})();
