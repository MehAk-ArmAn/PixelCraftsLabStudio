@extends('layouts.app')

{{-- Page Title --}}
@section('title', 'Contact Us | PixelCraftsLabStudio')

{{-- Page Specific CSS --}}
@push('styles')
<link rel="stylesheet" href="{{ asset('css/pages/contact.css') }}">
@endpush

@section('content')

    {{-- Navbar --}}
    @include('partials.navbar')

    <section class="contact-section">
        <div class="contact-container">

            {{-- Header --}}
            <div class="contact-header">
                <h1>Contact Us</h1>
                <p>Have a project in mind? Get in touch with us!</p>
            </div>

            <div class="contact-content">

                {{-- Contact Form --}}
                <section id="contact-form" class="contact-form">
                    <form action="#" method="post">

                        <label for="name">Name:</label><br>
                        <input type="text" id="name" name="name" required><br><br>

                        <label for="email">Email:</label><br>
                        <input type="email" id="email" name="email" required><br><br>

                        <label for="subject">Subject:</label><br>
                        <input type="text" id="subject" name="subject"><br><br>

                        <label for="message">Message:</label><br>
                        <textarea id="message" name="message" rows="5" required></textarea><br><br>

                        <button type="submit">Send Message</button>

                    </form>
                </section>

                {{-- Alternative Contact Info --}}
                <section id="contact-info" class="contact-info">
                    <h2>Other Ways to Reach Us</h2>

                    <p><strong>Email:</strong> pixelcraftslabstudio@gmail.com</p>
                    <p><strong>Phone:</strong> +971 56 701 8403</p>
                    <p><strong>Address:</strong> London, United Kingdom</p>

                    <p>
                        <strong>Follow us:</strong>
                        <a href="#">LinkedIn</a> |
                        <a href="#">Twitter</a> |
                        <a href="#">Facebook</a>
                    </p>

                </section>

            </div>

        </div>
    </section>

@endsection