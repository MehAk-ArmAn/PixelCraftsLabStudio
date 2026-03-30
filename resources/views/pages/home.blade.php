@extends('layouts.app')

{{-- Page specific CSS --}}
@section('styles')
    <link rel="stylesheet" href="{{ asset('css/pages/home.css') }}">
    <link rel="stylesheet" href="{{ asset('css/sections/about-preview.css') }}">
    <link rel="stylesheet" href="{{ asset('css/sections/portfolio-preview.css') }}">
    <link rel="stylesheet" href="{{ asset('css/sections/cta.css') }}">
    <link rel="stylesheet" href="{{ asset('css/sections/hero.css') }}">
    <link rel="stylesheet" href="{{ asset('css/sections/services-preview.css') }}">
    <link rel="stylesheet" href="{{ asset('css/sections/testimonials.css') }}">

    {{-- Inject DB images BEFORE JS loads --}}
    <script>
        window.dbImages = @json($images ?? []);
    </script>

    <script src="{{ asset('js/pages/home.js') }}" defer></script>
    <link rel="shortcut icon" href="{{ asset('favicon/1/Profile_pic/Transparent.gif') }}" type="image/x-icon">
@endsection

@section('content')

    <div class="page-wrapper">
        @include('components.forms.big-form')
    </div>

    <div class="page-wrapper">
        @include('components.sections.about-preview')
    </div>

    <div class="page-wrapper">
        @include('components.sections.portfolio-preview')
    </div>

    <div class="page-wrapper">
        @include('components.sections.services-preview')
    </div>

    <div class="page-wrapper">
        @include('components.sections.testimonials')
    </div>

    <div class="page-wrapper">
        @include('components.sections.cta')
    </div>

@endsection
