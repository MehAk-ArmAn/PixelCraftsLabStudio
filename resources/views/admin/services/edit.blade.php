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

<label>Image</label>
<input type="file" name="image">

@if($service->image)
<img src="{{ asset('storage/'.$service->image) }}" width="80">
@endif

<button class="btn primary">Update</button>

</form>

</div>

@endsection