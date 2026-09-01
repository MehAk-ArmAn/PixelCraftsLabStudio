<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="robots" content="noindex, nofollow">
<title>Sign in · PixelCraftsLab Admin</title>
<link rel="stylesheet" href="{{ asset('admin.css') }}">
</head>
<body style="display:flex; align-items:center; justify-content:center; min-height:100vh; padding:20px;">
  <div style="width:min(400px,100%);">
    <div style="display:flex; align-items:center; gap:11px; margin-bottom:20px;">
      <img src="{{ asset('assets/pcl-logo.png') }}" alt="" style="height:38px;">
      <span style="line-height:1;">
        <strong style="font-size:16px; letter-spacing:-0.02em;">PixelCraftsLab</strong>
        <span style="display:block; font-size:9px; letter-spacing:0.2em; text-transform:uppercase; color:var(--muted); margin-top:4px;">Studio admin</span>
      </span>
    </div>

    <div class="card">
      <h2>Sign in</h2>

      @if ($errors->any())
        <div class="flash error">{{ $errors->first() }}</div>
      @endif

      <form method="POST" action="{{ route('admin.login.attempt') }}">
        @csrf
        <label class="field">
          <span class="lab">Email</span>
          <input type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username">
        </label>
        <label class="field">
          <span class="lab">Password</span>
          <input type="password" name="password" required autocomplete="current-password">
        </label>
        <label class="check">
          <input type="checkbox" name="remember" value="1">
          <span>Stay signed in</span>
        </label>
        <button class="btn" type="submit" style="width:100%; justify-content:center;">Sign in</button>
      </form>
    </div>

    <p class="small muted" style="text-align:center;">
      Accounts are created with <span class="mono">php artisan pcl:admin</span>.
      There is no public registration.
    </p>
  </div>
</body>
</html>
