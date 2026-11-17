{{--
  FAITH IN ACTION — Philippines mission photo gallery (Church Rehabilitation Project, Sarangani Mountains).
  Spotlight row: Pastor Edgardo Buico, the story, and the horseback outreach photo. Then the mission banner
  photo as the feature, the three church rebuild photos, and the full "Faith in Action" flyer.
  Photos are shown as taken (cropped from the flyer, never retouched). Any photo opens full size in a
  lightbox with previous/next (js/philippines-mission-gallery.js); the lightbox order follows $pmgPhotos.
  Sits directly above the More Than Meals October 2026 project update on the homepage.
--}}
@php
  $pmgDir = 'images/philippines-mission/';
  $pmgPhotos = [
      'pastor' => ['file' => 'pastor-buico.jpg', 'width' => 306, 'height' => 428,
          'caption' => 'Pastor Edgardo Buico (Barco) — Philippines Ministry Director',
          'alt' => 'Portrait of Pastor Edgardo Buico (Barco), Philippines Ministry Director'],
      'horseback' => ['file' => 'horseback-outreach.jpg', 'width' => 228, 'height' => 340,
          'caption' => 'Horseback outreach — reaching mountain communities where the roads end.',
          'alt' => 'A JDGM team member on horseback, guided by a young man, setting out on outreach in the Sarangani Mountains'],
      'banner' => ['file' => 'mission-banner-team.jpg', 'width' => 578, 'height' => 254,
          'caption' => 'Our Sarangani team with the church rehabilitation banner and the cement for the rebuild.',
          'alt' => 'The JDGM Philippines team holding a banner reading "Johnny Davis Global Missions — Church Rehabilitation, Sarangani Philippines" behind stacked bags of cement'],
      'frame' => ['file' => 'church-frame-raising.jpg', 'width' => 330, 'height' => 206,
          'caption' => 'Raising the frame of the new house of worship.',
          'alt' => 'Volunteers raising the wooden frame and roofing sheets of the church'],
      'sanctuary' => ['file' => 'church-sanctuary-floor.jpg', 'width' => 382, 'height' => 208,
          'caption' => 'The sanctuary takes shape — block walls, pulpit and benches.',
          'alt' => 'The church sanctuary floor with new concrete block walls, a wooden pulpit, benches and potted plants'],
      'roof' => ['file' => 'church-roof-and-walls.jpg', 'width' => 360, 'height' => 200,
          'caption' => 'Roof on and walls going up, block by block.',
          'alt' => 'Workers under the new church roof laying concrete blocks for the walls'],
      'flyer' => ['file' => 'faith-in-action-flyer.webp', 'width' => 1199, 'height' => 1312,
          'caption' => 'Faith in Action — Rebuilding Communities. Thank you to our generous donors!',
          'alt' => 'Faith in Action — Rebuilding Communities flyer: Church Rehabilitation Project, Sarangani Mountains, Philippines, with Pastor Edgardo Buico (Barco), Philippines Ministry Director. Thank you to our generous donors.'],
  ];
  $pmgIndex = array_flip(array_keys($pmgPhotos));
  $pmgSrc = fn (string $key): string => asset($pmgDir.$pmgPhotos[$key]['file']);
