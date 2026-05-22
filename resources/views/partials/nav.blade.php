@php
    use App\Models\NavItem;

    $currentPath = '/' . ltrim(request()->path(), '/');
    $logoSrc = asset('images/logo.webp');
    $navItems = NavItem::forNav();
@endphp

<style>
  /* ── Ministry logo ── */
  .nav-ministry-logo {
    height: 22px;
    width: auto;
    object-fit: contain;
    display: inline-block;
    vertical-align: middle;
    margin-right: 6px;
    margin-top: -2px;
    filter: brightness(0) invert(1) opacity(.75);
    transition: filter .2s ease;
  }

  /* ── Transitions ── */
  .nav-links a,
  .nav-mobile a {
    transition: color .2s ease, background .2s ease, box-shadow .2s ease;
  }

  /* ── Desktop active ── */
  .nav-links a.nav-active {
    color: #f07c1e !important;
    font-weight: 700 !important;
    background: rgba(240,124,30,.12) !important;
    border-radius: 6px !important;
    box-shadow: inset 0 -2px 0 0 #f07c1e !important;
    text-decoration: none !important;
  }
  .nav-links a.nav-active .nav-ministry-logo {
    filter: brightness(0) saturate(100%) invert(55%) sepia(90%)
            saturate(600%) hue-rotate(5deg) brightness(1.1)
            drop-shadow(0 0 4px rgba(240,124,30,.7)) !important;
  }

  /* ── Mobile active ── */
  .nav-mobile a.nav-active {
    color: #f07c1e !important;
    font-weight: 700 !important;
  }
  .nav-mobile a.nav-active .nav-ministry-logo {
    filter: brightness(0) saturate(100%) invert(55%) sepia(90%)
            saturate(600%) hue-rotate(5deg) brightness(1.1) !important;
  }
</style>

<header id="navbar" role="banner">
  <div class="container">
    <nav class="nav-inner" aria-label="Main navigation">
      <a href="{{ url('/') }}" class="nav-logo" aria-label="Johnny Davis Global Missions Home">
        <img src="{{ $logoSrc }}" alt="Johnny Davis Global Missions Logo" />
      </a>

      <ul class="nav-links" role="list">
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

      <button class="nav-toggle" id="navToggle" aria-label="Toggle mobile menu" aria-expanded="false">
        <span></span><span></span><span></span>
      </button>
    </nav>
  </div>

  {{-- Mobile nav --}}
  <nav class="nav-mobile" id="navMobile" aria-label="Mobile navigation">
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
</header>

{{-- JS fallback: sets nav-active client-side so it works regardless of
     server-side URL format or CSS cascade order issues. --}}
<script>
(function () {
  var current = window.location.pathname.replace(/\/$/, '') || '/';

  document.querySelectorAll('.nav-links a, .nav-mobile a').forEach(function (a) {
    try {
      var linkPath = new URL(a.href, window.location.origin).pathname.replace(/\/$/, '') || '/';
      /* Skip pure anchors like /#mission */
      if (a.href.indexOf('#') !== -1 && linkPath === '/') return;
      if (linkPath !== '/' && current === linkPath ||
          linkPath === '/' && current === '/') {
        a.classList.add('nav-active');
      }
    } catch (e) {}
  });
})();
</script>
