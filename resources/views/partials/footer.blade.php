<!-- Link CSS -->
<link rel="stylesheet" href="{{ asset('css/footer.css') }}">

<footer class="footer">
    <div class="footer-container">

        <!-- Brand Section -->
        <div class="footer-brand">
            <img src="{{ asset('favicon/1/Profile_pic/Transparent.gif') }}" alt="PixelCraftsLab Studio's Logo" class="footer-logo">
            <p>
                Crafting digital experiences that actually convert.
                Design. Code. Create.
            </p>
        </div>

        <!-- Quick Links -->
        <div class="footer-links">
            <h4>Quick Links</h4>
            <ul>
                <li><a href="/">Home</a></li>
                <li><a href="/services">Services</a></li>
                <li><a href="/portfolio">Portfolio</a></li>
                <li><a href="/about">About Us</a></li>
                <li><a href="/contact">Contact</a></li>
            </ul>
        </div>

        <!-- Services -->
        <div class="footer-services">
            <h4>Services</h4>
            <ul>
                <li>Web Development</li>
                <li>UI/UX Design</li>
                <li>Brand Identity</li>
                <li>SEO Optimization</li>
            </ul>
        </div>

        <!-- Contact -->
        <div class="footer-contact">
            <h4>Contact</h4>
            <p>Email: {{ $adminEmail }}</p>
            <p>Location: {{ $location }}</p>
            <p>Phone: {{ $phone }}</p>

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