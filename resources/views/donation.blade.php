@extends('layouts.donation')

@section('content')
  @include('partials.hunger-campaign', ['hcwVariant' => 'donate', 'hcwButtonHref' => '#give'])
  @include('partials.campaign-overview')
  @include('partials.loading-screen')
  @include('partials.donation-form')
  @include('partials.thank-you')
@endsection
