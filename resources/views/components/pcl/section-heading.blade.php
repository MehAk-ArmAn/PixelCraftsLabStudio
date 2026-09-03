@props(['eyebrow' => null, 'heading' => null, 'body' => null, 'light' => false])

<header {{ $attributes->class(['section-heading', 'section-heading--light' => $light]) }}>
    @if ($eyebrow)<p class="eyebrow">{{ $eyebrow }}</p>@endif
    @if ($heading)<h2>{{ $heading }}</h2>@endif
    @if ($body)<p class="section-heading__body">{{ $body }}</p>@endif
</header>
