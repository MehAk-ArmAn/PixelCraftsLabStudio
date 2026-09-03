@extends('layouts.public')

@php
    $labels = $pageCopy['detail'] ?? [];
    $caseLabels = $pageCopy['marketing'] ?? [];
    $gallery = collect([$project['featureImage'], $project['image']])->merge($project['gallery'])->filter()->unique()->values();
    $caseFields = collect([
        [$caseLabels['goalLabel'] ?? 'Client goal', $project['goal']],
        [$caseLabels['challengeLabel'] ?? 'The problem', $project['challenge']],
        [$caseLabels['audienceLabel'] ?? 'Audience', $project['audience']],
        [$caseLabels['strategyLabel'] ?? 'Strategy', $project['strategy']],
        [$caseLabels['approachLabel'] ?? 'Approach', $project['approach']],
        [$caseLabels['deliverablesLabel'] ?? 'Deliverables', $project['deliverables']],
        [$caseLabels['resultsLabel'] ?? 'Results', $project['results']],
        [$caseLabels['lessonsLabel'] ?? 'What we’d do next', $project['lessons']],
    ])->filter(fn ($row) => filled($row[1]));
@endphp

@section('content')
  <article class="project-detail">
    <header class="project-detail__hero">
      <a class="back-link" href="{{ route('work.index') }}" data-transition-link>← {{ $labels['backLabel'] ?? 'All work' }}</a>
      <div class="project-detail__title">
        <p class="eyebrow">{{ $project['cat'] }} · {{ $project['kind'] }}</p>
        <h1>{{ $project['name'] }}</h1>
        <p>{{ $project['blurb'] }}</p>
        @if ($project['link'])<a class="pcl-button" href="{{ $project['link'] }}" target="_blank" rel="noopener">{{ $labels['visitLabel'] ?? 'Visit the live project' }} ↗</a>@endif
      </div>
      @if ($gallery->isNotEmpty())
        <button class="project-detail__cover" type="button" data-gallery-open="0" data-gallery='@json($gallery)' data-gallery-title="{{ $project['name'] }}">
          <img src="{{ $gallery->first() }}" alt="{{ $project['name'] }}">
          @if ($project['icon'])<img class="project-detail__icon" src="{{ $project['icon'] }}" alt="">@endif
          <span>View project media</span>
        </button>
      @endif
    </header>

    <dl class="project-meta">
      <div><dt>{{ $labels['metaDiscipline'] ?? 'Discipline' }}</dt><dd>{{ $project['kind'] }}</dd></div>
      <div><dt>{{ $labels['metaPlatform'] ?? 'Platform' }}</dt><dd>{{ $project['platform'] }}</dd></div>
      <div><dt>{{ $labels['metaCategory'] ?? 'Category' }}</dt><dd>{{ $project['cat'] }}</dd></div>
      <div><dt>{{ $labels['metaStatus'] ?? 'Status' }}</dt><dd>{{ $project['link'] ? ($labels['statusLive'] ?? 'Live') : ($labels['statusBuild'] ?? 'In build') }}</dd></div>
    </dl>

    @if ($gallery->count() > 1)
      <section class="project-gallery" data-section="gallery">
        @foreach ($gallery as $index => $image)
          <button type="button" data-gallery-open="{{ $index }}" data-gallery='@json($gallery)' data-gallery-title="{{ $project['name'] }} media {{ $index + 1 }}"><img src="{{ $image }}" alt="{{ $project['name'] }} media {{ $index + 1 }}" loading="lazy"></button>
        @endforeach
      </section>
    @endif

    @if ($project['caseStudy'])
      <section class="pcl-section project-story"><p class="eyebrow">{{ $labels['builtLabel'] ?? 'What we built' }}</p><div class="prose"><p>{{ $project['caseStudy'] }}</p></div></section>
    @endif

    @if ($caseFields->isNotEmpty() || collect($project['metrics'])->isNotEmpty())
      <section class="pcl-section case-study-grid">
        @foreach ($caseFields as [$label, $value])<article><p class="eyebrow">{{ $label }}</p><p>{{ $value }}</p></article>@endforeach
        @foreach ($project['metrics'] as $metric)<article class="metric"><strong>{{ $metric['value'] }}</strong><span>{{ $metric['label'] }}</span>@if ($metric['context'])<small>{{ $metric['context'] }}</small>@endif</article>@endforeach
      </section>
    @endif

    @if ($nextProject)
      <a class="next-project" href="{{ $nextProject['url'] }}" data-transition-link><span>{{ $labels['nextLabel'] ?? 'Next project' }}</span><strong>{{ $nextProject['name'] }}</strong><b>→</b></a>
    @endif
  </article>
@endsection
