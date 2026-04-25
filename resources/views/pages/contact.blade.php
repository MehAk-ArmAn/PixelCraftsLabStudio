@extends('layouts.app')

@section('title', 'Contact Us | PixelCraftsLabStudio')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/pages/contact.css') }}">
@endsection

@section('content')

<section class="contact-section">
    <div class="contact-container">

        <div class="contact-header">
            <h1>Contact Us</h1>
            <p>Have a project in mind? Get in touch with us!</p>
        </div>

        <div class="contact-content">

            <section id="contact-form" class="contact-form">
                @if(session('success'))
                    <div class="success-message">
                        {{ session('success') }}
                    </div>
                @endif

                @if($errors->any())
                    <div style="margin-bottom: 20px; padding: 14px 16px; border-radius: 12px; background: rgba(239,68,68,0.10); border: 1px solid rgba(239,68,68,0.35); color: #fecaca;">
                        <strong style="display:block; margin-bottom:8px;">Please fix these errors:</strong>
                        <ul style="margin:0; padding-left:18px;">
                            @foreach($errors->all() as $error)
                                <li style="color:#fecaca;">{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('contact.store') }}" method="POST">
                    @csrf

                    <label for="name">Name:</label><br>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" required><br><br>

                    <label for="email">Email:</label><br>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required><br><br>

                    {{-- Service Selection --}}
                    <label for="service">Select a Service:</label><br>
                    <select id="service" name="service" required>
                        <option value="">Select a service</option>

                        @foreach($services as $service)
                            <option value="{{ $service->title }}" {{ old('service') == $service->title ? 'selected' : '' }}>
                                {{ $service->title }}
                            </option>
                        @endforeach
                    </select><br><br>

                    <label for="message">Message:</label><br>
                    <textarea id="message" name="message" rows="5" required>{{ old('message') }}</textarea><br><br>

                    <button type="submit">Send Message</button>
                </form>
            </section>

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
