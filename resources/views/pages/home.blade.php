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
    <div class="page-stack">
        <section class="section-shell section-shell-hero">
            @include('components.sections.hero')
        </section>

        <section class="section-shell">
            @include('components.sections.about-preview')
        </section>

        <section class="section-shell">
            @include('components.sections.portfolio-preview')
        </section>

        <section class="section-shell">
            @include('components.sections.services-preview')
        </section>

        <section class="section-shell">
            @include('components.sections.testimonials')
        </section>

        <section class="section-shell section-shell-cta">
            @include('components.sections.cta')
        </section>
    </div>
@endsection
