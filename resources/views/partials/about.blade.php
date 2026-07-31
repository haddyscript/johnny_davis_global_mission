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
          Evangelist Johnny Davis is an evangelist, Bible teacher, conference speaker, prayer leader, and President of Johnny Davis Global Missions.
        </p>
        <p class="about-para">
          In 1991, he received Jesus Christ as his Lord and Savior at the age of 24. He began his spiritual journey at World Changers Church International in College Park, Georgia, under the leadership of Dr. Creflo A. Dollar Jr. He served at World Changers Church International in various areas of ministry, including pastoral personal assistance, the intercessory prayer team, the prayer counseling team, and men's ministry leadership.
        </p>
        <p class="about-para">
          In 1992, he received the call to fivefold ministry. He continued his ministerial education and graduated from the School of Ministry at World Changers Church International in 1994 under the leadership of Dr. Creflo A. Dollar Jr.
        </p>
        <p class="about-para">
          In 2000, he founded Johnny Davis Ministries, an outreach ministry. This opened doors for him to evangelize and minister in churches throughout Georgia and other states.
        </p>
        <p class="about-para">
          In 2002, he received the call to pastor, and Life Changing Christian Ministries was birthed in his hometown of Loxley, Alabama.
        </p>
        <p class="about-para">
          In 2004, the Lord placed a desire in his heart to begin a ministry for incarcerated men. The goal of the ministry was to teach, equip, and empower men who were incarcerated at the city's local correctional facility.
        </p>
        <p class="about-para">
          In 2007, he relocated to Atlanta, Georgia, to continue the vision of Life Changing Christian Ministries.
        </p>
        <p class="about-para">
          In 2016, he relaunched Johnny Davis Ministries Outreach. The mission of Johnny Davis Ministries is to extend the hand and love of Jesus Christ to a hungry, hurting, and lost world. The ministry's goal is to partner with local and global ministries to transform lives through the power of God's Word and empower communities through education and resources.
        </p>
        <p class="about-para">
          In 2022, Johnny Davis Global Missions launched a feeding program in the Philippines called Feed Filipino Children: "Hunger Can't Wait." The program partners with pastors and marketplace leaders in their communities to provide children and families with access to healthy food. We envision communities where high-quality, nutritious, and delicious food is available to hungry children and families both globally and locally.
        </p>

        <blockquote class="about-quote">
          "But whoever has this world's goods and sees his brother and fellow believer in need, yet closes his heart of compassion against him, how can the love of God live and remain in him?"
        </blockquote>
        <p class="about-quote-citation">— 1 John 3:17 (AMPC)</p>

        <hr class="about-divider">

        <h3 class="about-subheading">Grace to Elevate Leadership Podcast</h3>
        <p class="about-tagline">"Expand Your Mission. Elevate Your Influence."</p>
        <p class="about-para">
          Evangelist Davis is the founder and host of the Grace to Elevate Leadership Podcast, which was created to equip, encourage, and empower ministry and marketplace leaders through biblical principles and practical leadership strategies.
        </p>

        <h3 class="about-subheading">Johnny Davis Ministerial Fellowship</h3>
        <p class="about-tagline">"Empowering to Lead | Maximizing Vision"</p>
        <p class="about-para">
          The Johnny Davis Ministerial Fellowship is a global, non-denominational outreach ministry and fellowship that partners with other ministries to help equip leaders and maximize their vision and mission. This leadership fellowship was created to connect, encourage, equip, and strengthen pastors, evangelists, ministry leaders, and emerging leaders as they fulfill their God-given assignments.
        </p>

        <h3 class="about-subheading">Book Evangelist Johnny Davis for Your Next Event</h3>
        <p class="about-para">
          To invite Evangelist Johnny Davis to speak at your church, conference, social media platform, workshop, or other special event, or to learn more about his global mission projects, leadership podcast, and ministerial fellowship, visit
          <a href="https://www.johnnydavisministries.org" class="about-link" target="_blank" rel="noopener">www.johnnydavisministries.org</a>.
        </p>

        <p class="about-para about-note">
          Evangelist Johnny Davis' teaching style is practical, relevant, thought-provoking, and humorous. He is known for "keeping it real" in the pulpit.
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
