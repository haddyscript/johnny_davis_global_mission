<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <meta name="description" content="{{ $description }}"/>
  <meta name="csrf-token" content="{{ csrf_token() }}"/>
  <title>{{ $title }}</title>

  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com"/>
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin/>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,700;0,800;1,700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet"/>

  <link rel="stylesheet" href="{{ asset('css/for_news.css') }}"/>
</head>
<body>


<!-- ============================================================
     STICKY DONATE
============================================================ -->
<a href="{{ route('donate') }}" id="stickyDonate" aria-label="Donate Now">&#9829; Donate</a>


@include('partials.nav')


<!-- ============================================================
     HERO
============================================================ -->
<section id="news-hero" aria-labelledby="hero-title">
  <div class="hero-gradient-bg"></div>
  <div class="hero-grid" aria-hidden="true"></div>
  <div class="hero-orb hero-orb-1" aria-hidden="true"></div>
  <div class="hero-orb hero-orb-2" aria-hidden="true"></div>
  <div class="hero-orb hero-orb-3" aria-hidden="true"></div>

  <div class="hero-visual" aria-hidden="true">
    <div class="hero-visual-img"></div>
  </div>

  <div class="container">
    <div class="hero-content">
      <div class="hero-eyebrow">
        <span class="hero-eyebrow-dot"></span>
        Stories from the Field
      </div>
      @if($cms->has('hero', 'headline'))
        <h1 class="hero-title" id="hero-title">{{ $cms->text('hero', 'headline', '') }}</h1>
      @else
        <h1 class="hero-title" id="hero-title">
          Stories of <span class="accent">Impact</span>
        </h1>
      @endif
      <p class="hero-subtitle">{{ $cms->text('hero', 'subtitle', 'Updates From the Front Lines of Our Mission') }}</p>
      <p class="hero-desc">
        {{ $cms->text('hero', 'description', '"Every meal served, every child helped, and every life changed has a story. Follow the journey of communities in the Philippines and Uganda as hope becomes reality."') }}
      </p>
      <div class="hero-actions">
        <a href="#posts-section" class="btn btn-primary">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-5 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z"/></svg>
          Read Latest Stories
        </a>
        <a href="#newsletter" class="btn btn-ghost" style="color:rgba(255,255,255,.85);border-color:rgba(255,255,255,.3);">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M20 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/></svg>
          Get Updates by Email
        </a>
      </div>
      <div class="hero-stats-row" role="list">
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
            <strong>6</strong>
            <span>Reports this year</span>
          </div>
          <div class="hero-stat" role="listitem">
            <strong>2</strong>
            <span>Countries covered</span>
          </div>
          <div class="hero-stat" role="listitem">
            <strong>2,400+</strong>
            <span>Meals tracked monthly</span>
          </div>
        @endif
      </div>
    </div>
  </div>

  <div class="scroll-indicator" aria-hidden="true">
    <div class="scroll-line"></div>
    <span>Scroll</span>
  </div>
</section>


<!-- ============================================================
     FEATURED STORY SECTION
============================================================ -->
<section id="featured-story" aria-labelledby="featured-title">
  <div class="container">
    <div class="featured-story-card reveal">
      <div class="featured-story-image">
        <img src="{{ asset('images/landingpage/community_outreach.webp') }}"
             alt="Marco's transformation from malnourished to thriving child"
             loading="lazy"/>
        <div class="featured-story-overlay"></div>
        <div class="featured-story-badge">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
          Featured Impact Story
        </div>
      </div>
      <div class="featured-story-content">
        <div class="featured-story-meta">
          <span class="featured-category">Impact Story</span>
          <span class="featured-location">
            <span class="flag">🇵🇭</span> Philippines
          </span>
        </div>
        <h2 class="featured-story-title" id="featured-title">Marco's Story: From Malnourished to Thriving</h2>
        <p class="featured-story-excerpt">
          When Marco first came to our feeding program, he hadn't eaten a full meal in two days. Six months later, he attends school every day and his health has completely transformed. This is why we do what we do.
        </p>
        <a href="#" class="btn btn-primary featured-cta">
          Read Full Story →
        </a>
      </div>
    </div>
  </div>
