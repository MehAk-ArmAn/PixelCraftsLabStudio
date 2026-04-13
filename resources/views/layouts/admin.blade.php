<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'Admin | ' . ($setting->site_name ?? 'PixelCraftsLabStudio'))</title>

    <link rel="shortcut icon" href="{{ asset($setting->favicon_path ?? 'favicon/1/Profile_pic/Transparent.gif') }}">

    <!-- CSS -->
    <link rel="stylesheet" href="{{ asset('css/layouts/admin.css') }}">

    <!-- FONTS -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&family=Orbitron:wght@600;800&display=swap" rel="stylesheet">

    @yield('styles')
</head>

<body>

<div class="admin-shell">

    <!-- SIDEBAR -->
    <aside class="admin-sidebar">

        <div class="admin-logo">⚡ Admin</div>

        <nav class="admin-nav">
            <a href="{{ route('admin.dashboard') }}">Dashboard</a>
            <a href="{{ route('admin.services.index') }}">Services</a>
            <a href="{{ route('admin.portfolio.index') }}">Portfolio</a>
            <a href="{{ route('admin.testimonials.index') }}">Testimonials</a>
            <a href="{{ route('admin.team.index') }}">Team</a>
            <a href="{{ route('admin.messages.index') }}">Messages</a>
            <a href="{{ route('admin.contacts.index') }}">Contacts</a>
            <a href="{{ route('admin.settings.edit') }}">Settings</a>
        </nav>

        <form method="POST" action="{{ route('admin.logout') }}">
            @csrf
            <button class="btn danger">Logout</button>
        </form>

    </aside>

    <!-- MAIN -->
    <main class="admin-main">

        <div class="admin-topbar">
            <h1>@yield('title')</h1>
        </div>

        @yield('content')

    </main>

</div>

</body>
</html>