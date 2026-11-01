{{--
  OUR MISSION IN ACTION — seven TikTok outreach videos (exact order requested by Johnny Davis),
  the "Meet the Hearts Behind the Mission" team poster and a $7.99 monthly partner call-to-action.
  Sits directly beneath the Hunger Can't Wait banner and tracker on the homepage.
  Videos play in place (TikTok's player, loaded on click, or a site-hosted MP4), so visitors never leave the site.
  Videos 1–6 share one large spot: Video 1 shows first, and picking another from the row below swaps it in
  (js/mission-in-action.js), so every video gets the full layout and description.

  Each video shows its 'lead', optional 'tagline' and 'intro' paragraphs (or its 'summary'), then an optional
  'story' behind a "Read the full story" toggle (parts with an optional 'heading') and optional 'steps'.

  @param string $miaButtonHref  Where "Become a $7.99 Monthly Partner" goes
--}}
@php
  $miaVideos = [
      ['id' => '7702684615672204574', 'title' => 'From Shopping to Serving',
       'steps' => ['Shop', 'Prepare', 'Organize', 'Serve'],
       'lead' => '❤️ BEHIND EVERY MEAL IS A STORY OF LOVE, COMPASSION, AND SERVICE!',
       'intro' => [
           'It all begins with a mission — to bring hope and food to hungry children in the Philippines.',
           'Watch as our Johnny Davis Global Missions feeding team comes together with one purpose: to make a difference in the lives of children and families in need.',
       ],
       'story' => [
           ['heading' => '🚗 THE JOURNEY BEGINS!', 'paragraphs' => [
               'Our dedicated team gathers together and travels into the community to begin preparing for another feeding outreach.',
           ]],
           ['heading' => '🛒 SHOPPING WITH A PURPOSE!', 'paragraphs' => [
               'The team visits local stores and markets, carefully purchasing food, groceries, cooking supplies, and equipment needed to prepare meals for the children.',
           ]],
           ['heading' => '⛺ PREPARING THE LOCATION!', 'paragraphs' => [
               'After gathering the supplies, our team travels to the feeding location, unloads the equipment, sets up the venue, and prepares everything needed to welcome the children.',
           ]],
           ['heading' => '🍲 COOKING WITH LOVE!', 'paragraphs' => [
               'Our volunteers work together, washing, cutting, preparing, and cooking meals. Every step represents their commitment to serving others with compassion.',
           ]],
           ['heading' => '🍽️ THE MOMENT THAT MATTERS MOST!', 'paragraphs' => [
               'After all the shopping, traveling, preparation, and cooking, the meals are finally served to the children.',
               'This is what your generosity makes possible!',
               'Every meal represents more than food. It represents hope, kindness, and a reminder to these children that they are not forgotten.',
           ]],
       ]],
      // No custom poster: the thumbnail is a frame from the video itself
      ['id' => '7702674176703810846', 'title' => 'Hear From the Children!',
       'lead' => '🇵🇭 HEAR THEIR VOICES! ❤️ | FEED FILIPINO CHILDREN',
       'intro' => [
           'Listen as children and families share their stories and express their appreciation for the JDGM Feeding Program in Brgy. Fatima, Villaba, Leyte, Philippines.',
           'Through the generosity of our partners, Johnny Davis Global Missions is helping feed hungry children while sharing the love and Gospel of Jesus Christ.',
       ],
       'thumb' => 'frame'],
      ['id' => '7702931280853273886', 'title' => 'Feeding Filipino Children: Sharing God’s Love',
       'lead' => '🇵🇭 ANOTHER DAY OF FEEDING CHILDREN, SHARING GOD’S LOVE! ❤️',
       'tagline' => 'HUNGER CAN’T WAIT! | Bringing Hope to Children in the Philippines! 🌍',
       'intro' => [
           '📍 Purok XI-C, Fatima, General Santos City, Philippines',
           'Every child deserves a nutritious meal, a reason to smile, and the opportunity to experience God’s love!',
           'Join Johnny Davis Global Missions as we take you inside another special feeding outreach conducted by our dedicated Feed Filipino Children team in General Santos City, Philippines.',
       ],
       'story' => [
           ['heading' => '❤️ WATCH THE STORY UNFOLD!', 'paragraphs' => [
               'Our volunteers gathered together, prepared the food, organized the serving stations, and welcomed children from the surrounding community.',
               'Watch as our team carefully prepares plates of food, serves hungry children, and shares moments of joy and encouragement.',
               'Children gathered beneath the tents and along the community walls, enjoying their meals while our volunteers continued serving with compassion and love.',
               'But our mission goes beyond feeding hungry children!',
               'We believe every feeding outreach is also an opportunity to share the Gospel of Jesus Christ, demonstrate His love, and bring hope to children and families facing difficult circumstances.',
               'Every meal matters. Every child matters. Every act of love makes a difference!',
           ]],
           ['heading' => '❤️ THANK YOU TO OUR FAITHFUL PARTNERS AND SUPPORTERS!', 'paragraphs' => [
               'Because of your prayers, generosity, and financial support, our team has been able to reach children and families through feeding outreaches like this one.',
               'You are helping us put compassion into action!',
           ]],
       ]],
      ['id' => '7702920164274113822', 'title' => 'Feeding Outreach: General Santos City',
       'lead' => 'FEEDING CHILDREN, SHARING GOD’S LOVE! 🇵🇭',
       'tagline' => 'HUNGER CAN’T WAIT! | Real People. Real Love. Real Impact. 🌍',
       'intro' => [
           'Every feeding outreach begins with a mission, a dedicated team, and a heart to make a difference!',
           'Join Johnny Davis Global Missions as we take you inside one of our Feed Filipino Children outreaches in the Philippines!',
       ],
       'story' => [
           ['paragraphs' => [
               'As the children begin gathering, our dedicated team, including Assistant Director Jennifer Buico, prepares for another special day of feeding children and sharing God’s love.',
               'Watch as the children gather beneath the tents, volunteers organize and distribute meals, and families experience the love and compassion of people who genuinely care.',
               'More than 100 children and their families were reached through this feeding outreach!',
               'But this mission is about more than providing food.',
               'It’s about bringing hope, sharing the Gospel of Jesus Christ, and reminding every child that they are loved, valued, and never forgotten.',
           ]],
           ['heading' => '❤️ THANK YOU TO OUR PARTNERS AND SUPPORTERS!', 'paragraphs' => [
               'Because of your LOVE, GENEROSITY, and FAITHFUL SUPPORT, we continue making a difference in the lives of children and families.',
               'Every meal served represents compassion in action. Every smile reminds us why this mission matters!',
           ]],
       ]],
      // TikTok blocks this one from embedding, so it plays from the site once the MP4 is uploaded
      ['id' => '7702948137345797406', 'title' => 'Feeding Children: Purok XI-C, Fatima',
       'file' => 'videos/mission-in-action/feeding-children-purok-xi-c-fatima.mp4',
       'lead' => '😆 BRINGING SMILES TO FILIPINO CHILDREN! 🇵🇭',
       'tagline' => 'HUNGER CAN’T WAIT! | Feeding Children, Sharing God’s Love! 🌍',
       'intro' => [
           'Every child deserves a nutritious meal, a reason to smile, and the assurance that someone cares!',
           'Join Johnny Davis Global Missions as we take you inside one of our feeding outreaches in the Philippines, where compassion is being turned into action.',
       ],
       'story' => [
           ['heading' => '❤️ MORE THAN JUST A MEAL!', 'paragraphs' => [
               'Watch as our dedicated volunteers prepare delicious meals, organize the feeding outreach, and serve children in need.',
               'From plates filled with rice, chicken, and fresh fruit to children gathering together to enjoy their meals, every moment tells a story of kindness, generosity, and love.',
               'But our mission goes beyond feeding hungry children.',
               'We are sharing the love of Jesus Christ, bringing encouragement to families, and helping children experience the dignity, hope, and compassion they deserve.',
               'One Child. One Meal. One Act of Love at a Time! ❤️',
           ]],
       ]],
      ['id' => '7702906737132703006', 'title' => 'Hear Their Stories: Real Voices, Real Impact',
       'lead' => '🎤 HEARING THE VOICES OF THE COMMUNITY!',
       'intro' => [
           'Our Assistant Director, Jennifer Buico joined our dedicated volunteers in speaking with children, parents, and other adults in the community.',
           'As Jennifer and our team conducted interviews, families had an opportunity to share their experiences, express their gratitude, and talk about what this outreach meant to them.',
       ],
       'story' => [
           ['paragraphs' => [
               'One young girl even took the microphone to speak about the children’s experience, including learning about prayer and worshiping God!',
               'And as the children enjoyed their meals, our volunteers took time to ask how they liked the food.',
               'These are the moments that remind us why we do what we do!',
           ]],
           ['heading' => '❤️ OVER 100 CHILDREN AND THEIR FAMILIES REACHED!', 'paragraphs' => [
               'Because of the love and generosity of our faithful partners and supporters, we were able to provide food, share the Gospel of Jesus Christ, and bring encouragement to more than 100 children and their families during this outreach.',
               'Every meal represented compassion. Every conversation created a connection. Every smile reminded us that even the smallest act of kindness can make a lasting difference!',
           ]],
       ]],
      // Its poster sits beside it, so the thumbnail is the team photo with the title over it
      ['id' => '7702969922942405918', 'title' => 'Meet the Hearts Behind the Mission',
       'summary' => 'Our Philippines leadership and volunteer team, including Pastor Edgardo Buico, Jennifer Buico, Ivy and our dedicated volunteers.',
       'thumb' => 'titled'],
  ];
  $miaStage = array_slice($miaVideos, 0, 6, true);
  $miaTeam = $miaVideos[6];
@endphp
<section id="mission-in-action" class="mia" aria-labelledby="mia-title">
  <div class="mia-inner">

    <header class="mia-head">
      <p class="mia-eyebrow">Our Mission in Action</p>
      <h2 class="mia-title" id="mia-title">Watch the Difference!</h2>
      <p class="mia-lede">See the real work your $7.99 a month makes possible in the Philippines, from the market to a child&rsquo;s plate.</p>
    </header>

    {{-- Videos 1–6 — one shown large at a time, Video 1 first --}}
    <div class="mia-stage" data-mia-stage>
      @foreach($miaStage as $index => $video)
        <article class="mia-featured" id="mia-panel-{{ $index + 1 }}" data-mia-panel="{{ $index + 1 }}" aria-labelledby="mia-video-{{ $index + 1 }}" @if($index > 0) hidden @endif>
          @include('partials.mission-in-action-player', ['video' => $video, 'number' => $index + 1, 'playerClass' => 'mia-player--featured'])
          <div class="mia-featured-copy">
            <span class="mia-num">Video {{ $index + 1 }}@if($index === 0) &middot; Start here @endif</span>
            <h3 class="mia-featured-title" tabindex="-1" id="mia-video-{{ $index + 1 }}">{{ $video['title'] }}</h3>
            @isset($video['lead'])
              <p class="mia-featured-lead">{{ $video['lead'] }}</p>
            @endisset
            @isset($video['tagline'])
              <p class="mia-featured-tagline">{{ $video['tagline'] }}</p>
            @endisset
            @foreach($video['intro'] ?? [$video['summary']] as $paragraph)
              <p class="mia-featured-text">{{ $paragraph }}</p>
            @endforeach
            @isset($video['story'])
              <details class="mia-more">
                <summary class="mia-more-toggle">
                  <span class="mia-more-label-open">Read the full story</span>
                  <span class="mia-more-label-close">Show less</span>
                </summary>
                <div class="mia-more-body">
                  @foreach($video['story'] as $part)
                    @isset($part['heading'])
                      <h4 class="mia-more-heading">{{ $part['heading'] }}</h4>
                    @endisset
                    @foreach($part['paragraphs'] as $paragraph)
                      <p class="mia-more-text">{{ $paragraph }}</p>
                    @endforeach
                  @endforeach
                </div>
              </details>
            @endisset
            @isset($video['steps'])
              <ol class="mia-steps" aria-label="What the video shows">
                @foreach($video['steps'] as $step)
                  <li>{{ $step }}</li>
                @endforeach
              </ol>
            @endisset
          </div>
        </article>
      @endforeach
    </div>

    {{-- Picking a video moves it into the large spot; the one it replaces returns to this row --}}
    <div class="mia-picks-head">
      <h3 class="mia-picks-title">More from the field</h3>
      <p class="mia-picks-hint">Choose a video to watch it above.</p>
    </div>
    <ol class="mia-picks" aria-label="More outreach videos">
      @foreach($miaStage as $index => $video)
        @php
          $miaPickThumb = 'images/mission-in-action/thumbs/'.$video['id'].'.jpg';
        @endphp
        <li class="mia-pick" @if($index === 0) hidden @endif>
          <button type="button" class="mia-pick-btn" data-mia-pick="{{ $index + 1 }}" aria-controls="mia-panel-{{ $index + 1 }}">
            <span class="mia-pick-thumb">
              @if(file_exists(public_path($miaPickThumb)))
                <img src="{{ asset($miaPickThumb) }}" alt="" loading="lazy" decoding="async" />
              @endif
              <span class="mia-pick-play" aria-hidden="true">
                <svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor"><path d="M8 5.5v13a1 1 0 0 0 1.52.85l10.4-6.5a1 1 0 0 0 0-1.7L9.52 4.65A1 1 0 0 0 8 5.5z"/></svg>
              </span>
            </span>
            <span class="mia-num">Video {{ $index + 1 }}</span>
            <span class="mia-pick-title">{{ $video['title'] }}</span>
          </button>
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
<script src="{{ asset('js/mission-in-action.js') }}?v={{ filemtime(public_path('js/mission-in-action.js')) }}" defer></script>
