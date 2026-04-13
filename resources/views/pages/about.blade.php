@extends('layouts.app')

{{-- Page Title --}}
@section('title', 'About Us | PixelCraftsLab Studio')

{{-- Page Specific CSS --}}
@section('styles')
<link rel="stylesheet" href="{{ asset('css/pages/about.css') }}">
@endsection

@section('content')

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

        @foreach($team as $member)

        <div class="team-card">

            {{-- image comes from DB --}}
            <img src="{{ asset('storage/' . $member->image) }}" alt="{{ $member->name }}">

            {{-- role --}}
            <h3>{{ $member->role }}</h3>

            {{-- bio --}}
            <p>{{ $member->bio }}</p>

        </div>

        @endforeach

    </div>

</div>

</div>
</section>

@endsection