</section>


<!-- ============================================================
     BREADCRUMB
============================================================ -->
<div id="breadcrumb-bar">
  <div class="container">
    <nav class="breadcrumb-inner" aria-label="Breadcrumb">
      <a href=\"{{ route('home') }}\">Home</a>
      <span class="sep">›</span>
      <span class="current">Blog &amp; News</span>
    </nav>
  </div>
</div>


<!-- ============================================================
     FILTER BAR
============================================================ -->
<section id="filter-section" aria-labelledby="filter-heading">
  <div class="container">
    <div class="filter-header reveal">
      <span class="section-label" id="filter-heading">Browse by Category</span>
    </div>
    <div class="filter-row reveal" role="group" aria-label="Filter posts by category">
      <button class="filter-btn active" data-filter="all">
        <span>All Posts <span class="filter-count">6</span></span>
      </button>
      <button class="filter-btn" data-filter="field-reports">
        <span>Field Reports <span class="filter-count">3</span></span>
      </button>
      <button class="filter-btn" data-filter="impact-stories">
        <span>Impact Stories <span class="filter-count">2</span></span>
      </button>
      <button class="filter-btn" data-filter="prayer-requests">
        <span>Prayer Requests <span class="filter-count">0</span></span>
      </button>
      <button class="filter-btn" data-filter="disaster-response">
        <span>Disaster Response <span class="filter-count">1</span></span>
      </button>
      <button class="filter-btn" data-filter="uganda">
        <span>Uganda <span class="filter-count">1</span></span>
      </button>
      <button class="filter-btn" data-filter="philippines">
        <span>Philippines <span class="filter-count">5</span></span>
      </button>
    </div>
    <p class="results-count reveal" id="resultsCount">Showing <strong>{{ count($posts) }}</strong> posts</p>
  </div>
</section>


<!-- ============================================================
     POSTS GRID
