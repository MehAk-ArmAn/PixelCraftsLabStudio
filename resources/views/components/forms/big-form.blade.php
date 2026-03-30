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
            <form action="{{ route('contact.store') }}" method="POST">
                @csrf

                <div class="form-grid">
                    <div class="field">
                        <label for="name">Your Name</label>
                        <input id="name" type="text" name="name" placeholder="Enter your name" required>
                    </div>

                    <div class="field">
                        <label for="email">Your Email</label>
                        <input id="email" type="email" name="email" placeholder="Enter your email" required>
                    </div>
                </div>

                <div class="field">
                    <label for="service">Select Service</label>
                    <select id="service" name="service">
                        <option value="">Select Products</option>
                        <option value="option1">...</option>
                        <option value="option2">...</option>
                    </select>
                </div>

                <div class="field">
                    <label for="message">Project Description</label>
                    <textarea id="message" name="message" placeholder="Tell us a bit about your idea..."></textarea>
                </div>

                <button type="submit">Submit</button>
            </form>
        </div>
    </div>
</section>
