@extends('layouts.admin')

@section('title', 'Settings')

@section('content')

@php
    $setting = $setting ?? \App\Models\Setting::firstOrCreate(['id' => 1]);
@endphp

<div class="admin-card">
    <form method="POST" action="{{ route('admin.settings.update') }}" class="admin-form">
        @csrf

        <label>Site Name</label>
        <input name="site_name" value="{{ old('site_name', $setting->site_name) }}">

        <label>Admin Email</label>
        <input name="admin_email" value="{{ old('admin_email', $setting->admin_email) }}">

        <label>Phone</label>
        <input name="phone" value="{{ old('phone', $setting->phone) }}">

        <label>Location</label>
        <input name="location" value="{{ old('location', $setting->location) }}">

        <label>Footer Text</label>
        <textarea name="footer_text">{{ old('footer_text', $setting->footer_text) }}</textarea>

        <button class="btn primary" type="submit">Save</button>
    </form>
</div>

@endsection
