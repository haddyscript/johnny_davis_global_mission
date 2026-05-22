<!-- ============================================================
     ABOUT SECTION
============================================================ -->
<section id="about" aria-labelledby="about-title">
  <div class="container">
    <div class="about-grid">

      <!-- Image -->
      <div class="about-img-wrap reveal-left">
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

      <!-- Text -->
      <div class="reveal-right">
        <span class="section-label">{{ $cms->text('about', 'label', 'His Story') }}</span>
        <h2 class="section-title" id="about-title">{{ $cms->text('about', 'title', 'About Johnny Davis') }}</h2>

        <p class="about-para">
          {{ $cms->text('about', 'para1', 'Evangelist Johnny Davis is not only a teacher, prayer leader, and speaker — he is a visionary committed to turning compassion into action.') }}
        </p>
        <p class="about-para">
          {{ $cms->text('about', 'para2', 'After giving his life to Jesus Christ at age 24 and answering the call to ministry in 1992, Johnny has spent over three decades building ministries that transform lives both spiritually and practically. From church leadership and prison outreach to community empowerment and international missions, his work has always centered on one mission: serving people with excellence and integrity.') }}
        </p>
        <p class="about-para">
          {{ $cms->text('about', 'para3', 'In 2000, he founded Johnny Davis Ministries to equip believers and strengthen churches. In 2002, he launched Life Changing Christian Ministries in Alabama and established the "More Than Conquerors" prison outreach, bringing hope and restoration to incarcerated men.') }}
        </p>
        <p class="about-para">
          {{ $cms->text('about', 'para4', 'In 2021, the vision expanded globally. Through Johnny Davis Global Missions and the initiative Feed Filipino Children: Hunger Can\'t Wait!, Johnny and his team partner with trusted pastors in the Philippines to provide meals, prayer, and spiritual support to children and families facing extreme poverty.') }}
        </p>
        <p class="about-para">
          {{ $cms->text('about', 'para5', "This isn't temporary relief. It's sustainable impact.") }}
        </p>

        <div class="mission-points" role="list" aria-label="Mission points">
          <div class="mp-item" role="listitem">
            <span class="mp-icon" aria-hidden="true">🍽️</span>
            Provide nutritious meals to children
          </div>
          <div class="mp-item" role="listitem">
            <span class="mp-icon" aria-hidden="true">⛪</span>
            Support local pastors and churches
          </div>
          <div class="mp-item" role="listitem">
            <span class="mp-icon" aria-hidden="true">✝️</span>
            Extend the Gospel through practical love
          </div>
          <div class="mp-item" role="listitem">
            <span class="mp-icon" aria-hidden="true">🤝</span>
            Build long-term relationships in communities
          </div>
        </div>
      </div>

    </div>
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
