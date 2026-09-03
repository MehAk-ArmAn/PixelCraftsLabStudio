@extends('errors.layout', ['code' => 408, 'title' => 'Request timed out', 'accent' => '#5B2394', 'accent2' => '#8B45FF', 'motif' => 'wait'])
@section('eyebrow', 'Error 408 · Timed out')
@section('headline')The request took <em>too long</em>.@endsection
@section('body')We stopped waiting before anything completed. That is usually a connection hiccup rather than a fault — try it once more.
@include('errors.partials.home-action')

@endsection
