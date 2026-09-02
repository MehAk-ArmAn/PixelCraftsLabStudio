@extends('admin.layouts.app')
@section('title', $page->title.' page')

@section('actions')
  <a class="btn ghost small" href="{{ route('admin.pages.index') }}">All pages</a>
@endsection

@section('content')
<div class="card">
  <h2>Page &amp; SEO</h2>
  <form method="POST" action="{{ route('admin.pages.update', $page) }}">
    @csrf @method('PUT')
    <div class="grid-2">
      <div>
        <label class="field"><span class="lab">Title</span><input type="text" name="title" value="{{ old('title', $page->title) }}" required></label>
        <label class="check">
          <input type="hidden" name="is_published" value="0">
          <input type="checkbox" name="is_published" value="1" @checked($page->is_published)>
          <span>Published</span>
        </label>
        <label class="check">
          <input type="hidden" name="robots_index" value="0">
          <input type="checkbox" name="robots_index" value="1" @checked($page->robots_index)>
          <span>Allow search engines to index</span>
        </label>
      </div>
      <div>
        <label class="field"><span class="lab">SEO title</span><input type="text" name="seo_title" value="{{ old('seo_title', $page->seo_title) }}"></label>
        <label class="field"><span class="lab">Meta description</span><textarea name="seo_description" rows="2">{{ old('seo_description', $page->seo_description) }}</textarea></label>
        <label class="field"><span class="lab">OG title</span><input type="text" name="og_title" value="{{ old('og_title', $page->og_title) }}"></label>
        <label class="field"><span class="lab">OG description</span><textarea name="og_description" rows="2">{{ old('og_description', $page->og_description) }}</textarea></label>
        <label class="field"><span class="lab">OG image</span><input type="text" name="og_image" value="{{ old('og_image', $page->og_image) }}" placeholder="storage:library/og.png or assets/og.png"></label>
        <label class="field"><span class="lab">Canonical URL</span><input type="text" name="canonical_url" value="{{ old('canonical_url', $page->canonical_url) }}"></label>
      </div>
    </div>
    <div class="row">
      <button class="btn" type="submit">Save page</button>
    </div>
  </form>

  @if ($page->revisions()->exists())
    <form method="POST" action="{{ route('admin.pages.restore', $page) }}" style="margin-top:12px;"
          onsubmit="return confirm('Restore this page to its previous version?');">
      @csrf
      <button class="btn ghost small" type="submit">Restore previous version</button>
    </form>
  @endif
</div>

@foreach ($page->sections as $section)
  <div class="card">
    <div class="row" style="align-items:baseline; margin-bottom:10px;">
      <h2 style="margin:0;">{{ $section->label ?: \Illuminate\Support\Str::headline($section->section_key) }}</h2>
      <span class="mono small muted">{{ $section->section_key }}</span>
      <span style="margin-left:auto;"></span>
      <form class="inline" method="POST" action="{{ route('admin.pages.sections.toggle', [$page, $section]) }}">
        @csrf
        <button class="btn ghost small" type="submit">{{ $section->is_enabled ? 'Hide section' : 'Show section' }}</button>
      </form>
      @if ($section->revisions_exists)
        <form class="inline" method="POST" action="{{ route('admin.pages.sections.restore', [$page, $section]) }}"
              onsubmit="return confirm('Restore this section to its previous saved version?');">
          @csrf
          <button class="btn ghost small" type="submit">Restore</button>
        </form>
      @endif
      <span class="badge {{ $section->is_enabled ? 'on' : 'off' }}">{{ $section->is_enabled ? 'Visible' : 'Hidden' }}</span>
    </div>

    <form method="POST" action="{{ route('admin.pages.sections.update', [$page, $section]) }}">
      @csrf @method('PUT')
      <input type="hidden" name="is_enabled" value="{{ $section->is_enabled ? 1 : 0 }}">

      <div class="grid-2">
        <div>
          @if ($section->eyebrow !== null || $section->heading !== null || $section->body !== null)
            <label class="field"><span class="lab">Eyebrow</span><input type="text" name="eyebrow" value="{{ $section->eyebrow }}"></label>
            <label class="field"><span class="lab">Heading</span><textarea name="heading" rows="2">{{ $section->heading }}</textarea></label>
            <label class="field"><span class="lab">Subheading</span><input type="text" name="subheading" value="{{ $section->subheading }}"></label>
            <label class="field"><span class="lab">Body</span><textarea name="body" rows="4">{{ $section->body }}</textarea></label>
          @endif
          <div class="row">
            <label class="field" style="flex:1;"><span class="lab">CTA label</span><input type="text" name="cta_label" value="{{ $section->cta_label }}"></label>
            <label class="field" style="flex:1;"><span class="lab">CTA URL</span><input type="text" name="cta_url" value="{{ $section->cta_url }}"></label>
          </div>
          <div class="row">
            <label class="field" style="flex:1;"><span class="lab">Second CTA label</span><input type="text" name="secondary_cta_label" value="{{ $section->secondary_cta_label }}"></label>
            <label class="field" style="flex:1;"><span class="lab">Second CTA URL</span><input type="text" name="secondary_cta_url" value="{{ $section->secondary_cta_url }}"></label>
          </div>
          <label class="field"><span class="lab">Media</span><input type="text" name="media" value="{{ $section->media }}"></label>
        </div>

        <div>
          @if ($section->settings)
            <span class="section-head" style="margin-top:0;">Section fields</span>
            @foreach ($section->settings as $key => $value)
              <label class="field">
                <span class="lab">{{ \Illuminate\Support\Str::headline($key) }}</span>
                @if (strlen((string) $value) > 90)
                  <textarea name="settings[{{ $key }}]" rows="3">{{ $value }}</textarea>
                @else
                  <input type="text" name="settings[{{ $key }}]" value="{{ $value }}">
                @endif
              </label>
            @endforeach
          @else
            <p class="small muted">This section has no extra fields.</p>
          @endif
        </div>
      </div>

      <div class="row">
        <button class="btn small" type="submit">Save section</button>
        <span class="small muted">Live on the public site as soon as you save.</span>
      </div>
    </form>
  </div>
@endforeach
@endsection
