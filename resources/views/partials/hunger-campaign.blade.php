{{--
  HUNGER CAN'T WAIT! — $7.99 monthly partner campaign.
  Approved premium banner artwork + live partner tracker.
  Shared by the homepage and the donation page.

  @param string     $hcwVariant      'home' | 'donate'
  @param string     $hcwButtonHref   Where "Become a Monthly Partner" goes
  @param array|null $hungerProgress  HungerCampaignTracker::progress() (null = count unavailable)
--}}
@php
  $hcwBanner = fn (string $ext) => collect([768, 1280, 2048])
      ->map(fn ($w) => asset("images/hunger-cant-wait/hunger-cant-wait-banner-{$w}.{$ext}")." {$w}w")
      ->implode(', ');
  $hcwPartners = $hungerProgress['active_partners'] ?? 0;
@endphp
<section id="hunger-campaign" class="hcw hcw--{{ $hcwVariant }}" aria-labelledby="hcw-title">
  <h2 class="hcw-sr-only" id="hcw-title">HUNGER CAN&rsquo;T WAIT! Become one of 10,000 monthly partners for $7.99 a month.</h2>

  <div class="hcw-banner-wrap">
    <a href="{{ $hcwButtonHref }}" class="hcw-banner" aria-label="Become a $7.99 monthly partner">
      <picture>
        <source type="image/webp" srcset="{{ $hcwBanner('webp') }}" sizes="(min-width: 1320px) 1280px, 100vw" />
        <img src="{{ asset('images/hunger-cant-wait/hunger-cant-wait-banner-1280.jpg') }}"
             srcset="{{ $hcwBanner('jpg') }}"
             sizes="(min-width: 1320px) 1280px, 100vw"
             alt="Hunger Can't Wait! 10,000 monthly partners — $7.99 a month. Help feed hungry children in the Philippines and Africa. Philippines: feeding children. Uganda: feeding children, coming soon. johnnydavisglobalmission.org/donate"
             width="2048" height="768"
             fetchpriority="high"
             decoding="async" />
      </picture>
    </a>
  </div>

  <div class="hcw-tracker"
       data-hcw-tracker
       data-progress-url="{{ route('donate.hunger-progress') }}"
       @if(! $hungerProgress) hidden @endif>
    <div class="hcw-tracker-stats">
      <p class="hcw-eyebrow">Our journey to 10,000 monthly partners</p>

      <p class="hcw-count" data-hcw-has-partners @if($hcwPartners < 1) hidden @endif>
        <span class="hcw-count-num" data-hcw-count>{{ number_format($hcwPartners) }}</span>
        <span class="hcw-count-label">active monthly {{ $hcwPartners === 1 ? 'partner' : 'partners' }}</span>
      </p>
      <p class="hcw-count hcw-count--first" data-hcw-no-partners @if($hcwPartners > 0) hidden @endif>
        Be one of our first monthly partners
      </p>

      <div class="hcw-bar"
           role="progressbar"
           aria-label="Active monthly partners toward the 10,000 goal"
           aria-valuemin="0"
           aria-valuemax="{{ $hungerProgress['goal'] ?? 10000 }}"
           aria-valuenow="{{ $hcwPartners }}">
        <span class="hcw-bar-fill @if($hcwPartners > 0) hcw-bar-fill--started @endif"
              data-hcw-bar
              style="width: {{ $hungerProgress['percent'] ?? 0 }}%"></span>
      </div>

      <p class="hcw-bar-legend">
        <span>Next milestone: <strong data-hcw-milestone>{{ number_format($hungerProgress['next_milestone'] ?? 100) }}</strong></span>
        <span>Goal: <strong>10,000</strong></span>
      </p>
    </div>

    <div class="hcw-tracker-cta">
      <a href="{{ $hcwButtonHref }}" class="hcw-btn" id="hcwPartnerBtn">
        Become a Monthly Partner
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
      </a>
      <p class="hcw-cta-note"><strong>$7.99 a month</strong>, billed monthly &middot; Cancel anytime &middot; Secure checkout by Stripe</p>

      <ul class="hcw-programs" aria-label="Feeding programs">
        <li class="hcw-program hcw-program--active">
          <span class="hcw-program-name">Philippines:</span> Feeding children now.
        </li>
        <li class="hcw-program hcw-program--soon">
          <span class="hcw-program-name">Uganda:</span> Coming soon.
        </li>
      </ul>
    </div>
  </div>

  {{-- Shown instead of the tracker when the live count can't be read — never a made-up number --}}
  <div class="hcw-tracker hcw-tracker--fallback" data-hcw-fallback @if($hungerProgress) hidden @endif>
    <div class="hcw-tracker-stats">
      <p class="hcw-eyebrow">Our journey to 10,000 monthly partners</p>
      <p class="hcw-count hcw-count--first">Join us for $7.99 a month</p>
    </div>
    <div class="hcw-tracker-cta">
      <a href="{{ $hcwButtonHref }}" class="hcw-btn">Become a Monthly Partner</a>
    </div>
  </div>
</section>
<script src="{{ asset('js/hunger-campaign.js') }}?v={{ filemtime(public_path('js/hunger-campaign.js')) }}" defer></script>