============================================================ -->
<section id="posts-section" aria-labelledby="posts-heading">
  <div class="container">
    <h2 class="sr-only" id="posts-heading">Mission Updates and Stories</h2>

    <div class="posts-grid" id="postsGrid">

      @foreach($posts as $post)

      @if($post['featured'])
      <!-- Featured post -->
      <article class="post-card featured reveal"
               data-categories="{{ $post['categories'] }}"
               data-country="{{ $post['country'] }}"
               aria-label="Featured post: {{ $post['title'] }}">
        <div class="post-img-wrap">
          <img class="post-img"
               src="{{ asset($post['image']) }}"
               alt="{{ $post['img_alt'] }}"
               loading="lazy"/>
          <div class="post-img-overlay" aria-hidden="true"></div>
          <span class="country-tag"><span class="flag">{{ $post['flag'] }}</span> {{ $post['country'] }}</span>
          <span class="read-time">{{ $post['read_time'] }}</span>
        </div>
        <div class="post-body">
          <div class="featured-badge">
            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
            Featured Story
          </div>
          <div class="post-meta-row">
            <span class="post-category {{ $post['cat_class'] }}">{{ $post['category'] }}</span>
            <span class="post-date">
              <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M19 3h-1V1h-2v2H8V1H6v2H5c-1.11 0-1.99.9-1.99 2L3 19c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V8h14v11zM7 10h5v5H7z"/></svg>
              {{ $post['date'] }}
            </span>
          </div>
          <h3 class="post-title">{{ $post['title'] }}</h3>
          <p class="post-excerpt">{{ $post['excerpt'] }}</p>
          <a href="{{ $post['cta_href'] }}" class="btn btn-primary" style="margin-top:8px; align-self:flex-start;">
            {{ $post['cta_label'] }}
          </a>
        </div>
      </article>

      @else
      <!-- Standard post -->
      <article class="post-card reveal {{ $post['delay'] }}"
               data-categories="{{ $post['categories'] }}"
               data-country="{{ $post['country'] }}"
               aria-label="{{ $post['title'] }}">
        <div class="post-img-wrap">
          <img class="post-img"
               src="{{ asset($post['image']) }}"
               alt="{{ $post['img_alt'] }}"
               loading="lazy"/>
          <div class="post-img-overlay" aria-hidden="true"></div>
          <span class="country-tag"><span class="flag">{{ $post['flag'] }}</span> {{ $post['country'] }}</span>
          <span class="read-time">{{ $post['read_time'] }}</span>
        </div>
        <div class="post-body">
          <div class="post-meta-row">
            <span class="post-category {{ $post['cat_class'] }}">{{ $post['category'] }}</span>
            <span class="post-date">
              <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M19 3h-1V1h-2v2H8V1H6v2H5c-1.11 0-1.99.9-1.99 2L3 19c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V8h14v11zM7 10h5v5H7z"/></svg>
              {{ $post['date'] }}
            </span>
          </div>
          <h3 class="post-title">{{ $post['title'] }}</h3>
          <p class="post-excerpt">{{ $post['excerpt'] }}</p>
        </div>
        <div class="post-footer">
          <a href="{{ route('donate') }}" class="post-cta">
            {{ $post['cta_label'] }}
            <span class="post-cta-arrow">→</span>
          </a>
          <div class="post-share" aria-label="Share options">
            <button class="share-dot" aria-label="Share on Facebook" title="Share on Facebook">f</button>
            <button class="share-dot" aria-label="Share on Twitter" title="Share on Twitter">𝕏</button>
          </div>
        </div>
      </article>
      @endif

      @endforeach

      <!-- No results message -->
      <div class="no-results" id="noResults" aria-live="polite">
        <div class="no-results-icon">📭</div>
        <h3>No posts in this category yet</h3>
        <p>Check back soon — we publish new updates every month.</p>
      </div>

    </div><!-- /posts-grid -->

    <!-- Pagination -->
    <nav id="pagination" aria-label="Post pagination">
      <button class="pg-btn arrow" disabled aria-label="Previous page">← Prev</button>
      <button class="pg-btn active" aria-label="Page 1, current">1</button>
      <button class="pg-btn" aria-label="Page 2">2</button>
      <button class="pg-btn" aria-label="Page 3">3</button>
      <span style="color:var(--gray-200); font-size:.9rem;">…</span>
      <button class="pg-btn" aria-label="Page 8">8</button>
      <button class="pg-btn arrow" aria-label="Next page">Next →</button>
    </nav>

  </div>
</section>


<!-- ============================================================
     MISSION STATISTICS SECTION
============================================================ -->
<section id="mission-stats" aria-labelledby="stats-title">
  <div class="stats-bg">
    <div class="stats-bg-gradient"></div>
    <div class="stats-bg-pattern" aria-hidden="true"></div>
  </div>
  <div class="container">
    <div class="stats-header reveal">
      <span class="section-label">Real Impact</span>
      <h2 class="section-title white" id="stats-title">Numbers That Tell Stories</h2>
      <p class="stats-subtitle">
        Every statistic represents lives changed, families fed, and communities strengthened.
      </p>
    </div>
    <div class="stats-grid">
      <div class="stat-card reveal delay-1">
        <div class="stat-icon" aria-hidden="true">🍽️</div>
        <div class="stat-number" data-target="42000">0</div>
        <div class="stat-label">Meals Served</div>
        <div class="stat-desc">Children fed monthly across Cebu and Uganda</div>
      </div>
      <div class="stat-card reveal delay-2">
        <div class="stat-icon" aria-hidden="true">🏘️</div>
        <div class="stat-number" data-target="18">0</div>
        <div class="stat-label">Communities Reached</div>
        <div class="stat-desc">Barangays and villages served this year</div>
      </div>
      <div class="stat-card reveal delay-3">
        <div class="stat-icon" aria-hidden="true">⚕️</div>
        <div class="stat-number" data-target="340">0</div>
        <div class="stat-label">Patients Treated</div>
        <div class="stat-desc">Free medical care provided in mobile clinics</div>
      </div>
      <div class="stat-card reveal delay-4">
        <div class="stat-icon" aria-hidden="true">💧</div>
        <div class="stat-number" data-target="200">0</div>
        <div class="stat-label">Families With Clean Water</div>
        <div class="stat-desc">Wells drilled and water systems installed</div>
      </div>
    </div>
  </div>
