@extends('layouts.app')

{{-- Page Title --}}
@section('title', 'Portfolio | PixelCraftsLabStudio')

{{-- Page Specific CSS --}}
@section('styles')
<link rel="stylesheet" href="{{ asset('css/pages/portfolio.css') }}">
@endsection

@section('content')

<section class="portfolio-hero">
    <div class="hero-content">
        <h1>Our Creations</h1>
        <p>Where creativity meets tech. Explore our projects below!</p>
    </div>
</section>

<section class="portfolio-grid">
    @foreach($projects as $project)
    <div class="portfolio-card">
        <div class="card-img">
            <img src="{{ asset($project->image) }}" alt="{{ $project->title }}">
            <div class="overlay">
                <h3>{{ $project->title }}</h3>
                <p>{{ $project->description }}</p>
                <a href="{{ $project->link }}" target="_blank" class="view-btn">View Project</a>
            </div>
        </div>
    </div>
    @endforeach
</section>

{{-- Optional: Particle effect layer --}}
<div id="portfolio-particles"></div>

@endsection

{{-- JS --}}
@push('scripts')
<script src="{{ asset('js/portfolio.js') }}"></script>
@endpush
