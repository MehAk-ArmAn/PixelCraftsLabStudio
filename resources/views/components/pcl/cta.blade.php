@props(['href' => '/', 'secondary' => false])
@php
    $destination = match ($href) {
        '#home' => route('home'),
        '#work' => route('work.index'),
        '#services' => route('services.index'),
        '#growth', '#marketing' => route('marketing.index'),
        '#pricing' => route('pricing.index'),
        '#studio' => route('studio'),
        '#lab' => route('lab'),
        '#contact' => route('contact'),
        default => $href ?: route('home'),
    };
@endphp

<a href="{{ $destination }}" {{ $attributes->class(['pcl-button', 'pcl-button--secondary' => $secondary]) }}>
    {{ $slot }} <span aria-hidden="true">↗</span>
</a>
