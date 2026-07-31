<!-- ============================================================
     ABOUT SECTION
============================================================ -->
<section id="about" aria-labelledby="about-title">
  <div class="container">
    <div class="about-grid">

      <!-- Image + Beyond the Pulpit -->
      <div class="about-left-col reveal-left">
        <div class="about-img-wrap">
          <img
            src="{{ $cms->image('about', 'image', asset('images/johnny-davis-ministry/hero-image1.webp')) }}"
            alt="Evangelist Johnny Davis"
            loading="lazy"
            class="about-img-clickable"
            role="button"
            tabindex="0"
            aria-label="View full image of Evangelist Johnny Davis"
          />
          <div class="about-accent">
            <strong>{{ $cms->text('about', 'accent_number', '30+') }}</strong>
            <span>{{ $cms->text('about', 'accent_label', 'Years in Ministry') }}</span>
          </div>
        </div>

        <!-- Beyond the pulpit — podcast / fellowship / booking -->
        <div class="about-programs">
          <header class="about-programs-header reveal">
            <span class="section-label">Beyond the Pulpit</span>
            <h3 class="about-block-title">Podcast, Fellowship &amp; Booking</h3>
          </header>

          <div class="programs-grid">
            <article class="program-card reveal" style="transition-delay:.05s">
              <span class="program-icon" aria-hidden="true">🎙️</span>
              <h4 class="program-title">Grace to Elevate Leadership Podcast</h4>
              <p class="program-tagline">&ldquo;Expand Your Mission. Elevate Your Influence.&rdquo;</p>
              <p class="program-desc">Evangelist Davis is the founder and host of the Grace to Elevate Leadership Podcast, created to equip, encourage, and empower ministry and marketplace leaders through biblical principles and practical leadership strategies.</p>
              <a href="#podcast" class="program-link">Listen to the podcast &rarr;</a>
            </article>

            <article class="program-card reveal" style="transition-delay:.15s">
              <span class="program-icon" aria-hidden="true">🤝</span>
              <h4 class="program-title">Johnny Davis Ministerial Fellowship</h4>
              <p class="program-tagline">&ldquo;Empowering to Lead | Maximizing Vision&rdquo;</p>
              <p class="program-desc">A global, non-denominational outreach ministry and fellowship that partners with other ministries to connect, encourage, equip, and strengthen pastors, evangelists, and emerging leaders as they fulfill their God-given assignments.</p>
              <a href="{{ route('ministerial-fellowship') }}" class="program-link">Explore the fellowship &rarr;</a>
            </article>

            <article class="program-card reveal" style="transition-delay:.25s">
              <span class="program-icon" aria-hidden="true">📅</span>
              <h4 class="program-title">Book Evangelist Johnny Davis</h4>
              <p class="program-tagline">For Your Next Event</p>
              <p class="program-desc">Invite Evangelist Johnny Davis to speak at your church, conference, social media platform, workshop, or special event, or learn more about his global mission projects, leadership podcast, and ministerial fellowship.</p>
              <a href="https://www.johnnydavisministries.org" target="_blank" rel="noopener" class="program-link">www.johnnydavisministries.org &rarr;</a>
            </article>
          </div>
        </div>
      </div>

      <!-- Text -->
      <div class="reveal-right">
        <span class="section-label">{{ $cms->text('about', 'label', 'His Story') }}</span>
        <h2 class="section-title" id="about-title">{{ $cms->text('about', 'title', 'About Johnny Davis') }}</h2>

        <p class="about-para">
          Evangelist Johnny Davis is an evangelist, Bible teacher, conference speaker, prayer leader, and President of Johnny Davis Global Missions — a visionary committed to turning compassion into action.
        </p>

        <!-- His Journey — timeline -->
        <div class="about-timeline-wrap">
          <span class="section-label">The Journey</span>
          <h3 class="about-block-title">Three Decades of Ministry</h3>

          <div class="about-timeline-track">
          <span class="about-timeline-progress" aria-hidden="true"></span>
          <ol class="about-timeline">
            <li class="timeline-item reveal-left">
              <span class="timeline-dot" aria-hidden="true"></span>
              <span class="timeline-year">1991</span>
              <p class="timeline-text">He received Jesus Christ as his Lord and Savior at the age of 24. He began his spiritual journey at World Changers Church International in College Park, Georgia, under the leadership of Dr. Creflo A. Dollar Jr., serving in pastoral personal assistance, the intercessory prayer team, the prayer counseling team, and men's ministry leadership.</p>
            </li>
            <li class="timeline-item reveal-left" style="transition-delay:.08s">
              <span class="timeline-dot" aria-hidden="true"></span>
              <span class="timeline-year">1992</span>
              <p class="timeline-text">He received the call to fivefold ministry and continued his ministerial education, graduating from the School of Ministry at World Changers Church International in 1994 under the leadership of Dr. Creflo A. Dollar Jr.</p>
            </li>
            <li class="timeline-item reveal-left" style="transition-delay:.08s">
              <span class="timeline-dot" aria-hidden="true"></span>
              <span class="timeline-year">2000</span>
              <p class="timeline-text">He founded Johnny Davis Ministries, an outreach ministry that opened doors for him to evangelize and minister in churches throughout Georgia and other states.</p>
            </li>
            <li class="timeline-item reveal-left" style="transition-delay:.08s">
              <span class="timeline-dot" aria-hidden="true"></span>
              <span class="timeline-year">2002</span>
              <p class="timeline-text">He received the call to pastor, and Life Changing Christian Ministries was birthed in his hometown of Loxley, Alabama.</p>
            </li>
            <li class="timeline-item reveal-left" style="transition-delay:.08s">
              <span class="timeline-dot" aria-hidden="true"></span>
              <span class="timeline-year">2004</span>
              <p class="timeline-text">The Lord placed a desire in his heart to begin a ministry for incarcerated men — teaching, equipping, and empowering men incarcerated at the city's local correctional facility.</p>
            </li>
            <li class="timeline-item reveal-left" style="transition-delay:.08s">
              <span class="timeline-dot" aria-hidden="true"></span>
              <span class="timeline-year">2007</span>
              <p class="timeline-text">He relocated to Atlanta, Georgia, to continue the vision of Life Changing Christian Ministries.</p>
            </li>
            <li class="timeline-item reveal-left" style="transition-delay:.08s">
              <span class="timeline-dot" aria-hidden="true"></span>
              <span class="timeline-year">2016</span>
              <p class="timeline-text">He relaunched Johnny Davis Ministries Outreach to extend the hand and love of Jesus Christ to a hungry, hurting, and lost world — partnering with local and global ministries to transform lives through God's Word and empower communities through education and resources.</p>
            </li>
            <li class="timeline-item reveal-left" style="transition-delay:.08s">
              <span class="timeline-dot" aria-hidden="true"></span>
              <span class="timeline-year">2022</span>
              <p class="timeline-text">Johnny Davis Global Missions launched a feeding program in the Philippines called Feed Filipino Children: "Hunger Can't Wait," partnering with pastors and marketplace leaders to provide children and families with access to healthy food — both globally and locally.</p>
            </li>
          </ol>
          </div>
        </div>
      </div>

    </div>

    <!-- Scripture pull-quote -->
    <blockquote class="about-pullquote reveal">
      <p>&ldquo;But whoever has this world's goods and sees his brother and fellow believer in need, yet closes his heart of compassion against him, how can the love of God live and remain in him?&rdquo;</p>
      <cite>— 1 John 3:17 (AMPC)</cite>
    </blockquote>

    <p class="about-teaching-note reveal">
      Evangelist Johnny Davis' teaching style is practical, relevant, thought-provoking, and humorous. He is known for &ldquo;keeping it real&rdquo; in the pulpit.
    </p>

  </div>
