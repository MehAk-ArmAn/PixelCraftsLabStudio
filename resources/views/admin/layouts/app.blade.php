<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta name="robots" content="noindex, nofollow">
<title>@yield('title', 'Admin') · PixelCraftsLab</title>
<link rel="stylesheet" href="{{ asset('admin.css') }}">
</head>
<body>
<div class="shell">
  @include('admin.layouts.sidebar')

  <div class="main">
    <div class="topbar">
      <h1>@yield('title', 'Admin')</h1>
      <div class="spacer"></div>
      @yield('actions')
      <a class="btn ghost small" href="{{ route('home') }}" target="_blank" rel="noopener">View site</a>
      <a class="btn ghost small" href="{{ route('admin.preview') }}" target="_blank" rel="noopener">Preview drafts</a>
    </div>

    <div class="content">
      @if (session('status'))
        <div class="flash">{{ session('status') }}</div>
      @endif

      @if ($errors->any())
        <div class="flash error">
          {{ $errors->count() === 1 ? 'There is a problem with this form.' : 'There are '.$errors->count().' problems with this form.' }}
          <ul style="margin:6px 0 0; padding-left:18px;">
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      @yield('content')
    </div>
  </div>
</div>
@stack('scripts')
</body>
</html>
