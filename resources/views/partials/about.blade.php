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
