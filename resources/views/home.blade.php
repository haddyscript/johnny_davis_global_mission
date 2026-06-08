<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="description" content="{{ $description }}" />
  <title>{{ $title }}</title>

  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,700;0,800;1,700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet" />

  <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,
    <svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'>
      <rect width='100' height='100' fill='%23000'/>
      <text x='50%' y='50%' 
            font-size='34' text-anchor='middle' 
            fill='%23fff' 
            font-family='Arial, sans-serif' 
            font-weight='bold'
            letter-spacing='-1'
            dy='.35em'>
        JDGM
      </text>
    </svg>">
  <link rel="stylesheet" href="{{ asset('css/for_index.css') }}?v={{ filemtime(public_path('css/for_index.css')) }}" />
</head>
<body>

@include('partials.nav')

<a id="stickyDonate" class="btn btn-primary wwa-donate-btn" href="{{ route('donate') }}" aria-label="Donate Now"><span class="wwa-heart" aria-hidden="true">&#9829;</span> Donate Now</a>


<!-- ============================================================
     VIDEO MODAL
============================================================ -->
<div class="modal-overlay" id="videoModal" role="dialog" aria-modal="true" aria-label="Mission video">
  <div class="modal-box">
    <button class="modal-close" id="modalClose" aria-label="Close video">&times;</button>
    <iframe id="youtubeFrame"
      src=""
      title="Johnny Davis Global Missions — Our Mission"
      allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
      referrerpolicy="strict-origin-when-cross-origin"
      allowfullscreen>
    </iframe>
    <div id="videoFallback" class="video-fallback" hidden>
      <p>Video cannot be played here due to YouTube restrictions. Watch directly on YouTube:</p>
      <a href="https://www.youtube.com/watch?v=s3DO45gx0KY" target="_blank" rel="noopener noreferrer">Watch on YouTube</a>
    </div>
  </div>
</div>


<!-- ============================================================
     FARMERS VIDEO MODAL
============================================================ -->
<div class="modal-overlay" id="farmersVideoModal" role="dialog" aria-modal="true" aria-label="Gensan Farmers Partnership video">
  <div class="modal-box">
    <button class="modal-close" id="farmersModalClose" aria-label="Close video">&times;</button>
    <iframe id="farmersYoutubeFrame"
      src=""
      title="Johnny Davis Global Missions — Farmers of Sarangani Province"
      allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
      referrerpolicy="strict-origin-when-cross-origin"
      allowfullscreen>
    </iframe>
  </div>
</div>


<!-- ============================================================
     HERO
============================================================ -->
<section id="hero" aria-label="Hero — Feed Filipino Children">
  <div class="hero-bg" id="heroBg"></div>
  <div class="hero-overlay"></div>

  <div class="container">
    <div class="hero-content">
      <div class="hero-badge" aria-label="Active campaign">{{ $cms->text('hero', 'eyebrow', 'Active Campaign 2026') }}</div>

      @if($cms->has('hero', 'headline'))
        <h1 class="hero-headline">{{ $cms->text('hero', 'headline', '') }}
          <span class="accent">"Hunger Can't Wait"</span>
        </h1>
      @else
        <h1 class="hero-headline">
          Feed Filipino Children
          <span class="accent">"Hunger Can't Wait"</span>
        </h1>
      @endif

      <p class="hero-sub">
        <!-- {!! nl2br(e($cms->text('hero', 'subtitle', "Help us fight hunger and crisis in the Philippines.\n Together we can make a difference."))) !!} -->
         Help us fight hunger and crisis in the Philippines. </b> Together we can make a difference.
      </p>

      <div class="hero-ctas">
        <a href="{{ $cms->url('hero', 'primary_cta_label', route('donate')) }}" class="btn btn-primary btn-lg hero-donate-btn">
          <span class="hero-heart" aria-hidden="true">&#9829;</span> Donate Now / $7.99 Monthly
        </a>
        <button class="btn btn-outline btn-lg hero-watch-btn" id="watchMissionBtn" aria-label="Watch our mission video">
          <svg class="hero-play-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M8 5v14l11-7z"/></svg>
          <span class="hero-watch-text">{{ $cms->text('hero', 'secondary_cta_label', 'Watch Our Mission') }}</span>
        </button>
        <button class="btn btn-lg hero-farmers-btn" id="watchFarmersBtn" aria-label="Watch our Gensan Farmers Partnership video">
          <svg class="hero-play-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M8 5v14l11-7z"/></svg>
          <span>🌾 Gensan Farmers <span class="farmers-new-tag">NEW</span></span>
        </button>
      </div>

      <div class="hero-stats" role="list" aria-label="Impact statistics">
        @php $heroStats = $cms->listItems('hero', 'stats'); @endphp
        @if(!empty($heroStats))
          @foreach($heroStats as $stat)
            <div class="hero-stat" role="listitem">
              <strong>{{ $stat['value'] ?? '' }}</strong>
              <span>{{ $stat['label'] ?? '' }}</span>
            </div>
          @endforeach
        @else
          <div class="hero-stat" role="listitem">
            <strong>5,000+</strong>
            <span>Children Fed</span>
          </div>
          <div class="hero-stat" role="listitem">
            <strong>12+</strong>
            <span>Communities Served</span>
          </div>
          <div class="hero-stat" role="listitem">
            <strong>$7.99</strong>
            <span>Feeds a Child Monthly</span>
          </div>
        @endif
      </div>
    </div>
  </div>

  <a href="#mission" class="hero-scroll" aria-label="Scroll to Mission section">
    <div class="hero-scroll-line"></div>
    <span>Scroll</span>
  </a>
