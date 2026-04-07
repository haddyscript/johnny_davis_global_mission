@php $activePage = $activePage ?? 'donation'; @endphp
<header id="navbar-wrap">
  <nav class="navbar" id="mainNav" role="navigation" aria-label="Main navigation">
    <a href="{{ route('home') }}" class="nav-logo" aria-label="Johnny Davis Global Missions Home">
      <img src="{{ asset('images/logo.webp') }}" alt="Johnny Davis Global Missions" />
    </a>
    <div class="nav-links">
      <a href="{{ route('home') }}" class="{{ $activePage === 'home' ? 'nav-link active' : 'nav-link' }}">Home</a>
      <a href="{{ route('who-we-are') }}" class="{{ $activePage === 'who-we-are' ? 'nav-link active' : 'nav-link' }}">Who We Are</a>
      <a href="{{ route('home') }}#help" class="{{ $activePage === 'what-we-do' ? 'nav-link active' : 'nav-link' }}">What We Do</a>
      <a href="{{ route('donate') }}" class="{{ $activePage === 'donation' ? 'nav-link active' : 'nav-link' }}">Make a Difference</a>
      <a href="{{ route('contact') }}" class="{{ $activePage === 'contact' ? 'nav-link active' : 'nav-link' }}">Contact Us</a>
      <a href="{{ route('donate') }}" class="nav-cta">&#9829; Donate Now</a>
    </div>
    <button class="nav-toggle" id="navToggle" aria-label="Open menu" aria-expanded="false">
      <span></span><span></span><span></span>
    </button>
  </nav>

  <nav class="nav-mobile" id="navMobile" aria-label="Mobile navigation">
    <a href="{{ route('home') }}" class="nav-link">Home</a>
    <a href="{{ route('who-we-are') }}" class="nav-link">Who We Are</a>
    <a href="{{ route('home') }}#help" class="nav-link">What We Do</a>
    <a href="{{ route('donate') }}" class="nav-link active">Make a Difference</a>
    <a href="{{ route('contact') }}" class="nav-link">Contact Us</a>
    <a href="{{ route('donate') }}" class="nav-cta">&#9829; Donate Now</a>
  </nav>
</header>
