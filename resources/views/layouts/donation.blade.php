<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="description" content="{{ $description }}" />
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  <meta name="stripe-key" content="{{ $stripeKey ?? '' }}" />
  <meta name="paypal-client-id" content="{{ config('services.paypal.client_id') }}" />
  <title>{{ $title }}</title>

  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;800&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet" />

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
  <link rel="stylesheet" href="{{ asset('css/for_donationpage.css') }}" />
</head>
<body>

@include('partials.nav-donation')

@yield('content')

@include('partials.footer-donation')

{{-- Stripe.js must load before our page script --}}
<script src="https://js.stripe.com/v3/"></script>
{{-- PayPal JS SDK — only injected when client ID is configured --}}
@if(config('services.paypal.client_id'))
<script src="https://www.paypal.com/sdk/js?client-id={{ config('services.paypal.client_id') }}&currency=USD&intent=capture&components=buttons&disable-funding=credit,card"></script>
@endif
<script src="{{ asset('js/for_donationpage.js') }}"></script>
</body>
</html>
