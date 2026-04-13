@extends('layouts.admin')

@section('title', 'Settings')

@section('content')

<div class="admin-card">

<form method="POST" action="{{ route('admin.settings.update') }}">
@csrf

<input name="site_name" value="{{ $setting->site_name }}">
<input name="admin_email" value="{{ $setting->admin_email }}">
<input name="phone" value="{{ $setting->phone }}">
<input name="location" value="{{ $setting->location }}">

<textarea name="footer_text">{{ $setting->footer_text }}</textarea>

<button class="btn primary">Save</button>

</form>

</div>

@endsection