</section>


<!-- ============================================================
     MISSION LOCATIONS SECTION
============================================================ -->
<section id="mission-locations" aria-labelledby="locations-title">
  <div class="container">
    <div class="locations-header reveal">
      <span class="section-label">Where We Serve</span>
      <h2 class="section-title" id="locations-title">Mission Locations</h2>
      <p class="locations-subtitle">
        Our work spans two countries, touching lives in communities that need hope the most.
      </p>
    </div>
    <div class="locations-grid">
      <div class="location-card philippines reveal delay-1">
        <div class="location-image">
          <img src="{{ asset('images/landingpage/community_outreach.webp') }}"
               alt="Philippines mission work in Cebu and Leyte" loading="lazy"/>
          <div class="location-overlay"></div>
          <div class="location-flag">🇵🇭</div>
        </div>
        <div class="location-content">
          <h3 class="location-title">Philippines</h3>
          <div class="location-areas">
            <span class="location-area">Cebu</span>
            <span class="location-area">Leyte</span>
          </div>
          <p class="location-desc">
            Serving communities through feeding programs, medical missions, and education support across Cebu and Leyte provinces.
          </p>
          <button class="location-btn" data-location="philippines">
            View Philippines Stories
          </button>
        </div>
      </div>
      <div class="location-card uganda reveal delay-2">
        <div class="location-image">
          <img src="{{ asset('images/landingpage/clean_drink_water.webp') }}"
               alt="Uganda mission work in Mbale" loading="lazy"/>
          <div class="location-overlay"></div>
          <div class="location-flag">🇺🇬</div>
        </div>
        <div class="location-content">
          <h3 class="location-title">Uganda</h3>
          <div class="location-areas">
            <span class="location-area">Mbale</span>
          </div>
          <p class="location-desc">
            Bringing clean water through well drilling and community development initiatives in Mbale region.
          </p>
          <button class="location-btn" data-location="uganda">
            View Uganda Stories
          </button>
        </div>
      </div>
    </div>
  </div>
</section>


<!-- ============================================================
     TRENDING STRIP
============================================================ -->
<section id="trending-strip" aria-labelledby="trending-heading">
  <div class="container">
    <div class="trending-inner">

      <div class="trending-label-col reveal-left">
        <span class="section-label" id="trending-heading">Most Read</span>
        <h3>Stories That Moved People</h3>
        <p>The reports our readers shared, prayed over, and donated to most this season.</p>
      </div>

      <div class="trending-list reveal-right">
        <div class="trending-item" role="button" tabindex="0" aria-label="Read: Marco's Story">
          <span class="trending-num" aria-hidden="true">01</span>
          <div class="trending-info">
            <p class="trending-cat">Impact Story</p>
            <p class="trending-title">Marco's Story: From Malnourished to Thriving in 6 Months</p>
          </div>
        </div>
        <div class="trending-item" role="button" tabindex="0" aria-label="Read: Well #7 Complete">
          <span class="trending-num" aria-hidden="true">02</span>
          <div class="trending-info">
            <p class="trending-cat">Field Report · Uganda</p>
            <p class="trending-title">Well #7 is Complete — 200 Families Now Have Clean Water</p>
          </div>
        </div>
        <div class="trending-item" role="button" tabindex="0" aria-label="Read: 2,400 Meals Served">
          <span class="trending-num" aria-hidden="true">03</span>
          <div class="trending-info">
            <p class="trending-cat">Field Report · Philippines</p>
            <p class="trending-title">2,400 Meals Served in February — Our Biggest Month Yet</p>
          </div>
        </div>
        <div class="trending-item" role="button" tabindex="0" aria-label="Read: 18 Children Enrolled">
          <span class="trending-num" aria-hidden="true">04</span>
          <div class="trending-info">
            <p class="trending-cat">Education · Philippines</p>
            <p class="trending-title">18 Children Enrolled in Education Sponsorship for 2025</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>


