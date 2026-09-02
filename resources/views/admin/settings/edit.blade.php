@extends('admin.layouts.app')
@section('title', 'Settings')

@section('content')
<div class="card">
  <div class="row">
    @foreach ($groups as $key => $label)
      <a class="btn {{ $group === $key ? '' : 'ghost' }} small" href="{{ route('admin.settings.edit', $key) }}">{{ $label }}</a>
    @endforeach
  </div>
</div>

<div class="card">
  <h2>{{ $groups[$group] }}</h2>

  @if ($settings->isEmpty())
    <p class="empty">No settings in this group.</p>
  @else
    <form method="POST" action="{{ route('admin.settings.update', $group) }}">
      @csrf @method('PUT')

      @foreach ($settings as $setting)
        @php
            $name = 'values['.$setting->key.']';
            $label = $setting->label ?: \Illuminate\Support\Str::headline($setting->key);
        @endphp

        @if ($setting->type === 'bool')
          <label class="check">
            <input type="hidden" name="{{ $name }}" value="0">
            <input type="checkbox" name="{{ $name }}" value="1" @checked(filter_var($setting->value, FILTER_VALIDATE_BOOLEAN))>
            <span>{{ $label }}</span>
          </label>
          @if ($setting->hint)
            <span class="small muted" style="display:block; margin:-8px 0 12px 26px;">{{ $setting->hint }}</span>
          @endif
        @else
          <label class="field">
            <span class="lab">{{ $label }}</span>
            @if ($setting->type === 'text')
              <textarea name="{{ $name }}" rows="3">{{ $setting->value }}</textarea>
            @else
              <input type="text" name="{{ $name }}" value="{{ $setting->value }}">
            @endif
            @if ($setting->hint)<span class="help">{{ $setting->hint }}</span>@endif
            <span class="mono small muted" style="display:block; margin-top:3px;">{{ $setting->key }}</span>
          </label>
        @endif
        @if ($setting->revisions_exists)
          <button class="btn ghost small" type="submit" form="restore-setting-{{ $setting->id }}">Restore previous value</button>
        @endif
      @endforeach

      <button class="btn" type="submit">Save {{ strtolower($groups[$group]) }} settings</button>
    </form>
  @endif
</div>

@foreach ($settings as $setting)
  @if ($setting->revisions_exists)
    <form id="restore-setting-{{ $setting->id }}" method="POST" action="{{ route('admin.settings.restore', $setting) }}"
          onsubmit="return confirm('Restore the previous value for this setting?');">
      @csrf
    </form>
  @endif
@endforeach
@endsection
