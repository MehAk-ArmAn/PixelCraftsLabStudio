@extends('admin.layouts.app')
@section('title', 'Pages')

@section('content')
<p class="muted" style="margin-top:0; max-width:70ch;">
  Every heading, paragraph, label and button on the public site. Projects, services, team members and
  testimonials have their own screens — this is everything else.
</p>

<div class="card">
  <div class="table-wrap">
    <table>
      <thead>
        <tr><th>Page</th><th>Key</th><th>Sections</th><th>Published</th><th></th></tr>
      </thead>
      <tbody>
        @foreach ($pages as $page)
          <tr>
            <td><a href="{{ route('admin.pages.edit', $page) }}"><strong>{{ $page->title }}</strong></a></td>
            <td class="mono small">{{ $page->key }}</td>
            <td>{{ $page->sections_count }}</td>
            <td><span class="badge {{ $page->is_published ? 'on' : 'off' }}">{{ $page->is_published ? 'Yes' : 'No' }}</span></td>
            <td class="actions"><a class="btn ghost small" href="{{ route('admin.pages.edit', $page) }}">Edit</a></td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>
@endsection