</section>


<!-- ============================================================
     FARMERS PARTNERSHIP MISSION SECTION
============================================================ -->
<section id="farmers-mission" aria-labelledby="farmers-title">
  <div class="farmers-mission-bg" aria-hidden="true"></div>
  <div class="container">
    <div class="farmers-mission-inner">

      {{-- Left: Video card --}}
      <div class="farmers-video-col reveal-left">
        <button class="farmers-video-card" id="watchFarmersFromSection" aria-label="Watch Gensan Farmers Partnership video">
          <div class="farmers-video-thumb">
            <img src="https://img.youtube.com/vi/TRVCOGhuZUQ/maxresdefault.jpg"
                 alt="Farmers in the mountains of Sarangani Province, Philippines"
                 loading="lazy"/>
            <div class="farmers-play-overlay">
              <div class="farmers-play-btn">
                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="white" aria-hidden="true"><path d="M8 5v14l11-7z"/></svg>
              </div>
              <span class="farmers-play-label">Watch Video</span>
            </div>
          </div>
          <div class="farmers-video-footer">
            <span class="farmers-video-tag"><span aria-hidden="true">🌾</span> New Mission</span>
            <span class="farmers-video-title">Sarangani Province · Gensan Farmers</span>
          </div>
        </button>
      </div>

      {{-- Right: Mission text --}}
      <div class="farmers-text-col reveal-right">
        <div class="farmers-eyebrow">
          <span class="farmers-eyebrow-dot" aria-hidden="true"></span>
          New Mission Partnership
        </div>
        <h2 class="farmers-heading" id="farmers-title">
          Empowering Farmers<br/>
          <em class="farmers-heading-em">in Sarangani Province</em>
        </h2>
        <p class="farmers-desc-intro">
          Please watch the Johnny Davis Global Missions team video.
        </p>
        <p class="farmers-body">
          In this segment, our team is interviewing farmers in the mountains of Sarangani Province, Philippines. We are hearing directly from families and local communities about the challenges they face and the ongoing need for sustainable solutions to hunger.
        </p>
        <div class="farmers-highlight">
          <p>
            Our mission is not only to provide temporary relief but also to create <strong>long-term impact</strong>. We desire to partner with local farmers by helping provide seeds, agricultural resources, and support so they can grow crops such as <strong>rice, vegetables, and corn</strong>.
          </p>
        </div>
        <p class="farmers-body">
          By empowering communities to grow food locally, we can help create a pathway toward lasting change, strengthen families, and fight hunger throughout the region.
        </p>
        <a href="{{ route('donate') }}?campaign=gensan" class="btn btn-lg farmers-cta wwa-donate-btn">
          <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M17 8C8 10 5.9 16.17 3.82 19.07L5.71 21c2-2.25 4-6.25 7.29-7 2.09 9.75 11 10 11 10S17 20.25 17 8z"/></svg>
          Support Gensan Farmers
        </a>
      </div>

    </div>
  </div>
</section>


<!-- ============================================================
     MISSION
