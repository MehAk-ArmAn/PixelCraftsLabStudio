@extends('layouts.app')

{{-- Page Title --}}
@section('title', 'Our Services | PixelCraftsLab Studio')

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
            <div class="services-header" style="text-align: center; margin-bottom: 40px;">
                <h1>Our Services</h1>
                <p class="subtitle" style="color: gray;">
                    At <strong>PixelCraftsLabStudio</strong>, we provide innovative, reliable,
                    and scalable digital solutions designed to help businesses grow, innovate, and succeed online.
                </p>
            </div>

            <div class="services-content">

                {{-- Brand Kits --}}
                <div class="service-item" style="margin-bottom: 30px;">
                    <h2>Brand Kits & Visual Identity</h2>
                    <p>
                        A strong brand builds trust and recognition. We create complete brand kits
                        that include logo design, typography selection, color systems, brand guidelines,
                        and visual assets that ensure consistency across all platforms.
                    </p>
                    <p>
                        Whether you’re launching a startup or rebranding an existing business,
                        our design process ensures your identity reflects your values, mission,
                        and target audience.
                    </p>
                </div>

                {{-- Web Development --}}
                <div class="service-item" style="margin-bottom: 30px;">
                    <h2>Web Development</h2>
                    <p>
                        We build modern, responsive, and high-performance websites tailored
                        to your business goals. Our websites are designed for speed,
                        usability, and search engine visibility.
                    </p>
                    <p>
                        From corporate websites and portfolios to full-scale e-commerce platforms,
                        we focus on clean code, strong security, and seamless user experience.
                    </p>
                </div>

                {{-- App Development --}}
                <div class="service-item" style="margin-bottom: 30px;">
                    <h2>App Development</h2>
                    <p>
                        We design and develop mobile and web applications that are intuitive,
                        scalable, and performance-driven. From idea validation to deployment,
                        we handle the entire development lifecycle.
                    </p>
                    <p>
                        Our apps are optimized for user engagement, reliability, and long-term growth,
                        ensuring your product delivers real value to users.
                    </p>
                </div>

                {{-- Custom Software --}}
                <div class="service-item" style="margin-bottom: 30px;">
                    <h2>Custom Software Solutions</h2>
                    <p>
                        Every business has unique challenges. We create tailored software
                        solutions that streamline workflows, automate repetitive tasks,
                        and improve operational efficiency.
                    </p>
                    <p>
                        Our custom systems are built to scale with your business,
                        giving you flexibility and long-term reliability.
                    </p>
                </div>

                {{-- App Support --}}
                <div class="service-item" style="margin-bottom: 30px;">
                    <h2>App Maintenance & Support</h2>
                    <p>
                        Technology evolves constantly. We provide continuous monitoring,
                        updates, bug fixes, performance optimization, and security enhancements
                        to ensure your applications remain stable and secure.
                    </p>
                    <p>
                        Our support services guarantee minimal downtime and maximum performance.
                    </p>
                </div>

                {{-- Digital Consultation --}}
                <div class="service-item" style="margin-bottom: 30px;">
                    <h2>Digital Consultation</h2>
                    <p>
                        Not sure where to start? Our digital consultation services provide
                        strategic guidance on technology selection, digital transformation,
                        branding, and online growth strategies.
                    </p>
                    <p>
                        We analyze your business model and recommend solutions that align
                        with your long-term objectives.
                    </p>
                </div>

                {{-- API Development --}}
                <div class="service-item" style="margin-bottom: 30px;">
                    <h2>API Development & Integration</h2>
                    <p>
                        We develop secure and scalable APIs that allow your systems,
                        platforms, and third-party services to communicate seamlessly.
                    </p>
                    <p>
                        Whether integrating payment gateways, CRM systems, or external tools,
                        we ensure smooth and efficient data exchange.
                    </p>
                </div>

            </div>

        </div>
    </section>

@endsection