@extends('layouts.app')

{{-- Page Title --}}
@section('title', 'About Us | PixelCraftsLab Studio')

{{-- Page Specific CSS --}}
@section('styles')
<link rel="stylesheet" href="{{ asset('css/pages/about.css') }}">
@endsection

@section('content')

    {{-- Navbar --}}
    @include('partials.navbar')

    <section class="about-section">

<div class="about-container">

<!-- HERO -->
<div class="about-hero">
<h1>About PixelCraftsLab Studio</h1>
<p>Crafting digital experiences where creativity meets precision.</p>
</div>


<!-- STORY -->
<div class="about-card">
<h2>Our Story</h2>
<p>
PixelCraftsLab Studio started with one goal — build digital experiences
that actually stand out. We combine creativity, clean code,
and strong UI design to craft modern websites, digital tools, and fun games.
</p>
</div>


<!-- MISSION + FUTURE -->
<div class="about-grid">

<div class="about-card">
<h2>Our Mission</h2>
<p>
Create fast, modern, and visually powerful utility app, and online games
that help brands grow and stand out online.
</p>
</div>

<div class="about-card">
<h2>Future Vision</h2>
<p>
We aim to grow PixelCraftsLab into a full creative tech studio
specializing in web platforms, design systems, and digital innovation.
</p>
</div>

</div>


<!-- TEAM -->
<div class="team-section">

<h2 class="team-title">Meet The Team</h2>

    <div class="team-grid">

        <div class="team-card">
        <img src="{{ asset('imgs/team/member1.png') }}">
        <h3>Founder & Creative Director</h3>
        <p>Leads brand strategy and design. Oversees creative direction, including logo design, color palette, UI/UX, and frontend development.</p>
        </div>

        <div class="team-card">
        <img src="{{ asset('imgs/team/member2.png') }}">
        <h3>Lead Backend Developer</h3>
        <p>Responsible for backend architecture, coding, and error resolution. Ensures robust and scalable application performance.</p>
        </div>

        <div class="team-card">
        <img src="{{ asset('imgs/team/member3.png') }}">
        <h3>Games Developer</h3>
        <p>Develops interactive games and applications using Unity. Focused on gameplay mechanics, optimization, and app integration.</p>
        </div>

        <div class="team-card">
        <img src="{{ asset('imgs/team/member4.png') }}">
        <h3>API & Application Developer</h3>
        <p>Specializes in API development and integration. Supports games and app projects while enhancing backend functionality.</p>
        </div>

    </div>

</div>

</div>
</section>

@endsection