@extends('layouts.public')

@php $header = $pageCopy['header'] ?? []; @endphp

@section('content')
  <section class="page-hero studio-hero">
    <p class="eyebrow">{{ $header['eyebrow'] ?? 'Studio' }}</p>
    <h1>{{ $header['heading'] ?? '' }} <span>{{ $header['wordA'] ?? '' }} {{ $header['wordB'] ?? '' }} {{ $header['wordC'] ?? '' }}</span></h1>
  </section>

  @if ($name = $pageCopy['name'] ?? null)
    <section class="pcl-section name-section"><x-pcl.section-heading :heading="$name['heading'] ?? null" />
      <div class="name-parts">@foreach ([1, 2, 3] as $part)<article><strong>{{ $name["part{$part}Key"] ?? '' }}</strong><p>{{ $name["part{$part}Body"] ?? '' }}</p></article>@endforeach</div>
    </section>
  @endif

  <section class="pcl-section studio-story-grid">
    @foreach (['story', 'mission', 'vision'] as $key)@if ($section = $pageCopy[$key] ?? null)<article><p class="eyebrow">{{ str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT) }}</p><h2>{{ $section['heading'] }}</h2><p>{{ $section['body'] }}</p></article>@endif @endforeach
  </section>

  @if ($teamCopy = $pageCopy['team'] ?? null)
    <section class="pcl-section team-section"><x-pcl.section-heading :heading="str_replace('{count}', (string) count($content['team'] ?? []), $teamCopy['heading'] ?? '')" :body="$teamCopy['body'] ?? null" />
      <div class="team-grid">@foreach (($content['team'] ?? []) as $member)<article>@if ($member['image'])<img src="{{ $member['image'] }}" alt="{{ $member['name'] }}" loading="lazy">@else<div class="team-placeholder"><img src="{{ asset('assets/pcl-logo.png') }}" alt=""></div>@endif<h3>{{ $member['name'] }}</h3><p class="eyebrow">{{ $member['role'] }}</p><p>{{ $member['bio'] }}</p></article>@endforeach</div>
    </section>
  @endif

  @if ($proof = $pageCopy['proof'] ?? null)<section class="pcl-section proof-section"><x-pcl.section-heading :eyebrow="$proof['eyebrow'] ?? null" :heading="$proof['heading'] ?? null" :body="$proof['body'] ?? null" light /><x-pcl.cta href="#work" secondary>Open the work</x-pcl.cta></section>@endif
@endsection
