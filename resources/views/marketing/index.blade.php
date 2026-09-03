@extends('layouts.public')

@php
    $hero = $pageCopy['hero'] ?? [];
    $services = collect($content['marketingServices'] ?? []);
    $plans = collect($content['growthPlans'] ?? []);
    $campaigns = collect($content['campaigns'] ?? []);
@endphp

@section('content')
  <section class="growth-hero" data-signal-field>
    <div>
      <p class="eyebrow">{{ $hero['eyebrow'] ?? 'Marketing & growth' }}</p>
      <h1>{{ $hero['heading'] ?? 'Build it.' }} <span>{{ $hero['emphasis'] ?? 'Then grow it.' }}</span></h1>
      <p>{{ $hero['body'] ?? '' }}</p>
      <div class="hero__actions">
        <x-pcl.cta :href="$hero['ctaUrl'] ?? '#contact'">{{ $hero['ctaLabel'] ?? 'Start a project' }}</x-pcl.cta>
        <x-pcl.cta :href="$hero['cta2Url'] ?? '#work'" secondary>{{ $hero['cta2Label'] ?? 'See the work' }}</x-pcl.cta>
      </div>
    </div>
    <div class="signal-field" aria-hidden="true"><i></i><i></i><i></i><i></i><svg viewBox="0 0 600 320"><path d="M20 268 C118 264 116 202 204 210 S314 156 382 164 S472 78 580 42"/><circle cx="580" cy="42" r="10"/></svg></div>
  </section>

  @foreach (['capabilities', 'social', 'strategy'] as $sectionKey)
    @if ($section = $pageCopy[$sectionKey] ?? null)
      <section class="pcl-section growth-copy" data-section="{{ $sectionKey }}">
        <x-pcl.section-heading :eyebrow="$section['eyebrow'] ?? null" :heading="$section['heading'] ?? null" :body="$section['body'] ?? null" />
        @if ($sectionKey === 'capabilities')
          <div class="growth-capabilities" data-growth-network>
            @foreach ($services as $service)
              <article><p class="eyebrow">{{ $service['group'] }}</p><h3>{{ $service['title'] }}</h3><p>{{ $service['body'] }}</p></article>
              @foreach ($service['children'] as $child)<article><p class="eyebrow">{{ $child['group'] }}</p><h3>{{ $child['title'] }}</h3><p>{{ $child['body'] }}</p></article>@endforeach
            @endforeach
          </div>
        @endif
      </section>
    @endif
  @endforeach

  @if ($channelsCopy = $pageCopy['channels'] ?? null)
    <section class="pcl-section channel-section">
      <x-pcl.section-heading :eyebrow="$channelsCopy['eyebrow'] ?? null" :heading="$channelsCopy['heading'] ?? null" :body="$channelsCopy['body'] ?? null" />
      <div class="channel-cloud">
        @foreach (($content['channels'] ?? []) as $channel)<article style="--accent:{{ $channel['accent'] ?: '#8B45FF' }}"><span></span><h3>{{ $channel['label'] }}</h3><p>{{ $channel['body'] }}</p></article>@endforeach
      </div>
    </section>
  @endif

  @if ($plansCopy = $pageCopy['plans'] ?? null)
    <section class="pcl-section plan-section">
      <div class="section-heading-row"><x-pcl.section-heading :eyebrow="$plansCopy['eyebrow'] ?? null" :heading="$plansCopy['heading'] ?? null" :body="$plansCopy['body'] ?? null" light /><x-pcl.cta href="#pricing" secondary>View all pricing</x-pcl.cta></div>
      <div class="plan-grid">
        @foreach ($plans->take(4) as $plan)
          <article class="plan-card {{ $plan['featured'] ? 'is-featured' : '' }}">
            <p class="eyebrow">{{ $plan['category'] ?? $plan['duration'] ?? '' }}</p>
            <h3>{{ $plan['name'] }}</h3>
            <p>{{ $plan['short'] ?? $plan['description'] ?? '' }}</p>
            <strong>{{ $plan['priceLabel'] ?? $plan['price'] ?? 'Custom' }}</strong>
            <ul>@foreach ($plan['items'] as $item)<li>{{ $item['text'] ?? $item['title'] }}</li>@endforeach</ul>
            <x-pcl.cta :href="$plan['ctaUrl'] ?? '#contact'">{{ $plan['ctaLabel'] ?? 'Start a project' }}</x-pcl.cta>
          </article>
        @endforeach
      </div>
    </section>
  @endif

  @if ($process = $pageCopy['process'] ?? null)
    <section class="pcl-section growth-process"><x-pcl.section-heading :eyebrow="$process['eyebrow'] ?? null" :heading="$process['heading'] ?? null" :body="$process['body'] ?? null" />
      <ol>@foreach (($content['growthStages'] ?? []) as $stage)<li><span>{{ $stage['no'] }}</span><h3>{{ $stage['name'] }}</h3><p>{{ $stage['body'] }}</p></li>@endforeach</ol>
    </section>
  @endif

  @if ($cases = $pageCopy['cases'] ?? null)
    <section class="pcl-section"><x-pcl.section-heading :eyebrow="$cases['eyebrow'] ?? null" :heading="$cases['heading'] ?? null" :body="$cases['body'] ?? null" />
      @if ($campaigns->isEmpty())<p class="empty-state">{{ $cases['empty'] ?? '' }}</p>@else<div class="project-grid">@foreach ($campaigns as $campaign)<article class="project-card"><div class="project-card__copy"><p class="eyebrow">{{ $campaign['type'] }}</p><h3>{{ $campaign['name'] }}</h3><p>{{ $campaign['summary'] }}</p></div></article>@endforeach</div>@endif
    </section>
  @endif

  @if ($cta = $pageCopy['cta'] ?? null)<section class="closing-cta"><h2>{{ $cta['heading'] }}</h2><p>{{ $cta['body'] }}</p><x-pcl.cta :href="$cta['ctaUrl'] ?? '#contact'">{{ $cta['ctaLabel'] }}</x-pcl.cta></section>@endif
@endsection
