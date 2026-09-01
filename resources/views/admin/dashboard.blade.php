@extends('admin.layouts.app')
@section('title', 'Dashboard')

@section('content')
<div class="stats">
  @foreach ($cards as $card)
    <a class="stat {{ $card['tone'] ?? '' }}" href="{{ $card['href'] }}">
      <div class="n">{{ $card['value'] }}</div>
      <div class="l">{{ $card['label'] }}</div>
    </a>
  @endforeach
</div>

<div class="card">
  <h2>Quick actions</h2>
  <div class="row">
    <a class="btn small" href="{{ route('admin.projects.create') }}">Add project</a>
    <a class="btn ghost small" href="{{ route('admin.marketing-services.create') }}">Add marketing service</a>
    <a class="btn ghost small" href="{{ route('admin.team.create') }}">Add team member</a>
    <a class="btn ghost small" href="{{ route('admin.testimonials.create') }}">Add testimonial</a>
    <a class="btn ghost small" href="{{ route('admin.media.index') }}">Upload media</a>
    <a class="btn ghost small" href="{{ route('admin.pages.index') }}">Edit page copy</a>
    <a class="btn ghost small" href="{{ route('admin.settings.edit') }}">Site settings</a>
  </div>
</div>

<div class="grid-2">
  <div class="card">
    <h2>Recent enquiries</h2>
    @forelse ($recentEnquiries as $enquiry)
      <div class="row" style="align-items:baseline; padding:7px 0; border-bottom:1px solid var(--line-soft);">
        <a href="{{ route('admin.enquiries.show', $enquiry) }}"><strong>{{ $enquiry->name }}</strong></a>
        @unless ($enquiry->read_at)<span class="badge hot">New</span>@endunless
        @if ($enquiry->is_marketing_enquiry)<span class="badge on">Marketing</span>@endif
        <span style="margin-left:auto;"></span>
        <span class="small muted nowrap">{{ $enquiry->created_at->diffForHumans() }}</span>
      </div>
    @empty
      <p class="small muted">No enquiries yet.</p>
    @endforelse
  </div>

  <div class="card">
    <h2>Needs attention</h2>
    @forelse ($needsAttention as $item)
      <div style="padding:7px 0; border-bottom:1px solid var(--line-soft);">
        <a href="{{ $item['href'] }}">{{ $item['label'] }}</a>
      </div>
    @empty
      <p class="small muted">Everything is published and read. Nothing needs you right now.</p>
    @endforelse
  </div>

  <div class="card">
    <h2>Recently updated content</h2>
    @forelse ($recentlyUpdated as $project)
      <div class="row" style="align-items:baseline; padding:7px 0; border-bottom:1px solid var(--line-soft);">
        <a href="{{ route('admin.projects.edit', $project) }}">{{ $project->name }}</a>
        <span class="badge {{ $project->is_published ? 'on' : 'off' }}">{{ $project->is_published ? 'Live' : 'Draft' }}</span>
        <span style="margin-left:auto;"></span>
        <span class="small muted nowrap">{{ $project->updated_at->diffForHumans() }}</span>
      </div>
    @empty
      <p class="small muted">No projects yet.</p>
    @endforelse
  </div>

  <div class="card">
    <h2>Recent admin activity</h2>
    @forelse ($recentActivity as $log)
      <div style="padding:7px 0; border-bottom:1px solid var(--line-soft);">
        <div class="small">{{ $log->description ?? $log->action }}</div>
        <div class="small muted">{{ $log->user_name }} · {{ $log->created_at->diffForHumans() }}</div>
      </div>
    @empty
      <p class="small muted">No activity recorded yet.</p>
    @endforelse
  </div>
</div>
@endsection
