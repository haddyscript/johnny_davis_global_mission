@extends('layouts.donation')

@section('content')
  @include('partials.campaign-overview')
  @include('partials.loading-screen')
  @include('partials.donation-form')
  @include('partials.thank-you')
@endsection
