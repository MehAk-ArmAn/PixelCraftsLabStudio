<!DOCTYPE html>
<html lang="en">
<head>
    @php
        $setting = $setting ?? \App\Models\Setting::first();
    @endphp

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin | ' . ($setting?->site_name ?? 'PixelCraftsLabStudio'))</title>

    <link rel="shortcut icon" href="{{ asset($setting?->favicon_path ?? 'favicon/1/Profile_pic/Transparent.gif') }}">

    <link rel="stylesheet" href="{{ asset('css/layouts/admin.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&family=Orbitron:wght@600;800&display=swap" rel="stylesheet">

    @yield('styles')
</head>
<body>

<div class="admin-shell">
    <aside class="admin-sidebar">
        <div class="admin-logo">⚡ Admin</div>

        <nav class="admin-nav">

            {{-- FRONTEND LINK --}}
            <a href="{{ route('home') }}" target="_blank" class="admin-nav-home">
                🌐 View Website
            </a>

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
            <button class="btn danger" type="submit">Logout</button>
        </form>
    </aside>

    <main class="admin-main">
        <div class="admin-topbar">
            <h1>@yield('title')</h1>
        </div>

        @if(session('success'))
            <div class="admin-card" style="margin-bottom: 16px;">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="admin-card" style="margin-bottom: 16px;">
                <ul style="margin:0; padding-left:18px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </main>
</div>

</body>
</html>
