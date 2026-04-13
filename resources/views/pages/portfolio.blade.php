@extends('layouts.app')

@section('title', 'Portfolio | PixelCraftsLabStudio')

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

            {{-- IMAGE FIX (storage aware like admin) --}}
            @if($project->image)
                <img src="{{ asset($project->image) }}"
                     alt="{{ $project->title }}">
            @endif

            <div class="overlay">

                {{-- TITLE --}}
                <h3>{{ $project->title }}</h3>

                {{-- DESCRIPTION --}}
                <p>{{ $project->description }}</p>

                {{-- LINK --}}
                @if($project->link)
                    <a href="{{ $project->link }}"
                       target="_blank"
                       class="view-btn">
                        View Project
                    </a>
                @endif

            </div>

        </div>

    </div>

    @endforeach

</section>

<div id="portfolio-particles"></div>

@endsection

@push('scripts')
<script src="{{ asset('js/portfolio.js') }}"></script>
@endpush