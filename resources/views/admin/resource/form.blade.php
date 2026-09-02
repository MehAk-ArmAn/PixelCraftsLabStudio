@extends('admin.layouts.app')
@section('title', ($mode === 'create' ? 'New ' : 'Edit ').strtolower($singular))

@section('actions')
  <a class="btn ghost small" href="{{ route('admin.'.$routeBase.'.index') }}">Back to {{ strtolower($title) }}</a>
@endsection

@section('content')
@php
    $sections = collect($schema)->groupBy(fn ($f) => $f['section'] ?? 'Details');
@endphp

<form method="POST"
      action="{{ $mode === 'create' ? route('admin.'.$routeBase.'.store') : route('admin.'.$routeBase.'.update', $record) }}">
  @csrf
  @if ($mode !== 'create') @method('PUT') @endif

  <div class="two-col">
    <div>
      @if ($routeBase === 'packages')
        @include('admin.packages.file')
      @else
        @foreach ($sections as $sectionName => $fields)
          <div class="card">
            <h2>{{ $sectionName }}</h2>
            @foreach ($fields as $field)
              @include('admin.partials.field', ['field' => $field, 'record' => $record, 'extra' => $extra])
            @endforeach
          </div>
        @endforeach
      @endif
    </div>

    <div>
      <div class="card">
        <div class="row">
          <button class="btn" type="submit">{{ $mode === 'create' ? 'Create' : 'Save changes' }}</button>
        </div>
        @if ($mode !== 'create')
          <p class="small muted" style="margin:12px 0 0;">
            Last updated {{ $record->updated_at?->diffForHumans() ?? 'never' }}.
            Changes appear on the public site immediately.
          </p>
        @endif
      </div>

      @if ($mode !== 'create' && $routeBase === 'projects')
        @include('admin.resource.partials.project-extras')
      @endif

      @if ($mode !== 'create' && $routeBase === 'growth-plans')
        @include('admin.resource.partials.plan-items')
      @endif

      @if ($mode !== 'create' && $routeBase === 'packages')
        @include('admin.packages.preview')
        @include('admin.resource.partials.package-items')
      @endif
    </div>
  </div>
</form>

@if ($mode !== 'create' && $routeBase === 'projects')
  {{-- Kept outside the main form: HTML forms cannot nest. --}}
  <form id="metric-form" method="POST" action="{{ route('admin.projects.metrics.store', $record) }}">@csrf</form>
  @foreach ($extra['metrics'] as $metric)
    <form id="delm{{ $metric->id }}" method="POST" action="{{ route('admin.projects.metrics.destroy', [$record, $metric]) }}">
      @csrf @method('DELETE')
    </form>
  @endforeach
@endif

@if ($mode !== 'create' && $routeBase === 'packages')
  <form id="package-item-form" method="POST" action="{{ route('admin.packages.items.store', $record) }}">@csrf</form>
  @foreach ($extra['packageItems'] as $item)
    <form id="pkgitem{{ $item->id }}" method="POST" action="{{ route('admin.packages.items.update', [$record, $item]) }}">
      @csrf @method('PUT')
    </form>
    <form id="delpkg{{ $item->id }}" method="POST" action="{{ route('admin.packages.items.destroy', [$record, $item]) }}">
      @csrf @method('DELETE')
    </form>
  @endforeach
@endif

@if ($mode !== 'create' && $routeBase === 'growth-plans')
  <form id="plan-item-form" method="POST" action="{{ route('admin.growth-plans.items.store', $record) }}">@csrf</form>
  @foreach ($extra['planItems'] as $item)
    <form id="delpi{{ $item->id }}" method="POST" action="{{ route('admin.growth-plans.items.destroy', [$record, $item]) }}">
      @csrf @method('DELETE')
    </form>
  @endforeach
@endif

@if ($mode !== 'create')
  <div class="card">
    <h3>Danger zone</h3>
    <div class="row">
      @if ($routeBase === 'projects')
        @if ($record->revisions()->exists())
          <form class="inline" method="POST" action="{{ route('admin.projects.restore', $record) }}"
                onsubmit="return confirm('Restore this project to its previous saved version?');">
            @csrf
            <button class="btn ghost small" type="submit">Restore previous version</button>
          </form>
        @endif
        <form class="inline" method="POST" action="{{ route('admin.projects.duplicate', $record) }}">
          @csrf
          <button class="btn ghost small" type="submit">Duplicate as draft</button>
        </form>
        <form class="inline" method="POST" action="{{ route('admin.projects.toggle-publish', $record) }}">
          @csrf
          <button class="btn ghost small" type="submit">{{ $record->is_published ? 'Unpublish' : 'Publish' }}</button>
        </form>
      @endif
      <form class="inline" method="POST" action="{{ route('admin.'.$routeBase.'.destroy', $record) }}"
            onsubmit="return confirm('Delete this {{ strtolower($singular) }}? This cannot be undone.');">
        @csrf @method('DELETE')
        <button class="btn danger small" type="submit">Delete {{ strtolower($singular) }}</button>
      </form>
    </div>
  </div>
@endif
@endsection
