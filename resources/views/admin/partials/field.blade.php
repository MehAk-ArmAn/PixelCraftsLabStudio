@php
    /** @var array $field */
    $name = $field['name'];
    $type = $field['type'] ?? 'text';
    $label = $field['label'] ?? \Illuminate\Support\Str::headline($name);
    $help = $field['help'] ?? null;
    $required = $field['required'] ?? false;
    $value = old($name, data_get($record ?? null, $name));
    $error = $errors->first($name);
    $id = 'f_'.$name;

    $options = $field['options'] ?? [];
    if (isset($field['optionsFrom'])) {
        $options = ($extra[$field['optionsFrom']] ?? []);
    }
@endphp

@if ($type === 'checkbox')
  <label class="check" for="{{ $id }}">
    <input type="hidden" name="{{ $name }}" value="0">
    <input type="checkbox" id="{{ $id }}" name="{{ $name }}" value="1" @checked((bool) $value)>
    <span>{{ $label }}</span>
  </label>
  @if ($help)<span class="help small muted" style="display:block; margin:-8px 0 12px 26px;">{{ $help }}</span>@endif

@elseif ($type === 'checkboxes')
  @php $selected = old($name, $extra['selectedChannels'] ?? []); @endphp
  <div class="field" style="margin-bottom:14px;">
    <span class="lab" style="display:block; font-size:12px; font-weight:600; margin-bottom:6px;">{{ $label }}</span>
    @if ($options === [])
      <p class="small muted" style="margin:0;">Nothing to choose yet.</p>
    @else
      <div class="checks">
        @foreach ($options as $optValue => $optLabel)
          <label class="check">
            <input type="checkbox" name="{{ $name }}[]" value="{{ $optValue }}"
                   @checked(in_array((int) $optValue, array_map('intval', (array) $selected), true))>
            <span>{{ $optLabel }}</span>
          </label>
        @endforeach
      </div>
    @endif
    @if ($help)<span class="help">{{ $help }}</span>@endif
  </div>

@elseif ($type === 'media' || $type === 'media-multi')
  @include('admin.partials.media-picker', [
      'name' => $name,
      'label' => $label,
      'help' => $help,
      'value' => $value,
      'multiple' => $type === 'media-multi',
      'error' => $error,
  ])

@else
  <label class="field" for="{{ $id }}">
    <span class="lab">{{ $label }}@if ($required) <span style="color:#B8380A;">*</span>@endif</span>

    @if ($type === 'textarea')
      <textarea id="{{ $id }}" name="{{ $name }}" rows="{{ $field['rows'] ?? 3 }}">{{ $value }}</textarea>

    @elseif ($type === 'select')
      <select id="{{ $id }}" name="{{ $name }}">
        @unless ($required)<option value="">—</option>@endunless
        @foreach ($options as $optValue => $optLabel)
          <option value="{{ $optValue }}" @selected((string) $value === (string) $optValue)>{{ $optLabel }}</option>
        @endforeach
      </select>

    @elseif ($type === 'color')
      <span class="row">
        <input type="color" id="{{ $id }}" value="{{ $value ?: '#5B2394' }}"
               oninput="document.getElementById('{{ $id }}_t').value = this.value">
        <input type="text" id="{{ $id }}_t" name="{{ $name }}" value="{{ $value }}"
               placeholder="#5B2394" style="max-width:150px;">
      </span>

    @elseif ($type === 'number')
      <input type="number" id="{{ $id }}" name="{{ $name }}" value="{{ $value }}">

    @elseif ($type === 'date')
      <input type="date" id="{{ $id }}" name="{{ $name }}"
             value="{{ $value instanceof \DateTimeInterface ? $value->format('Y-m-d') : $value }}">

    @elseif ($type === 'datetime')
      <input type="datetime-local" id="{{ $id }}" name="{{ $name }}"
             value="{{ $value instanceof \DateTimeInterface ? $value->format('Y-m-d\TH:i') : $value }}">

    @else
      <input type="text" id="{{ $id }}" name="{{ $name }}" value="{{ $value }}"
             @if (! empty($field['datalist'])) list="{{ $id }}_list" @endif>
      @if (! empty($field['datalist']))
        <datalist id="{{ $id }}_list">
          @foreach ($field['datalist'] as $option)<option value="{{ $option }}"></option>@endforeach
        </datalist>
      @endif
    @endif

    @if ($help)<span class="help">{{ $help }}</span>@endif
    @if ($error)<span class="err">{{ $error }}</span>@endif
  </label>
@endif
