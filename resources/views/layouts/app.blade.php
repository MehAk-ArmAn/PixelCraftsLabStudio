<!DOCTYPE html>
<html>
    <head>
        <title>PixelCraftsLabStudio</title>

        {{-- Base Layout Styles (global reset, colors, spacing) --}}
        <link rel="stylesheet" href="{{ asset('css/layouts/layout.css') }}">

        {{-- Navbar Styles --}}
        <link rel="stylesheet" href="{{ asset('css/navbar.css') }}">

        {{-- Footer Styles --}}
        <link rel="stylesheet" href="{{ asset('css/footer.css') }}">

        {{-- Page Specific Styles --}}
        @yield('styles')
    </head>
    <body>

        <!-- Navbar -->
        @include('partials.navbar')
        
        @yield('content')

        <!-- Footer -->
        @include('partials.footer')
        
    </body>
</html>

