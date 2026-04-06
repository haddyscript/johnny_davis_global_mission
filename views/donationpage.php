<?php
// ============================================================
// Page Configuration
// ============================================================
$page_title       = 'Donate — Johnny Davis Global Missions';
$page_description = 'Donate to Johnny Davis Global Missions — Feed Filipino Children, support disaster relief, and bring hope to communities in need.';
$base ??= '../'; // '../' when accessed directly; '' when included from project root

// ============================================================
// Campaign Data
// ============================================================
$campaigns = [
    [
        'key'      => 'Feed Filipino Children',
        'icon'     => '🍽️',
        'label'    => 'Active Campaign',
        'status'   => 'active-campaign',
        'image'    => 'images/landingpage/feed_the_hungry.webp',
        'goal'     => '$45,000',
        'pct'      => 72,
        'raised'   => '$32,480',
        'total'    => '$45,000',
        'bar_style'=> '',
        'sub'      => '$7.99/month feeds one child — 100+ children currently enrolled',
        'meta'     => '$32,480 raised of $45,000 goal · 72% funded',
        'snippet'  => 'School lunch programs for 100+ children in Cebu City, building nutrition and future stability one meal at a time.',
        'story'    => '"Juan\'s first full meal in weeks."',
        'story_full' => 'Feed Filipino Children is providing hot meals and academic support to over 100 students in Cebu every month.',
        'goal_full'  => 'Goal: $45,000 to reach 100+ children for full school year',
        'tour_icon'  => '🍽️',
        'tour_num'   => 'Campaign 1',
    ],
    [
        'key'      => 'Cebu Earthquake Relief',
        'icon'     => '🏚️',
        'label'    => 'Urgent Relief',
        'status'   => 'urgent',
        'image'    => 'images/landingpage/pray_for_earthquake_victems.webp',
        'goal'     => '$30,000',
        'pct'      => 41,
        'raised'   => '$12,300',
        'total'    => '$30,000',
        'bar_style'=> 'background:linear-gradient(90deg,#c0392b,#e74c3c);',
        'sub'      => 'Emergency shelter, food packets, and rebuilding supplies for quake-affected families',
        'meta'     => '$12,300 raised of $30,000 goal · 41% funded',
        'snippet'  => 'Urgent tents, water, and medical care for communities displaced by the recent earthquake.',
        'story'    => '"Rebuilding hope after the tremors."',
        'story_full' => '',
        'goal_full'  => '',
        'tour_icon'  => '🏠',
        'tour_num'   => 'Campaign 2',
    ],
    [
        'key'      => 'Uganda Water Wells',
        'icon'     => '💧',
        'label'    => 'Water Access',
        'status'   => 'water',
        'image'    => 'images/landingpage/clean_drink_water.webp',
        'goal'     => '$22,000',
        'pct'      => 58,
        'raised'   => '$5,220',
        'total'    => '$9,000',
        'bar_style'=> 'background:linear-gradient(90deg,#1a4480,#2563a8);',
        'sub'      => '$4,500 funds one well · clean water for 200 people for 25 years',
        'meta'     => '$5,220 raised of $9,000 goal · 58% funded',
        'snippet'  => 'Permanent clean wells serving villages in Soroti — health and opportunity for 200 people each.',
        'story'    => '"No more boiled river water."',
        'story_full' => '',
        'goal_full'  => '',
        'tour_icon'  => '💧',
        'tour_num'   => 'Campaign 3',
    ],
];

$pastor_img = 'https://d14tal8bchn59o.cloudfront.net/RhGkp7h3Fm5bBymv78FLEpsQSnC3q7PFpecGpojrkak/w:2000/plain/https://02f0a56ef46d93f03c90-22ac5f107621879d5667e0d7ed595bdb.ssl.cf2.rackcdn.com/sites/104216/photos/23052432/JDM_Logo_6_original.jpg';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <meta name="description" content="<?= htmlspecialchars($page_description) ?>"/>
  <title><?= htmlspecialchars($page_title) ?></title>

  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com"/>
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin/>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;800&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet"/>

  <link rel="stylesheet" href="<?= $base ?>style/for_donationpage.css"/>
</head>
<body>


<!-- ============================================================
     NAVBAR
