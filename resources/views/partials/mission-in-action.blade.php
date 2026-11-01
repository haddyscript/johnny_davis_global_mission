{{--
  OUR MISSION IN ACTION — seven TikTok outreach videos (exact order requested by Johnny Davis),
  the "Meet the Hearts Behind the Mission" team poster and a $7.99 monthly partner call-to-action.
  Sits directly beneath the Hunger Can't Wait banner and tracker on the homepage.
  Videos play in place (TikTok's embedded player, or a site-hosted MP4), so visitors never leave the site.

  @param string $miaButtonHref  Where "Become a $7.99 Monthly Partner" goes
--}}
@php
  $miaVideos = [
      ['id' => '7702684615672204574', 'title' => 'From Shopping to Serving',
       'summary' => 'Follow our Philippines outreach team as they buy food and supplies, cook, organize the outreach and serve hungry children: every step your monthly gift makes possible.'],
      ['id' => '7702674176703810846', 'title' => 'Hear From the Children!',
       'summary' => 'A young girl shares her story in her own words.'],
      ['id' => '7702931280853273886', 'title' => 'Feeding Filipino Children: Sharing God’s Love',
       'summary' => 'Children receive a meal and experience God’s love through our partners’ generosity.'],
      ['id' => '7702920164274113822', 'title' => 'Feeding Outreach: General Santos City',
       'summary' => 'A large gathering of children and volunteers serving the community.'],
      // TikTok blocks this one from embedding, so it plays from the site once the MP4 is uploaded
      ['id' => '7702948137345797406', 'title' => 'Feeding Children: Purok XI-C, Fatima',
       'summary' => 'Food preparation, volunteers serving meals and children receiving food.',
       'file' => 'videos/mission-in-action/feeding-children-purok-xi-c-fatima.mp4'],
      ['id' => '7702906737132703006', 'title' => 'Hear Their Stories: Real Voices, Real Impact',
       'summary' => 'Jennifer Buico talks with individuals and families about the impact of the ministry.'],
      ['id' => '7702969922942405918', 'title' => 'Meet the Hearts Behind the Mission',
       'summary' => 'Our Philippines leadership and volunteer team, including Pastor Edgardo Buico, Jennifer Buico, Ivy and our dedicated volunteers.'],
  ];
  $miaFeatured = $miaVideos[0];
  $miaGrid = array_slice($miaVideos, 1, 5, true);
  $miaTeam = $miaVideos[6];
@endphp
<section id="mission-in-action" class="mia" aria-labelledby="mia-title">
  <div class="mia-inner">

    <header class="mia-head">
      <p class="mia-eyebrow">Our Mission in Action</p>
      <h2 class="mia-title" id="mia-title">Watch the Difference!</h2>
      <p class="mia-lede">See the real work your $7.99 a month makes possible in the Philippines, from the market to a child&rsquo;s plate.</p>
    </header>

    {{-- Video 1 — featured first and largest --}}
    <article class="mia-featured" aria-labelledby="mia-video-1">
      @include('partials.mission-in-action-player', ['video' => $miaFeatured, 'number' => 1, 'playerClass' => 'mia-player--featured'])
      <div class="mia-featured-copy">
        <span class="mia-num">Video 1 &middot; Start here</span>
        <h3 class="mia-featured-title" id="mia-video-1">{{ $miaFeatured['title'] }}</h3>
        <p class="mia-featured-text">{{ $miaFeatured['summary'] }}</p>
        <ol class="mia-steps" aria-label="What the video shows">
          <li>Shop</li>
          <li>Prepare</li>
          <li>Organize</li>
          <li>Serve</li>
        </ol>
      </div>
    </article>

    {{-- Videos 2–6 — scrolls sideways on phones and tablets --}}
    <ol class="mia-grid" aria-label="More outreach videos">
      @foreach($miaGrid as $index => $video)
        <li class="mia-card">
          @include('partials.mission-in-action-player', ['video' => $video, 'number' => $index + 1])
          <span class="mia-num">Video {{ $index + 1 }}</span>
          <h3 class="mia-card-title">{{ $video['title'] }}</h3>
          <p class="mia-card-text">{{ $video['summary'] }}</p>
        </li>
      @endforeach
    </ol>

    {{-- Video 7 + team poster, side by side --}}
    <article class="mia-team" aria-labelledby="mia-video-7">
      <div class="mia-team-copy">
        <span class="mia-num">Video 7</span>
        <h3 class="mia-team-title" id="mia-video-7">{{ $miaTeam['title'] }}</h3>
        <p class="mia-team-text">{{ $miaTeam['summary'] }}</p>
      </div>
      <div class="mia-team-media">
        @include('partials.mission-in-action-player', ['video' => $miaTeam, 'number' => 7])
        <figure class="mia-poster">
          <img src="{{ asset('images/mission-in-action/meet-the-hearts-poster.jpg') }}"
               alt="Meet the Hearts Behind the Mission: the Johnny Davis Global Missions team in General Santos City, Philippines, with Pastor Edgardo Buico (Philippines Director), Jennifer Buico (Assistant Director) and Ivy (Assistant to Jennifer)."
               width="1199" height="1312"
               loading="lazy"
               decoding="async" />
        </figure>
      </div>
    </article>

    <div class="mia-cta">
      <a href="{{ $miaButtonHref }}" class="mia-btn">
        Become a $7.99 Monthly Partner
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
      </a>
      <p class="mia-cta-note"><strong>$7.99 a month</strong> feeds a child &middot; Cancel anytime &middot; Secure checkout by Stripe</p>
    </div>

  </div>
</section>