============================================================ -->
<section id="mission" aria-labelledby="mission-title">
  <div class="container">
    <div class="mission-grid">

      <div class="mission-image-wrap reveal-left">
        <img src="{{ asset('images/landingpage/feedthehungry.png') }}"
             alt="Johnny Davis Global Missions volunteers feeding children in the Philippines"
             loading="lazy" />
        <div class="mission-badge" aria-label="Since 2015">
          <strong>{{ $cms->text('mission', 'badge_number', '4+') }}</strong>
          <span>{{ $cms->text('mission', 'badge_label', 'Years of Impact') }}</span>
        </div>
      </div>

      <div class="mission-text reveal-right">
        <span class="section-label">Our Mission</span>
        <h2 class="section-title" id="mission-title">{{ $cms->text('mission', 'headline', 'Bringing Hope to the Philippines') }}</h2>
        <p class="body-text">
          {{ $cms->text('mission', 'body', 'At Johnny Davis Global Missions, our mission is to help those in need in the Philippines. From providing food and medical care, to education and disaster relief, our organization provides the necessary resources and support that every child, adult, and community deserves.') }}
        </p>
        @if(!$cms->has('mission', 'body'))
        <p class="body-text" style="margin-top:16px;">
          We believe that every life has value, and that small acts of generosity — multiplied —
          can transform entire communities. Your gift goes directly to those who need it most.
        </p>
        @endif

        <div class="mission-pillars" role="list" aria-label="Our focus areas">
          <div class="pillar-tag" role="listitem">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M18.5 3a4.5 4.5 0 0 1 0 9H13v7h-2v-7H5.5a4.5 4.5 0 0 1 0-9H11V2h2v1h5.5z"/></svg>
            Food Programs
          </div>
          <div class="pillar-tag" role="listitem">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 2a5 5 0 1 1 0 10A5 5 0 0 1 12 2zm0 12c5.33 0 8 2.67 8 4v2H4v-2c0-1.33 2.67-4 8-4z"/></svg>
            Medical Care
          </div>
          <div class="pillar-tag" role="listitem">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 3L1 9l11 6 9-4.91V17h2V9L12 3zM5 13.18V17l7 4 7-4v-3.82L12 17l-7-3.82z"/></svg>
            Education
          </div>
          <div class="pillar-tag" role="listitem">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 15v-4H7l5-8v4h4l-5 8z"/></svg>
            Disaster Relief
          </div>
          <div class="pillar-tag" role="listitem">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M17 8C8 10 5.9 16.17 3.82 19.07L5.71 21c2-2.25 4-6.25 7.29-7 2.09 9.75 11 10 11 10S17 20.25 17 8z"/></svg>
            Clean Water
          </div>
          <div class="pillar-tag" role="listitem">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/></svg>
            Community Outreach
          </div>
        </div>
      </div>

    </div>
  </div>
</section>


<!-- ============================================================
     DONATION HIGHLIGHT
============================================================ -->
<section id="donation-highlight" class="reveal">
  <div class="container">
    <p class="highlight-text">
      @if($cms->has('donation-highlight', 'body'))
        {{ $cms->text('donation-highlight', 'body', '') }}
      @else
        Every contribution moves us closer to ending hunger for Filipino children.
      @endif
    </p>
    <div class="highlight-amount">
      @if($cms->has('donation_highlight', 'highlight_amount'))
        {{ $cms->text('donation_highlight', 'highlight_amount', '') }}
      @else
        $7.99 / month = 1 child fed
      @endif
    </div>
    <p>
      @if($cms->has('donation_highlight', 'body'))
        {{ $cms->text('donation_highlight', 'body', '') }}
      @else
        Choose a monthly donation and we'll provide a full meal plan, clean water, and hope to a child in need.
      @endif
      </p>
    <a href="{{ route('donate') }}" class="btn btn-blue btn-lg" style="display:inline-flex; align-items:center; justify-content:center; margin-top:16px;">
      @if($cms->has('donation-highlight', 'button_text'))
        {{ $cms->text('donation-highlight', 'button_text', '') }}
      @else
        Donate &amp; Change a Life
    @endif
    </a>
  </div>
</section>


<!-- ============================================================
     IMPACT COUNTERS
============================================================ -->
<section id="impact" aria-labelledby="impact-title" class="reveal">
  <div class="container">
    <header class="help-header">
      <span class="section-label">Our Impact</span>
      <h2 class="section-title" id="impact-title">Lives Transformed So Far</h2>
    </header>

    <div class="counter-grid">
      <div class="counter-item">
        <div class="counter-value" data-target="5000">0</div>
        <div>Children fed</div>
      </div>
      <div class="counter-item">
        <div class="counter-value" data-target="150">0</div>
        <div>Volunteers engaged</div>
      </div>
      <div class="counter-item">
        <div class="counter-value" data-target="30">0</div>
        <div>Communities served</div>
      </div>
      <div class="counter-item">
        <div class="counter-value" data-target="25000">0</div>
        <div>Meals distributed</div>
      </div>
    </div>
  </div>
</section>


<!-- ============================================================
     URGENCY / CAMPAIGN
