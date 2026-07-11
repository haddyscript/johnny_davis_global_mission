<!-- ============================================================
     EVENTS
============================================================ -->
<section id="events" aria-labelledby="events-title">
  <div class="container">

    <header class="events-header reveal">
      <span class="section-label">Gatherings &amp; Outreach</span>
      <h2 class="section-title" id="events-title">Ministry Events &amp; Gatherings</h2>
      <p class="body-text">
        Join us in prayer, worship, and community — online and in person.
      </p>
    </header>

    <div class="events-grid">

      <!-- Elevation Prayer -->
      <article class="event-card reveal" style="transition-delay:.05s" aria-label="Elevation Prayer — Virtual Prayer Gathering"
               data-img="{{ asset('images/johnny-davis-ministry/breaking-strong-holds.webp') }}"
               data-title="Elevation Prayer"
               role="button" tabindex="0">
        <div class="event-bg" style="background-image:url('{{ asset('images/johnny-davis-ministry/breaking-strong-holds.webp') }}')"></div>
        <div class="event-overlay"></div>
        <div class="event-body">
          <span class="event-type">Virtual &bull; Zoom</span>
          <h3 class="event-title">Elevation Prayer</h3>
          <div class="event-meta">
            <div class="event-meta-row">
              <span class="event-meta-icon">📅</span>
              Every Thursday
            </div>
            <div class="event-meta-row">
              <span class="event-meta-icon">🕖</span>
              7 PM EST &bull; 6 PM CST
            </div>
          </div>
          <p class="event-detail">
            Weekly Virtual Prayer Gathering on Zoom.<br/>
            Zoom ID: <strong style="color:#fff;">788 154 3458</strong> &nbsp; Passcode: <strong style="color:#fff;">dzW3WL</strong><br/>
            Worship begins at 6:30 PM.
          </p>
          <div class="event-tag-row">
            <span class="event-hashtag">#elevationprayergathering</span>
            <span class="event-hashtag">&nbsp; #hungercantwait</span>
          </div>
          <a href="#elevation-prayer-spotlight" class="event-detail" style="display:inline-block;margin-top:10px;color:var(--orange-light);font-weight:700;text-decoration:underline;" onclick="event.stopPropagation();">
            Read full message &amp; listen ↓
          </a>
        </div>
      </article>

      <!-- Wake Up To Prayer -->
      <article class="event-card reveal" style="transition-delay:.15s" aria-label="Wake Up To Prayer — Live on TikTok"
               data-img="{{ asset('images/johnny-davis-ministry/wake-up-to-prayer.webp') }}"
               data-title="Wake Up To Prayer"
               role="button" tabindex="0">
        <div class="event-bg" style="background-image:url('{{ asset('images/johnny-davis-ministry/wake-up-to-prayer.webp') }}')"></div>
        <div class="event-overlay"></div>
        <div class="event-body">
          <span class="event-type">Live &bull; TikTok</span>
          <h3 class="event-title">Wake Up To Prayer</h3>
          <div class="event-meta">
            <div class="event-meta-row">
              <span class="event-meta-icon">📅</span>
              Thursday 8 PM EST
            </div>
            <div class="event-meta-row">
              <span class="event-meta-icon">🌏</span>
              Friday 9 AM Philippines Time
            </div>
          </div>
          <p class="event-detail">
            Join Wake Up To Prayer with Prayer Coach Johnny — LIVE on TikTok.<br/>
            Come for encouragement. Stay for prayer.
          </p>
        </div>
      </article>

      <!-- Elevation Prayer Breakfast -->
      <article class="event-card reveal" style="transition-delay:.25s" aria-label="Elevation Prayer Breakfast"
               data-img="{{ asset('images/johnny-davis-ministry/elevation-prayer-breakfast.webp') }}"
               data-title="Elevation Prayer Breakfast"
               role="button" tabindex="0">
        <div class="event-bg" style="background-image:url('{{ asset('images/johnny-davis-ministry/elevation-prayer-breakfast.webp') }}')"></div>
        <div class="event-overlay"></div>
        <div class="event-body">
          <span class="event-type">In Person &bull; Free</span>
          <h3 class="event-title">Elevation Prayer Breakfast</h3>
          <div class="event-meta">
            <div class="event-meta-row">
              <span class="event-meta-icon">📅</span>
              Saturday, April 11, 2026
            </div>
            <div class="event-meta-row">
              <span class="event-meta-icon">🕙</span>
              10:00 AM – 12:00 PM
            </div>
            <div class="event-meta-row">
              <span class="event-meta-icon">📍</span>
              House of Refuge Fellowship Ministries
            </div>
          </div>
          <p class="event-detail">
            404 S Oak Street, Valdosta GA<br/>
            A gathering for pastors, leaders, and believers for prophetic prayer, encouragement, and fellowship.
          </p>
          <span class="event-free-badge">Free Event</span>
        </div>
      </article>

      <!-- Vision & Mission Empowerment -->
      <article class="event-card reveal" style="transition-delay:.35s" aria-label="12 Keys to Expanding Your Vision and Mission"
               data-img="{{ asset('images/johnny-davis-ministry/vision-and-mision.webp') }}"
               data-title="12 Keys to Expanding Your Vision &amp; Mission"
               role="button" tabindex="0">
        <div class="event-bg" style="background-image:url('{{ asset('images/johnny-davis-ministry/vision-and-mision.webp') }}')"></div>
        <div class="event-overlay"></div>
        <div class="event-body">
          <span class="event-type">Virtual &bull; Zoom Live</span>
          <h3 class="event-title">12 Keys to Expanding<br/>Your Vision &amp; Mission</h3>
          <div class="event-meta">
            <div class="event-meta-row">
              <span class="event-meta-icon">📅</span>
              Every Wednesday Night
            </div>
            <div class="event-meta-row">
              <span class="event-meta-icon">🕘</span>
              9 PM — Zoom Live
            </div>
          </div>
          <p class="event-detail">
            Weekly empowerment sessions for ministry leaders.<br/>
            Includes Worship Music · Prayer · Leadership Empowerment.
          </p>
        </div>
      </article>

    </div>
  </div>
