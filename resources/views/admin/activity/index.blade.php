@extends('admin.layouts.app')
@section('title', 'Activity log')

@section('content')
<div class="card">
  <form method="GET" class="row" style="margin-bottom:14px;">
    <input type="search" name="q" value="{{ $q }}" placeholder="Search descriptions or users" style="max-width:280px;">
    <select name="action" style="max-width:170px;">
      <option value="">Any action</option>
      @foreach ($actions as $option)
        <option value="{{ $option }}" @selected($action === $option)>{{ \Illuminate\Support\Str::headline($option) }}</option>
      @endforeach
    </select>
    <button class="btn ghost small" type="submit">Filter</button>
    <a class="btn ghost small" href="{{ route('admin.activity.index') }}">Reset</a>
  </form>

  @if ($logs->isEmpty())
    <p class="empty">Nothing logged yet.</p>
  @else
    <div class="table-wrap">
      <table>
        <thead>
          <tr><th>When</th><th>Who</th><th>Action</th><th>Resource</th><th>Description</th><th>Changes</th></tr>
        </thead>
        <tbody>
          @foreach ($logs as $log)
            <tr>
              <td class="small muted nowrap">{{ $log->created_at->format('j M, H:i') }}</td>
              <td class="small">{{ $log->user_name }}</td>
              <td><span class="badge">{{ \Illuminate\Support\Str::headline($log->action) }}</span></td>
              <td class="small">{{ $log->resource_label ?: '—' }}</td>
              <td class="small">{{ $log->description }}</td>
              <td>
                @if ($log->changes)
                  <details>
                    <summary class="small" style="cursor:pointer;">{{ count($log->changes['after'] ?? []) }} field(s)</summary>
                    <table style="margin-top:6px;">
                      <tbody>
                        @foreach (($log->changes['after'] ?? []) as $field => $newValue)
                          <tr>
                            <th class="small">{{ $field }}</th>
                            <td class="small muted">{{ \Illuminate\Support\Str::limit((string) ($log->changes['before'][$field] ?? '—'), 50) }}</td>
                            <td class="small">→ {{ \Illuminate\Support\Str::limit((string) $newValue, 50) }}</td>
                          </tr>
                        @endforeach
                      </tbody>
                    </table>
                  </details>
                @else
                  <span class="small muted">—</span>
                @endif
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>

    {{ $logs->links() }}
  @endif
</div>
@endsection
