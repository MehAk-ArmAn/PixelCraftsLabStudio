
<footer class="footer premium-footer">
    <div class="footer-glow"></div>

    <div class="footer-container">
        <div class="footer-brand card-glass">
            <div class="footer-brand-top">
                <img src="{{ asset('favicon/1/Profile_pic/Transparent.gif') }}" alt="PixelCraftsLab Studio's Logo" class="footer-logo">
                <div>
                    <h3>PixelCraftsLab Studio</h3>
                    <span class="footer-badge">Creative Digital Studio</span>
                </div>
            </div>

            <p>
                Crafting digital experiences that actually convert.
                Design. Code. Create.
            </p>
        </div>

        <div class="footer-links card-glass">
            <h4>Quick Links</h4>
            <ul>
                <li><a href="/">Home</a></li>
                <li><a href="/services">Services</a></li>
                <li><a href="/portfolio">Portfolio</a></li>
                <li><a href="/about">About Us</a></li>
                <li><a href="/contact">Contact</a></li>
            </ul>
        </div>

        <div class="footer-services card-glass">
            <h4>Services</h4>
            <ul>
                <li>Web Development</li>
                <li>UI/UX Design</li>
                <li>Brand Identity</li>
                <li>SEO Optimization</li>
            </ul>
        </div>

        <div class="footer-contact card-glass">
            <h4>Contact</h4>
            <p><strong>Email:</strong> {{ $adminEmail }}</p>
            <p><strong>Location:</strong> {{ $location }}</p>
            <p><strong>Phone:</strong> {{ $phone }}</p>

            <div class="footer-socials">
                <ul>
                    <li><a href="{{ $instagram }}">Instagram</a></li>
                    <li><a href="{{ $linkedIn }}">LinkedIn</a></li>
                    <li><a href="{{ $X }}">X / Twitter</a></li>
                    <li><a href="{{ $tiktok }}">Tik Tok</a></li>
                    <li><a href="{{ $pinterest }}">Pinterest</a></li>
                    <li><a href="{{ $facebook }}">Facebook</a></li>
                    <li><a href="{{ $youtube }}">Youtube</a></li>
                    <li><a href="{{ $whatsapp }}">Whatsapp</a></li>
                </ul>
            </div>
        </div>
    </div>

    <div class="footer-bottom">
        <p>© 2026 PixelCraftsLab Studio. All rights reserved.</p>
    </div>
</footer>