============================================================ -->
<section id="urgency" aria-labelledby="urgency-title">
  <div class="urgency-bg" aria-hidden="true"></div>
  <div class="urgency-overlay" aria-hidden="true"></div>

  <div class="container">
    <div class="urgency-content">

      {{-- ── LEFT: Narrative column ── --}}
      <div class="urgency-text reveal-left">

        {{-- Pulsing kicker badge --}}
        <div class="urgency-kicker" aria-label="Urgent Campaign">
          <span class="urgency-pulse-dot" aria-hidden="true"></span>
          Urgent Campaign
        </div>

        {{-- Main heading --}}
        <h2 class="urgency-heading" id="urgency-title">
          @if($cms->has('urgency', 'headline'))
            {{ $cms->text('urgency', 'headline', '') }}
          @else
            Urgent Relief<br/><em class="urgency-heading-em">Needed Now</em>
          @endif
        </h2>

        {{-- Body copy --}}
        @if($cms->has('urgency', 'body'))
          <p class="urgency-body">{!! nl2br(e($cms->text('urgency', 'body', ''))) !!}</p>
        @else
          <p class="urgency-body">
            A typhoon left families without food, shelter, or clean water.
            Your support helps us mobilise immediate relief — emergency meals,
            hygiene kits, and medical care for children still recovering from the storm.
          </p>
        @endif

        {{-- Feature list with SVG check icons --}}
        <ul class="urgency-gift-list" aria-label="Your donation provides">
          @php
          $gifts = [
            'Emergency food supplies',
            'Clean drinking water',
            'Hygiene and medical kits',
            'Shelter assistance for displaced families',
            'Hope for children and families in crisis',
          ];
          @endphp
          @foreach($gifts as $gift)
          <li>
            <span class="urgency-check" aria-hidden="true">
              <svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M2.5 7.5L5.5 10.5L11.5 3.5" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
              </svg>
            </span>
            {{ $gift }}
          </li>
          @endforeach
        </ul>

        {{-- Elevated blockquote card --}}
        <blockquote class="urgency-quote-card">
          <svg class="urgency-quote-mark" width="32" height="24" viewBox="0 0 32 24" fill="none" aria-hidden="true">
            <path d="M0 24V14.4C0 10.56 0.96 7.28 2.88 4.56C4.8 1.84 7.68 0.16 11.52 0L12.48 2.16C10.08 2.72 8.24 3.92 6.96 5.76C5.68 7.6 5.04 9.52 5.04 11.52H9.6V24H0ZM19.2 24V14.4C19.2 10.56 20.16 7.28 22.08 4.56C24 1.84 26.88 0.16 30.72 0L31.68 2.16C29.28 2.72 27.44 3.92 26.16 5.76C24.88 7.6 24.24 9.52 24.24 11.52H28.8V24H19.2Z" fill="currentColor"/>
          </svg>
          <p class="urgency-quote-lines">
            This is more than a donation —<br/>
            This is relief in action.<br/>
            This is compassion responding quickly.<br/>
            This is hope reaching families in their darkest hour.
          </p>
          <p class="urgency-quote-close">
            Because of <strong>YOU</strong>&hellip; families survive, recover, and rebuild.
          </p>
        </blockquote>

      </div>

      {{-- ── RIGHT: Glassmorphism action card ── --}}
      <div class="urgency-action-card reveal-right" role="complementary" aria-label="Donation options">

        {{-- Price hero --}}
        <div class="urgency-price-hero">
          <span class="urgency-price-amount">$29</span>
          <span class="urgency-price-period">/month</span>
        </div>
        <p class="urgency-card-tagline">
          Provide urgent disaster relief to families devastated by storms, flooding, and crisis.
        </p>

        <div class="urgency-card-divider" aria-hidden="true"></div>

        {{-- Primary CTA --}}
        <a href="{{ route('donate') }}?campaign=where&amount=29.99"
           class="btn btn-primary btn-lg urgency-btn-main wwa-donate-btn news-read-btn"
           aria-label="Give $29 monthly to disaster relief">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
          Give $29 Monthly
        </a>

        {{-- Secondary CTA --}}
        <a href="{{ route('donate') }}?campaign=where"
           class="btn btn-outline urgency-btn-secondary news-email-btn"
           aria-label="Give a one-time relief gift">
          Give a One-Time Relief Gift
        </a>

        {{-- Trust signals --}}
        <div class="urgency-trust-row">
          <div class="urgency-trust-item">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
            <span>Secure &amp; encrypted</span>
          </div>
          <div class="urgency-trust-item">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span>Tax-deductible</span>
          </div>
          <div class="urgency-trust-item">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
            <span>Cancel anytime</span>
          </div>
        </div>

      </div>

    </div>
  </div>
</section>


<!-- ============================================================
     DISASTER RELIEF
