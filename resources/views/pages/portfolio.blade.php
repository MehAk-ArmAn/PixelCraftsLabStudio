@extends('layouts.app')

{{-- Page Title --}}
@section('title', 'Services | PixelCraftsLabStudio')

{{-- Page Specific CSS --}}
@push('styles')
<link rel="stylesheet" href="{{ asset('css/pages/services.css') }}">
@endpush

@section('content')

    {{-- Navbar --}}
    @include('partials.navbar')

    <section class="services-section">
        <div class="services-container">

            {{-- Header --}}
            <div class="services-header">
                <h1>Our Services</h1>
                <p>
                    At <strong>PixelCraftsLabStudio</strong>, we provide a wide range of digital solutions designed to help businesses and entrepreneurs grow, innovate, and succeed online.
                </p>
            </div>

            <div class="services-content">

                {{-- Brand Kits --}}
                <section id="brandkits" class="service-block">
                    <h2>Brand Kits & Visual Identity</h2>
                    <p>
                        Create a strong and consistent brand presence with our professional brand kits. 
                        We design logos, color palettes, typography, and brand guidelines that help your business stand out.
                    </p>
                </section>

                {{-- Web Development --}}
                <section id="web-development" class="service-block">
                    <h2>Web Development</h2>
                    <p>
                        We build responsive, user-friendly, and SEO-optimized websites tailored to your business. 
                        From corporate websites to e-commerce platforms, we ensure your online presence is powerful and effective.
                    </p>
                </section>

                {{-- App Development --}}
                <section id="app-development" class="service-block">
                    <h2>App Development</h2>
                    <p>
                        From concept to launch, we develop mobile and web applications that are intuitive, scalable, and engaging. 
                        Our apps are built to enhance user experience and drive results.
                    </p>
                </section>

                {{-- Custom Software --}}
                <section id="custom-software" class="service-block">
                    <h2>Custom Software Solutions</h2>
                    <p>
                        Need a software solution tailored specifically to your business processes? 
                        We develop custom applications to solve unique challenges, improve efficiency, and automate tasks.
                    </p>
                </section>

                {{-- App Maintenance --}}
                <section id="app-support" class="service-block">
                    <h2>App Maintenance & Support</h2>
                    <p>
                        We don’t just build apps—we ensure they run smoothly. 
                        Our maintenance and support services keep your apps updated, secure, and performing at their best.
                    </p>
                </section>

                {{-- Digital Consultation --}}
                <section id="digital-consultation" class="service-block">
                    <h2>Digital Consultation</h2>
                    <p>
                        Looking for guidance on your digital strategy? 
                        Our experts provide consultation on technology, marketing, and growth strategies to help your business thrive online.
                    </p>
                </section>

                {{-- API Development --}}
                <section id="api-development" class="service-block">
                    <h2>API Development & Integration</h2>
                    <p>
                        We design, develop, and integrate APIs to connect your applications seamlessly. 
                        Whether you need third-party integration or custom APIs, we make your systems work together efficiently.
                    </p>
                </section>

            </div>

        </div>
    </section>

@endsection