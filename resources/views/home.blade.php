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

  <link rel="stylesheet" href="{{ asset('css/for_index.css') }}" />
</head>
<body>

<!-- ============================================================
     NAVIGATION
============================================================ -->
<header id="navbar" role="banner">
  <div class="container">
    <nav class="nav-inner" aria-label="Main navigation">
      <a href="#hero" class="nav-logo" aria-label="Johnny Davis Global Missions Home">
        <img src="{{ asset('images/logo.webp') }}" alt="Johnny Davis Global Missions Logo" />
      </a>

      <ul class="nav-links" role="list">
        <li><a href="#hero">Home</a></li>
        <li><a href="#mission">Mission</a></li>
        <!-- <li><a href="#help">How You Can Help</a></li> -->
        <li><a href="{{ route('news') }}">Blog &amp; News</a></li>
        <li><a href="{{ route('who-we-are') }}">Who We Are</a></li>
        <li><a href="{{ route('ministry') }}">Ministry</a></li>
        <li><a href="#testimonials">Testimonials</a></li>
        <li><a href="{{ route('donate') }}" class="btn-nav-donate" aria-label="Donate to Johnny Davis Global Missions">&#9829; Donate</a></li>
        <li><a href="{{ route('contact') }}">Contact</a></li>
      </ul>

      <button class="nav-toggle" id="navToggle" aria-label="Toggle mobile menu" aria-expanded="false">
        <span></span><span></span><span></span>
      </button>
    </nav>
  </div>

  <nav class="nav-mobile" id="navMobile" aria-label="Mobile navigation">
    <a href="#hero">Home</a>
    <a href="#mission">Mission</a>
    <a href="#help">How You Can Help</a>
    <a href="{{ route('news') }}">Blog &amp; News</a>
    <a href="{{ route('who-we-are') }}">Who We Are</a>
    <a href="{{ route('ministry') }}">Ministry</a>
    <a href="#testimonials">Testimonials</a>
    <a href="{{ route('contact') }}">Contact</a>
    <a href="{{ route('donate') }}" class="btn-nav-donate">&#9829; Donate Now</a>
  </nav>
</header>

<a id="stickyDonate" class="btn btn-primary" href="{{ route('donate') }}" aria-label="Donate Now">Donate Now</a>


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
     HERO
============================================================ -->
<section id="hero" aria-label="Hero — Feed Filipino Children">
  <div class="hero-bg" id="heroBg"></div>
  <div class="hero-overlay"></div>

  <div class="container">
    <div class="hero-content">
      <div class="hero-badge" aria-label="Active campaign">{{ $cms->text('hero', 'eyebrow', 'Active Campaign 2026') }}</div>

      @if($cms->has('hero', 'headline'))
        <h1 class="hero-headline">{{ $cms->text('hero', 'headline', '') }}</h1>
      @else
        <h1 class="hero-headline">
          Feed Filipino Children
          <span class="accent">"Hunger Can't Wait"</span>
        </h1>
      @endif

      <p class="hero-sub">
        {!! nl2br(e($cms->text('hero', 'subtitle', "Help us fight hunger and crisis in the Philippines.\nTogether we can make a difference."))) !!}
      </p>

      <div class="hero-ctas">
        <a href="{{ $cms->url('hero', 'primary_cta_label', route('donate')) }}" class="btn btn-primary btn-lg">
          &#9829; {{ $cms->text('hero', 'primary_cta_label', 'Donate Now') }}
        </a>
        <button class="btn btn-outline btn-lg" id="watchMissionBtn" aria-label="Watch our mission video">
          <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M8 5v14l11-7z"/></svg>
          {{ $cms->text('hero', 'secondary_cta_label', 'Watch Our Mission') }}
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

  <div class="hero-scroll" aria-hidden="true">
    <div class="hero-scroll-line"></div>
    <span>Scroll</span>
  </div>
</section>


<!-- ============================================================
     MISSION
