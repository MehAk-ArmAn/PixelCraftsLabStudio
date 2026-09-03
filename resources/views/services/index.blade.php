@extends('layouts.public')

@php
    $header = $pageCopy['header'] ?? [];
    $services = collect($content['services'] ?? []);
    $marketing = collect($content['marketingServices'] ?? []);
@endphp

@section('content')
  <section class="page-hero page-hero--services">
    <p class="eyebrow">{{ $header['eyebrow'] ?? 'Services' }}</p>
    <h1>{{ $header['heading'] ?? 'Watch what we do' }}</h1>
    <p>{{ str_replace('{count}', (string) ($services->count() + $marketing->count()), $header['body'] ?? '') }}</p>
  </section>

  <section class="service-path pcl-section" data-build-path>
    @foreach ($services->concat($marketing) as $index => $service)
      <article class="service-card" data-service-step>
        <div class="service-card__number">{{ str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT) }}</div>
        @if ($service['icon'])<img src="{{ $service['icon'] }}" alt="">@else<div class="service-card__visual" aria-hidden="true"><i></i><i></i><i></i></div>@endif
        <p class="eyebrow">{{ $service['tag'] ?: $service['stage'] }}</p>
        <h2>{{ $service['title'] }}</h2>
        <p>{{ $service['body'] }}</p>
        @if ($service['caption'])<small>{{ $service['caption'] }}</small>@endif
        @php $serviceUrl = $service['ctaUrl'] ?: ($service['id'] === 'digital-marketing-growth' ? route('marketing.index') : route('contact')); @endphp
        <a class="text-link" href="{{ $serviceUrl }}" data-transition-link>{{ $service['ctaLabel'] ?: ($service['id'] === 'digital-marketing-growth' ? 'Explore marketing & growth' : 'Start a project') }} →</a>
      </article>
    @endforeach
  </section>

  @if ($closing = $pageCopy['closing'] ?? null)
    <section class="closing-cta"><h2>{{ $closing['heading'] }}</h2><x-pcl.cta :href="$closing['ctaUrl'] ?? '#contact'">{{ $closing['ctaLabel'] }}</x-pcl.cta></section>
  @endif
@endsection
