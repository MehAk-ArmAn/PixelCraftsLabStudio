@extends('admin.layouts.app')
@section('title', 'Media')

@section('content')
<div class="card">
  <h2>Upload</h2>
  <p class="sub">Images and video up to 20 MB each. Uploads go to the public storage disk.</p>
  <form method="POST" action="{{ route('admin.media.store') }}" enctype="multipart/form-data">
    @csrf
    <div class="row">
      <input type="file" name="files[]" multiple accept="image/*,video/*" required>
      <input type="text" name="folder" value="library" placeholder="folder" style="max-width:160px;">
      <button class="btn small" type="submit">Upload</button>
    </div>
  </form>

  <div style="margin-top:14px; padding-top:12px; border-top:1px solid var(--line-soft);">
    <form method="POST" action="{{ route('admin.media.import-legacy') }}">
      @csrf
      <div class="row">
        <button class="btn ghost small" type="submit">Register existing public assets</button>
        <span class="small muted">Adds files already in public/assets and public/uploads to the library. Nothing is moved.</span>
      </div>
    </form>
  </div>
</div>

<div class="card">
  <form method="GET" class="row" style="margin-bottom:14px;">
    <input type="search" name="q" value="{{ $q }}" placeholder="Search media" style="max-width:240px;">
    <select name="type" style="max-width:150px;">
      <option value="">All types</option>
      <option value="image" @selected($type === 'image')>Images</option>
      <option value="video" @selected($type === 'video')>Video</option>
      <option value="legacy" @selected($type === 'legacy')>Existing assets</option>
    </select>
    <button class="btn ghost small" type="submit">Filter</button>
    <span style="margin-left:auto;"></span>
    <span class="small muted">{{ $media->total() }} files</span>
  </form>

  @if ($media->isEmpty())
    <p class="empty">No media yet.</p>
  @else
    <div class="media-grid">
      @foreach ($media as $item)
        <div class="media-item">
          <div class="thumb">
            @if ($item->isVideo())
              <video src="{{ $item->url() }}" controls muted></video>
            @else
              <img src="{{ $item->url() }}" alt="{{ $item->alt_text }}" loading="lazy">
            @endif
          </div>
          <div class="meta">
            <b title="{{ $item->title }}">{{ $item->title ?: $item->original_name }}</b>
            <small>{{ $item->humanSize() }} · {{ $item->is_legacy ? 'existing asset' : 'upload' }}</small>
            <details style="margin-top:6px;">
              <summary class="small" style="cursor:pointer;">Details</summary>

              <p class="mono small" style="word-break:break-all; margin:6px 0;">{{ $item->reference() }}</p>

              <form method="POST" action="{{ route('admin.media.update', $item) }}">
                @csrf @method('PUT')
                <label class="field"><span class="lab">Title</span><input type="text" name="title" value="{{ $item->title }}"></label>
                <label class="field"><span class="lab">Alt text</span><input type="text" name="alt_text" value="{{ $item->alt_text }}"></label>
                <label class="field"><span class="lab">Caption</span><input type="text" name="caption" value="{{ $item->caption }}"></label>
                <button class="btn ghost small" type="submit">Save</button>
              </form>

              <form method="POST" action="{{ route('admin.media.replace', $item) }}" enctype="multipart/form-data" style="margin-top:8px;">
                @csrf
                <input type="file" name="files[]" accept="image/*,video/*" required style="max-width:100%;">
                <button class="btn ghost small" type="submit" style="margin-top:6px;">Replace file</button>
              </form>

              <form method="POST" action="{{ route('admin.media.destroy', $item) }}" style="margin-top:8px;"
                    onsubmit="return confirm('Delete this file permanently?');">
                @csrf @method('DELETE')
                <button class="btn danger small" type="submit">Delete</button>
              </form>
            </details>
          </div>
        </div>
      @endforeach
    </div>

    {{ $media->links() }}
  @endif
</div>
@endsection