@endphp
<section id="philippines-mission-gallery" class="pmg" aria-labelledby="pmg-title">
  <div class="pmg-inner">

    <header class="pmg-header">
      <p class="pmg-kicker">Philippines Mission &middot; Photo Gallery</p>
      <h2 class="pmg-title" id="pmg-title">Faith in Action: <span>Rebuilding Communities</span></h2>
      <ul class="pmg-meta">
        <li>
          <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 2a7 7 0 0 0-7 7c0 5.25 7 13 7 13s7-7.75 7-13a7 7 0 0 0-7-7zm0 9.5A2.5 2.5 0 1 1 12 6.5a2.5 2.5 0 0 1 0 5z"/></svg>
          Sarangani Mountains, Philippines
        </li>
        <li>
          <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 2v5M9.5 4.5h5M4 21V12l8-5 8 5v9M9 21v-5h6v5M2 21h20"/></svg>
          Church Rehabilitation Project
        </li>
      </ul>
    </header>

    <div class="pmg-spotlight">
      @foreach (['pastor', 'horseback'] as $pmgKey)
        <figure class="pmg-item pmg-item--{{ $pmgKey }}">
          <button type="button" class="pmg-open" data-pmg-index="{{ $pmgIndex[$pmgKey] }}" aria-label="View full photo — {{ $pmgPhotos[$pmgKey]['caption'] }}">
            <img src="{{ $pmgSrc($pmgKey) }}" alt="{{ $pmgPhotos[$pmgKey]['alt'] }}"
                 width="{{ $pmgPhotos[$pmgKey]['width'] }}" height="{{ $pmgPhotos[$pmgKey]['height'] }}"
                 loading="lazy" decoding="async" />
          </button>
          @if ($pmgKey === 'pastor')
            <figcaption class="pmg-nameplate">
              <strong>Pastor Edgardo Buico (Barco)</strong>
              <span>Philippines Ministry Director</span>
            </figcaption>
          @else
            <figcaption>{{ $pmgPhotos[$pmgKey]['caption'] }}</figcaption>
          @endif
        </figure>
      @endforeach

      <div class="pmg-story">
        <p class="pmg-story-lead">Leading the rebuild on the ground.</p>
        <p>Deep in the Sarangani Mountains, Pastor Edgardo Buico and our local team are rebuilding a house of worship &mdash; carrying in cement, raising the frame and laying every block alongside the community. Where the roads end, the outreach continues on horseback.</p>
        <ul class="pmg-pillars">
          <li>
            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 21h18M5 21V10l7-5 7 5v11M10 21v-6h4v6"/></svg>
            Rebuilding a House of Worship
          </li>
          <li>
            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="9" cy="8" r="3.5"/><circle cx="17" cy="9" r="2.5"/><path d="M2.5 20c.6-3.6 3.3-6 6.5-6s5.9 2.4 6.5 6M15.5 14.2c2.9-.4 5.3 1.6 6 5.8"/></svg>
            Strengthening the Community
          </li>
          <li>
            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 20.5s-8-4.9-8-11A4.5 4.5 0 0 1 12 6.6a4.5 4.5 0 0 1 8 2.9c0 6.1-8 11-8 11z"/></svg>
            Sharing the Love of Christ
          </li>
        </ul>
      </div>
    </div>

    <div class="pmg-grid">
      @foreach (['banner', 'frame', 'sanctuary', 'roof'] as $pmgKey)
        <figure class="pmg-item pmg-item--{{ $pmgKey }}">
          <button type="button" class="pmg-open" data-pmg-index="{{ $pmgIndex[$pmgKey] }}" aria-label="View full photo — {{ $pmgPhotos[$pmgKey]['caption'] }}">
            <img src="{{ $pmgSrc($pmgKey) }}" alt="{{ $pmgPhotos[$pmgKey]['alt'] }}"
                 width="{{ $pmgPhotos[$pmgKey]['width'] }}" height="{{ $pmgPhotos[$pmgKey]['height'] }}"
                 loading="lazy" decoding="async" />
          </button>
          <figcaption>{{ $pmgPhotos[$pmgKey]['caption'] }}</figcaption>
        </figure>
      @endforeach
    </div>

    <button type="button" class="pmg-open pmg-flyer" data-pmg-index="{{ $pmgIndex['flyer'] }}">
      <img src="{{ $pmgSrc('flyer') }}" alt="{{ $pmgPhotos['flyer']['alt'] }}" width="1199" height="1312" loading="lazy" decoding="async" />
      <span class="pmg-flyer-copy">
        <strong>Thank you to our generous donors!</strong>
        <span>Your partnership is helping rebuild this church and restore hope in the Sarangani Mountain region of the Philippines.</span>
        <em>View the full &ldquo;Faith in Action&rdquo; flyer &rarr;</em>
      </span>
    </button>

  </div>

  <script type="application/json" id="pmgPhotos">@json(array_values(array_map(
      fn (array $photo): array => ['src' => asset($pmgDir.$photo['file']), 'alt' => $photo['alt'], 'caption' => $photo['caption']],
      $pmgPhotos
  )))</script>

  <div class="pmg-lightbox" id="pmgLightbox" hidden role="dialog" aria-modal="true" aria-label="Philippines mission photo">
    <div class="pmg-lightbox-backdrop" data-pmg-close></div>
    <figure class="pmg-lightbox-box">
      <img src="" alt="" id="pmgLightboxImg" />
      <figcaption>
        <span id="pmgLightboxCaption"></span>
        <span class="pmg-lightbox-count" id="pmgLightboxCount"></span>
      </figcaption>
    </figure>
    <button type="button" class="pmg-lightbox-close" data-pmg-close aria-label="Close photo">&times;</button>
    <button type="button" class="pmg-lightbox-nav pmg-lightbox-prev" id="pmgLightboxPrev" aria-label="Previous photo">&#8249;</button>
    <button type="button" class="pmg-lightbox-nav pmg-lightbox-next" id="pmgLightboxNext" aria-label="Next photo">&#8250;</button>
  </div>
</section>
<script src="{{ asset('js/philippines-mission-gallery.js') }}?v={{ filemtime(public_path('js/philippines-mission-gallery.js')) }}" defer></script>
