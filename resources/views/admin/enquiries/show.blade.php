@extends('admin.layouts.app')
@section('title', 'Enquiry from '.$enquiry->name)

@section('actions')
  <a class="btn ghost small" href="{{ route('admin.enquiries.index') }}">All enquiries</a>
@endsection

@section('content')
<div class="two-col">
  <div>
    <div class="card">
      <h2>The brief</h2>
      <table>
        <tbody>
          <tr><th style="width:170px;">Name</th><td>{{ $enquiry->name }}</td></tr>
          <tr><th>Email</th><td><a href="mailto:{{ $enquiry->email }}">{{ $enquiry->email }}</a></td></tr>
          @foreach ([
              'Building' => $enquiry->build_type,
              'Service' => $enquiry->service,
              'Scope' => $enquiry->scope,
              'Timing' => $enquiry->timeline,
              'Budget' => $enquiry->budget,
          ] as $label => $value)
            @if ($value)<tr><th>{{ $label }}</th><td>{{ $value }}</td></tr>@endif
          @endforeach
          <tr><th>Received</th><td>{{ $enquiry->created_at->format('j M Y, H:i') }}</td></tr>
        </tbody>
      </table>

      @if ($enquiry->message)
        <div style="margin-top:14px;">
          <span class="section-head">Message</span>
          <p style="white-space:pre-wrap; margin:0;">{{ $enquiry->message }}</p>
        </div>
      @endif
    </div>

    @if ($enquiry->is_marketing_enquiry)
      <div class="card">
        <h2>Marketing detail</h2>
        @php
            $marketing = array_filter([
                'Business / brand' => $enquiry->business_name,
                'Website' => $enquiry->website_url,
                'Social platforms' => $enquiry->social_platforms,
                'Primary goal' => $enquiry->primary_goal,
                'Target audience' => $enquiry->target_audience,
                'Preferred channels' => $enquiry->preferred_channels,
                'Current marketing' => $enquiry->current_marketing,
            ]);
        @endphp
        @if ($marketing === [])
          <p class="small muted">Flagged as a marketing enquiry, but no extra detail was supplied.</p>
        @else
          <table><tbody>
            @foreach ($marketing as $label => $value)
              <tr><th style="width:170px;">{{ $label }}</th><td>{{ $value }}</td></tr>
            @endforeach
          </tbody></table>
        @endif
      </div>
    @endif
  </div>

  <div>
    <div class="card">
      <h2>Manage</h2>
      <form method="POST" action="{{ route('admin.enquiries.update', $enquiry) }}">
        @csrf @method('PUT')
        <label class="field">
          <span class="lab">Status</span>
          <select name="status">
            @foreach (\App\Models\ContactSubmission::STATUSES as $option)
              <option value="{{ $option }}" @selected($enquiry->status === $option)>{{ \Illuminate\Support\Str::headline($option) }}</option>
            @endforeach
          </select>
        </label>
        <label class="field">
          <span class="lab">Internal notes</span>
          <textarea name="admin_notes" rows="5">{{ $enquiry->admin_notes }}</textarea>
        </label>
        <button class="btn" type="submit">Save</button>
      </form>
    </div>

    <div class="card">
      <h3>Actions</h3>
      <div class="row">
        <form class="inline" method="POST" action="{{ route('admin.enquiries.toggle-read', $enquiry) }}">
          @csrf
          <button class="btn ghost small" type="submit">Mark as {{ $enquiry->read_at ? 'unread' : 'read' }}</button>
        </form>
        <a class="btn ghost small" href="mailto:{{ $enquiry->email }}">Reply by email</a>
        <form class="inline" method="POST" action="{{ route('admin.enquiries.destroy', $enquiry) }}"
              onsubmit="return confirm('Delete this enquiry permanently?');">
          @csrf @method('DELETE')
          <button class="btn danger small" type="submit">Delete</button>
        </form>
      </div>
    </div>

    <div class="card">
      <h3>Trace</h3>
      <p class="small muted mono" style="word-break:break-all;">
        IP {{ $enquiry->ip_address ?: '—' }}<br>
        {{ $enquiry->user_agent ?: '—' }}
      </p>
    </div>
  </div>
</div>
@endsection
