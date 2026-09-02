@extends('errors.layout', ['code' => 503, 'title' => 'Temporarily unavailable', 'accent' => '#5B2394', 'accent2' => '#8B45FF', 'motif' => 'wait'])
@section('eyebrow', 'Error 503 · Maintenance')
@section('headline')We are making a <em>careful adjustment</em>.@endsection
@section('body')The site is briefly unavailable while we finish a change behind the scenes. Nothing is lost — check back shortly and it will be here.@endsection
@if (! empty($retryAfter))
@section('note'){{ $retryAfter }}@endsection
@endif
