@extends('errors.layout', ['code' => 401, 'title' => 'Sign in required', 'accent' => '#3A3346', 'accent2' => '#5B2394', 'motif' => 'locked'])
@section('eyebrow', 'Error 401 · Unauthorized')
@section('headline')<em>Sign in</em> required.@endsection
@section('body')This page is only available to signed-in accounts. Sign in and we will bring you straight back to it.@endsection
@section('extra-action')<a class="btn" href="{{ url('/admin/login') }}">Sign in</a>
@include('errors.partials.home-action')

@endsection
