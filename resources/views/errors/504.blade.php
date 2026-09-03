@extends('errors.layout', ['code' => 504, 'title' => 'Gateway timeout', 'accent' => '#5B2394', 'accent2' => '#8B45FF', 'motif' => 'wait'])
@section('eyebrow', 'Error 504 · Gateway timeout')
@section('headline')The upstream request <em>timed out</em>.@endsection
@section('body')We waited, and nothing came back in time. This normally clears by itself — try again shortly.
@include('errors.partials.home-action')

@endsection
