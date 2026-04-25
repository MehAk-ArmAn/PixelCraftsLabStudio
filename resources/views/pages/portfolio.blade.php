@extends('layouts.app')

@section('title', 'Portfolio | PixelCraftsLabStudio')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/pages/portfolio.css') }}">
@endsection

@section('content')

<section class="portfolio-hero">
    <div class="hero-content">
        <h1>Our Solutions</h1>
        <p>Where creativity meets tech. Explore our projects below!</p>
    </div>
</section>

<section class="portfolio-grid">

    @foreach($projects as $project)

    <div class="portfolio-card">

        <div class="card-img">

            @if($project->image)
                <img src="{{ asset('public/' . $project->image) }}"
                    alt="{{ $project->title }}">
            @endif

            <div class="overlay">
                <h3>{{ $project->title }}</h3>
                <p>{{ $project->description }}</p>

                @if($project->link)
                    <a href="{{ $project->link }}" target="_blank" class="view-btn">
                        View Project
                    </a>
                @endif
            </div>

        </div>

        <div class="card-content">
            <h3>{{ $project->title }}</h3>
            <p>{{ Str::limit($project->description, 80) }}</p>
        </div>

    </div>

    @endforeach

</section>

<div id="portfolio-particles"></div>

@endsection

@push('scripts')
<script src="{{ asset('js/portfolio.js') }}"></script>
@endpush
