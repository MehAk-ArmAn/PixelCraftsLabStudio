{{--
    BIG MAIN FORM
    This form is centered and designed as the main focus of the landing page.
--}}

<section class="form-section">
    <div class="particles" id="particles"></div>

    <div class="form-hero-shell">
        <div class="form-copy">
            <span class="eyebrow">PixelCraftsLabStudio</span>
            <h1>Bring your idea to life</h1>
            <p>
                From websites and branding to UI/UX and digital experiences, we help turn ideas
                into polished, professional work that feels modern, purposeful, and ready to launch.
            </p>
        </div>

        <div class="form-container">
            @if(session('success'))
                <div style="margin-bottom: 16px; padding: 14px 16px; border-radius: 16px; background: rgba(34,197,94,0.12); border: 1px solid rgba(34,197,94,0.35); color: #bbf7d0;">
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div style="margin-bottom: 16px; padding: 14px 16px; border-radius: 16px; background: rgba(239,68,68,0.10); border: 1px solid rgba(239,68,68,0.35); color: #fecaca;">
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

                <div class="form-grid">
                    <div class="field">
                        <label for="name">Your Name</label>
                        <input
                            id="name"
                            type="text"
                            name="name"
                            value="{{ old('name') }}"
                            placeholder="Enter your full name"
                            required
                        >
                    </div>

                    <div class="field">
                        <label for="email">Your Email</label>
                        <input
                            id="email"
                            type="email"
                            name="email"
                            value="{{ old('email') }}"
                            placeholder="Enter your email address"
                            required
                        >
                    </div>
                </div>

                <div class="field">
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
                </div>

                <div class="field">
                    <label for="message">Tell us about your project</label>
                    <textarea
                        id="message"
                        name="message"
                        placeholder="Share your idea, goals, style, or the kind of help you're looking for..."
                        required
                    >{{ old('message') }}</textarea>
                </div>

                <button type="submit">Start the Conversation</button>
            </form>
        </div>
    </div>
</section>