<!-- ============================================================
     NEWSLETTER
============================================================ -->
<section id="newsletter" aria-labelledby="newsletter-title">
  <div class="newsletter-bg" aria-hidden="true"></div>
  <div class="newsletter-bg-orb newsletter-bg-orb-1" aria-hidden="true"></div>
  <div class="newsletter-bg-orb newsletter-bg-orb-2" aria-hidden="true"></div>

  <div class="container">
    <div class="newsletter-inner">

      <div class="newsletter-text reveal-left">
        <span class="section-label">Stay Connected</span>
        <h2 class="section-title" id="newsletter-title">Get Field Updates in Your Inbox</h2>
        <p class="body-text" style="color:rgba(255,255,255,.7); margin-bottom:32px;">
          "Receive real mission reports directly from Cebu and Uganda. No spam. Just impact."
        </p>
        <div class="newsletter-perks" role="list">
          <div class="newsletter-perk" role="listitem">
            <div class="perk-icon" aria-hidden="true">📍</div>
            <span>Field reports from Cebu &amp; Uganda, every month</span>
          </div>
          <div class="newsletter-perk" role="listitem">
            <div class="perk-icon" aria-hidden="true">📊</div>
            <span>Impact numbers — meals, wells, patients, and students</span>
          </div>
          <div class="newsletter-perk" role="listitem">
            <div class="perk-icon" aria-hidden="true">🙏</div>
            <span>Prayer requests and urgent needs as they arise</span>
          </div>
          <div class="newsletter-perk" role="listitem">
            <div class="perk-icon" aria-hidden="true">🚫</div>
            <span>Zero spam, zero pressure — unsubscribe any time</span>
          </div>
        </div>
      </div>

      <div class="newsletter-form-card reveal-right">
        <div id="nlForm">
          <h3>Join 1,200+ Supporters</h3>
          <p>Monthly updates. Real impact. Straight to your inbox.</p>
          <form id="subscribeForm" novalidate data-action="{{ route('newsletter.subscribe') }}">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="nl-field-group">
              <div class="nl-field">
                <label class="nl-label" for="nlFirstName">First Name</label>
                <input class="nl-input" id="nlFirstName" name="first_name" type="text" placeholder="Maria" autocomplete="given-name" required/>
              </div>
              <div class="nl-field">
                <label class="nl-label" for="nlEmail">Email Address</label>
                <input class="nl-input" id="nlEmail" name="email" type="email" placeholder="maria@example.com" autocomplete="email" required/>
              </div>
            </div>
            <button type="submit" class="nl-submit" id="nlSubmitBtn">
              &#9829; Subscribe — It's Free
            </button>
            <p class="nl-consent">
              By subscribing you agree to our <a href="#">Privacy Policy</a>.
              We never sell or share your information.
            </p>
            <p style="margin-top:16px; font-size:.8rem; color:rgba(255,255,255,.5); text-align:center;">
              Join thousands of supporters following our mission.
            </p>
          </form>
        </div>
        <div class="nl-success" id="nlSuccess" aria-live="polite">
          <div class="nl-success-icon" aria-hidden="true">🎉</div>
          <h4>You're in!</h4>
          <p>Thank you for subscribing. Your first field update is on its way.</p>
          <p style="margin-top:12px; font-size:.78rem; color:rgba(255,255,255,.4);">
            Check your inbox — including spam folder — for a confirmation email.
          </p>
        </div>
      </div>

    </div>
  </div>
