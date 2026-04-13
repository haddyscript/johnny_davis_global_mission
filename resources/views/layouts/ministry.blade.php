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
  <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,
      <svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'>
        <rect width='100' height='100' fill='%23000'/>
        <text x='50%' y='50%' 
              font-size='34' text-anchor='middle' 
              fill='%23fff' 
              font-family='Arial, sans-serif' 
              font-weight='bold'
              letter-spacing='-1'
              dy='.35em'>
          JDGM
        </text>
      </svg>">
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
