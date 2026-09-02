<div class="card">
  <h3>Package items</h3>

  @forelse ($extra['packageItems'] as $item)
    <div style="padding:10px 0; border-bottom:1px solid var(--line-soft);">
      <label class="field">
        <span class="lab">Item</span>
        <input form="pkgitem{{ $item->id }}" type="text" name="text" value="{{ $item->text }}">
      </label>
      <label class="field">
        <span class="lab">Group</span>
        <input form="pkgitem{{ $item->id }}" type="text" name="group" value="{{ $item->group }}">
      </label>
      <div class="row">
        <label class="check">
          <input form="pkgitem{{ $item->id }}" type="checkbox" name="is_included" value="1" @checked($item->is_included)>
          <span>Included</span>
        </label>
        <label class="check">
          <input form="pkgitem{{ $item->id }}" type="checkbox" name="is_highlighted" value="1" @checked($item->is_highlighted)>
          <span>Highlighted</span>
        </label>
        <input form="pkgitem{{ $item->id }}" type="number" name="sort_order" value="{{ $item->sort_order }}" min="0" style="width:80px;">
        <button form="pkgitem{{ $item->id }}" class="btn ghost small" type="submit">Save</button>
        <button class="btn danger small" type="button" onclick="if (confirm('Remove this package item?')) document.getElementById('delpkg{{ $item->id }}').submit();">Remove</button>
      </div>
    </div>
  @empty
    <p class="small muted">No package items yet.</p>
  @endforelse

  <div style="margin-top:12px; padding-top:12px; border-top:1px solid var(--line-soft);">
    <label class="field"><span class="lab">Item</span><input form="package-item-form" type="text" name="text"></label>
    <label class="field"><span class="lab">Group</span><input form="package-item-form" type="text" name="group"></label>
    <button class="btn ghost small" type="submit" form="package-item-form">Add item</button>
  </div>
</div>