</section>

{{-- About image modal --}}
<div id="aboutImgModal" class="aimg-overlay" hidden role="dialog" aria-modal="true" aria-label="Full image view">
  <div class="aimg-backdrop"></div>
  <div class="aimg-box">
    <button class="aimg-close" aria-label="Close image">&times;</button>
    <img id="aboutImgSrc" src="" alt="Evangelist Johnny Davis" class="aimg-photo" />
  </div>
</div>

<style>
  .about-img-clickable {
    cursor: zoom-in;
    transition: transform .25s ease, box-shadow .25s ease;
  }
  .about-img-clickable:hover {
    transform: scale(1.02);
    box-shadow: 0 12px 36px rgba(0,0,0,.35);
  }

  .aimg-overlay {
    position: fixed;
    inset: 0;
    z-index: 9999;
    display: flex;
    align-items: center;
    justify-content: center;
  }
  .aimg-overlay[hidden] { display: none; }

  .aimg-backdrop {
    position: absolute;
    inset: 0;
    background: rgba(0,0,0,.85);
    backdrop-filter: blur(4px);
  }

  .aimg-box {
    position: relative;
    z-index: 1;
    max-width: min(90vw, 760px);
    animation: aimgFadeIn .25s ease;
  }
  @keyframes aimgFadeIn {
    from { opacity: 0; transform: scale(.94); }
    to   { opacity: 1; transform: scale(1); }
  }

  .aimg-photo {
    display: block;
    width: 100%;
    height: auto;
    border-radius: 12px;
    box-shadow: 0 24px 64px rgba(0,0,0,.6);
  }

  .aimg-close {
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
  .aimg-close:hover { background: #d4680e; }
</style>

<script>
(function () {
  var modal   = document.getElementById('aboutImgModal');
  var imgEl   = document.getElementById('aboutImgSrc');
  var closeBtn = modal ? modal.querySelector('.aimg-close') : null;
  var backdrop = modal ? modal.querySelector('.aimg-backdrop') : null;
  var trigger  = document.querySelector('.about-img-clickable');

  if (!modal || !trigger) return;

  function openModal() {
    imgEl.src = trigger.src;
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
