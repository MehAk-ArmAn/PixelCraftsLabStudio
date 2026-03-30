<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PixelCraftsLabStudio</title>

    <link rel="shortcut icon" href="{{ asset('favicon/1/Profile_pic/Transparent.gif') }}" type="image/x-icon">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/layouts/layout.css') }}">
    <link rel="stylesheet" href="{{ asset('css/navbar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/footer.css') }}">

    @yield('styles')
</head>
<body>
    <div class="site-bg"></div>
    <div class="site-noise"></div>

    @include('partials.navbar')

    <main class="site-main">
        @yield('content')
    </main>

    @include('partials.footer')
</body>
</html>