============================================================ -->
<header id="navbar-wrap">
  <nav class="navbar" id="mainNav" role="navigation" aria-label="Main navigation">
    <a href="<?= $base ?>index.php" class="nav-logo" aria-label="Johnny Davis Global Missions Home">
      <img src="<?= $base ?>images/logo.webp" alt="Johnny Davis Global Missions" />
    </a>
    <div class="nav-links">
      <a href="<?= $base ?>index.php" class="nav-link">Home</a>
      <a href="<?= $base ?>who-we-are.php" class="nav-link">Who We Are</a>
      <a href="<?= $base ?>index.php#help" class="nav-link">What We Do</a>
      <a href="<?= $base ?>donationpage.php" class="nav-link active">Make a Difference</a>
      <a href="<?= $base ?>contact.php" class="nav-link">Contact Us</a>
      <a href="<?= $base ?>donationpage.php" class="nav-cta">&#9829; Donate Now</a>
    </div>
    <button class="nav-toggle" id="navToggle" aria-label="Open menu" aria-expanded="false">
      <span></span><span></span><span></span>
    </button>
  </nav>

  <nav class="nav-mobile" id="navMobile" aria-label="Mobile navigation">
    <a href="<?= $base ?>index.php" class="nav-link">Home</a>
    <a href="<?= $base ?>who-we-are.php" class="nav-link">Who We Are</a>
    <a href="<?= $base ?>index.php#help" class="nav-link">What We Do</a>
    <a href="<?= $base ?>donationpage.php" class="nav-link active">Make a Difference</a>
    <a href="<?= $base ?>contact.php" class="nav-link">Contact Us</a>
    <a href="<?= $base ?>donationpage.php" class="nav-cta">&#9829; Donate Now</a>
  </nav>
</header>


<!-- ============================================================
     CAMPAIGN OVERVIEW SCREEN
============================================================ -->
<div id="campaign-overview-screen">
  <p class="screen-label">Make a Difference</p>
  <h1 class="screen-title">Campaigns for Change</h1>
  <p class="screen-sub">Pick one cause to support, read the story, and watch your gift power real impact in the field.</p>

  <div class="campaign-pages-grid">
    <?php foreach ($campaigns as $c): ?>
    <div class="campaign-page-card reveal"
         data-campaign="<?= htmlspecialchars($c['key']) ?>"
         role="button" tabindex="0"
         aria-label="Support <?= htmlspecialchars($c['key']) ?> campaign">
      <div class="campaign-img-area"
           style="background-image:url('<?= $base . htmlspecialchars($c['image']) ?>');"
           role="img"
           aria-label="<?= htmlspecialchars($c['snippet']) ?>"></div>
      <div class="card-body-inner">
        <p class="card-meta"><?= htmlspecialchars($c['label']) ?></p>
        <h3><?= htmlspecialchars($c['key']) ?></h3>
        <small>Goal <?= htmlspecialchars($c['goal']) ?> · <?= $c['pct'] ?>% reached</small>
        <div class="progress" style="margin:6px 0 10px;">
          <div class="progress-fill" style="width:<?= $c['pct'] ?>%;<?= $c['bar_style'] ?>"></div>
        </div>
        <p class="story-snippet"><?= htmlspecialchars($c['snippet']) ?></p>
        <p class="card-goal">Story: <?= htmlspecialchars($c['story']) ?></p>
        <span class="card-cta-link" aria-hidden="true">Support this campaign ›</span>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
</div>


<!-- ============================================================
     CAMPAIGN TOUR OVERLAY
