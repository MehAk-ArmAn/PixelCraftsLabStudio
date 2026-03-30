<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PixelCraftsLabStudio</title>

    <link rel="shortcut icon" href="{{ asset('favicon/1/Profile_pic/Transparent.gif') }}" type="image/x-icon">

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=Orbitron:wght@500;700;800&display=swap" rel="stylesheet">

    {{-- Existing CSS --}}
    <link rel="stylesheet" href="{{ asset('css/layouts/layout.css') }}">
    <link rel="stylesheet" href="{{ asset('css/navbar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/footer.css') }}">

    {{-- New premium galaxy theme --}}
    <link rel="stylesheet" href="{{ asset('css/theme-galaxy.css') }}">

    @yield('styles')
</head>
<body class="galaxy-body">
    <div class="galaxy-bg">
        <span class="orb orb-1"></span>
        <span class="orb orb-2"></span>
        <span class="orb orb-3"></span>
        <span class="grid-lines"></span>
        <span class="noise-layer"></span>
    </div>

    <div class="site-shell">
        @include('partials.navbar')

        <main class="site-main">
            @yield('content')
        </main>

        @include('partials.footer')
    </div>
</body>
</html>

