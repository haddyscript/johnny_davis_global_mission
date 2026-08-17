<!-- ============================================================
     OUR MISSION — quick overview + video previews
     Continues the dark navy straight out of the ministry focus bar.
============================================================ -->
<section id="mission-preview" aria-labelledby="mission-preview-title">
  <div class="container">
    <div class="mission-grid">

      <div class="mission-text reveal-left">
        <div class="mission-label-row">
          <span class="section-label">{{ $cms->text('mission', 'label', 'Our Mission') }}</span>
          <span class="mission-label-line" aria-hidden="true"></span>
        </div>
        <h2 class="section-title white" id="mission-preview-title">
          {{ $cms->text('mission', 'title', 'Empowering People to Lead and Fulfill Their God-Given Purpose.') }}
        </h2>
        <p class="body-text white">
          {{ $cms->text('mission', 'description', 'At Johnny Davis Ministries, we are committed to raising leaders, strengthening families, and transforming communities through the Gospel of Jesus Christ.') }}
        </p>
        <a href="#about" class="btn btn-outline">
          Learn More About Us <span aria-hidden="true">&rarr;</span>
        </a>
      </div>

      <div class="mission-cards reveal-right">

        <a href="#daily-push" class="mission-card" aria-label="Watch Daily Push">
          <div class="dp-thumb">
            <img src="{{ asset('images/johnny-davis-ministry/hero-image3.webp') }}" alt="Daily Push" loading="lazy" />
            <div class="dp-play"><div class="dp-play-btn">
              <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="white"><path d="M8 5v14l11-7z"/></svg>
            </div></div>
          </div>
          <h3 class="mission-card-title">Daily Push</h3>
          <p class="mission-card-desc">Encouragement to start your day in God's presence.</p>
        </a>

        <a href="#podcast" class="mission-card" aria-label="Explore the Podcast">
          <div class="dp-thumb">
            <img src="{{ asset('images/johnny-davis-ministry/hero-image3.webp') }}" alt="Podcast" loading="lazy" />
            <div class="dp-play"><div class="dp-play-btn">
              <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="white"><path d="M8 5v14l11-7z"/></svg>
            </div></div>
          </div>
          <h3 class="mission-card-title">Podcast</h3>
          <p class="mission-card-desc">Inspiring conversations on faith, leadership, and purpose.</p>
        </a>

        <a href="https://www.youtube.com/@johnnydavisministries" target="_blank" rel="noopener noreferrer" class="mission-card" aria-label="Watch the Latest Message on YouTube">
          <div class="dp-thumb">
            <img src="{{ $cms->image('about', 'image', asset('images/johnny-davis-ministry/about-johnny.jpeg')) }}" alt="Latest Message" loading="lazy" />
            <div class="dp-play"><div class="dp-play-btn">
              <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="white"><path d="M8 5v14l11-7z"/></svg>
            </div></div>
          </div>
          <h3 class="mission-card-title">Latest Message</h3>
          <p class="mission-card-desc">Watch powerful messages that uplift and equip.</p>
        </a>

      </div>
    </div>
  </div>
</section>
