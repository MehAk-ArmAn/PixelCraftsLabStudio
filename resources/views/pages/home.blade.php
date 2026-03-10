@extends('layouts.app')
    
{{-- Page specific CSS --}}
@section('styles')
    <link rel="stylesheet" href="{{ asset('css/pages/home.css') }}">
    <link rel="stylesheet" href="{{ asset('css/sections/about-preview.css') }}">
    <link rel="stylesheet" href="{{ asset('css/sections/portfolio-preview.css') }}">

    {{-- Inject DB images BEFORE JS loads --}}
    <script>
        window.dbImages = @json($images ?? []);
    </script>

    <script src="{{ asset('js/home.js') }}" defer></script>
    <link rel="shortcut icon" href="{{ asset('favicon/1/Profile_pic/Transparent.gif') }}" type="image/x-icon">
@endsection

@section('content')

    @include('components.forms.big-form')
    <div class="page-wrapper">
    
        @include('components.sections.about-preview')
        @include('components.sections.cta')
        @include('components.sections.hero')
        @include('components.sections.portfolio-preview')
        @include('components.sections.services-preview')
        @include('components.sections.testimonials')
    </div>

@endsection
