@props(['project', 'featured' => false])
@php
    $media = $project['featureImage'] ?: ($project['image'] ?: ($project['icon'] ?: asset('assets/pcl-logo.png')));
@endphp

<article {{ $attributes->class(['project-card', 'project-card--featured' => $featured]) }} data-project-card data-category="{{ $project['cat'] }}">
    <a class="project-card__media" href="{{ $project['url'] }}" data-transition-link>
        <img src="{{ $media }}" alt="{{ $project['name'] }}" loading="lazy">
        @if ($project['icon'])
            <img class="project-card__icon" src="{{ $project['icon'] }}" alt="" loading="lazy">
        @endif
        <span class="project-card__category">{{ $project['cat'] }}</span>
    </a>
    <div class="project-card__copy">
        <p class="eyebrow">{{ $project['kind'] ?: $project['platform'] }}</p>
        <h3><a href="{{ $project['url'] }}" data-transition-link>{{ $project['name'] }}</a></h3>
        @if ($project['short'])<p>{{ $project['short'] }}</p>@endif
        <a class="text-link" href="{{ $project['url'] }}" data-transition-link>Open project <span>→</span></a>
    </div>
</article>
