<div class="card">
  <h3>Metrics</h3>
  <p class="sub">Only publish figures you can stand behind. Nothing appears publicly until it is added here.</p>

  @if ($extra['metrics']->isEmpty())
    <p class="small muted">No metrics recorded.</p>
  @else
    <ul class="list-plain">
      @foreach ($extra['metrics'] as $metric)
        <li class="row" style="align-items:baseline;">
          <strong>{{ $metric->metric_value }}</strong>
          <span>{{ $metric->metric_label }}</span>
          @if ($metric->metric_context)<span class="small muted">{{ $metric->metric_context }}</span>@endif
          <span style="margin-left:auto;"></span>
          <button type="button" class="btn danger small"
                  onclick="if (confirm('Remove this metric?')) document.getElementById('delm{{ $metric->id }}').submit();">Remove</button>
        </li>
      @endforeach
    </ul>
  @endif

  <div style="margin-top:12px; padding-top:12px; border-top:1px solid var(--line-soft);">
    <label class="field"><span class="lab">Label</span><input type="text" form="metric-form" name="metric_label" placeholder="Engagement growth"></label>
    <label class="field"><span class="lab">Value</span><input type="text" form="metric-form" name="metric_value" placeholder="+38%"></label>
    <label class="field"><span class="lab">Context</span><input type="text" form="metric-form" name="metric_context" placeholder="Instagram, Jan–Mar"></label>
    <button class="btn ghost small" type="submit" form="metric-form">Add metric</button>
  </div>
</div>
