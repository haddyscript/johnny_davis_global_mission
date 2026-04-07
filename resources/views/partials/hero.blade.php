<!-- ============================================================
     HERO SLIDER
============================================================ -->
<section id="hero" aria-label="Johnny Davis Ministries Hero">

  <div class="slider-track" id="sliderTrack">

    <div class="slide active" id="slide-0">
      <div class="slide-bg" style="background-image:url('{{ asset('images/johnny-davis-ministry/hero-image1.webp') }}')"></div>
      <div class="slide-overlay"></div>
    </div>

    <div class="slide" id="slide-1">
      <div class="slide-bg" style="background-image:url('{{ asset('images/johnny-davis-ministry/hero-image2.webp') }}')"></div>
      <div class="slide-overlay"></div>
    </div>

    <div class="slide" id="slide-2">
      <div class="slide-bg" style="background-image:url('{{ asset('images/johnny-davis-ministry/hero-image3.webp') }}')"></div>
      <div class="slide-overlay"></div>
    </div>

    <div class="slide" id="slide-3">
      <div class="slide-bg" style="background-image:url('{{ asset('images/johnny-davis-ministry/hero-image4.webp') }}')"></div>
      <div class="slide-overlay"></div>
    </div>

  </div>

  <!-- Prev / Next -->
  <button class="slider-prev" id="sliderPrev" aria-label="Previous slide">
    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M15.41 16.59L10.83 12l4.58-4.59L14 6l-6 6 6 6z"/></svg>
  </button>
  <button class="slider-next" id="sliderNext" aria-label="Next slide">
    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M8.59 16.59L13.17 12 8.59 7.41 10 6l6 6-6 6z"/></svg>
  </button>

  <!-- Dots -->
  <div class="slider-dots" role="list" aria-label="Slide indicators">
    <button class="slider-dot active" data-index="0" aria-label="Slide 1"></button>
    <button class="slider-dot"        data-index="1" aria-label="Slide 2"></button>
    <button class="slider-dot"        data-index="2" aria-label="Slide 3"></button>
    <button class="slider-dot"        data-index="3" aria-label="Slide 4"></button>
  </div>

  <!-- Content -->
  <div class="container" style="height:100%; position:relative; z-index:3;">
    <div class="hero-content">
      <div class="hero-badge">Ministry &bull; Outreach &bull; Missions</div>

      <h1 class="hero-headline">Johnny Davis<br/>Ministries</h1>

      <p class="hero-sub">
        Transforming Lives. Empowering Communities.<br/>
        Expanding the Kingdom of God.
      </p>

      <div class="hero-ctas">
        <a href="#daily-push" class="btn btn-primary btn-lg">
          <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M8 5v14l11-7z"/></svg>
          Watch Daily Push
        </a>
        <a href="{{ route('donate') }}" class="btn btn-outline btn-lg">
          &#9829; Support the Mission
        </a>
      </div>
    </div>
  </div>

  <div class="hero-scroll" onclick="document.getElementById('about').scrollIntoView({behavior:'smooth'})" aria-label="Scroll down">
    <span>Scroll</span>
    <div class="hero-scroll-line"></div>
  </div>

</section>
