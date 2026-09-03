@extends('errors.layout', ['code' => 404, 'title' => 'Page not found', 'accent' => '#5B2394', 'motif' => 'broken'])
@section('eyebrow', 'Error 404 · Not found')
@section('headline')This page has <em>moved</em>—or never existed.@endsection
@section('body')We checked the build and there is nothing at this address. It may have been renamed, retired, or never shipped outside someone&rsquo;s notes.
@include('errors.partials.home-action')

@endsection
