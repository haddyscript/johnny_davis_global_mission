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
