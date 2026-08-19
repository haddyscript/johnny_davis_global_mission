<!-- ============================================================
     HERO
============================================================ -->
<section id="hero" aria-label="Johnny Davis Ministries Hero">

  <div class="slide-bg" style="background-image:url('{{ asset('images/johnny-davis-ministry/half-hero-section-white.png') }}')"></div>

  <!-- Content -->
  <div class="container" style="height:100%; position:relative; z-index:3;">
    <div class="hero-content">
      <div class="hero-badge hero-badge-icon sr-only">
        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 2 4 5v6c0 5.25 3.4 9.74 8 11 4.6-1.26 8-5.75 8-11V5l-8-3zm0 2.18 6 2.25V11c0 4.17-2.6 7.79-6 8.92C8.6 18.79 6 15.17 6 11V6.43l6-2.25z"/></svg>
        <span>Empowering to Lead</span>
        <span class="hero-badge-sep" aria-hidden="true">&#124;</span>
        <span>Maximizing Vision</span>
      </div>

      <h1 class="hero-headline sr-only">
        <span class="hero-headline-line hero-headline-white">Johnny Davis</span>
        <span class="hero-headline-line hero-headline-gold">Ministries <span aria-hidden="true">&mdash;</span></span>
      </h1>

      <p class="hero-script sr-only">
        {!! nl2br(e($cms->text('hero', 'subtitle', "Transforming Lives. Empowering Communities.\nExpanding the Kingdom of God."))) !!}
      </p>

      <p class="hero-explore sr-only">
        {{ $cms->text('hero', 'explore_line', 'Explore ministry updates, videos, podcast, and upcoming events.') }}
      </p>

      <ul class="sr-only" aria-label="Ministry focus areas">
        <li>Teaching</li>
        <li>Prophetic Word</li>
        <li>Prayer</li>
        <li>Healing</li>
      </ul>

      <div class="hero-ctas sr-only">
        <a href="#daily-push" class="btn btn-primary btn-lg">
          <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M8 5v14l11-7z"/></svg>
          Watch Daily Push
        </a>
        <a href="{{ str_contains(request()->getHost(), 'johnnydavisministries.org') ? route('donate').'?campaign=Feed+Filipino+Children' : route('donate') }}" class="btn btn-outline btn-lg">
          &#9829; Support the Mission
        </a>
      </div>
    </div>
  </div>

</section>
