@php $activePage = $activePage ?? ''; @endphp
<header id="navbar" role="banner">
  <div class="container">
    <nav class="nav-inner" aria-label="Main navigation">
      <a href="{{ route('home') }}" class="nav-logo" aria-label="Johnny Davis Global Missions Home">
        <img src="{{ asset('images/ministry-logo.png') }}" alt="Johnny Davis Global Missions Logo" />
      </a>

      <ul class="nav-links" role="list">
        <li><a href="{{ route('home') }}"{{ $activePage === 'home' ? ' class="active"' : '' }}>Home</a></li>
        <li><a href="{{ route('home') }}#mission"{{ $activePage === 'mission' ? ' class="active"' : '' }}>Mission</a></li>
        <!-- <li><a href="{{ route('home') }}#help"{{ $activePage === 'help' ? ' class="active"' : '' }}>How You Can Help</a></li> -->
        <li><a href="{{ route('news') }}"{{ $activePage === 'news' ? ' class="active"' : '' }}>Blog &amp; News</a></li>
        <li><a href="{{ route('who-we-are') }}"{{ $activePage === 'who-we-are' ? ' class="active"' : '' }}>Who We Are</a></li>
        <li><a href="{{ route('ministry') }}"{{ $activePage === 'ministry' ? ' class="active"' : '' }}>Ministry</a></li>
        <li><a href="{{ route('donate') }}" class="btn-nav-donate" aria-label="Donate">&#9829; Donate</a></li>
        <li><a href="{{ route('contact') }}"{{ $activePage === 'contact' ? ' class="active"' : '' }}>Contact</a></li>
      </ul>

      <button class="nav-toggle" id="navToggle" aria-label="Toggle mobile menu" aria-expanded="false">
        <span></span><span></span><span></span>
      </button>
    </nav>
  </div>

  <nav class="nav-mobile" id="navMobile" aria-label="Mobile navigation">
    <a href="{{ route('home') }}">Home</a>
    <a href="{{ route('home') }}#mission">Mission</a>
    <a href="{{ route('home') }}#help">How You Can Help</a>
    <a href="{{ route('news') }}">Blog &amp; News</a>
    <a href="{{ route('who-we-are') }}">Who We Are</a>
    <a href="{{ route('ministry') }}">Ministry</a>
    <a href="{{ route('contact') }}">Contact</a>
    <a href="{{ route('donate') }}" class="btn-nav-donate">&#9829; Donate Now</a>
  </nav>
</header>