============================================================ -->
<section id="mission" aria-labelledby="mission-title">
  <div class="container">
    <div class="mission-grid">

      <div class="mission-image-wrap reveal-left">
        <img src="{{ asset('images/landingpage/feedthehungry.webp') }}"
             alt="Johnny Davis Global Missions volunteers feeding children in the Philippines"
             loading="lazy" />
        <div class="mission-badge" aria-label="Since 2015">
          <strong>{{ $cms->text('mission', 'badge_number', '10+') }}</strong>
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
    <p class="highlight-text">Every contribution moves us closer to ending hunger for Filipino children.</p>
    <div class="highlight-amount">$7.99 / month = 1 child fed</div>
    <p>Choose a monthly donation and we'll provide a full meal plan, clean water, and hope to a child in need.</p>
    <a href="{{ route('donate') }}" class="btn btn-blue btn-lg" style="display:inline-flex; align-items:center; justify-content:center; margin-top:16px;">Donate &amp; Change a Life</a>
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

      <div class="urgency-text reveal-left">
        <p class="overline">Urgent Campaign</p>
        <h2 class="urgency-heading" id="urgency-title">
          Hunger Can't Wait —<br/>This Is The Moment
        </h2>
        <p class="urgency-body">
          There is a child praying for a meal right now.<br/>
          <strong style="color:#fff;">You can be the answer.</strong>
        </p>
        <p class="urgency-price">
          For just $7.99 a month, you can help feed Filipino children in need
          and bring hope to a hungry family.
        </p>
        <blockquote class="urgency-closing">
          This is more than a donation —<br/>
          This is compassion in action.<br/>
          This is faith with works.<br/>
          This is love moving.<br/><br/>
          <strong style="color: var(--orange-light);">Because of YOU… they eat.</strong>
        </blockquote>
        <a href="https://filipinochildren.org" target="_blank" rel="noopener noreferrer" class="btn btn-primary btn-lg">
          &#9829; Donate $7.99 Monthly
        </a>
      </div>

      <div class="urgency-card reveal-right">
        <div class="qr-placeholder" role="img" aria-label="QR code for FilipinoChildren.org donation page">
          <span>Scan to<br/>Donate<br/><br/>QR Code</span>
        </div>
        <h3>Scan &amp; Give</h3>
        <p>Scan the QR code or visit FilipinoChildren.org to donate and feed a child today.</p>
        <a href="https://filipinochildren.org" target="_blank" rel="noopener noreferrer" class="btn btn-primary">
          Visit FilipinoChildren.org
        </a>
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
             loading="lazy" />
      </div>

      <div class="disaster-text reveal-right">
        <span class="section-label">Disaster Relief</span>
        <h2 class="section-title" id="disaster-title">
          Pray For Earthquake Victims<br/>
          <span style="font-size:80%; color: var(--gray-600);">(Cebu, Philippines)</span>
        </h2>
        <p class="body-text">
          Hello Friends. Let us pray for the victims of the recent earthquake in Cebu Province,
          Central Visayas, Philippines that happened on <strong>September 30th, 2025</strong>.
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

        <div class="disaster-alert" role="alert">
          <strong>Critical situation:</strong> Hospitals are overwhelmed with patients and many homes have sustained
          severe structural damage, leaving families without safe shelter. Your support can make an immediate difference.
        </div>

        <a href=\"{{ route('donate') }}\" class=\"btn btn-primary btn-lg\">
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
      <h2 class="section-title" id="help-title">How You Can Help</h2>
      <p class="body-text">
        Every action — big or small — creates ripples of change in the lives of children and families in the Philippines.
      </p>
    </header>

    <div class="help-cards">

      <article class="help-card reveal" style="transition-delay:.1s">
        <img class="help-card-img" src="{{ asset('images/landingpage/community_outreach.webp') }}"
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
        <img class="help-card-img" src="{{ asset('images/landingpage/education.webp') }}"
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
        <img class="help-card-img" src="{{ asset('images/landingpage/medical_care.webp') }}"
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
            <p class="author-location">Leyte, Philippines</p>
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
      <span class="section-label">Make a Difference Today</span>
      <h2 class="section-title" id="donate-cta-title">Giving Back Feels Good</h2>
      <p class="body-text">
        Show your support by making a donation today. Every gift — no matter the size —
        directly feeds a child, supports a family, and brings hope to a community in need.
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
        <a href="https://filipinochildren.org" target="_blank" rel="noopener noreferrer" class="btn btn-primary btn-lg">
          &#9829; Donate Now
        </a>
        <a href="https://filipinochildren.org" target="_blank" rel="noopener noreferrer" class="btn btn-outline btn-lg">
          Give Monthly — $7.99
        </a>
      </div>

      <p class="impact-note">
        100% of your donation goes directly to those in need. Johnny Davis Global Missions is a 501(c)(3) nonprofit.
        Your gift may be tax-deductible.
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
          <a href="https://www.tiktok.com/@johnnydavisministries" class="social-icon" aria-label="TikTok">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75-.08 1.4-.54 2.79-1.35 3.94-1.31 1.92-3.58 3.17-5.91 3.21-1.43.08-2.86-.31-4.08-1.03-2.02-1.19-3.44-3.37-3.65-5.71-.02-.5-.03-1-.01-1.49.18-1.9 1.12-3.72 2.58-4.96 1.66-1.44 3.98-2.13 6.15-1.72.02 1.48-.04 2.96-.04 4.44-.99-.32-2.15-.23-3.02.37-.63.41-1.11 1.04-1.36 1.75-.21.51-.15 1.07-.14 1.61.24 1.64 1.82 3.02 3.5 2.87 1.12-.01 2.19-.66 2.77-1.61.19-.33.4-.67.41-1.06.1-1.79.06-3.57.07-5.36.01-4.03-.01-8.05.02-12.07z"/></svg>
          </a>
        </div>
      </div>

      <!-- Quick Links -->
      <nav aria-label="Footer navigation">
        <h3 class="footer-heading">Quick Links</h3>
        <ul class="footer-links" role="list">
          <li><a href="#hero">Home</a></li>
          <li><a href="#mission">Who We Are</a></li>
          <li><a href="#help">What We Do</a></li>
          <li><a href="{{ route('donate') }}">Make a Difference</a></li>
          <li><a href="https://johnnydavisministries.org" target="_blank" rel="noopener noreferrer">Johnny Davis Ministries</a></li>
          <li><a href="#footer">Contact Us</a></li>
        </ul>
      </nav>

      <!-- Programs -->
      <nav aria-label="Programs navigation">
        <h3 class="footer-heading">Our Programs</h3>
        <ul class="footer-links" role="list">
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


<script src="{{ asset('js/for_index.js') }}"></script>
</body>
</html>
