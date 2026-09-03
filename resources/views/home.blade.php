@extends('layouts.public')

@php
    $homeFeatured = collect($content['homeFeaturedProjects'] ?? []);
    $primaryProject = $homeFeatured->firstWhere('primary', true);
    $hero = $pageCopy['hero'] ?? [];
    $introEnabled = ($flags['introEnabled'] ?? true) && ($settings['introMode'] ?? 'forge') !== 'off';
@endphp

@section('content')
  @if ($introEnabled)
    <section class="home-intro" role="dialog" aria-label="PixelCraftsLab introduction"
             data-home-intro
             data-replay="{{ ($settings['introReplayOnHome'] ?? true) ? 'true' : 'false' }}"
             data-duration="{{ $settings['introDuration'] ?? 2600 }}"
             data-intensity="{{ $settings['introIntensity'] ?? 1 }}"
             data-mode="{{ $settings['introMode'] ?? 'forge' }}"
             data-transition="{{ $settings['introTransitionPreset'] ?? 'scatter' }}"
             data-background="{{ $settings['introBackgroundPreset'] ?? 'paper-grid' }}">
      <div class="home-intro__glow home-intro__glow--violet"></div>
      <div class="home-intro__glow home-intro__glow--orange"></div>
      @if ($settings['introShowProjectFragments'] ?? true)
        <div class="home-intro__fragments" aria-hidden="true">
          @foreach ($homeFeatured->take(8) as $index => $featured)
            @php $fragment = $featured['icon'] ?: ($featured['featureImage'] ?: $featured['image']); @endphp
            @if ($fragment)<img src="{{ $fragment }}" alt="" style="--fragment:{{ $index }}">@endif
          @endforeach
        </div>
      @endif
      <div class="home-intro__content">
        <div class="brand-forge" aria-hidden="true">
          @foreach (['L-pixels.png', 'L-stem.png', 'L-bowl.png', 'L-icon.png', 'L-orange.png', 'L-orange2.png', 'L-black.png', 'L-dot1.png', 'L-dot3.png', 'L-dot2.png'] as $layer)
            <img src="{{ asset('assets/'.$layer) }}" alt="">
          @endforeach
        </div>
        <h1>{{ $settings['introHeading'] ?? 'PixelCraftsLab' }}</h1>
        <div class="home-intro__beats" aria-hidden="true">
          @foreach (['Idea', 'Design', 'Build', 'Launch', 'Grow'] as $beat)<span>{{ $beat }}</span>@endforeach
        </div>
        <p>{{ $settings['introSubheading'] ?? '' }}</p>
        <button class="pcl-button" type="button" data-intro-enter>{{ $settings['introCta'] ?? 'Enter the studio' }} <span>→</span></button>
        <small>{{ $settings['tagline'] ?? 'Ideas · Build · Launch' }} · scroll to enter</small>
      </div>
    </section>
  @endif

  <section class="hero home-hero" data-section="hero">
    <div class="hero__copy reveal">
      <p class="eyebrow">{{ $hero['eyebrow'] ?? '' }}</p>
      <h1>{{ $hero['heading'] ?? 'Bring your idea' }} <span>{{ $hero['emphasis'] ?? 'life' }}</span></h1>
      @if ($hero['body'] ?? null)<p class="hero__body">{{ $hero['body'] }}</p>@endif
      <div class="hero__actions">
        <x-pcl.cta :href="$hero['ctaUrl'] ?? '#work'">{{ $hero['ctaLabel'] ?? 'See the work' }}</x-pcl.cta>
        <x-pcl.cta :href="$hero['cta2Url'] ?? '#contact'" secondary>{{ $hero['cta2Label'] ?? 'Start a project' }}</x-pcl.cta>
      </div>
    </div>
    <div class="home-hero__stage reveal" data-project-stage>
      @if ($primaryProject)
        <a href="{{ $primaryProject['url'] }}" data-transition-link>
          <div class="home-hero__canvas">
            <img class="home-hero__feature" src="{{ $primaryProject['featureImage'] ?: $primaryProject['image'] }}" alt="{{ $primaryProject['name'] }}">
            @if ($primaryProject['icon'])<img class="home-hero__icon" src="{{ $primaryProject['icon'] }}" alt="">@endif
            <img class="home-hero__brand-layer" src="{{ asset('assets/L-orange.png') }}" alt="">
            <img class="home-hero__brand-layer home-hero__brand-layer--pixels" src="{{ asset('assets/L-pixels.png') }}" alt="">
          </div>
          <div class="home-hero__badge"><span>{{ $primaryProject['badgeText'] ?: ($hero['badgeLabel'] ?? 'Now live') }}</span><strong>{{ $primaryProject['name'] }}</strong></div>
        </a>
      @else
        <div class="home-hero__generic"><img src="{{ asset('assets/pcl-logo.png') }}" alt="PixelCraftsLab"></div>
      @endif
    </div>
  </section>

  @if ($craft = $pageCopy['craft'] ?? null)
    <section class="pcl-section craft-section" data-section="craft">
      <x-pcl.section-heading :eyebrow="$craft['eyebrow'] ?? null" :heading="$craft['heading'] ?? null" :body="$craft['body'] ?? null" />
      <div class="craft-mark" aria-hidden="true"><span></span><span></span><span></span></div>
    </section>
  @endif

  @if ($selected = $pageCopy['selected_work'] ?? null)
    <section class="pcl-section featured-work" data-section="selected_work">
      <div class="section-heading-row">
        <x-pcl.section-heading :eyebrow="$selected['eyebrow'] ?? null" :heading="$selected['heading'] ?? null" />
        <x-pcl.cta :href="$selected['ctaUrl'] ?? '#work'" secondary>{{ str_replace('{count}', (string) ($content['projectCount'] ?? 0), $selected['ctaLabel'] ?? 'All work') }}</x-pcl.cta>
      </div>
      <div class="featured-work__list">
        @forelse ($homeFeatured as $featured)
          <x-project.card :project="$featured" :featured="true" data-slot="{{ $featured['slot'] }}" data-primary="{{ $featured['primary'] ? 'true' : 'false' }}" />
        @empty
          <div class="empty-state">Featured projects are being curated.</div>
        @endforelse
      </div>
    </section>
  @endif

  @if ($capabilities = $pageCopy['capabilities'] ?? null)
    <section class="pcl-section capabilities-section" data-section="capabilities">
      <div class="section-heading-row">
        <x-pcl.section-heading :eyebrow="$capabilities['eyebrow'] ?? null" :heading="$capabilities['heading'] ?? null" light />
        <x-pcl.cta :href="$capabilities['ctaUrl'] ?? '#services'" secondary>{{ $capabilities['ctaLabel'] ?? 'How we work' }}</x-pcl.cta>
      </div>
      <div class="capability-marquee">
        @foreach (collect($content['services'] ?? [])->concat($content['marketingServices'] ?? [])->where('onHome', true) as $service)
          <a href="{{ $service['ctaUrl'] ?: ($service['id'] === 'digital-marketing-growth' ? route('marketing.index') : route('services.index')) }}"><span>{{ $service['tag'] }}</span>{{ $service['title'] }}</a>
        @endforeach
      </div>
    </section>
  @endif

  @if ($process = $pageCopy['process'] ?? null)
    <section class="pcl-section process-section" data-section="process">
      <div>
        <p class="eyebrow">{{ $process['eyebrow'] ?? '' }}</p>
        <h2>{{ $process['word1'] ?? '' }}<br>{{ $process['word2'] ?? '' }}<br>{{ $process['word3'] ?? '' }}</h2>
        <p>{{ $process['body'] ?? '' }}</p>
        <x-pcl.cta :href="$process['ctaUrl'] ?? '#studio'" secondary>{{ $process['ctaLabel'] ?? 'Inside the studio' }}</x-pcl.cta>
      </div>
      <div class="process-steps">
        @foreach ([1, 2, 3] as $step)
          <article><span>{{ $process["step{$step}No"] ?? '' }}</span><h3>{{ $process["step{$step}Title"] ?? '' }}</h3><p>{{ $process["step{$step}Body"] ?? '' }}</p></article>
        @endforeach
      </div>
    </section>
  @endif

  @if ($cta = $pageCopy['cta'] ?? null)
    <section class="closing-cta" data-section="cta">
      <h2>{{ $cta['heading'] ?? '' }} <em>{{ $cta['emphasis'] ?? '' }}</em></h2>
      <p>{{ $cta['body'] ?? '' }}</p>
      <x-pcl.cta :href="$cta['ctaUrl'] ?? '#contact'">{{ $cta['ctaLabel'] ?? 'Start a project' }}</x-pcl.cta>
    </section>
  @endif
@endsection
