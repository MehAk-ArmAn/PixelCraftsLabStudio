@extends('errors.layout', ['code' => 403, 'title' => 'Access denied', 'accent' => '#3A3346', 'accent2' => '#5B2394', 'motif' => 'locked'])
@section('eyebrow', 'Error 403 · Forbidden')
@section('headline')That area is <em>not available</em> to you.@endsection
@section('body')Your account is signed in, but this page sits behind a permission it does not hold. If that looks wrong, ask whoever manages access.
@include('errors.partials.home-action')

@endsection
