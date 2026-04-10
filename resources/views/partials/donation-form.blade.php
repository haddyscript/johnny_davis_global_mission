<!-- ============================================================
     DONATE BODY SCREEN
============================================================ -->
<div id="donate-body-screen">

  <!-- Hero -->
  <div class="donate-hero">
    <div class="donate-hero-inner">
      <nav class="breadcrumb" aria-label="Breadcrumb">
        <span class="bc-home">Make a Difference</span>
        <span class="bc-sep">›</span>
        <span class="bc-cur">Donate</span>
      </nav>
      <button id="back-to-campaigns-btn" aria-label="Back to all campaigns">← Back to all campaigns</button>
      <h1>Give Today — Change a Life</h1>
      <p>100% of your donation goes to field programs. No overhead surprises — just direct, traceable impact in the Philippines and Uganda.</p>
    </div>
  </div>

  <!-- Two-column form + sidebar -->
  <div class="donate-page-layout">

    <!-- LEFT: Multi-step form -->
    <div>

      <!-- Step 1: Campaign -->
      <div class="donate-step-title">
        <div class="step-num" aria-hidden="true">1</div>
        <span>Choose a Campaign</span>
      </div>

      <div class="campaign-list" role="radiogroup" aria-label="Choose a campaign">

        @foreach($campaigns as $i => $c)
        <div class="campaign-opt {{ $i === 0 ? 'active' : '' }}"
             data-campaign-opt="{{ $c->title }}"
             role="radio"
             aria-checked="{{ $i === 0 ? 'true' : 'false' }}"
             tabindex="0">
          <div class="camp-icon" aria-hidden="true">{{ $c->icon }}</div>
          <div style="flex:1;">
            <div class="camp-title">{{ $c->title }}</div>
            <div class="camp-sub">{{ $c->subtitle }}</div>
            <div class="camp-progress-wrap">
              <div class="progress">
                <div class="progress-fill" style="width:{{ $c->goal_pct }}%;{{ $c->bar_style }}"></div>
              </div>
            </div>
            <div class="camp-meta">{{ $c->meta }}</div>
          </div>
          <div class="camp-radio {{ $i === 0 ? 'on' : '' }}" aria-hidden="true"></div>
        </div>
        @endforeach

        <div class="campaign-opt" data-campaign-opt="Where it's needed most" role="radio" aria-checked="false" tabindex="0">
          <div class="camp-icon" aria-hidden="true">🌍</div>
          <div style="flex:1;">
            <div class="camp-title">Where it's needed most</div>
            <div class="camp-sub">JDGM allocates to the highest-impact area at that moment</div>
          </div>
          <div class="camp-radio" aria-hidden="true"></div>
        </div>

      </div>

      <!-- Campaign Story Card -->
      <div class="story-card">
        <div class="sidebar-heading">Campaign Story</div>
        <p id="campaign-story-text" style="font-size:.88rem;color:var(--text-body);line-height:1.75;margin-bottom:8px;">
          {{ $campaigns->first()?->story_full }}
        </p>
        <div id="campaign-goal-text" style="font-size:.82rem;font-weight:700;color:var(--orange-dark);">
          {{ $campaigns->first()?->goal_full }}
        </div>
      </div>

      <!-- Step 2: Amount -->
      <div class="donate-step-title" style="margin-top:6px;">
        <div class="step-num" aria-hidden="true">2</div>
        <span>Choose an Amount</span>
      </div>

      <div class="freq-tabs-wrap" role="group" aria-label="Giving frequency">
        <button class="dtab active" aria-pressed="true">Monthly giving</button>
        <button class="dtab" aria-pressed="false">One-time</button>
      </div>

      <div class="amounts-grid amounts-4" role="group" aria-label="Preset donation amounts">
        <button class="amount-btn" aria-label="Donate $7.99 — feeds 1 child">
          <span class="amount-price">$7.99</span>
          <span class="amount-impact">1 child fed</span>
        </button>
        <button class="amount-btn selected" aria-label="Donate $29.99 — feeds a family of 4" aria-pressed="true">
          <span class="amount-price">$29.99</span>
          <span class="amount-impact">Family of 4</span>
        </button>
        <button class="amount-btn" aria-label="Donate $59 — feeds 10 children">
          <span class="amount-price">$59</span>
          <span class="amount-impact">10 children</span>
        </button>
        <button class="amount-btn" aria-label="Donate $99 — village medicine">
          <span class="amount-price">$99</span>
          <span class="amount-impact">Village meds</span>
        </button>
      </div>

      <input class="custom-amount" id="customAmountInput" type="number" min="1"
             placeholder="Or enter a custom amount ($)"
             aria-label="Custom donation amount"
             style="max-width:320px;"/>

      <!-- Step 3: Your Info -->
      <div class="donor-info-section">
        <div class="donate-step-title">
          <div class="step-num" aria-hidden="true">3</div>
          <span>Your Information</span>
        </div>
        <div class="form-row">
          <div class="form-group">
            <label class="form-label" for="firstName">First name</label>
            <input class="form-input" id="firstName" type="text" placeholder="Maria" autocomplete="given-name"/>
          </div>
          <div class="form-group">
            <label class="form-label" for="lastName">Last name</label>
            <input class="form-input" id="lastName" type="text" placeholder="Santos" autocomplete="family-name"/>
          </div>
        </div>
        <div class="form-group">
          <label class="form-label" for="emailAddr">Email address</label>
          <input class="form-input" id="emailAddr" type="email" placeholder="maria@example.com" autocomplete="email"/>
        </div>
        <div class="email-note" role="note">
          <span>📧</span>
          <span>We'll send your tax-deductible receipt and monthly impact updates to this address.</span>
        </div>
      </div>

      <!-- Step 4: Payment -->
      <div class="donate-step-title">
        <div class="step-num" aria-hidden="true">4</div>
        <span>Payment Details</span>
      </div>

      <div class="pay-method-selector" role="tablist" aria-label="Payment method">
        <button class="pay-tab active" id="pay-tab-card" role="tab" aria-selected="true" aria-controls="pay-panel-card">
          <span aria-hidden="true">💳</span> Credit / Debit Card
        </button>
        <button class="pay-tab" id="pay-tab-gcash" role="tab" aria-selected="false" aria-controls="pay-panel-gcash">
          <span style="width:22px;height:22px;border-radius:6px;background:#007DFF;display:inline-flex;align-items:center;justify-content:center;font-size:13px;font-weight:900;color:#fff;flex-shrink:0;" aria-hidden="true">G</span>
          GCash
        </button>
        <button class="pay-tab" id="pay-tab-paypal" role="tab" aria-selected="false" aria-controls="pay-panel-paypal">
          <span style="width:22px;height:22px;border-radius:6px;background:#0070BA;display:inline-flex;align-items:center;justify-content:center;font-size:13px;font-weight:900;color:#fff;flex-shrink:0;" aria-hidden="true">P</span>
          PayPal
        </button>
      </div>

      <!-- Card panel — Stripe Elements -->
      <div id="pay-panel-card" class="payment-box" role="tabpanel" aria-labelledby="pay-tab-card">
        <label class="form-label">Card Details</label>
        <div id="stripe-card-element" class="stripe-card-container"></div>
        <div id="stripe-card-errors" class="stripe-error-msg" role="alert" style="display:none;"></div>
        <p class="stripe-note">🔒 Payments secured by Stripe · PCI-DSS compliant · We never store card data</p>
      </div>

      <!-- GCash panel -->
      <div id="pay-panel-gcash" style="display:none;" class="gcash-panel" role="tabpanel" aria-labelledby="pay-tab-gcash">
        <div class="gcash-header">
          <div class="gcash-logo-badge" aria-hidden="true">G</div>
          <div>
            <div class="gcash-label">GCash Mobile Payment</div>
            <div class="gcash-sublabel">Fast, cashless giving via your GCash wallet · Philippines</div>
          </div>
        </div>
        <label class="form-label" for="gcashNumber">GCash-registered mobile number</label>
        <input class="form-input" id="gcashNumber" placeholder="+63 9XX XXX XXXX" type="tel" style="margin-bottom:0;" autocomplete="tel"/>
        <div class="gcash-steps">
          <div class="gcash-step"><div class="gcash-step-num" aria-hidden="true">1</div>Enter your GCash number above and tap the button below</div>
          <div class="gcash-step"><div class="gcash-step-num" aria-hidden="true">2</div>Open your GCash app — you'll receive a payment request notification instantly</div>
          <div class="gcash-step"><div class="gcash-step-num" aria-hidden="true">3</div>Confirm the amount with your GCash MPIN to complete your gift</div>
        </div>
        <p class="gcash-note">📱 Your tax-deductible receipt will be sent to your GCash-registered email and your JDGM donor account.</p>
      </div>

      <!-- PayPal panel -->
      <div id="pay-panel-paypal" style="display:none;" class="paypal-panel" role="tabpanel" aria-labelledby="pay-tab-paypal">
        <div class="paypal-header">
          <div class="paypal-logo-badge" aria-hidden="true">P</div>
          <div>
            <div class="paypal-label">PayPal</div>
            <div class="paypal-sublabel">Secure online payments worldwide · Fast and trusted</div>
          </div>
        </div>
        <div class="paypal-benefits">
          <div class="paypal-benefit"><span aria-hidden="true">🔒</span><span>Bank-level security with buyer protection</span></div>
          <div class="paypal-benefit"><span aria-hidden="true">🌍</span><span>Available in 200+ countries and regions</span></div>
          <div class="paypal-benefit"><span aria-hidden="true">⚡</span><span>Instant processing with email confirmation</span></div>
        </div>
        {{-- PayPal Buttons are rendered here by the JS SDK --}}
        <div id="paypal-button-container" style="margin-top:16px;min-height:48px;"></div>
        <p class="paypal-note" style="margin-top:12px;">💳 You can pay with your PayPal balance, bank account, or credit card. Your tax-deductible receipt will be emailed after payment.</p>
      </div>

      <!-- Payment error -->
      <div id="payment-error-msg" class="stripe-error-msg" role="alert" style="display:none;margin-top:14px;"></div>

      <!-- Donate CTA -->
      <button class="btn-donate" id="completeDonationBtn" style="margin-top:16px;" aria-label="Complete donation">
        &#9829; Complete Gift — $29.99 / Month
      </button>
      <p class="cancel-note">You can cancel or change your giving amount anytime from your email.</p>

    </div><!-- /LEFT -->


    <!-- RIGHT: Sidebar -->
    <aside aria-label="Donation summary">

      <!-- Impact -->
      <div class="card" style="margin-bottom:18px;">
        <div class="sidebar-heading" id="donate-impact-heading">Your $29.99/mo Impact</div>
        <div class="impact-rows">
          <div class="impact-row">
            <div class="impact-emoji" aria-hidden="true">🍽️</div>
            <div class="impact-copy"><strong>$7.99/mo</strong> feeds one child nutritious daily meals for an entire month</div>
          </div>
          <div class="impact-row">
            <div class="impact-emoji" aria-hidden="true">👨‍👩‍👧‍👦</div>
            <div class="impact-copy"><strong>$29.99/mo</strong> feeds a family of four every single day of the month</div>
          </div>
          <div class="impact-row">
            <div class="impact-emoji" aria-hidden="true">💊</div>
            <div class="impact-copy"><strong>$59/mo</strong> provides medicine for 10 children per month</div>
          </div>
          <div class="impact-row">
            <div class="impact-emoji" aria-hidden="true">🏥</div>
            <div class="impact-copy"><strong>$99/mo</strong> covers medicine for an entire village clinic visit</div>
          </div>
        </div>
      </div>

      <!-- Order Summary -->
      <div class="card" style="margin-bottom:18px;">
        <div class="sidebar-heading">Order Summary</div>
        <div class="order-line">
          <span id="donate-order-campaign">Feed Filipino Children</span>
          <span id="donate-order-type" style="font-size:.78rem;background:var(--blue-pale);color:var(--blue-mid);padding:3px 10px;border-radius:var(--r-pill);font-weight:600;">Monthly</span>
        </div>
        <div class="order-line">
          <span>Donation amount</span>
          <span style="font-weight:700;" id="donate-order-amount">$29.99</span>
        </div>
        <div class="order-line">
          <span>Processing fee (waived)</span>
          <span style="color:var(--orange);font-weight:600;">$0.00</span>
        </div>
        <div class="order-total">
          <span id="donate-total-label">Monthly total</span>
          <span class="order-total-amount" id="donate-order-total">$29.99</span>
        </div>
      </div>

      <!-- Trust -->
      <div class="card" style="margin-bottom:18px;">
        <div class="sidebar-heading">Giving Assurance</div>
        <div class="trust-chips" role="list">
          <div class="trust-chip" role="listitem">🔒 SSL Secured</div>
          <div class="trust-chip" role="listitem">🧾 501(c)(3)</div>
          <div class="trust-chip" role="listitem">✅ Tax deductible</div>
          <div class="trust-chip" role="listitem">♻️ Cancel anytime</div>
          <div class="trust-chip" role="listitem">💯 100% to field</div>
        </div>
        <p class="assurance-note">Johnny Davis personally oversees all fund disbursements. Annual reports and 990 filings available on our <span>Transparency page</span>.</p>
      </div>

      <!-- Pastor Note -->
      <div class="pastor-note-card">
        <div class="pastor-note-header">
          <img src="{{ $pastorImg }}" alt="Pastor Johnny Davis" />
          <div>
            <div class="pastor-name">A note from Pastor Johnny</div>
            <div class="pastor-role">Founder &amp; Executive Director</div>
          </div>
        </div>
        <p class="pastor-quote">"Every dollar you give is a statement of faith that a child's life has value. We are honored to be your hands in the Philippines and Uganda."</p>
      </div>

    </aside><!-- /sidebar -->

  </div><!-- /donate-page-layout -->

</div><!-- /donate-body-screen -->
