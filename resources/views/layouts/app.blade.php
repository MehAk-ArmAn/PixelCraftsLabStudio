<!DOCTYPE html>
<html>
<head>
    <title>PixelCraftsLabStudio</title>

    {{-- Global layout styles --}}
    <link rel="stylesheet" href="{{ asset('css/layout.css') }}">

    {{-- Page specific styles --}}
    @yield('styles')

</head>
<body>

    @yield('content')

</body>
</html>