============================================================ -->
<section id="disaster" aria-labelledby="disaster-title">
  <div class="container">
    <div class="disaster-grid">

      <div class="disaster-image-wrap reveal-left">
        <span class="disaster-ribbon" aria-label="Urgent alert">&#9888; Urgent Relief Needed</span>
        <img src="{{ asset('images/landingpage/pray_for_earthquake_victems.webp') }}"
             alt="Earthquake victims in Cebu Province, Philippines needing urgent aid"
             loading="lazy"
             class="disaster-img-clickable"
             role="button"
             tabindex="0"
             aria-label="View full image" />
      </div>

      <div class="disaster-text reveal-right">
        <span class="section-label">Disaster Relief</span>
        <h2 class="section-title" id="disaster-title">
          @if($cms->has('disaster', 'headline'))
            {{ $cms->text('disaster', 'headline', '') }}
          @else
            Pray For Earthquake Victims<br/>
            <span style="font-size:80%; color: var(--gray-600);">(Cebu, Philippines)</span>
          @endif
        </h2>
        <p class="body-text">
          @if($cms->has('disaster', 'body'))
            {{ $cms->text('disaster', 'body', '') }}
          @else
              Hello Friends. Let us pray for the victims of the recent earthquake in Cebu Province,
              Central Visayas, Philippines that happened on <strong>September 30th, 2025</strong>.
          @endif
        </p>

        <ul class="needs-list" aria-label="Immediate needs">
          <li class="need-item">
            <div class="need-icon" style="background:#e0f0ff;" aria-hidden="true">💧</div>
            Clean Water
          </li>
          <li class="need-item">
            <div class="need-icon" style="background:#fff3e0;" aria-hidden="true">🍱</div>
            Ready-to-Eat Food
          </li>
          <li class="need-item">
            <div class="need-icon" style="background:#ffe0e0;" aria-hidden="true">🏥</div>
            Medical Care
          </li>
          <li class="need-item">
            <div class="need-icon" style="background:#f0ffe0;" aria-hidden="true">🏠</div>
            Emergency Shelter
          </li>
        </ul>

        <div class="disaster-alert" role="alert" aria-live="assertive">
          <div class="disaster-alert-inner">
            <div class="disaster-alert-header">
              <span class="disaster-alert-badge">
                <span class="disaster-alert-dot" aria-hidden="true"></span>
                Critical Situation
              </span>
            </div>
            <div class="disaster-alert-stats" aria-label="Key statistics">
              <div class="disaster-stat-chip">
                <strong>500+</strong>
                <span>Families displaced</span>
              </div>
              <div class="disaster-stat-chip">
                <strong>72hrs</strong>
                <span>Most critical window</span>
              </div>
              <div class="disaster-stat-chip">
                <strong>3 cities</strong>
                <span>Affected provinces</span>
              </div>
            </div>
            <div class="disaster-alert-divider" aria-hidden="true"></div>
            <p class="disaster-alert-body">
              Hospitals are overwhelmed with patients and many homes have sustained
              <strong>severe structural damage</strong>, leaving families without safe shelter.
              Your support can make an immediate difference — every hour counts.
            </p>
          </div>
        </div>

        <a href="{{ route('donate') }}?campaign=cebu" class="btn btn-primary btn-lg">
          &#9829; Support Disaster Relief
        </a>
      </div>

    </div>
  </div>
</section>


<!-- ============================================================
     HOW YOU CAN HELP
============================================================ -->
<section id="help" aria-labelledby="help-title">
  <div class="container">

    <header class="help-header reveal">
      <span class="section-label">Take Action</span>
      <h2 class="section-title" id="help-title">
        @if ($cms->has('help', 'headline'))
          {{ $cms->text('help', 'headline', '') }}
        @else
          How You Can Help
        @endif
      </h2>
      <p class="body-text">
        @if ($cms->has('help', 'body'))
          {{ $cms->text('help', 'body', '') }}
        @else
          Every action — big or small — creates ripples of change in the lives of children and families in the Philippines.
        @endif
      </p>
    </header>

    <div class="help-cards">

      <article class="help-card reveal" style="transition-delay:.1s">
        <img class="help-card-img" src="{{ asset('images/landingpage/community_outreach.png') }}"
             alt="Volunteers making a donation for community outreach"
             loading="lazy" />
        <div class="help-card-body">
          <div class="help-card-icon" aria-hidden="true">
            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="currentColor"><path d="M11.5 2C6.81 2 3 5.81 3 10.5S6.81 19 11.5 19h.5v3c4.86-2.34 8-7 8-11.5C20 5.81 16.19 2 11.5 2zm1 14.5h-2v-2h2v2zm0-4h-2c0-3.25 3-3 3-5 0-1.1-.9-2-2-2s-2 .9-2 2h-2c0-2.21 1.79-4 4-4s4 1.79 4 4c0 2.5-3 2.75-3 5z"/></svg>
          </div>
          <h3>Make a Donation</h3>
          <p>Your donation allows those in need to receive food, clean water, medical care and educational support. Every dollar counts.</p>
          <a href="{{ route('donate') }}" class="help-card-link">
            Donate Today
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M8.59 16.59L13.17 12 8.59 7.41 10 6l6 6-6 6z"/></svg>
          </a>
        </div>
      </article>

      <article class="help-card reveal" style="transition-delay:.2s">
        <img class="help-card-img" src="{{ asset('images/landingpage/education.png') }}"
             alt="Volunteers participating in community education programs"
             loading="lazy" />
        <div class="help-card-body">
          <div class="help-card-icon" aria-hidden="true">
            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="currentColor"><path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/></svg>
          </div>
          <h3>Become a Volunteer</h3>
          <p>Do you have extra time? Help us participate in outreach programs and community events. Your presence transforms lives.</p>
          <a href="#footer" class="help-card-link">
            Get Involved
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M8.59 16.59L13.17 12 8.59 7.41 10 6l6 6-6 6z"/></svg>
          </a>
        </div>
      </article>

      <article class="help-card reveal" style="transition-delay:.3s">
        <img class="help-card-img" src="{{ asset('images/landingpage/medical_care.png') }}"
             alt="Community awareness and outreach programs in the Philippines"
             loading="lazy" />
        <div class="help-card-body">
          <div class="help-card-icon" aria-hidden="true">
            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="currentColor"><path d="M18 16.08c-.76 0-1.44.3-1.96.77L8.91 12.7c.05-.23.09-.46.09-.7s-.04-.47-.09-.7l7.05-4.11c.54.5 1.25.81 2.04.81 1.66 0 3-1.34 3-3s-1.34-3-3-3-3 1.34-3 3c0 .24.04.47.09.7L8.04 9.81C7.5 9.31 6.79 9 6 9c-1.66 0-3 1.34-3 3s1.34 3 3 3c.79 0 1.5-.31 2.04-.81l7.12 4.16c-.05.21-.08.43-.08.65 0 1.61 1.31 2.92 2.92 2.92 1.61 0 2.92-1.31 2.92-2.92s-1.31-2.92-2.92-2.92z"/></svg>
          </div>
          <h3>Spread the Word</h3>
          <p>Help spread awareness so we can reach more families in need. Share our mission on social media and with your community.</p>
          <a href="#footer" class="help-card-link">
            Share Our Mission
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M8.59 16.59L13.17 12 8.59 7.41 10 6l6 6-6 6z"/></svg>
          </a>
        </div>
      </article>

    </div>
  </div>
