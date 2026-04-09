@php
    use App\Models\NavItem;

    /* Active-state detection — compare current URL path to item URL path */
    $currentPath = '/' . ltrim(request()->path(), '/');

    $navItems = NavItem::forNav();
@endphp

<header id="navbar" role="banner">
  <div class="container">
    <nav class="nav-inner" aria-label="Main navigation">
      <a href="{{ url('/') }}" class="nav-logo" aria-label="Johnny Davis Global Missions Home">
        <img src="{{ asset('images/logo.webp') }}" alt="Johnny Davis Global Missions Logo" />
      </a>

      <ul class="nav-links" role="list">
        @foreach($navItems as $item)
          @php
            /* Determine active: match path portion of URL, skip pure anchors */
            $itemPath = parse_url($item->url, PHP_URL_PATH) ?? '';
            $isActive = $itemPath && $itemPath !== '/#'
                ? rtrim($itemPath, '/') === rtrim($currentPath, '/')
                : false;

            $classes = trim(($item->nav_class ?? '') . ($isActive ? ' active' : ''));
            $attrs   = $item->is_external ? ' target="_blank" rel="noopener noreferrer"' : '';
            $href    = $item->url;
            /* Make relative paths absolute for cross-page consistency */
            if (str_starts_with($href, '/') && !str_starts_with($href, '//')) {
                $href = url($href);
            }
          @endphp
          <li>
            <a href="{{ $href }}"{{ $classes ? ' class="' . e($classes) . '"' : '' }}{!! $attrs !!}>
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

  {{-- Mobile nav mirrors the desktop items --}}
  <nav class="nav-mobile" id="navMobile" aria-label="Mobile navigation">
    @foreach($navItems as $item)
      @php
        $itemPath = parse_url($item->url, PHP_URL_PATH) ?? '';
        $isActive = $itemPath && $itemPath !== '/#'
            ? rtrim($itemPath, '/') === rtrim($currentPath, '/')
            : false;
        $classes = trim(($item->nav_class ?? '') . ($isActive ? ' active' : ''));
        $attrs   = $item->is_external ? ' target="_blank" rel="noopener noreferrer"' : '';
        $href    = $item->url;
        if (str_starts_with($href, '/') && !str_starts_with($href, '//')) {
            $href = url($href);
        }
      @endphp
      <a href="{{ $href }}"{{ $classes ? ' class="' . e($classes) . '"' : '' }}{!! $attrs !!}>
        {!! $item->label !!}
      </a>
    @endforeach
  </nav>
</header>
