<nav id="navbar">
    <div class="nav-container">
        <a href="/" class="brand">
            <span class="brand-icon">
                <img src="{{ asset('favicon/1/Profile_pic/Transparent.gif') }}" alt="PixelCraftsLab Studio Logo">
            </span>

            <span class="brand-text">
                <strong>{{ $name ?? 'PixelCraftsLabStudio' }}</strong>
                <small>Ideas . Build . Launch</small>
            </span>
        </a>

        <div class="nav-links">
            <a href="/">Home</a>
            <a href="/about">About Us</a>
            <a href="/services">Our Services</a>
            <a href="/portfolio">Our Portfolio</a>

            {{-- ADMIN ACCESS --}}
            @auth
                @if(auth()->user()->role === 'admin')
                    <a href="{{ route('admin.dashboard') }}" class="nav-links">Dashboard</a>

                    <form action="{{ route('admin.logout') }}" method="POST" class="nav-inline-form">
                        @csrf
                        <button type="submit" class="nav-btn">Logout</button>
                    </form>
                @endif
            @else
                <a href="{{ route('admin.login') }}" class="nav-btn">Admin Login</a>
            @endauth

            <a href="/contact" class="nav-btn">Contact Us</a>

        </div>
    </div>
</nav>
