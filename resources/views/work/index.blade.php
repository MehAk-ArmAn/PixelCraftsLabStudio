@extends('layouts.public')

@php $header = $pageCopy['header'] ?? []; @endphp

@section('content')
  <section class="page-hero page-hero--work">
    <p class="eyebrow">{{ str_replace('{count}', (string) $projects->count(), $header['eyebrow'] ?? 'Portfolio') }}</p>
    <h1>{{ $header['heading'] ?? 'Our' }} <span>{{ $header['subheading'] ?? 'Solutions' }}</span></h1>
    <p>{{ $header['body'] ?? '' }} <strong>{{ $header['body2'] ?? '' }}</strong></p>
  </section>

  <section class="work-index pcl-section" data-work-index>
    <nav class="work-filters" aria-label="Filter projects">
      @foreach (($content['categories'] ?? ['All']) as $category)
        <button type="button" data-project-filter="{{ $category }}" class="{{ $loop->first ? 'is-active' : '' }}">{{ $category }}</button>
      @endforeach
    </nav>
    <div class="project-grid">
      @foreach ($projects as $item)<x-project.card :project="$item" />@endforeach
    </div>
  </section>

  @if ($closing = $pageCopy['closing'] ?? null)
    <section class="closing-cta">
      <h2>{{ $closing['heading'] }}</h2>
      <x-pcl.cta :href="$closing['ctaUrl'] ?? '#contact'">{{ $closing['ctaLabel'] ?? 'Start yours' }}</x-pcl.cta>
    </section>
  @endif
@endsection