============================================================ -->
<div id="campaign-tour-overlay" class="tour-overlay" hidden aria-live="assertive" role="dialog" aria-labelledby="tour-title">
  <div class="tour-backdrop"></div>
  <div class="tour-content">

    <div class="tour-progress">
      <div class="tour-progress-dots">
        <?php foreach ($campaigns as $i => $c): ?>
        <span class="tour-dot <?= $i === 0 ? 'active' : '' ?>" data-step="<?= $i ?>"></span>
        <?php endforeach; ?>
      </div>
      <button class="tour-skip" aria-label="Skip tour">Skip Tour</button>
    </div>

    <?php foreach ($campaigns as $i => $c): ?>
    <div class="tour-step <?= $i === 0 ? 'active' : '' ?>" data-step="<?= $i ?>">
      <div class="tour-header">
        <span class="tour-icon"><?= $c['tour_icon'] ?></span>
        <h3 <?= $i === 0 ? 'id="tour-title"' : '' ?>><?= htmlspecialchars($c['tour_num']) ?>: <?= htmlspecialchars($c['key']) ?></h3>
      </div>
      <div class="tour-meta">
        <span class="tour-status <?= $c['status'] ?>"><?= htmlspecialchars($c['label']) ?></span>
        <span class="tour-goal">Goal <?= htmlspecialchars($c['goal']) ?> · <?= $c['pct'] ?>% reached</span>
      </div>
      <p class="tour-description"><?= htmlspecialchars($c['snippet']) ?></p>
      <p class="tour-story"><?= htmlspecialchars($c['story']) ?></p>
      <div class="tour-actions">
        <?php if ($i > 0): ?>
        <button class="tour-btn tour-prev" aria-label="Go to previous campaign">
          <span class="btn-icon">←</span> Previous
        </button>
        <?php endif; ?>
        <?php if ($i < count($campaigns) - 1): ?>
        <button class="tour-btn tour-next" aria-label="Go to next campaign">
          Next Campaign → <span class="btn-icon"><?= $campaigns[$i + 1]['tour_icon'] ?></span>
        </button>
        <?php else: ?>
        <button class="tour-btn tour-cta" aria-label="Choose a campaign to support">
          Choose a Campaign to Support <span class="btn-icon">🎯</span>
        </button>
        <?php endif; ?>
      </div>
    </div>
    <?php endforeach; ?>

    <div class="tour-footer">
      <p class="tour-instruction">Use arrow keys to navigate • Press Escape or click outside to close</p>
    </div>

    <button class="tour-close" aria-label="Close tour">✕</button>
  </div>
</div>


<!-- ============================================================
     LOADING SCREEN
============================================================ -->
<div id="loading-screen" class="loading-screen" hidden>
  <div class="loading-content">
    <div class="loading-spinner">
      <div class="spinner-ring"></div>
      <div class="spinner-ring"></div>
      <div class="spinner-ring"></div>
    </div>
    <h2 class="loading-title">Preparing Your Impact</h2>
    <p class="loading-subtitle">Setting up your donation for maximum change...</p>
    <div class="loading-progress">
      <div class="progress-bar">
        <div class="progress-fill"></div>
      </div>
    </div>
  </div>
</div>


<!-- ============================================================
     DONATE BODY SCREEN
============================================================ -->
<div id="donate-body-screen">

  <!-- Hero -->
  <div class="donate-hero">
    <div class="donate-hero-inner">
      <nav class="breadcrumb" aria-label="Breadcrumb">
        <span class="bc-home">Home</span>
        <span class="bc-sep">›</span>
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

        <?php foreach ($campaigns as $i => $c): ?>
        <div class="campaign-opt <?= $i === 0 ? 'active' : '' ?>"
             data-campaign-opt="<?= htmlspecialchars($c['key']) ?>"
             role="radio"
             aria-checked="<?= $i === 0 ? 'true' : 'false' ?>"
             tabindex="0">
          <div class="camp-icon" aria-hidden="true"><?= $c['icon'] ?></div>
          <div style="flex:1;">
            <div class="camp-title"><?= htmlspecialchars($c['key']) ?></div>
            <div class="camp-sub"><?= htmlspecialchars($c['sub']) ?></div>
            <div class="camp-progress-wrap">
              <div class="progress">
                <div class="progress-fill" style="width:<?= $c['pct'] ?>%;<?= $c['bar_style'] ?>"></div>
              </div>
            </div>
            <div class="camp-meta"><?= htmlspecialchars($c['meta']) ?></div>
          </div>
          <div class="camp-radio <?= $i === 0 ? 'on' : '' ?>" aria-hidden="true"></div>
        </div>
        <?php endforeach; ?>

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
          <?= htmlspecialchars($campaigns[0]['story_full']) ?>
        </p>
        <div id="campaign-goal-text" style="font-size:.82rem;font-weight:700;color:var(--orange-dark);">
          <?= htmlspecialchars($campaigns[0]['goal_full']) ?>
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

      <!-- Card panel -->
      <div id="pay-panel-card" class="payment-box" role="tabpanel" aria-labelledby="pay-tab-card">
        <label class="form-label" for="cardNumber">Card number</label>
        <input class="card-field" id="cardNumber" placeholder="•••• •••• •••• ••••" inputmode="numeric" autocomplete="cc-number"/>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
          <div>
            <label class="form-label" for="cardExpiry">Expiry</label>
            <input class="card-field" id="cardExpiry" placeholder="MM / YY" style="margin-bottom:0;" autocomplete="cc-exp"/>
          </div>
          <div>
            <label class="form-label" for="cardCvc">CVC</label>
            <input class="card-field" id="cardCvc" placeholder="CVC" style="margin-bottom:0;" autocomplete="cc-csc"/>
          </div>
        </div>
        <p class="stripe-note">🔒 Payments processed by Stripe · PCI compliant · We never store card data</p>
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
        <p class="paypal-note">💳 You can pay with your PayPal balance, bank account, or credit card. Your tax-deductible receipt will be sent to your PayPal-registered email.</p>
      </div>

      <!-- Donate CTA -->
      <button class="btn-donate" id="completeDonationBtn" style="margin-top:28px;" aria-label="Complete donation">
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
          <img src="<?= $pastor_img ?>" alt="Pastor Johnny Davis" />
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


