@extends('admin.layouts.app')
@section('title', 'Home · Featured Projects')

@section('actions')
  <a class="btn ghost small" href="{{ route('admin.settings.edit', 'home') }}">Landing experience</a>
@endsection

@section('content')
  <p class="muted" style="max-width:76ch;">
    Homepage highlights are independent from the Work portfolio’s Featured flag and order.
    Exactly one selected, visible project must be Primary.
  </p>

  <form method="POST" action="{{ route('admin.home.featured-projects.update') }}">
    @csrf
    @method('PUT')

    <div class="card">
      <div class="table-wrap">
        <table>
          <thead>
            <tr>
              <th>Use</th>
              <th>Primary</th>
              <th>Project</th>
              <th>Visible</th>
              <th>Order</th>
              <th>Display treatment</th>
              <th>Media source</th>
              <th>Badge</th>
              <th>CTA label</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($projects as $index => $project)
              @php
                $feature = $features->get($project->id);
                $selected = old("featured.$index.selected", (bool) $feature);
                $enabled = old("featured.$index.enabled", $feature?->enabled ?? true);
              @endphp
              <tr>
                <td>
                  <input type="hidden" name="featured[{{ $index }}][project_id]" value="{{ $project->id }}">
                  <input type="hidden" name="featured[{{ $index }}][selected]" value="0">
                  <input type="checkbox" name="featured[{{ $index }}][selected]" value="1" @checked($selected) aria-label="Use {{ $project->name }} on Home">
                </td>
                <td>
                  <input type="radio" name="primary_project_id" value="{{ $project->id }}" @checked((int) old('primary_project_id', $features->firstWhere('is_primary', true)?->project_id) === $project->id) aria-label="Make {{ $project->name }} Primary">
                </td>
                <td>
                  <strong>{{ $project->name }}</strong>
                  <div class="small muted">{{ $project->isLive() ? 'Published' : 'Draft or hidden' }} · {{ $project->category }}</div>
                </td>
                <td>
                  <input type="hidden" name="featured[{{ $index }}][enabled]" value="0">
                  <input type="checkbox" name="featured[{{ $index }}][enabled]" value="1" @checked($enabled) aria-label="Show {{ $project->name }}">
                </td>
                <td><input type="number" name="featured[{{ $index }}][sort_order]" value="{{ old("featured.$index.sort_order", $feature?->sort_order ?? (($index + 1) * 10)) }}" min="0" max="100000" style="width:76px;"></td>
                <td>
                  <select name="featured[{{ $index }}][display_mode]">
                    @foreach ($displayModes as $value => $label)
                      <option value="{{ $value }}" @selected(old("featured.$index.display_mode", $feature?->display_mode ?? 'auto') === $value)>{{ $label }}</option>
                    @endforeach
                  </select>
                </td>
                <td>
                  <select name="featured[{{ $index }}][media_mode]">
                    @foreach ($mediaModes as $value => $label)
                      <option value="{{ $value }}" @selected(old("featured.$index.media_mode", $feature?->media_mode ?? 'auto') === $value)>{{ $label }}</option>
                    @endforeach
                  </select>
                </td>
                <td><input type="text" name="featured[{{ $index }}][badge_text]" value="{{ old("featured.$index.badge_text", $feature?->badge_text) }}" maxlength="120"></td>
                <td><input type="text" name="featured[{{ $index }}][cta_label]" value="{{ old("featured.$index.cta_label", $feature?->cta_label) }}" maxlength="120"></td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>

      <div class="row" style="margin-top:16px;">
        <button class="btn" type="submit">Save featured projects</button>
        <span class="small muted">Lower order numbers appear first. Saving automatically demotes the previous Primary.</span>
      </div>
    </div>
  </form>
@endsection