</section>


<!-- ============================================================
     TESTIMONIALS
============================================================ -->
<section id="testimonials" aria-labelledby="testimonials-title">
  <div class="container">

    <header class="testimonials-header reveal">
      <span class="section-label">Stories of Hope</span>
      <h2 class="section-title" id="testimonials-title">What People Are Saying About Us</h2>
      <p class="body-text white">
        Real voices. Real change. These are the stories that fuel our mission every single day.
      </p>
    </header>

    <div class="testimonial-carousel">

      <article class="testimonial-card reveal" style="transition-delay:.1s">
        <span class="quote-mark" aria-hidden="true">"</span>
        <div class="testimonial-stars" aria-label="5 stars">★★★★★</div>
        <blockquote>
          <p class="testimonial-text">
            Johnny Davis Global Missions has been a tremendous blessing to our community here in Manila.
            The feeding programs they run have brought joy to so many children who go to bed hungry.
            I've witnessed firsthand the transformation that happens when love is put into action.
            This organization truly lives out its faith through works.
          </p>
        </blockquote>
        <footer class="testimonial-author">
          <div class="author-avatar" aria-hidden="true">PA</div>
          <div>
            <p class="author-name">Pastor Esther A.</p>
            <p class="author-location">Manila, Philippines</p>
          </div>
        </footer>
      </article>

      <article class="testimonial-card reveal" style="transition-delay:.2s">
        <span class="quote-mark" aria-hidden="true">"</span>
        <div class="testimonial-stars" aria-label="5 stars">★★★★★</div>
        <blockquote>
          <p class="testimonial-text">
            When typhoon season devastated our barangay in Leyte, Johnny Davis Global Missions was one of
            the first organizations to respond. They brought food, clean water, and most importantly —
            hope. The children in our community now smile again because of the difference this mission makes.
            I am forever grateful.
          </p>
        </blockquote>
        <footer class="testimonial-author">
          <div class="author-avatar" aria-hidden="true">AG</div>
          <div>
            <p class="author-name">Aylen G.</p>
            <p class="author-location">Leyte, Philippines</p>
          </div>
        </footer>
      </article>

      <article class="testimonial-card reveal" style="transition-delay:.3s">
        <span class="quote-mark" aria-hidden="true">"</span>
        <div class="testimonial-stars" aria-label="5 stars">★★★★★</div>
        <blockquote>
          <p class="testimonial-text">
            I never imagined that people from across the world would care so deeply about our little province.
            But Johnny Davis Global Missions showed us that love has no borders. The medical missions and
            food distribution programs have saved lives in our community. God bless this organization and
            every single donor who made it possible.
          </p>
        </blockquote>
        <footer class="testimonial-author">
          <div class="author-avatar" aria-hidden="true">YM</div>
          <div>
            <p class="author-name">Yvonne M.</p>
            <p class="author-location">Gensan, Philippines</p>
          </div>
        </footer>
      </article>

    </div>
  </div>
</section>


<!-- ============================================================
     DONATION CTA