</section>


<!-- ============================================================
     TESTIMONIALS SECTION
============================================================ -->
<section id="testimonials" aria-labelledby="testimonials-title">
  <div class="container">
    <div class="testimonials-header reveal">
      <span class="section-label">Voices from the Field</span>
      <h2 class="section-title" id="testimonials-title">What People Are Saying</h2>
      <p class="testimonials-subtitle">
        Real stories from volunteers, pastors, and beneficiaries who witness the impact firsthand.
      </p>
    </div>
    <div class="testimonials-carousel">
      <div class="testimonial-card reveal delay-1 active">
        <div class="testimonial-quote">
          "These meals mean our children can finally focus in school again. Before the program, many were too hungry to learn. Now they come to class with energy and hope."
        </div>
        <div class="testimonial-author">
          <div class="author-avatar">👩‍👧‍👦</div>
          <div class="author-info">
            <div class="author-name">Maria Santos</div>
            <div class="author-title">Local Parent, Cebu</div>
          </div>
        </div>
      </div>
      <div class="testimonial-card reveal delay-2">
        <div class="testimonial-quote">
          "The medical mission saved my grandmother's life. She couldn't afford the medicine she needed, but the doctors here treated her for free. God bless this ministry."
        </div>
        <div class="testimonial-author">
          <div class="author-avatar">👨‍⚕️</div>
          <div class="author-info">
            <div class="author-name">Pastor Roberto</div>
            <div class="author-title">Community Leader, Leyte</div>
          </div>
        </div>
      </div>
      <div class="testimonial-card reveal delay-3">
        <div class="testimonial-quote">
          "Clean water changed everything for our village. No more walking miles to find water, no more children getting sick. This well is a miracle from God."
        </div>
        <div class="testimonial-author">
          <div class="author-avatar">👨‍👩‍👧‍👦</div>
          <div class="author-info">
            <div class="author-name">James Okello</div>
            <div class="author-title">Village Elder, Mbale, Uganda</div>
          </div>
        </div>
      </div>
    </div>
    <div class="testimonial-dots">
      <button class="dot active" data-slide="0" aria-label="Show testimonial 1"></button>
      <button class="dot" data-slide="1" aria-label="Show testimonial 2"></button>
      <button class="dot" data-slide="2" aria-label="Show testimonial 3"></button>
    </div>
  </div>
</section>


<!-- ============================================================
     DONATION REMINDER SECTION
============================================================ -->
<section id="donation-reminder" aria-labelledby="donation-title">
  <div class="donation-bg">
    <img src="{{ asset('images/landingpage/community_outreach.webp') }}" alt="Mission work in action" loading="lazy"/>
    <div class="donation-overlay"></div>
  </div>
  <div class="container">
    <div class="donation-content reveal">
      <h2 class="donation-title" id="donation-title">Be Part of the Next Story</h2>
      <p class="donation-text">
        Every update you read is made possible by people who care. Your support helps us reach more children, families, and communities in need.
      </p>
      <div class="donation-actions">
        <a href="{{ route('donate') }}" class="btn btn-primary donation-btn">
          Donate Now
        </a>
        <a href="{{ route('donate') }}" class="btn btn-ghost donation-btn-secondary">
          Sponsor a Child
        </a>
      </div>
    </div>
  </div>
</section>


<!-- ============================================================
     FOOTER
