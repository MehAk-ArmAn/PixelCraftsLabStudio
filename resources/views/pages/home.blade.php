@extends('layouts.app')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/pages/home.css') }}">
    <link rel="stylesheet" href="{{ asset('css/sections/about-preview.css') }}">
    <link rel="stylesheet" href="{{ asset('css/sections/portfolio-preview.css') }}">
    <link rel="stylesheet" href="{{ asset('css/sections/cta.css') }}">
    <link rel="stylesheet" href="{{ asset('css/sections/hero.css') }}">
    <link rel="stylesheet" href="{{ asset('css/sections/services-preview.css') }}">
    <link rel="stylesheet" href="{{ asset('css/sections/testimonials.css') }}">

    <script>
        window.dbImages = @json($images ?? []);
    </script>

    <script src="{{ asset('js/pages/home.js') }}" defer></script>
@endsection

@section('content')
    @include('components.forms.big-form')
    @include('components.sections.about-preview')
    @include('components.sections.portfolio-preview')
    @include('components.sections.services-preview')
    @include('components.sections.testimonials')
    @include('components.sections.cta')
@endsection