============================================================ -->
<section id="donate-cta" aria-labelledby="donate-cta-title">
  <div class="donate-cta-bg" aria-hidden="true"></div>
  <div class="donate-cta-overlay" aria-hidden="true"></div>

  <div class="container">
    <div class="donate-cta-content reveal">
      <span class="section-label">
        @if($cms->has('donate_cta', 'section_label'))
          {{ $cms->text('donate_cta', 'section_label', '') }}
        @else
          Make a Difference Today
        @endif
      </span>
      <h2 class="section-title" id="donate-cta-title">
        @if($cms->has('donate_cta', 'headline'))
          {{ $cms->text('donate_cta', 'headline', '') }}
        @else
          Giving Back Feels Good
        @endif
      </h2>
      <p class="body-text">
        @if ($cms->has('donate_cta', 'body'))
          {{ $cms->text('donate_cta', 'body', '') }}
         @else
          Show your support by making a donation today. Every gift — no matter the size —
          directly feeds a child, supports a family, and brings hope to a community in need.
        @endif
      </p>

      <div class="donate-options" role="group" aria-label="Select donation amount">
        <button class="donate-amount" onclick="selectAmount(this, '$7.99')">$7.99/mo</button>
        <button class="donate-amount" onclick="selectAmount(this, '$15')">$15</button>
        <button class="donate-amount selected" onclick="selectAmount(this, '$25')">$25</button>
        <button class="donate-amount" onclick="selectAmount(this, '$50')">$50</button>
        <button class="donate-amount" onclick="selectAmount(this, '$100')">$100</button>
        <button class="donate-amount" onclick="selectAmount(this, 'custom')">Other</button>
      </div>

      <div class="donate-cta-btns">
        <a href="{{ route('donate') }}?campaign=where" class="btn btn-primary btn-lg donate-hero-btn">
          &#9829; Donate Now
        </a>
        <a href="{{ route('donate') }}?campaign=where&amount=7.99" class="btn btn-outline btn-lg donate-monthly-btn">
          Give Monthly — $7.99
        </a>
      </div>

      <p class="impact-note">
        @if ($cms->has('donate_cta', 'impact_note'))
          {{ $cms->text('donate_cta', 'impact_note', '') }}
         @else
            100% of your donation goes directly to those in need. Johnny Davis Global Missions is a 501(c)(3) nonprofit. Your gift may be tax-deductible.
        @endif
      </p>
    </div>
  </div>
</section>


<!-- ============================================================
     FOOTER
============================================================ -->
<footer id="footer" role="contentinfo">
  <div class="container">
    <div class="footer-grid">

      <!-- Brand -->
      <div class="footer-brand">
        <img src="{{ asset('images/logo.webp') }}" alt="Johnny Davis Global Missions Logo" />
        <p>
          Johnny Davis Global Missions is a non-profit organization founded on the belief that
          a little help can go a long way. We serve the poorest communities in the Philippines
          with food, medical care, education, and disaster relief.
        </p>
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
          <!-- <a href="https://www.tiktok.com/@johnnydavisministries" class="social-icon" aria-label="TikTok">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75-.08 1.4-.54 2.79-1.35 3.94-1.31 1.92-3.58 3.17-5.91 3.21-1.43.08-2.86-.31-4.08-1.03-2.02-1.19-3.44-3.37-3.65-5.71-.02-.5-.03-1-.01-1.49.18-1.9 1.12-3.72 2.58-4.96 1.66-1.44 3.98-2.13 6.15-1.72.02 1.48-.04 2.96-.04 4.44-.99-.32-2.15-.23-3.02.37-.63.41-1.11 1.04-1.36 1.75-.21.51-.15 1.07-.14 1.61.24 1.64 1.82 3.02 3.5 2.87 1.12-.01 2.19-.66 2.77-1.61.19-.33.4-.67.41-1.06.1-1.79.06-3.57.07-5.36.01-4.03-.01-8.05.02-12.07z"/></svg>
          </a> -->
        </div>
      </div>

      <!-- Quick Links -->
      <nav class="footer-nav-accordion" aria-label="Footer navigation">
        <button class="footer-heading footer-accordion-btn" aria-expanded="false" aria-controls="footer-quick-links">
          Quick Links
          <svg class="footer-accordion-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="6 9 12 15 18 9"/></svg>
        </button>
        <ul class="footer-links footer-accordion-content" id="footer-quick-links" role="list">
          <li><a href="#hero">Home</a></li>
          <li><a href="#mission">Who We Are</a></li>
          <li><a href="#help">What We Do</a></li>
          <li><a href="{{ route('donate') }}">Make a Difference</a></li>
          <li><a href="https://johnnydavisministries.org" target="_blank" rel="noopener noreferrer">Johnny Davis Ministries</a></li>
          <li><a href="#footer">Contact Us</a></li>
        </ul>
      </nav>

      <!-- Programs -->
      <nav class="footer-nav-accordion" aria-label="Programs navigation">
        <button class="footer-heading footer-accordion-btn" aria-expanded="false" aria-controls="footer-programs">
          Our Programs
          <svg class="footer-accordion-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="6 9 12 15 18 9"/></svg>
        </button>
        <ul class="footer-links footer-accordion-content" id="footer-programs" role="list">
          <li><a href="#urgency">Feed the Hungry</a></li>
          <li><a href="#disaster">Disaster Relief</a></li>
          <li><a href="#help">Medical Missions</a></li>
          <li><a href="#help">Education Support</a></li>
          <li><a href="#help">Clean Water</a></li>
          <li><a href="#help">Community Outreach</a></li>
        </ul>
      </nav>

      <!-- Contact -->
      <address>
        <h3 class="footer-heading">Contact Us</h3>
        <div class="footer-contact">
          <div class="contact-row">
            <div class="contact-icon" aria-hidden="true">
              <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5a2.5 2.5 0 0 1 0-5 2.5 2.5 0 0 1 0 5z"/></svg>
            </div>
            <span>P.O. Box 1904<br/>Suwanee, GA 30024</span>
          </div>
          <div class="contact-row">
            <div class="contact-icon" aria-hidden="true">
              <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M6.62 10.79c1.44 2.83 3.76 5.14 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z"/></svg>
            </div>
            <a href="tel:+14044262856">(404) 426-2856</a>
          </div>
          <div class="contact-row">
            <div class="contact-icon" aria-hidden="true">
              <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/></svg>
            </div>
            <a href="mailto:info@johnnydavisglobalmissions.org">info@johnnydavisglobalmissions.org</a>
          </div>
          <div class="contact-row">
            <div class="contact-icon" aria-hidden="true">
              <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M11.99 2C6.47 2 2 6.48 2 12s4.47 10 9.99 10C17.52 22 22 17.52 22 12S17.52 2 11.99 2zM12 20c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8zm.5-13H11v6l5.25 3.15.75-1.23-4.5-2.67V7z"/></svg>
            </div>
            <span>Mon–Fri: 9:00am – 5:00pm</span>
          </div>
        </div>
      </address>

    </div>

    <div class="footer-bottom">
      <p class="footer-copy">
        &copy;{{ date('Y') }} Johnny Davis Global Missions. All Rights Reserved.
      </p>
      <nav class="footer-legal" aria-label="Legal navigation">
        <a href="#">Privacy Policy</a>
        <a href="#">Terms of Use</a>
        <a href="#">Nonprofit Disclosure</a>
      </nav>
    </div>
  </div>
