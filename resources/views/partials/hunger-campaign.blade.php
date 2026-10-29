{{--
  HUNGER CAN'T WAIT! — $7.99 monthly partner campaign.
  Shared by the homepage and the donation page.

  @param string $hcwVariant     'home' | 'donate'
  @param string $hcwButtonHref  Where "Become a Monthly Partner" goes
--}}
<section id="hunger-campaign" class="hcw hcw--{{ $hcwVariant }}" aria-labelledby="hcw-title">
  <div class="hcw-inner">

    <div class="hcw-copy">
      <p class="hcw-eyebrow">Monthly Partner Campaign</p>
      <h2 class="hcw-title" id="hcw-title">HUNGER CAN&rsquo;T WAIT!</h2>
      <p class="hcw-goal">Be one of our first <strong>10,000</strong> monthly partners.</p>
      <p class="hcw-price"><span class="hcw-price-amount">$7.99</span> <span class="hcw-price-term">a month</span></p>
      <p class="hcw-body">For $7.99 a month, help feed hungry children in the Philippines and support our upcoming feeding program in Uganda.</p>

      <ul class="hcw-programs" aria-label="Feeding programs">
        <li class="hcw-program hcw-program--active">
          <span class="hcw-program-name">Philippines:</span> Feeding children now.
        </li>
        <li class="hcw-program hcw-program--soon">
          <span class="hcw-program-name">Uganda:</span> Coming soon.
        </li>
      </ul>

      <a href="{{ $hcwButtonHref }}" class="hcw-btn" id="hcwPartnerBtn">
        Become a Monthly Partner
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
      </a>
    </div>

    <figure class="hcw-flyer">
      <img src="{{ asset('images/hunger-cant-wait/hunger-cant-wait-flyer.jpg') }}"
           alt="Hunger Can't Wait! flyer: $7.99 a month. Help feed hungry children. Become a monthly partner. Philippines — active program. Uganda — coming soon. Johnny Davis, Founder. johnnydavisglobalmission.org/donate"
           width="543" height="679"
           fetchpriority="high"
           decoding="async" />
    </figure>

  </div>
</section>
