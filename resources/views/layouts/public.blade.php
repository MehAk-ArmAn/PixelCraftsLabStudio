<!doctype html>
<html lang="en" data-page="{{ $routeKey }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $seo['title'] ?: ($settings['studioName'] ?? 'PixelCraftsLab Studio') }}</title>
    <meta name="description" content="{{ $seo['description'] ?? '' }}">
    <meta name="robots" content="{{ ($seo['robotsIndex'] ?? true) ? 'index, follow' : 'noindex, nofollow' }}">
    <link rel="canonical" href="{{ $seo['canonical'] ?? url()->current() }}">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="{{ $settings['studioName'] ?? 'PixelCraftsLab Studio' }}">
    <meta property="og:title" content="{{ $seo['ogTitle'] ?? $seo['title'] ?? '' }}">
    <meta property="og:description" content="{{ $seo['ogDescription'] ?? $seo['description'] ?? '' }}">
    @if ($seo['ogImage'] ?? null)<meta property="og:image" content="{{ url($seo['ogImage']) }}">@endif
    <meta name="twitter:card" content="summary_large_image">
    @if ($settings['favicon'] ?? null)<link rel="icon" href="{{ $settings['favicon'] }}">@endif
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,400;12..96,500;12..96,700;12..96,800&family=Figtree:wght@400;500;600;700&family=Space+Mono:wght@400;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body
    data-transitions="{{ ($flags['transitionsEnabled'] ?? true) ? 'on' : 'off' }}"
    data-cursor="{{ ($flags['cursorEnabled'] ?? true) ? 'on' : 'off' }}"
    data-ambient="{{ ($flags['ambientEnabled'] ?? true) ? 'on' : 'off' }}"
>
    <div class="page-transition" aria-hidden="true"><span></span><span></span><span></span><span></span></div>

    @if ($flags['ambientEnabled'] ?? true)
        <div class="ambient-field" aria-hidden="true">
            @foreach (range(1, 14) as $pixel)<i style="--i:{{ $pixel }}"></i>@endforeach
        </div>
    @endif

    @if ($flags['cursorEnabled'] ?? true)
        <div class="custom-cursor" aria-hidden="true"><span></span></div>
    @endif

    <header class="site-header" data-site-header>
        <a class="site-brand" href="{{ route('home') }}" data-home-link data-transition-link>
            <img src="{{ $settings['logo'] ?? asset('assets/pcl-logo.png') }}" alt="">
            <span><strong>{{ $settings['shortName'] ?? 'PixelCraftsLab' }}</strong><small>{{ $settings['tagline'] ?? 'Ideas · Build · Launch' }}</small></span>
        </a>

        <nav class="desktop-nav" aria-label="Main navigation">
            @foreach ($navigation as $item)
                @if ($item['desktop'])
                    <a href="{{ $item['href'] }}" @if ($item['newTab']) target="_blank" rel="noopener" @else data-transition-link @endif class="{{ $routeKey === ($item['key'] === 'growth' ? 'marketing' : $item['key']) ? 'is-active' : '' }}">
                        <span>{{ $item['no'] }}</span>{{ $item['label'] }}
                    </a>
                @endif
            @endforeach
        </nav>

        <a class="header-contact" href="{{ route('contact') }}" data-transition-link>{{ $settings['contactStrip'] ?: 'Start a project' }}</a>
        <button class="menu-toggle" type="button" aria-label="Open navigation" aria-expanded="false" data-menu-toggle><i></i><i></i></button>
    </header>

    <div class="mobile-menu" data-mobile-menu hidden>
        <button type="button" class="mobile-menu__close" aria-label="Close navigation" data-menu-toggle>×</button>
        <p class="eyebrow">{{ $settings['menuLabel'] ?? 'Navigate' }}</p>
        <a href="{{ route('home') }}" data-home-link data-transition-link>Home <span>00</span></a>
        @foreach ($navigation as $item)
            @if ($item['mobile'])
                <a href="{{ $item['href'] }}" @if (! $item['newTab']) data-transition-link @endif>{{ $item['label'] }} <span>{{ $item['no'] }}</span></a>
            @endif
        @endforeach
    </div>

    @foreach (($content['experiences'] ?? []) as $experience)
        <i hidden data-pcl-experience
           data-page="{{ $experience['page'] }}"
           data-section="{{ $experience['section'] }}"
           data-type="{{ $experience['type'] }}"
           data-enabled="{{ $experience['enabled'] ? 'true' : 'false' }}"
           data-accent="{{ $experience['accentPreset'] }}"
           data-intensity="{{ $experience['intensity'] }}"></i>
    @endforeach

    <main id="main-content">
        @yield('content')
    </main>

    <footer class="site-footer">
        <div class="site-footer__brand">
            <img src="{{ $settings['logo'] ?? asset('assets/pcl-logo.png') }}" alt="">
            <h2>{{ $settings['shortName'] ?? 'PixelCraftsLab' }}</h2>
            <p>{{ $settings['footerDescription'] ?? '' }}</p>
        </div>
        <div>
            <p class="eyebrow">{{ $settings['footerSiteLabel'] ?? 'Site' }}</p>
            <a href="{{ route('home') }}">Home</a>
            @foreach ($navigation as $item)@if ($item['footer'])<a href="{{ $item['href'] }}">{{ $item['label'] }}</a>@endif @endforeach
        </div>
        <div>
            <p class="eyebrow">{{ $settings['footerServicesLabel'] ?? 'Services' }}</p>
            @foreach (($settings['footerServices'] ?? []) as $service)<a href="{{ $service['url'] }}">{{ $service['label'] }}</a>@endforeach
        </div>
        <div>
            <p class="eyebrow">{{ $settings['footerFollowLabel'] ?? 'Follow' }}</p>
            @foreach (($content['socials'] ?? []) as $social)<a href="{{ $social['url'] }}" target="_blank" rel="noopener">{{ $social['label'] }}</a>@endforeach
        </div>
        <div class="site-footer__bottom">
            <span>{{ $settings['copyright'] ?? '' }}</span>
            @if ($settings['footerSecondary'] ?? null)<span>{{ $settings['footerSecondary'] }}</span>@endif
        </div>
    </footer>

    <dialog class="media-viewer" data-media-viewer>
        <button type="button" aria-label="Close" data-viewer-close>×</button>
        <button type="button" aria-label="Previous image" data-viewer-prev>←</button>
        <figure><img src="" alt="" data-viewer-image><figcaption data-viewer-caption></figcaption></figure>
        <button type="button" aria-label="Next image" data-viewer-next>→</button>
    </dialog>
</body>
</html>
