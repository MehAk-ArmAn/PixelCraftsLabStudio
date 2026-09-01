<div class="card">
  <h3>Deliverables</h3>

  @if ($extra['planItems']->isEmpty())
    <p class="small muted">No deliverables yet.</p>
  @else
    <ul class="list-plain">
      @foreach ($extra['planItems'] as $item)
        <li>
          <div class="row" style="align-items:baseline;">
            <strong>{{ $item->title }}</strong>
            <span style="margin-left:auto;"></span>
            <button type="button" class="btn danger small"
                    onclick="if (confirm('Remove this deliverable?')) document.getElementById('delpi{{ $item->id }}').submit();">Remove</button>
          </div>
          @if ($item->description)<span class="small muted">{{ $item->description }}</span>@endif
        </li>
      @endforeach
    </ul>
  @endif

  <div style="margin-top:12px; padding-top:12px; border-top:1px solid var(--line-soft);">
    <label class="field"><span class="lab">Title</span><input type="text" form="plan-item-form" name="title"></label>
    <label class="field"><span class="lab">Description</span><textarea form="plan-item-form" name="description" rows="2"></textarea></label>
    <button class="btn ghost small" type="submit" form="plan-item-form">Add deliverable</button>
  </div>
</div>