============================================================ -->
<footer id="footer" role="contentinfo">
  <div class="container">
    <div class="footer-grid">

      <div class="footer-brand">
        <img src="{{ asset('images/logo.webp') }}" alt="Johnny Davis Global Missions" />
        <p>A nonprofit founded on the belief that a little help can go a long way. Serving communities in the Philippines and Uganda through food, water, medical care, and education.</p>
        <div class="footer-socials" aria-label="Social media">
          <a href="https://www.facebook.com/GlobalMissions55" class="social-icon" aria-label="Facebook">
            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg>
          </a>
          <a href="https://www.instagram.com/globalmissions50/" class="social-icon" aria-label="Instagram">
            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><rect x="2" y="2" width="20" height="20" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.5" cy="6.5" r="1" fill="currentColor" stroke="none"/></svg>
          </a>
          <a href="https://www.youtube.com/@johnnydavisministries" class="social-icon" aria-label="YouTube">
            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M22.54 6.42a2.78 2.78 0 0 0-1.95-1.96C18.88 4 12 4 12 4s-6.88 0-8.59.46a2.78 2.78 0 0 0-1.95 1.96A29 29 0 0 0 1 12a29 29 0 0 0 .46 5.58A2.78 2.78 0 0 0 3.41 19.6C5.12 20 12 20 12 20s6.88 0 8.59-.46a2.78 2.78 0 0 0 1.95-1.95A29 29 0 0 0 23 12a29 29 0 0 0-.46-5.58z"/><polygon points="9.75 15.02 15.5 12 9.75 8.98 9.75 15.02" fill="white"/></svg>
          </a>
        </div>
      </div>

      <nav aria-label="Quick links">
        <h3 class="footer-heading">Quick Links</h3>
        <div class="footer-links">
          <a href="{{ route('home') }}">Home</a>
          <a href="{{ route('who-we-are') }}">Who We Are</a>
          <a href="{{ route('ministry') }}">What We Do</a>
          <a href="{{ route('donate') }}">Make a Difference</a>
          <a href="https://johnnydavisministries.org" target="_blank" rel="noopener noreferrer">Johnny Davis Ministries</a>
          <a href="{{ route('contact') }}">Contact Us</a>
        </div>
      </nav>

      <nav aria-label="Programs">
        <h3 class="footer-heading">Our Programs</h3>
        <div class="footer-links">
          <a href="{{ route('home') }}#urgency">Feed the Hungry</a>
          <a href="{{ route('home') }}#disaster">Disaster Relief</a>
          <a href="{{ route('home') }}#help">Medical Missions</a>
          <a href="{{ route('home') }}#help">Education Support</a>
          <a href="{{ route('home') }}#help">Clean Water</a>
          <a href="{{ route('home') }}#help">Anti-Trafficking</a>
        </div>
      </nav>

      <address>
        <h3 class="footer-heading">Contact Us</h3>
        <div class="footer-contact">
          <div class="contact-row">
            <div class="contact-icon" aria-hidden="true">📍</div>
            <span>P.O. Box 1904<br/>Suwanee, GA 30024</span>
          </div>
          <div class="contact-row">
            <div class="contact-icon" aria-hidden="true">📞</div>
            <a href="tel:+14044262856">(404) 426-2856</a>
          </div>
          <div class="contact-row">
            <div class="contact-icon" aria-hidden="true">✉️</div>
            <a href="mailto:info@johnnydavisglobalmissions.org">info@johnnydavisglobalmissions.org</a>
          </div>
          <div class="contact-row">
            <div class="contact-icon" aria-hidden="true">🕐</div>
            <span>Mon–Fri: 9:00am – 5:00pm</span>
          </div>
        </div>
      </address>

    </div>

    <div class="footer-bottom">
      <p class="footer-copy">&copy;{{ date('Y') }} Johnny Davis Global Missions. All Rights Reserved.</p>
      <nav class="footer-legal" aria-label="Legal">
        <a href="#">Privacy Policy</a>
        <a href="#">Terms of Use</a>
        <a href="#">Nonprofit Disclosure</a>
      </nav>
    </div>
  </div>
</footer>


<script src="{{ asset('js/for_news.js') }}"></script>

</body>
</html>
