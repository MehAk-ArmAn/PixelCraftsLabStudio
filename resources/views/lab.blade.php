@extends('layouts.public')

@php $header = $pageCopy['header'] ?? []; @endphp

@section('content')
  <section class="page-hero lab-hero">
    <p class="eyebrow">Lab · experiments</p>
    <h1>{{ $header['heading'] ?? 'Make a' }} <span>{{ $header['emphasis'] ?? 'mess' }}</span></h1>
    <p>{{ $header['body'] ?? '' }}</p>
  </section>

  <section class="pixel-forge pcl-section" data-pixel-forge>
    <div class="pixel-forge__toolbar"><span>{{ $header['toolLabel'] ?? 'Tool' }}</span><button type="button" data-pixel-color="#5B2394" class="is-active" style="--color:#5B2394"></button><button type="button" data-pixel-color="#8B45FF" style="--color:#8B45FF"></button><button type="button" data-pixel-color="#FF5F1F" style="--color:#FF5F1F"></button><button type="button" data-pixel-color="#0D0B12" style="--color:#0D0B12"></button><button type="button" data-pixel-clear>{{ $header['clearLabel'] ?? 'Clear' }}</button></div>
    <div class="pixel-forge__board" data-pixel-board aria-label="Interactive pixel board"></div>
  </section>

  @if ($projectsCopy = $pageCopy['projects'] ?? null)
    <section class="pcl-section"><x-pcl.section-heading :eyebrow="$projectsCopy['eyebrow'] ?? null" :heading="$projectsCopy['heading'] ?? 'In the lab right now'" />
      <div class="project-grid">@foreach ($projects->where('cat', 'Lab') as $item)<x-project.card :project="$item" />@endforeach</div>
    </section>
  @endif

  @if ($cta = $pageCopy['cta'] ?? null)<section class="closing-cta"><h2>{{ $cta['heading'] }}</h2><x-pcl.cta :href="$cta['ctaUrl'] ?? '#contact'">{{ $cta['ctaLabel'] }}</x-pcl.cta></section>@endif
@endsection
