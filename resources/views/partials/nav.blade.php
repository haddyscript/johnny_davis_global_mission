@php
    use App\Models\NavItem;

    $currentPath    = '/' . ltrim(request()->path(), '/');
    $isMinistryPage = $currentPath === '/ministry'
        || str_contains(request()->getHost(), 'johnnydavisministries.org');
    $logoSrc        = $isMinistryPage
        ? asset('images/ministry-logo.png')
        : asset('images/logo.webp');
    $logoHref       = $isMinistryPage ? url('/ministry') : url('/');
    $logoAlt        = $isMinistryPage ? 'Johnny Davis Ministries' : 'Johnny Davis Global Missions Home';
    $navItems       = NavItem::forNav();
@endphp

<style>
  /* ── Ministry logo (inline icon) ── */
  .nav-ministry-logo {
    height: 22px; width: auto;
    object-fit: contain;
    display: inline-block;
    vertical-align: middle;
    margin-right: 6px; margin-top: -2px;
    filter: brightness(0) invert(1) opacity(.75);
    transition: filter .2s ease;
  }

  /* ── Ministry page: centred logo ── */
  .nav-ministry-only .nav-inner   { justify-content: center; }
  .nav-ministry-only .nav-logo img {
    height: 48px; width: auto; max-width: 220px;
    object-fit: contain; filter: brightness(0) invert(1);
  }

  /* ── Desktop nav link hover transitions ── */
  .nav-links a { transition: color .2s ease, background .2s ease, box-shadow .2s ease; }

  /* ── Desktop active state ── */
  .nav-links a.nav-active {
    color: #f07c1e !important; font-weight: 700 !important;
    background: rgba(240,124,30,.12) !important; border-radius: 6px !important;
    box-shadow: inset 0 -2px 0 0 #f07c1e !important; text-decoration: none !important;
  }
  .nav-links a.nav-active .nav-ministry-logo {
    filter: brightness(0) saturate(100%) invert(55%) sepia(90%)
            saturate(600%) hue-rotate(5deg) brightness(1.1)
            drop-shadow(0 0 4px rgba(240,124,30,.7)) !important;
  }

  /* ────────────────────────────────────────────────────────────
     MOBILE DRAWER OVERLAY
     Applied on all pages via this partial. ≤ 900 px (covers iPad Air 4 at 820 px).
  ──────────────────────────────────────────────────────────── */
  .m-nav-overlay { display: none; }

  @media (max-width: 900px) {
    /* Full-viewport container */
    .m-nav-overlay {
      display: block;
      position: fixed;
      inset: 0;
      z-index: 1998;
      pointer-events: none;
      visibility: hidden;
      transition: visibility 0s linear .46s;
    }
    .m-nav-overlay.open {
      pointer-events: auto;
      visibility: visible;
      transition-delay: 0s;
    }

    /* Frosted-glass backdrop */
    .m-nav-bd {
      position: absolute;
      inset: 0;
      background: rgba(4,12,30,0);
      backdrop-filter: blur(0px);
      -webkit-backdrop-filter: blur(0px);
      transition: background .38s ease, backdrop-filter .38s ease;
    }
    .m-nav-overlay.open .m-nav-bd {
      background: rgba(4,12,30,.78);
      backdrop-filter: blur(14px);
      -webkit-backdrop-filter: blur(14px);
    }

    /* Drawer: slides in from right */
    .m-nav-drawer {
      position: absolute;
      top: 0; right: 0; bottom: 0;
      width: min(88vw, 380px);
      background: linear-gradient(165deg, #0e2d52 0%, #091e3b 45%, #060f1e 100%);
      border-left: 1px solid rgba(255,255,255,.07);
      box-shadow: -24px 0 64px rgba(0,0,0,.65);
      display: flex;
      flex-direction: column;
      transform: translateX(105%) translateZ(0);
      transition: transform .44s cubic-bezier(.4,0,.2,1);
      will-change: transform;
      overflow: hidden;
    }
    .m-nav-overlay.open .m-nav-drawer {
      transform: translateX(0) translateZ(0);
    }

    /* Animated orange accent bar at top of drawer */
    .m-nav-drawer::before {
      content: '';
      position: absolute;
      top: 0; left: 0; right: 0; height: 3px;
      background: linear-gradient(90deg, #f07c1e 0%, #ffb347 50%, #f07c1e 100%);
      background-size: 200%;
      animation: mNavAccent 2.8s linear infinite;
      z-index: 1;
    }
    @keyframes mNavAccent {
      0%   { background-position: 0%; }
      100% { background-position: 200%; }
    }

    /* Drawer header row */
    .m-nav-top {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 20px 22px 18px;
      border-bottom: 1px solid rgba(255,255,255,.07);
      flex-shrink: 0;
    }
    .m-nav-logo { height: 38px; width: auto; object-fit: contain; opacity: .92; }

    /* Close (×) button */
    .m-nav-x {
      width: 42px; height: 42px;
      border-radius: 50%;
      background: rgba(255,255,255,.07);
      border: 1px solid rgba(255,255,255,.12);
      color: rgba(255,255,255,.9);
      display: flex; align-items: center; justify-content: center;
      cursor: pointer;
      transition:
        background .25s ease, border-color .25s ease,
        transform .32s cubic-bezier(.34,1.56,.64,1);
      flex-shrink: 0;
    }
    .m-nav-x:hover {
      background: rgba(240,124,30,.25);
      border-color: rgba(240,124,30,.5);
      transform: rotate(90deg) scale(1.1);
    }
    .m-nav-x:active { transform: rotate(90deg) scale(.92); }

    /* Nav link list */
    .m-nav-links {
      flex: 1;
      display: flex;
      flex-direction: column;
      padding: 8px 0;
      overflow-y: auto;
    }

    /* Individual links — hardware-accelerated stagger reveal */
    .m-nav-links a {
      --reveal-delay: 0s;
      position: relative;
      display: flex;
      align-items: center;
      gap: 12px;
      padding: 15px 24px;
      color: rgba(255,255,255,.72);
      font-size: 1.02rem;
      font-weight: 500;
      border-bottom: 1px solid rgba(255,255,255,.05);
      text-decoration: none;
      /* opacity/transform animate with stagger delay;
         hover properties (color, bg, padding) have 0s delay */
      opacity: 0;
      transform: translateX(26px) translateZ(0);
      transition:
        opacity   .3s ease var(--reveal-delay),
        transform .3s cubic-bezier(.34,1.56,.64,1) var(--reveal-delay),
        color       .18s ease 0s,
        background  .18s ease 0s,
        padding-left .18s ease 0s;
    }
    .m-nav-links a:last-child { border-bottom: none; }
    .m-nav-links a:hover {
      color: #fff;
      background: rgba(255,255,255,.06);
      padding-left: 30px;
    }
    .m-nav-links a:active { background: rgba(255,255,255,.11); }

    /* Active link */
    .m-nav-links a.nav-active {
      color: #f07c1e !important;
      font-weight: 700 !important;
      background: rgba(240,124,30,.07) !important;
    }
    .m-nav-links a.nav-active::after {
      content: '';
      position: absolute;
      right: 0; top: 50%;
      transform: translateY(-50%);
      width: 3px; height: 55%;
      background: #f07c1e;
      border-radius: 2px 0 0 2px;
    }
    .m-nav-links a.nav-active .nav-ministry-logo {
      filter: brightness(0) saturate(100%) invert(55%) sepia(90%)
              saturate(600%) hue-rotate(5deg) brightness(1.1) !important;
    }

    /* Stagger each link in with CSS custom property */
    .m-nav-overlay.open .m-nav-links a { opacity:1; transform:translateZ(0); }
    .m-nav-overlay.open .m-nav-links a:nth-child(1)  { --reveal-delay:.12s }
    .m-nav-overlay.open .m-nav-links a:nth-child(2)  { --reveal-delay:.17s }
    .m-nav-overlay.open .m-nav-links a:nth-child(3)  { --reveal-delay:.22s }
    .m-nav-overlay.open .m-nav-links a:nth-child(4)  { --reveal-delay:.27s }
    .m-nav-overlay.open .m-nav-links a:nth-child(5)  { --reveal-delay:.32s }
    .m-nav-overlay.open .m-nav-links a:nth-child(6)  { --reveal-delay:.37s }
    .m-nav-overlay.open .m-nav-links a:nth-child(7)  { --reveal-delay:.42s }

    /* Drawer footer: donate CTA + socials */
    .m-nav-footer {
      padding: 18px 22px calc(18px + env(safe-area-inset-bottom, 0px));
      border-top: 1px solid rgba(255,255,255,.07);
      flex-shrink: 0;
      opacity: 0;
      transform: translateY(18px) translateZ(0);
      transition: opacity .35s ease .46s, transform .35s ease .46s;
    }
    .m-nav-overlay.open .m-nav-footer {
      opacity: 1;
      transform: translateZ(0);
    }

    .m-nav-donate {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
      width: 100%;
      padding: 15px 20px;
      background: #f07c1e;
      color: #fff;
      font-weight: 700;
      font-size: .98rem;
      border-radius: 12px;
      border: none;
      cursor: pointer;
      text-decoration: none;
      margin-bottom: 14px;
      box-shadow: 0 4px 18px rgba(240,124,30,.4);
      transition: background .2s ease, transform .15s ease, box-shadow .2s ease;
    }
    .m-nav-donate:hover {
      background: #d96712;
      transform: translateY(-2px);
      box-shadow: 0 8px 28px rgba(240,124,30,.55);
    }
    .m-nav-donate:active { transform: scale(.97); }

    .m-nav-socials {
      display: flex;
      justify-content: center;
      gap: 10px;
    }
    .m-nav-social {
      width: 38px; height: 38px;
      border-radius: 9px;
      background: rgba(255,255,255,.06);
      border: 1px solid rgba(255,255,255,.1);
      display: flex; align-items: center; justify-content: center;
      color: rgba(255,255,255,.55);
      transition: background .2s, border-color .2s, color .2s, transform .2s;
    }
    .m-nav-social:hover {
      background: #f07c1e;
      border-color: #f07c1e;
      color: #fff;
      transform: translateY(-2px);
    }

    /* Hide the old nav-mobile: replaced by the overlay drawer */
    .nav-mobile { display: none !important; }
  }
</style>

<header id="navbar" role="banner"{{ $isMinistryPage ? ' class="nav-ministry-only"' : '' }}>
  <div class="container">
    <nav class="nav-inner" aria-label="Main navigation">
      <a href="{{ $logoHref }}" class="nav-logo" aria-label="{{ $logoAlt }}">
        <img src="{{ $logoSrc }}" alt="{{ $logoAlt }}" />
      </a>

      @if(!$isMinistryPage)
        <ul class="nav-links" role="list">
          @foreach($navItems as $item)
            @php
              $itemPath  = parse_url($item->url, PHP_URL_PATH) ?? '';
              $hasHash   = str_contains($item->url, '#');
              $isActive  = !$hasHash && $itemPath && !in_array($itemPath, ['/#', '#'])
                  ? rtrim($itemPath, '/') === rtrim($currentPath, '/')
                  : false;
              $baseClass = trim($item->nav_class ?? '');
              $classes   = trim($baseClass . ($isActive ? ' active nav-active' : ''));
              $attrs     = $item->is_external ? ' target="_blank" rel="noopener noreferrer"' : '';
              $href      = str_starts_with($item->url, '/') ? url($item->url) : $item->url;
            @endphp
            <li>
              <a href="{{ $href }}"{{ $classes ? ' class="' . e($classes) . '"' : '' }}{!! $attrs !!}>
                @if(strtolower(strip_tags($item->label)) === 'ministry')
                  <img src="{{ asset('images/ministry-logo.png') }}" alt="" class="nav-ministry-logo" aria-hidden="true" />
                @endif
                {!! $item->label !!}
              </a>
            </li>
          @endforeach
        </ul>

        <button class="nav-toggle" id="navToggle"
                aria-label="Open navigation menu"
                aria-expanded="false"
                aria-controls="mNavOverlay">
          <span></span><span></span><span></span>
        </button>
      @endif
    </nav>
  </div>

  @if(!$isMinistryPage)
    {{-- Legacy element kept for backward-compat; hidden on mobile by CSS --}}
    <nav class="nav-mobile" id="navMobile" aria-hidden="true" aria-label="Mobile navigation">
      @foreach($navItems as $item)
        @php
          $itemPath  = parse_url($item->url, PHP_URL_PATH) ?? '';
          $isActive  = $itemPath && !in_array($itemPath, ['/#', '#'])
              ? rtrim($itemPath, '/') === rtrim($currentPath, '/')
              : false;
          $baseClass = trim($item->nav_class ?? '');
          $classes   = trim($baseClass . ($isActive ? ' active nav-active' : ''));
          $attrs     = $item->is_external ? ' target="_blank" rel="noopener noreferrer"' : '';
          $href      = str_starts_with($item->url, '/') ? url($item->url) : $item->url;
        @endphp
        <a href="{{ $href }}"{{ $classes ? ' class="' . e($classes) . '"' : '' }}{!! $attrs !!}>
          @if(strtolower(strip_tags($item->label)) === 'ministry')
            <img src="{{ asset('images/ministry-logo.png') }}" alt="" class="nav-ministry-logo" aria-hidden="true" />
          @endif
          {!! $item->label !!}
        </a>
      @endforeach
    </nav>
  @endif
</header>

@if(!$isMinistryPage)
{{-- ── Mobile drawer overlay ──────────────────────────────────────── --}}
<div class="m-nav-overlay"
     id="mNavOverlay"
     role="dialog"
     aria-modal="true"
     aria-label="Site navigation"
     aria-hidden="true">

  <div class="m-nav-bd" id="mNavBd"></div>

  <aside class="m-nav-drawer">
    {{-- Header row: logo + close button --}}
    <div class="m-nav-top">
      <img src="{{ $logoSrc }}" alt="{{ $logoAlt }}" class="m-nav-logo" />
      <button class="m-nav-x" id="mNavClose" aria-label="Close navigation menu">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
             fill="none" stroke="currentColor" stroke-width="2.5"
             stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
          <path d="M18 6L6 18M6 6l12 12"/>
        </svg>
      </button>
    </div>

    {{-- Navigation links --}}
    <nav class="m-nav-links" aria-label="Primary navigation">
      @foreach($navItems as $item)
        @php
          $itemPath  = parse_url($item->url, PHP_URL_PATH) ?? '';
          $isActive  = $itemPath && !in_array($itemPath, ['/#', '#'])
              ? rtrim($itemPath, '/') === rtrim($currentPath, '/')
              : false;
          $baseClass = trim($item->nav_class ?? '');
          $classes   = trim($baseClass . ($isActive ? ' active nav-active' : ''));
          $attrs     = $item->is_external ? ' target="_blank" rel="noopener noreferrer"' : '';
          $href      = str_starts_with($item->url, '/') ? url($item->url) : $item->url;
        @endphp
        <a href="{{ $href }}"{{ $classes ? ' class="' . e($classes) . '"' : '' }}{!! $attrs !!}>
          @if(strtolower(strip_tags($item->label)) === 'ministry')
            <img src="{{ asset('images/ministry-logo.png') }}" alt="" class="nav-ministry-logo" aria-hidden="true" />
          @endif
          {!! $item->label !!}
        </a>
      @endforeach
    </nav>

    {{-- Footer: Donate CTA + social icons --}}
    <div class="m-nav-footer">
      <a href="{{ route('donate') }}" class="m-nav-donate">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
             viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
          <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3
                   7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3
                   19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
        </svg>
        Donate Now
      </a>

      <div class="m-nav-socials" aria-label="Social media links">
        <a href="https://www.facebook.com/GlobalMissions55" class="m-nav-social"
           aria-label="Facebook" target="_blank" rel="noopener noreferrer">
          <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15"
               viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
            <path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/>
          </svg>
        </a>
        <a href="https://www.instagram.com/globalmissions50/" class="m-nav-social"
           aria-label="Instagram" target="_blank" rel="noopener noreferrer">
          <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15"
               viewBox="0 0 24 24" fill="none" stroke="currentColor"
               stroke-width="2" aria-hidden="true">
            <rect x="2" y="2" width="20" height="20" rx="5" ry="5"/>
            <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/>
            <line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/>
          </svg>
        </a>
        <a href="https://www.youtube.com/@johnnydavisministries" class="m-nav-social"
           aria-label="YouTube" target="_blank" rel="noopener noreferrer">
          <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15"
               viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
            <path d="M22.54 6.42a2.78 2.78 0 0 0-1.95-1.96C18.88 4 12 4 12
                     4s-6.88 0-8.59.46a2.78 2.78 0 0 0-1.95 1.96A29 29 0 0 0 1
                     12a29 29 0 0 0 .46 5.58A2.78 2.78 0 0 0 3.41 19.6C5.12 20
                     12 20 12 20s6.88 0 8.59-.46a2.78 2.78 0 0 0
                     1.95-1.95A29 29 0 0 0 23 12a29 29 0 0 0-.46-5.58z"/>
            <polygon points="9.75 15.02 15.5 12 9.75 8.98 9.75 15.02" fill="white"/>
          </svg>
        </a>
      </div>
    </div>
  </aside>
</div>

<script>
(function () {
  var overlay  = document.getElementById('mNavOverlay');
  var backdrop = document.getElementById('mNavBd');
  var toggle   = document.getElementById('navToggle');
  var closeBtn = document.getElementById('mNavClose');

  if (!overlay || !toggle) return;

  function openNav() {
    overlay.classList.add('open');
    overlay.setAttribute('aria-hidden', 'false');
    toggle.setAttribute('aria-expanded', 'true');
    toggle.setAttribute('aria-label', 'Close navigation menu');
    toggle.classList.add('open');
    document.body.style.overflow = 'hidden';
    /* Focus first link after drawer finishes sliding in */
    var first = overlay.querySelector('.m-nav-links a');
    if (first) setTimeout(function () { first.focus(); }, 60);
  }

  function closeNav() {
    overlay.classList.remove('open');
    overlay.setAttribute('aria-hidden', 'true');
    toggle.setAttribute('aria-expanded', 'false');
    toggle.setAttribute('aria-label', 'Open navigation menu');
    toggle.classList.remove('open');
    document.body.style.overflow = '';
    toggle.focus();
  }

  toggle.addEventListener('click', function () {
    overlay.classList.contains('open') ? closeNav() : openNav();
  });

  if (closeBtn)  closeBtn.addEventListener('click', closeNav);
  if (backdrop)  backdrop.addEventListener('click', closeNav);

  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape' && overlay.classList.contains('open')) closeNav();
  });

  /* Close on any link click (smooth page navigation) */
  overlay.querySelectorAll('.m-nav-links a').forEach(function (a) {
    a.addEventListener('click', closeNav);
  });

  /* Active-link detection: drawer links */
  var current = window.location.pathname.replace(/\/$/, '') || '/';
  var currentHash = window.location.hash;
  overlay.querySelectorAll('.m-nav-links a').forEach(function (a) {
    try {
      var url = new URL(a.href, window.location.origin);
      var lp  = url.pathname.replace(/\/$/, '') || '/';
      var lh  = url.hash;
      if (lh) {
        if (lp === current && lh === currentHash) a.classList.add('nav-active');
      } else {
        if ((lp !== '/' && current === lp && !currentHash) ||
            (lp === '/' && current === '/' && !currentHash)) {
          a.classList.add('nav-active');
        }
      }
    } catch (e) {}
  });
}());

/* Active-link detection: desktop nav-links */
(function () {
  var current = window.location.pathname.replace(/\/$/, '') || '/';
  var currentHash = window.location.hash;
  document.querySelectorAll('.nav-links a').forEach(function (a) {
    try {
      var url = new URL(a.href, window.location.origin);
      var lp  = url.pathname.replace(/\/$/, '') || '/';
      var lh  = url.hash;
      if (lh) {
        if (lp === current && lh === currentHash) a.classList.add('nav-active');
      } else {
        if ((lp !== '/' && current === lp && !currentHash) ||
            (lp === '/' && current === '/' && !currentHash)) {
          a.classList.add('nav-active');
        }
      }
    } catch (e) {}
  });
}());
</script>
@endif
