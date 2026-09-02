@extends('admin.layouts.app')
@section('title', 'Marketing overview')

@section('content')
<div class="stats">
  @foreach ($cards as $card)
    <a class="stat" href="{{ $card['href'] }}">
      <div class="n">{{ $card['value'] }}</div>
      <div class="l">{{ $card['label'] }}</div>
    </a>
  @endforeach
</div>

<div class="grid-2">
  <div class="card">
    <h2>Marketing services</h2>
    <p class="sub">Top-level capabilities and the sub-services beneath them.</p>
    @forelse ($services as $service)
      <div style="padding:8px 0; border-bottom:1px solid var(--line-soft);">
        <div class="row" style="align-items:baseline;">
          <a href="{{ route('admin.marketing-services.edit', $service) }}"><strong>{{ $service->title }}</strong></a>
          <span class="badge {{ $service->is_published ? 'on' : 'off' }}">{{ $service->is_published ? 'Published' : 'Hidden' }}</span>
          <span style="margin-left:auto;"></span>
          <span class="small muted">{{ $service->children->count() }} sub-services</span>
        </div>
        @if ($service->children->isNotEmpty())
          <div class="small muted" style="margin-top:4px;">
            {{ $service->children->take(8)->pluck('title')->implode(' · ') }}@if ($service->children->count() > 8) …@endif
          </div>
        @endif
      </div>
    @empty
      <p class="small muted">No marketing services yet.</p>
    @endforelse
    <div class="row" style="margin-top:12px;">
      <a class="btn ghost small" href="{{ route('admin.marketing-services.index') }}">Manage all</a>
    </div>
  </div>

  <div class="card">
    <h2>Growth plans</h2>
    @forelse ($plans as $plan)
      <div class="row" style="align-items:baseline; padding:7px 0; border-bottom:1px solid var(--line-soft);">
        <a href="{{ route('admin.growth-plans.edit', $plan) }}"><strong>{{ $plan->name }}</strong></a>
        <span class="badge {{ $plan->is_published ? 'on' : 'off' }}">{{ $plan->is_published ? 'Live' : 'Hidden' }}</span>
        <span style="margin-left:auto;"></span>
        <span class="small muted">{{ $plan->items->count() }} deliverables · {{ $plan->priceDisplay() }}</span>
      </div>
    @empty
      <p class="small muted">No growth plans yet.</p>
    @endforelse
  </div>

  <div class="card">
    <h2>Marketing case studies</h2>
    @forelse ($caseStudies as $project)
      <div class="row" style="align-items:baseline; padding:7px 0; border-bottom:1px solid var(--line-soft);">
        <a href="{{ route('admin.projects.edit', $project) }}">{{ $project->name }}</a>
        <span class="badge {{ $project->is_published ? 'on' : 'off' }}">{{ $project->is_published ? 'Live' : 'Draft' }}</span>
      </div>
    @empty
      <p class="small muted">
        None yet. Mark a project as a marketing case study to publish one — and only with results the client has approved.
      </p>
    @endforelse
  </div>

  <div class="card">
    <h2>Campaigns</h2>
    @forelse ($campaigns as $campaign)
      <div class="row" style="align-items:baseline; padding:7px 0; border-bottom:1px solid var(--line-soft);">
        <a href="{{ route('admin.campaigns.edit', $campaign) }}">{{ $campaign->name }}</a>
        <span class="badge">{{ ucfirst($campaign->status) }}</span>
      </div>
    @empty
      <p class="small muted">No campaigns recorded.</p>
    @endforelse
  </div>

  @if ($canManageAdministration)
  <div class="card">
    <h2>Marketing enquiries</h2>
    @forelse ($enquiries as $enquiry)
      <div class="row" style="align-items:baseline; padding:7px 0; border-bottom:1px solid var(--line-soft);">
        <a href="{{ route('admin.enquiries.show', $enquiry) }}">{{ $enquiry->name }}</a>
        <span class="small muted">{{ $enquiry->service ?: $enquiry->build_type }}</span>
        <span style="margin-left:auto;"></span>
        <span class="small muted nowrap">{{ $enquiry->created_at->diffForHumans() }}</span>
      </div>
    @empty
      <p class="small muted">No marketing enquiries yet.</p>
    @endforelse
  </div>
  @endif

  <div class="card">
    <h2>Recent marketing updates</h2>
    @forelse ($recent as $log)
      <div style="padding:7px 0; border-bottom:1px solid var(--line-soft);">
        <div class="small">{{ $log->description ?? $log->action }}</div>
        <div class="small muted">{{ $log->user_name }} · {{ $log->created_at->diffForHumans() }}</div>
      </div>
    @empty
      <p class="small muted">No marketing content has been edited yet.</p>
    @endforelse
  </div>
</div>
@endsection