</section>

<!-- ============================================================
     EVENT IMAGE MODAL
============================================================ -->
<div id="eventImgModal" class="eimg-overlay" role="dialog" aria-modal="true" aria-label="Event image preview" hidden>
  <div class="eimg-backdrop"></div>
  <div class="eimg-box">
    <button class="eimg-close" aria-label="Close">
      <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
    </button>
    <img id="eventImgSrc" src="" alt="" class="eimg-photo" />
    <p id="eventImgCaption" class="eimg-caption"></p>
  </div>
</div>

<script>
(function () {
  const modal    = document.getElementById('eventImgModal');
  const imgEl    = document.getElementById('eventImgSrc');
  const caption  = document.getElementById('eventImgCaption');
  const backdrop = modal.querySelector('.eimg-backdrop');
  const closeBtn = modal.querySelector('.eimg-close');

  function openModal(src, title) {
    imgEl.src        = src;
    imgEl.alt        = title;
    caption.innerHTML = title;
    modal.hidden     = false;
    document.body.style.overflow = 'hidden';
    closeBtn.focus();
  }

  function closeModal() {
    modal.hidden = true;
    imgEl.src    = '';
    document.body.style.overflow = '';
  }

  document.querySelectorAll('.event-card[data-img]').forEach(function (card) {
    card.style.cursor = 'pointer';
    card.addEventListener('click', function () {
      openModal(card.dataset.img, card.dataset.title);
    });
    card.addEventListener('keydown', function (e) {
      if (e.key === 'Enter' || e.key === ' ') {
        e.preventDefault();
        openModal(card.dataset.img, card.dataset.title);
      }
    });
  });

  closeBtn.addEventListener('click', closeModal);
  backdrop.addEventListener('click', closeModal);
  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape' && !modal.hidden) closeModal();
  });
})();
</script>
