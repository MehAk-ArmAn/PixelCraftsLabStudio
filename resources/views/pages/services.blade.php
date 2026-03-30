@extends('layouts.app')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/pages/services.css') }}">

    @php
        $serviceItems = [];

        if (isset($services) && count($services)) {
            foreach ($services as $service) {
                $title = $service->title ?? 'Service';
                $description = $service->description ?? 'Professional digital service tailored to your needs.';

                $serviceItems[] = [
                    'slug' => \Illuminate\Support\Str::slug($title),
                    'title' => $title,
                    'description' => $description,
                ];
            }
        }

        if (empty($serviceItems)) {
            $serviceItems = [
                [
                    'slug' => 'brand-kits',
                    'title' => 'Brand Kits',
                    'description' => 'Logos, color palettes, typography systems, and brand guidelines that give your business a strong and consistent identity.',
                ],
                [
                    'slug' => 'web-development',
                    'title' => 'Web Development',
                    'description' => 'Responsive, fast, SEO-focused websites built with modern tools for real business growth.',
                ],
                [
                    'slug' => 'app-development',
                    'title' => 'App Development',
                    'description' => 'Mobile and web applications designed for performance, scalability, and smooth user experience.',
                ],
                [
                    'slug' => 'custom-software',
                    'title' => 'Custom Software',
                    'description' => 'Tailored software solutions built to automate operations and solve unique business challenges.',
                ],
                [
                    'slug' => 'app-support',
                    'title' => 'App Support',
                    'description' => 'Ongoing updates, performance optimization, and technical support to keep your product healthy.',
                ],
                [
                    'slug' => 'digital-consultation',
                    'title' => 'Digital Consultation',
                    'description' => 'Strategic guidance for product direction, technology choices, and digital growth.',
                ],
                [
                    'slug' => 'api-development',
                    'title' => 'API Development',
                    'description' => 'Secure API design and integrations that connect systems and workflows smoothly.',
                ],
            ];
        }
    @endphp

    <script>
        window.serviceItems = @json($serviceItems);
    </script>
    <script src="{{ asset('js/pages/services.js') }}" defer></script>
@endsection

@section('content')
    <section class="services-page">
        <div class="services-page__hero">
            <div class="services-page__hero-inner">
                <span class="services-page__eyebrow">PixelCraftsLabStudio</span>
                <h1>Our Services</h1>
                <p>
                    Explore what PixelCraftsLabStudio can build for you — from brand systems
                    to websites, apps, software, and digital product support.
                </p>
            </div>
        </div>

        <div class="services-page__container">
            <div class="services-page__grid">
                @foreach ($serviceItems as $item)
                    <article
                        class="services-page__card"
                        data-service="{{ $item['slug'] }}"
                        data-title="{{ $item['title'] }}"
                        data-description="{{ $item['description'] }}"
                        tabindex="0"
                        role="button"
                        aria-label="Open details for {{ $item['title'] }}"
                    >
                        <div class="services-page__card-glow"></div>
                        <span class="services-page__card-tag">Service</span>
                        <h2>{{ $item['title'] }}</h2>
                        <p>{{ $item['description'] }}</p>
                        <span class="services-page__card-link">View Details</span>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    <div class="services-modal" id="service-modal" aria-hidden="true">
        <div class="services-modal__backdrop" data-close="true"></div>

        <div class="services-modal__dialog" role="dialog" aria-modal="true" aria-labelledby="modal-title">
            <button class="services-modal__close" id="modal-close" type="button" aria-label="Close modal">
                ×
            </button>

            <span class="services-modal__tag">Service Details</span>
            <h2 id="modal-title">Service Title</h2>
            <p id="modal-text">Service description goes here.</p>
        </div>
    </div>
@endsection
