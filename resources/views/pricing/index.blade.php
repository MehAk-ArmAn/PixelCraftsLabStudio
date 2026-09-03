@extends('layouts.public')

@php $packages = collect($content['packages'] ?? [])->groupBy('category'); @endphp

@section('content')
  <section class="page-hero page-hero--pricing">
    <p class="eyebrow">Marketing & growth · indicative pricing</p>
    <h1>Clear starting points.<br><span>Scoped around you.</span></h1>
    <p>Every figure is an estimate or starting point. Final investment is confirmed after scope, channels, production and campaign requirements are understood.</p>
  </section>

  @if (($content['pricingPromotion']['active'] ?? false))
    <aside class="pricing-promotion"><strong>{{ $content['pricingPromotion']['text'] }}</strong>@if ($content['pricingPromotion']['remaining'] !== null)<span>{{ $content['pricingPromotion']['remaining'] }} places remaining</span>@endif</aside>
  @endif

  <section class="pricing-catalogue pcl-section">
    @foreach ($packages as $category => $categoryPackages)
      <section class="pricing-group">
        <x-pcl.section-heading :eyebrow="$loop->iteration < 10 ? '0'.$loop->iteration : $loop->iteration" :heading="$category" />
        <div class="pricing-grid">
          @foreach ($categoryPackages as $package)
            <article class="pricing-card {{ $package['featured'] ? 'is-featured' : '' }}">
              <div class="pricing-card__top"><p class="eyebrow">{{ $package['priceQualifier'] ?: 'Estimated' }}</p>@if ($package['promotionLabel'])<span>{{ $package['promotionLabel'] }}</span>@endif</div>
              <h2>{{ $package['name'] }}</h2>
              <p>{{ $package['description'] }}</p>
              <div class="pricing-card__price">{{ $package['priceLabel'] }} @if ($package['billingLabel'])<small>{{ $package['billingLabel'] }}</small>@endif</div>
              @if ($package['foundingPriceLabel'])<div class="pricing-card__offer">{{ $package['foundingPriceLabel'] }}</div>@endif
              <ul>@foreach ($package['items'] as $item)@if ($item['included'])<li>{{ $item['text'] }}</li>@endif @endforeach</ul>
              <x-pcl.cta :href="$package['ctaUrl']">{{ $package['ctaLabel'] }}</x-pcl.cta>
            </article>
          @endforeach
        </div>
      </section>
    @endforeach
  </section>

  <aside class="pricing-notes">
    @foreach (($content['pricingNotes'] ?? []) as $note)@if ($note)<p>{{ $note }}</p>@endif @endforeach
  </aside>
@endsection