<!-- ============================================================
     THANK YOU SCREEN
============================================================ -->
<div id="thank-you-screen">

  <div class="thank-header">
    <img src="<?= $base ?>images/logo.webp" alt="Johnny Davis Global Missions" />
    <div class="thank-header-line"></div>
  </div>

  <div class="thank-card">
    <h1 class="thank-title">You Did It — Thank You!</h1>
    <p class="thank-subtitle">Your gift is now making immediate impact. Pastor Johnny and the whole ministry team are so grateful for your generosity.</p>

    <div class="pastor-msg-block">
      <div class="pastor-msg-header">
        <img src="<?= $pastor_img ?>" alt="Pastor Johnny Davis" />
        <div>
          <div class="pastor-msg-name">A personal note from Pastor Johnny</div>
          <div class="pastor-msg-role">Founder &amp; Executive Director</div>
        </div>
      </div>
      <p class="pastor-msg-text">
        <strong>"Dear friend,</strong> thank you for your compassionate giving. Your donation changes lives through food, shelter, and water projects. We are praying for you and your family as this gift multiplies in the field."
      </p>
    </div>

    <div class="social-share-card">
      <div>
        <div class="social-share-title">Share Your Support</div>
        <p class="social-share-sub">Inspire others to join this cause today.</p>
      </div>
      <div class="social-btns">
        <a id="share-twitter"   class="social-btn-sm twitter"   target="_blank" rel="noopener noreferrer" href="#">Twitter</a>
        <a id="share-facebook"  class="social-btn-sm facebook"  target="_blank" rel="noopener noreferrer" href="#">Facebook</a>
        <a id="share-whatsapp"  class="social-btn-sm whatsapp"  target="_blank" rel="noopener noreferrer" href="#">WhatsApp</a>
      </div>
    </div>

    <p class="tell-friend-label">Tell a friend:</p>
    <div class="tell-friend-row">
      <input id="tell-friend-email" type="email" placeholder="Enter friend's email" class="tell-input" aria-label="Friend's email address"/>
      <button id="tell-friend-btn" class="tell-action">Send Invite</button>
    </div>
    <p class="tell-hint">The email is generated locally; press Send and continue your day with peace.</p>

    <p class="thank-summary">
      Campaign: <strong id="thank-campaign-name">Feed Filipino Children</strong> &nbsp;·&nbsp;
      Donation: <strong id="thank-amount">$29.99 / month</strong>
    </p>

    <button id="back-to-campaigns-from-thank" class="back-btn">← Return to Campaigns</button>
  </div>
</div>


<!-- ============================================================
     FOOTER
