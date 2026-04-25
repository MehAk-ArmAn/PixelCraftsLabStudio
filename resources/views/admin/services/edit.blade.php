@extends('layouts.admin')

@section('title', 'Edit Service')

@section('content')

<div class="admin-card">

<form method="POST" action="{{ route('admin.services.update', $service) }}" enctype="multipart/form-data" class="admin-form">
@csrf
@method('PUT')

<label>Title</label>
<input type="text" name="title" value="{{ $service->title }}"> {{-- title --}}

<label>Description</label>
<textarea name="description">{{ $service->description }}</textarea> {{-- desc --}}

<button class="btn primary">Update</button>

</form>

</div>

@endsection