</footer>


{{-- Disaster image modal --}}
<div id="disasterImgModal" class="dimg-overlay" hidden role="dialog" aria-modal="true" aria-label="Full image view">
  <div class="dimg-backdrop"></div>
  <div class="dimg-box">
    <button class="dimg-close" aria-label="Close image">&times;</button>
    <img src="{{ asset('images/landingpage/pray_for_earthquake_victems.webp') }}"
         alt="Earthquake victims in Cebu Province, Philippines needing urgent aid"
         class="dimg-photo" />
  </div>
</div>

<style>
  .disaster-img-clickable {
    cursor: zoom-in;
    transition: transform .25s ease, box-shadow .25s ease;
  }
  .disaster-img-clickable:hover {
    transform: scale(1.02);
    box-shadow: 0 12px 36px rgba(0,0,0,.4);
  }
  .dimg-overlay {
    position: fixed;
    inset: 0;
    z-index: 9999;
    display: flex;
    align-items: center;
    justify-content: center;
  }
  .dimg-overlay[hidden] { display: none; }
  .dimg-backdrop {
    position: absolute;
    inset: 0;
    background: rgba(0,0,0,.88);
    backdrop-filter: blur(4px);
  }
  .dimg-box {
    position: relative;
    z-index: 1;
    max-width: min(92vw, 820px);
    animation: dimgFadeIn .25s ease;
  }
  @keyframes dimgFadeIn {
    from { opacity: 0; transform: scale(.94); }
    to   { opacity: 1; transform: scale(1); }
  }
  .dimg-photo {
    display: block;
    width: 100%;
    height: auto;
    border-radius: 12px;
    box-shadow: 0 24px 64px rgba(0,0,0,.65);
  }
  .dimg-close {
    position: absolute;
    top: -14px;
    right: -14px;
    width: 36px;
    height: 36px;
    border-radius: 50%;
    border: none;
    background: #f07c1e;
    color: #fff;
    font-size: 1.3rem;
    line-height: 1;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 4px 12px rgba(0,0,0,.4);
    transition: background .2s;
    z-index: 2;
  }
  .dimg-close:hover { background: #d4680e; }
</style>

<script src="{{ asset('js/for_index.js') }}?v={{ filemtime(public_path('js/for_index.js')) }}"></script>
<script>
(function () {
  var modal    = document.getElementById('disasterImgModal');
  var closeBtn = modal.querySelector('.dimg-close');
  var backdrop = modal.querySelector('.dimg-backdrop');
  var trigger  = document.querySelector('.disaster-img-clickable');

  function openModal() {
    modal.hidden = false;
    document.body.style.overflow = 'hidden';
    closeBtn.focus();
  }
  function closeModal() {
    modal.hidden = true;
    document.body.style.overflow = '';
    trigger.focus();
  }

  trigger.addEventListener('click', openModal);
  trigger.addEventListener('keydown', function (e) {
    if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); openModal(); }
  });
  closeBtn.addEventListener('click', closeModal);
  backdrop.addEventListener('click', closeModal);
  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape' && !modal.hidden) closeModal();
  });
})();
</script>
@include('partials.chatbot')
</body>
</html>
