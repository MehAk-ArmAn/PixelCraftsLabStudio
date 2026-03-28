@extends('layouts.app')

{{-- Page Title --}}
@section('title', 'Contact Us | PixelCraftsLabStudio')

{{-- Page Specific CSS --}}
@section('styles')
<link rel="stylesheet" href="{{ asset('css/pages/contact.css') }}">
@endsection

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
                    @if(session('success'))
                        <div class="success-message">
                            ✅ Message sent successfully!
                        </div>
                    @endif
                    <form action="{{ route('contact.store') }}" method="POST">
                        @csrf {{-- protects form from 419 error --}}

                        <label for="name">Name:</label><br>
                        <input type="text" id="name" name="name" required><br><br>

                        <label for="email">Email:</label><br>
                        <input type="email" id="email" name="email" required><br><br>

                        <label for="subject">Subject:</label><br>
                        <input type="text" id="subject" name="subject"><br><br>

                        <label for="message">Message:</label><br>
                        <textarea id="message" name="message" rows="5" required></textarea><br><br>

                        <button type="submit" onclick="this.disabled=true; this.innerText='Sending...'; this.form.submit();">
                            Send Message
                        </button>

                    </form>
                </section>

                {{-- Alternative Contact Info --}}
                <section id="contact-info" class="contact-info">
                    <h2>Other Ways to Reach Us</h2>

                    <p><strong>Email:</strong> {{ $adminEmail }}</p>
                    <p><strong>Phone:</strong> {{ $phone }}</p>
                    <p><strong>Address:</strong> {{ $location }}</p>

                    <p>
                        <strong>Follow us:</strong><br>
                        <a href="{{ $instagram }}">Instagram</a> |
                        <a href="{{ $linkedIn }}">LinkedIn</a> |
                        <a href="{{ $X }}">Twitter</a> |
                        <a href="{{ $tiktok }}">TikTok</a> |
                        <a href="{{ $pinterest }}">Pinterest</a> |
                        <a href="{{ $youtube }}">YouTube</a> |
                        <a href="{{ $facebook }}">Facebook</a> |
                        <a href="{{ $whatsapp }}">Whatsapp</a>
                    </p>
                   
                </section>

            </div>

        </div>
    </section>

@endsection