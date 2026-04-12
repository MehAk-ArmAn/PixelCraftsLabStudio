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
            @auth {{-- user is logged in --}}
                @if(auth()->user()->is_admin) {{-- check admin --}}
                    <a href="{{ route('admin.dashboard') }}" class="nav-admin">Admin</a> {{-- go dashboard --}}
                    
                    <form action="{{ route('admin.logout') }}" method="POST" style="display:inline;">
                        @csrf
                        <button type="submit" class="nav-logout">Logout</button> {{-- logout btn --}}
                    </form>
                @endif
            @else
                <a href="{{ route('admin.login') }}" class="nav-admin">Admin Login</a> {{-- login btn --}}
            @endauth

            <a href="/contact" class="nav-btn">Contact Us</a>

        </div>
    </div>
</nav>