============================================================ -->
<footer id="footer" role="contentinfo">
  <div class="footer-inner">

    <div class="footer-brand">
      <img src="<?= $base ?>images/logo.webp" alt="Johnny Davis Global Missions" />
      <p class="footer-tagline">A nonprofit founded on the belief that a little help can go a long way. Serving communities in the Philippines and Uganda.</p>
      <div class="footer-socials" aria-label="Social media links">
        <a href="https://www.facebook.com/GlobalMissions55" class="social-icon" aria-label="Facebook">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg>
        </a>
        <a href="https://www.instagram.com/globalmissions50/" class="social-icon" aria-label="Instagram">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/></svg>
        </a>
        <a href="https://www.youtube.com/@johnnydavisministries" class="social-icon" aria-label="YouTube">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M22.54 6.42a2.78 2.78 0 0 0-1.95-1.96C18.88 4 12 4 12 4s-6.88 0-8.59.46a2.78 2.78 0 0 0-1.95 1.96A29 29 0 0 0 1 12a29 29 0 0 0 .46 5.58A2.78 2.78 0 0 0 3.41 19.6C5.12 20 12 20 12 20s6.88 0 8.59-.46a2.78 2.78 0 0 0 1.95-1.95A29 29 0 0 0 23 12a29 29 0 0 0-.46-5.58z"/><polygon points="9.75 15.02 15.5 12 9.75 8.98 9.75 15.02" fill="white"/></svg>
        </a>
        <a href="https://www.tiktok.com/@johnnydavisministries" class="social-icon" aria-label="TikTok">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75-.08 1.4-.54 2.79-1.35 3.94-1.31 1.92-3.58 3.17-5.91 3.21-1.43.08-2.86-.31-4.08-1.03-2.02-1.19-3.44-3.37-3.65-5.71-.02-.5-.03-1-.01-1.49.18-1.9 1.12-3.72 2.58-4.96 1.66-1.44 3.98-2.13 6.15-1.72.02 1.48-.04 2.96-.04 4.44-.99-.32-2.15-.23-3.02.37-.63.41-1.11 1.04-1.36 1.75-.21.51-.15 1.07-.14 1.61.24 1.64 1.82 3.02 3.5 2.87 1.12-.01 2.19-.66 2.77-1.61.19-.33.4-.67.41-1.06.1-1.79.06-3.57.07-5.36.01-4.03-.01-8.05.02-12.07z"/></svg>
        </a>
      </div>
    </div>

    <nav aria-label="Quick links">
      <p class="footer-col-title">Quick Links</p>
      <div class="footer-links">
        <a href="<?= $base ?>index.php">Home</a>
        <a href="<?= $base ?>who-we-are.php">Who We Are</a>
        <a href="<?= $base ?>index.php#help">What We Do</a>
        <a href="<?= $base ?>donationpage.php">Make a Difference</a>
        <a href="https://johnnydavisministries.org" target="_blank" rel="noopener noreferrer">Johnny Davis Ministries</a>
        <a href="<?= $base ?>contact.php">Contact Us</a>
      </div>
    </nav>

    <nav aria-label="Our programs">
      <p class="footer-col-title">Programs</p>
      <div class="footer-links">
        <a href="<?= $base ?>index.php#urgency">Feed the Hungry</a>
        <a href="<?= $base ?>index.php#disaster">Disaster Relief</a>
        <a href="<?= $base ?>index.php#help">Medical Missions</a>
        <a href="<?= $base ?>index.php#help">Education Support</a>
        <a href="<?= $base ?>index.php#help">Clean Water</a>
        <a href="<?= $base ?>index.php#help">Community Outreach</a>
      </div>
    </nav>

    <address>
      <p class="footer-col-title">Contact</p>
      <div class="footer-contact">
        <div class="fc-row">
          <div class="fc-icon" aria-hidden="true">📍</div>
          <span>P.O. Box 1904<br/>Suwanee, GA 30024</span>
        </div>
        <div class="fc-row">
          <div class="fc-icon" aria-hidden="true">📞</div>
          <a href="tel:+14044262856">(404) 426-2856</a>
        </div>
        <div class="fc-row">
          <div class="fc-icon" aria-hidden="true">✉️</div>
          <a href="mailto:info@johnnydavisglobalmissions.org">info@johnnydavisglobalmissions.org</a>
        </div>
        <div class="fc-row">
          <div class="fc-icon" aria-hidden="true">🕐</div>
          <span>Mon–Fri: 9:00am – 5:00pm</span>
        </div>
      </div>
    </address>

  </div>

  <div class="footer-bottom">
    <p class="footer-copy">&copy;<?= date('Y') ?> Johnny Davis Global Missions. All Rights Reserved.</p>
    <nav class="footer-legal" aria-label="Legal">
      <a href="#">Privacy Policy</a>
      <a href="#">Terms of Use</a>
      <a href="#">Nonprofit Disclosure</a>
    </nav>
  </div>
</footer>


<script src="<?= $base ?>js/for_donationpage.js"></script>

</body>
</html>
