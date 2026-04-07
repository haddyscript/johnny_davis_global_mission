<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="description" content="{{ $description }}" />
  <title>{{ $title }}</title>

  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,700;0,800;1,700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet" />

  <link rel="stylesheet" href="{{ asset('css/for_ministry.css') }}" />
</head>
<body>

@include('partials.nav-ministry')

<a id="stickyDonate" class="btn btn-primary" href="{{ route('donate') }}" aria-label="Donate Now">Donate Now</a>

@yield('content')

@include('partials.footer-ministry')

<script src="{{ asset('js/for_ministry.js') }}"></script>
</body>
</html>
