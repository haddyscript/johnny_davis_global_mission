<!-- ============================================================
     THANK YOU SCREEN
============================================================ -->
<div id="thank-you-screen">

  <div class="thank-header">
    <img src="{{ asset('images/logo.webp') }}" alt="Johnny Davis Global Missions" />
    <div class="thank-header-line"></div>
  </div>

  <div class="thank-card">
    <h1 class="thank-title">You Did It — Thank You!</h1>
    <p class="thank-subtitle">Your gift is now making immediate impact. Pastor Johnny and the whole ministry team are so grateful for your generosity.</p>

    <div class="pastor-msg-block">
      <div class="pastor-msg-header">
        <img src="{{ $pastorImg }}" alt="Pastor Johnny Davis" />
        <div>
          <div class="pastor-msg-name">A personal note from Pastor Johnny</div>
          <div class="pastor-msg-role">Founder &amp; Executive Director</div>
        </div>
      </div>
      <p class="pastor-msg-text">
        <strong>"Dear friend,</strong> thank you for your compassionate giving. Your donation changes lives through food, shelter, and water projects. We are praying for you and your family as this gift multiplies in the field."
      </p>
    </div>

    <div class="social-share-card">
      <div>
        <div class="social-share-title">Share Your Support</div>
        <p class="social-share-sub">Inspire others to join this cause today.</p>
      </div>
      <div class="social-btns">
        <a id="share-twitter"   class="social-btn-sm twitter"   target="_blank" rel="noopener noreferrer" href="#">Twitter</a>
        <a id="share-facebook"  class="social-btn-sm facebook"  target="_blank" rel="noopener noreferrer" href="#">Facebook</a>
        <a id="share-whatsapp"  class="social-btn-sm whatsapp"  target="_blank" rel="noopener noreferrer" href="#">WhatsApp</a>
      </div>
    </div>

    <p class="tell-friend-label">Tell a friend:</p>
    <div class="tell-friend-row">
      <input id="tell-friend-email" type="email" placeholder="Enter friend's email" class="tell-input" aria-label="Friend's email address"/>
      <button id="tell-friend-btn" class="tell-action">Send Invite</button>
    </div>
    <p class="tell-hint">The email is generated locally; press Send and continue your day with peace.</p>

    <p class="thank-summary">
      Campaign: <strong id="thank-campaign-name">Feed Filipino Children</strong> &nbsp;·&nbsp;
      Donation: <strong id="thank-amount">$29.99 / month</strong>
    </p>

    <button id="back-to-campaigns-from-thank" class="back-btn">← Return to Campaigns</button>
  </div>
</div>
