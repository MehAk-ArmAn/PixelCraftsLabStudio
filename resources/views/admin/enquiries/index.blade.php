@extends('admin.layouts.app')
@section('title', 'Enquiries')

@section('content')
<div class="stats">
  <a class="stat" href="{{ route('admin.enquiries.index') }}">
    <div class="n">{{ $counts['all'] }}</div><div class="l">All enquiries</div>
  </a>
  <a class="stat {{ $counts['unread'] ? 'alert' : '' }}" href="{{ route('admin.enquiries.index', ['unread' => 1]) }}">
    <div class="n">{{ $counts['unread'] }}</div><div class="l">Unread</div>
  </a>
  <a class="stat" href="{{ route('admin.enquiries.index', ['marketing' => 1]) }}">
    <div class="n">{{ $counts['marketing'] }}</div><div class="l">Marketing enquiries</div>
  </a>
</div>

<div class="card">
  <form method="GET" class="row" style="margin-bottom:14px;">
    <input type="search" name="q" value="{{ $q }}" placeholder="Search name, email, brief" style="max-width:260px;">
    <select name="status" style="max-width:160px;">
      <option value="">Any status</option>
      @foreach (\App\Models\ContactSubmission::STATUSES as $option)
        <option value="{{ $option }}" @selected($status === $option)>{{ \Illuminate\Support\Str::headline($option) }}</option>
      @endforeach
    </select>
    <button class="btn ghost small" type="submit">Filter</button>
    <a class="btn ghost small" href="{{ route('admin.enquiries.index') }}">Reset</a>
  </form>

  @if ($enquiries->isEmpty())
    <p class="empty">No enquiries match.</p>
  @else
    <div class="table-wrap">
      <table>
        <thead>
          <tr><th>From</th><th>Looking for</th><th>Status</th><th>Received</th><th></th></tr>
        </thead>
        <tbody>
          @foreach ($enquiries as $enquiry)
            <tr>
              <td>
                <a href="{{ route('admin.enquiries.show', $enquiry) }}">
                  <strong>{{ $enquiry->name }}</strong>
                </a>
                @unless ($enquiry->read_at)<span class="badge hot">New</span>@endunless
                <div class="small muted">{{ $enquiry->email }}</div>
              </td>
              <td class="small">
                {{ $enquiry->service ?: $enquiry->build_type ?: '—' }}
                @if ($enquiry->is_marketing_enquiry)<span class="badge on">Marketing</span>@endif
              </td>
              <td><span class="badge">{{ $enquiry->statusLabel() }}</span></td>
              <td class="small muted nowrap">{{ $enquiry->created_at->format('j M Y, H:i') }}</td>
              <td class="actions"><a class="btn ghost small" href="{{ route('admin.enquiries.show', $enquiry) }}">Open</a></td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>

    {{ $enquiries->links() }}
  @endif
</div>
@endsection
