@extends('layouts.public')

@php $header = $pageCopy['header'] ?? []; $steps = $pageCopy['steps'] ?? []; @endphp

@section('content')
  <section class="page-hero contact-hero">
    <p class="eyebrow">{{ $header['eyebrow'] ?? 'Start a project' }}</p>
    <h1>{{ $header['heading'] ?? 'Build your brief' }}</h1>
    <p>{{ $header['body'] ?? '' }}</p>
  </section>

  <section class="contact-layout pcl-section">
    <form class="contact-form" method="POST" action="{{ route('contact.store') }}" data-contact-form>
      @csrf
      <input class="honeypot" type="text" name="company_website" tabindex="-1" autocomplete="off" aria-hidden="true">
      @if (session('contact_status'))<div class="form-status is-success">{{ session('contact_status') }}</div>@endif
      <div class="form-status" data-contact-status hidden></div>

      <label><span>{{ $steps['nameLabel'] ?? 'Your name' }}</span><input name="name" required maxlength="120" value="{{ old('name') }}"></label>
      <label><span>{{ $steps['emailLabel'] ?? 'Your email' }}</span><input type="email" name="email" required maxlength="190" value="{{ old('email') }}"></label>
      <label><span>What are you building?</span><select name="build_type"><option value="">Choose one</option>@foreach (data_get($content, 'contactOptions.build', []) as $option)<option value="{{ $option['value'] }}">{{ $option['label'] }}</option>@endforeach</select></label>
      <label><span>Scope</span><select name="scope"><option value="">Choose one</option>@foreach (data_get($content, 'contactOptions.scope', []) as $option)<option value="{{ $option['value'] }}">{{ $option['label'] }}</option>@endforeach</select></label>
      <label><span>Timeline</span><select name="timeline"><option value="">Choose one</option>@foreach (data_get($content, 'contactOptions.timeline', []) as $option)<option value="{{ $option['value'] }}">{{ $option['label'] }}</option>@endforeach</select></label>
      <label><span>Marketing service</span><select name="service"><option value="">Optional</option>@foreach (data_get($content, 'contactOptions.service', []) as $option)<option value="{{ $option['value'] }}">{{ $option['label'] }}</option>@endforeach</select></label>
      <label><span>Budget</span><select name="budget"><option value="">Optional</option>@foreach (data_get($content, 'contactOptions.budget', []) as $option)<option value="{{ $option['value'] }}">{{ $option['label'] }}</option>@endforeach</select></label>
      <label class="contact-form__wide"><span>{{ $steps['projectLabel'] ?? 'The project' }}</span><textarea name="message" rows="7" maxlength="5000" placeholder="{{ $steps['projectPlaceholder'] ?? '' }}">{{ old('message') }}</textarea></label>
      <button class="pcl-button" type="submit">{{ $steps['submitLabel'] ?? 'Launch project' }} <span>↗</span></button>
    </form>

    <aside class="contact-card"><img src="{{ $settings['logo'] ?? asset('assets/pcl-logo.png') }}" alt=""><h2>{{ data_get($pageCopy, 'preview.heading', 'Let’s build yours') }}</h2><p>{{ $settings['description'] ?? '' }}</p><dl><div><dt>Email</dt><dd>{{ $settings['email'] ?: 'Available on request' }}</dd></div><div><dt>Phone</dt><dd>{{ $settings['phone'] ?? '' }}</dd></div><div><dt>Location</dt><dd>{{ $settings['location'] ?? '' }}</dd></div></dl></aside>
  </section>
@endsection
