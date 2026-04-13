{{--
    BIG MAIN FORM
    This form is centered and designed as the main focus of the landing page.
    All text is placeholder ("...") and can be replaced later.
--}}

<section class="form-section">
    <div class="particles" id="particles"></div>

    <div class="form-hero-shell">
        <div class="form-copy">
            <span class="eyebrow">PixelCraftsLabStudio</span>
            <h1>Get in touch with us</h1>
            <p>
                Tell us what you want to build and we’ll help shape it into something clean,
                modern, and launch-ready.
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
                            placeholder="Enter your name"
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
                            placeholder="Enter your email"
                            required
                        >
                    </div>
                </div>

                <div class="field">
                    <label for="subject">Subject</label>
                    <select id="service" name="service">
                        <option value="">Select a topic</option>
                        <option value="Web Design" {{ old('subject') === 'Web Design' ? 'selected' : '' }}>Web Design</option>
                        <option value="Branding" {{ old('subject') === 'Branding' ? 'selected' : '' }}>Branding</option>
                        <option value="UI/UX Design" {{ old('subject') === 'UI/UX Design' ? 'selected' : '' }}>UI/UX Design</option>
                        <option value="Logo Design" {{ old('subject') === 'Logo Design' ? 'selected' : '' }}>Logo Design</option>
                        <option value="Web Development" {{ old('subject') === 'Web Development' ? 'selected' : '' }}>Web Development</option>
                        <option value="Other" {{ old('subject') === 'Other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>

                <div class="field">
                    <label for="message">Project Description</label>
                    <textarea
                        id="message"
                        name="message"
                        placeholder="Tell us a bit about your idea..."
                        required
                    >{{ old('message') }}</textarea>
                </div>

                <button type="submit">Submit</button>
            </form>
        </div>
    </div>
</section>
