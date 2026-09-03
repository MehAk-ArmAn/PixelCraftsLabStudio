@extends('errors.layout', ['code' => 405, 'title' => 'Method not allowed', 'accent' => '#3A3346', 'accent2' => '#5B2394'])
@section('eyebrow', 'Error 405 · Method not allowed')
@section('headline')That action is <em>not available</em> here.@endsection
@section('body')The address exists, but not for the kind of request that reached it. Following a link from the site will use the right one.
@include('errors.partials.home-action')

@endsection
