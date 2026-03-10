@extends('layouts.app')

{{-- Page Title --}}
@section('title', 'Contact Us | PixelCraftsLab Studio')

@section('content')

    {{-- Navbar --}}
    @include('partials.navbar')

    <section class="contact-section" style="padding: 40px 0;">
        <div class="contact-container" style="width: 80%; margin: auto;">

            {{-- Header --}}
            <div class="contact-header" style="text-align: center; margin-bottom: 40px;">
                <h1>Contact Us</h1>
                <p style="color: gray;">
                    Have a project in mind? Get in touch with us!
                </p>
            </div>

            <div class="contact-content" style="display: flex; gap: 50px;">

                {{-- Contact Form --}}
                <div class="contact-form" style="flex: 1;">
                    <form action="#" method="POST">
                        @csrf

                        <div style="margin-bottom: 20px;">
                            <label for="name" style="display:block; margin-bottom:5px;">Name</label>
                            <input type="text" id="name" name="name" required
                                   style="width:100%; padding:10px; border:1px solid #ccc; border-radius:6px;">
                        </div>

                        <div style="margin-bottom: 20px;">
                            <label for="email" style="display:block; margin-bottom:5px;">Email</label>
                            <input type="email" id="email" name="email" required
                                   style="width:100%; padding:10px; border:1px solid #ccc; border-radius:6px;">
                        </div>

                        <div style="margin-bottom: 20px;">
                            <label for="subject" style="display:block; margin-bottom:5px;">Subject</label>
                            <input type="text" id="subject" name="subject"
                                   style="width:100%; padding:10px; border:1px solid #ccc; border-radius:6px;">
                        </div>

                        <div style="margin-bottom: 20px;">
                            <label for="message" style="display:block; margin-bottom:5px;">Message</label>
                            <textarea id="message" name="message" rows="5" required
                                      style="width:100%; padding:10px; border:1px solid #ccc; border-radius:6px;"></textarea>
                        </div>

                        <div>
                            <button type="submit"
                                    style="padding:10px 20px; border:none; border-radius:6px; cursor:pointer;">
                                Send Message
                            </button>
                        </div>

                    </form>
                </div>

                {{-- Contact Info --}}
                <div class="contact-info" style="flex: 1;">
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
                </div>

            </div>

        </div>
    </section>

@endsection