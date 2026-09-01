@extends('admin.layouts.app')
@section('title', $title)

@section('actions')
  <a class="btn small" href="{{ route('admin.'.$routeBase.'.create') }}">New {{ strtolower($singular) }}</a>
@endsection

@section('content')
  @if ($intro)
    <p class="muted" style="margin-top:0; max-width:70ch;">{{ $intro }}</p>
  @endif

  <div class="card">
    <form method="GET" class="row" style="margin-bottom:14px;">
      <input type="search" name="q" value="{{ $q }}" placeholder="Search {{ strtolower($title) }}" style="max-width:280px;">
      <button class="btn ghost small" type="submit">Search</button>
      @if ($q)
        <a class="btn ghost small" href="{{ route('admin.'.$routeBase.'.index') }}">Clear</a>
      @endif
      <span class="spacer" style="margin-left:auto;"></span>
      <span class="small muted">{{ $records->total() }} total</span>
    </form>

    @if ($records->isEmpty())
      <p class="empty">Nothing here yet. <a href="{{ route('admin.'.$routeBase.'.create') }}">Create the first one</a>.</p>
    @else
      <form method="POST" action="{{ route('admin.'.$routeBase.'.reorder') }}">
        @csrf
        <div class="table-wrap">
          <table>
            <thead>
              <tr>
                @if ($ordering)<th style="width:70px;">Order</th>@endif
                @foreach ($columns as $column)
                  <th>{{ $column['label'] }}</th>
                @endforeach
                <th></th>
              </tr>
            </thead>
            <tbody>
              @foreach ($records as $record)
                <tr>
                  @if ($ordering)
                    <td>
                      <input type="number" name="order[{{ $record->getKey() }}]"
                             value="{{ $record->sort_order }}" min="0" style="width:64px; padding:5px 7px;">
                    </td>
                  @endif

                  @foreach ($columns as $column)
                    @php $value = data_get($record, $column['key']); @endphp
                    <td>
                      @if (($column['type'] ?? '') === 'bool')
                        <span class="badge {{ $value ? 'on' : 'off' }}">{{ $value ? 'Yes' : 'No' }}</span>
                      @elseif (($column['type'] ?? '') === 'badge')
                        <span class="badge">{{ \Illuminate\Support\Str::headline((string) $value) }}</span>
                      @elseif ($loop->first)
                        <a href="{{ route('admin.'.$routeBase.'.edit', $record) }}"><strong>{{ $value }}</strong></a>
                      @else
                        {{ \Illuminate\Support\Str::limit((string) $value, 60) ?: '—' }}
                      @endif
                    </td>
                  @endforeach

                  <td class="actions">
                    <a class="btn ghost small" href="{{ route('admin.'.$routeBase.'.edit', $record) }}">Edit</a>
                    <form class="inline" method="POST" action="{{ route('admin.'.$routeBase.'.destroy', $record) }}"
                          onsubmit="return confirm('Delete this {{ strtolower($singular) }}? This cannot be undone.');">
                      @csrf @method('DELETE')
                      <button class="btn danger small" type="submit">Delete</button>
                    </form>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>

        @if ($ordering)
          <div class="row" style="margin-top:12px;">
            <button class="btn ghost small" type="submit">Save order</button>
            <span class="small muted">Lower numbers appear first.</span>
          </div>
        @endif
      </form>

      {{ $records->links() }}
    @endif
  </div>
@endsection
