<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', ($setting->site_name ?? 'PixelCraftsLabStudio'))</title>

    <link rel="shortcut icon" href="{{ asset($setting->favicon_path ?? 'favicon/1/Profile_pic/Transparent.gif') }}" type="image/x-icon">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=Orbitron:wght@500;700;800&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/layouts/layout.css') }}">
    <link rel="stylesheet" href="{{ asset('css/navbar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/footer.css') }}">

    @yield('styles')
</head>
<body class="premium-space">
    <div class="space-bg">
        <div class="stars stars-1"></div>
        <div class="stars stars-2"></div>
        <div class="stars stars-3"></div>

        <span class="nebula nebula-1"></span>
        <span class="nebula nebula-2"></span>
        <span class="nebula nebula-3"></span>

        <span class="grid-glow"></span>
        <span class="space-noise"></span>
    </div>

    @if (!request()->routeIs('admin.login'))
        @include('partials.navbar')
    @endif

    <main class="site-main">
        @yield('content')
    </main>

    @if (!request()->routeIs('admin.login'))
        @include('partials.footer')
    @endif
</body>
</html>
