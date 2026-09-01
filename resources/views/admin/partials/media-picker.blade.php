@php
    $multiple = $multiple ?? false;
    $picked = $multiple ? (array) ($value ?? []) : [$value];
    $picked = array_values(array_filter($picked, fn ($v) => filled($v)));
    $uid = 'mp_'.\Illuminate\Support\Str::random(6);
@endphp

<div class="field" style="margin-bottom:14px;">
  <span class="lab" style="display:block; font-size:12px; font-weight:600; margin-bottom:6px;">{{ $label }}</span>

  <div class="picker" data-picker="{{ $uid }}" data-multiple="{{ $multiple ? '1' : '0' }}" data-name="{{ $name }}">
    <div class="preview" data-preview>
      @forelse ($picked as $ref)
        <img src="{{ \App\Support\MediaResolver::url($ref) }}" alt="">
      @empty
        Nothing selected
      @endforelse
    </div>

    <div data-inputs>
      @if ($multiple)
        @foreach ($picked as $ref)
          <input type="hidden" name="{{ $name }}[]" value="{{ $ref }}">
        @endforeach
      @else
        <input type="hidden" name="{{ $name }}" value="{{ $picked[0] ?? '' }}">
      @endif
    </div>

    <div class="row" style="margin-top:8px;">
      <button type="button" class="btn ghost small" data-open>Choose from library</button>
      <button type="button" class="btn ghost small" data-clear>Clear</button>
      <input type="text" data-manual placeholder="or paste a path e.g. assets/pcl-logo.png"
             value="{{ $multiple ? '' : ($picked[0] ?? '') }}" style="flex:1; min-width:180px;">
    </div>
  </div>

  @if (! empty($help))<span class="help">{{ $help }}</span>@endif
  @if (! empty($error))<span class="err">{{ $error }}</span>@endif
</div>

@once
@push('scripts')
<script>
(function () {
  const BROWSE = @json(route('admin.media.browse'));
  let cache = null;

  async function items() {
    if (cache) return cache;
    const res = await fetch(BROWSE, { headers: { 'Accept': 'application/json' } });
    cache = (await res.json()).items || [];
    return cache;
  }

  function refs(picker) {
    return Array.from(picker.querySelectorAll('[data-inputs] input')).map(i => i.value).filter(Boolean);
  }

  function write(picker, values) {
    const name = picker.dataset.name;
    const multiple = picker.dataset.multiple === '1';
    const inputs = picker.querySelector('[data-inputs]');
    const preview = picker.querySelector('[data-preview]');

    inputs.innerHTML = '';
    preview.innerHTML = '';

    const list = multiple ? values : values.slice(0, 1);

    if (!list.length) {
      preview.textContent = 'Nothing selected';
      if (!multiple) {
        const input = document.createElement('input');
        input.type = 'hidden'; input.name = name; input.value = '';
        inputs.appendChild(input);
      }
      return;
    }

    list.forEach(function (entry) {
      const input = document.createElement('input');
      input.type = 'hidden';
      input.name = multiple ? name + '[]' : name;
      input.value = entry.reference || entry;
      inputs.appendChild(input);

      const img = document.createElement('img');
      img.src = entry.url || entry;
      img.alt = '';
      preview.appendChild(img);
    });
  }

  async function openModal(picker) {
    const list = await items();
    const multiple = picker.dataset.multiple === '1';

    const modal = document.createElement('div');
    modal.className = 'picker-modal';
    modal.innerHTML =
      '<div class="inner">' +
        '<div class="row" style="margin-bottom:12px;">' +
          '<strong style="font-size:15px;">Media library</strong>' +
          '<span style="margin-left:auto;"></span>' +
          '<input type="search" placeholder="Search" data-search style="max-width:200px;">' +
          '<button type="button" class="btn ghost small" data-close>Close</button>' +
        '</div>' +
        '<div class="media-grid" data-grid></div>' +
      '</div>';

    document.body.appendChild(modal);
    const grid = modal.querySelector('[data-grid]');

    function paint(filter) {
      grid.innerHTML = '';
      const term = (filter || '').toLowerCase();

      list.filter(m => !term || (m.title || '').toLowerCase().includes(term))
          .forEach(function (m) {
        const cell = document.createElement('button');
        cell.type = 'button';
        cell.className = 'media-item';
        cell.style.cssText = 'cursor:pointer; text-align:left; padding:0; font:inherit;';
        cell.innerHTML =
          '<div class="thumb">' +
            (String(m.mime || '').startsWith('video/')
              ? '<video src="' + m.url + '"></video>'
              : '<img src="' + m.url + '" alt="">') +
          '</div>' +
          '<div class="meta"><b></b><small>' + (m.legacy ? 'existing asset' : 'upload') + '</small></div>';
        cell.querySelector('b').textContent = m.title || m.reference;

        cell.addEventListener('click', function () {
          const next = multiple ? refs(picker).map(r => ({ reference: r, url: r })) : [];
          next.push(m);
          write(picker, next);
          const manual = picker.querySelector('[data-manual]');
          if (manual && !multiple) manual.value = m.reference;
          modal.remove();
        });

        grid.appendChild(cell);
      });

      if (!grid.children.length) {
        grid.innerHTML = '<p class="empty">Nothing in the library yet. Upload files under Media.</p>';
      }
    }

    paint('');
    modal.querySelector('[data-search]').addEventListener('input', e => paint(e.target.value));
    modal.querySelector('[data-close]').addEventListener('click', () => modal.remove());
    modal.addEventListener('click', e => { if (e.target === modal) modal.remove(); });
  }

  document.addEventListener('click', function (e) {
    const openBtn = e.target.closest('[data-picker] [data-open]');
    if (openBtn) { openModal(openBtn.closest('[data-picker]')); return; }

    const clearBtn = e.target.closest('[data-picker] [data-clear]');
    if (clearBtn) {
      const picker = clearBtn.closest('[data-picker]');
      write(picker, []);
      const manual = picker.querySelector('[data-manual]');
      if (manual) manual.value = '';
    }
  });

  // Legacy path entry — admins can still type a path that is not in the library.
  document.addEventListener('input', function (e) {
    const manual = e.target.closest('[data-picker] [data-manual]');
    if (!manual) return;
    const picker = manual.closest('[data-picker]');
    if (picker.dataset.multiple === '1') return;
    write(picker, manual.value ? [{ reference: manual.value, url: '/' + manual.value.replace(/^\/+/, '') }] : []);
  });
})();
</script>
@endpush
@endonce
