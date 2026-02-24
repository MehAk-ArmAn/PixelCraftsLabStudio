@extends('layouts.app')

{{-- Page Title --}}
@section('title', 'About Us | PixelCraftsLab Studio')

{{-- Page Specific CSS --}}
@push('styles')
<link rel="stylesheet" href="{{ asset('css\pages\about.css') }}">
@endpush

@section('content')

    {{-- Navbar --}}
    @include('partials.navbar')

    <section class="about-section">
        <div class="about-container">

            {{-- Heading --}}
            <div class="about-header">
                <h1>About PixelCraftsLab Studio</h1>
                <p class="subtitle">
                    Where creativity meets precision.
                </p>
            </div>

            {{-- About Content --}}
            <div class="about-content">

                <div class="about-text">
                    <h2>Who We Are</h2>
                    <p>
                        PixelCraftsLab Studio is a creative digital studio focused on
                        crafting modern, clean, and impactful web experiences.
                        We combine design, development, and strategy to build
                        products that don’t just look good — they perform.
                    </p>

                    <h2>Our Mission</h2>
                    <p>
                        Our mission is simple: deliver high-quality digital
                        solutions that elevate brands and help businesses grow
                        in the digital world.
                    </p>

                    <h2>What We Do</h2>
                    <ul class="about-list">
                        <li>• Web Design & Development</li>
                        <li>• UI/UX Strategy</li>
                        <li>• Brand Identity</li>
                        <li>• Digital Optimization</li>
                    </ul>
                </div>

                {{-- Optional Image Area --}}
                <div class="about-visual">
                    <img src="{{ asset('favicons/logo.png') }}" alt="PixelCraftsLab Studio Logo">
                </div>

            </div>

        </div>
    </section>

@endsection