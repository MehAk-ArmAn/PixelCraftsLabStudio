@extends('errors.layout', ['code' => 429, 'title' => 'Too many requests', 'accent' => '#FF5F1F', 'accent2' => '#F2894F', 'motif' => 'wait'])
@section('eyebrow', 'Error 429 · Rate limited')
@section('headline')A little <em>too much</em>, too quickly.@endsection
@section('body')We have paused things for a moment to keep the site healthy for everyone. Give it a short while, then try again.@endsection
