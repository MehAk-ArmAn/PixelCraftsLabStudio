@extends('layouts.admin')

@section('title', 'Edit Team Member')

@section('content')

<div class="admin-card">

<form method="POST" action="{{ route('admin.team.update', $team) }}" enctype="multipart/form-data" class="admin-form">
@csrf
@method('PUT')

<input name="name" value="{{ $team->name }}">
<input name="role" value="{{ $team->role }}">
<textarea name="bio">{{ $team->bio }}</textarea>

<input type="file" name="image">

@if($team->image)
    <img src="{{ asset('storage/'.$team->image) }}" width="80">
@endif

<button class="btn primary">Update</button>

</form>

</div>

@endsection