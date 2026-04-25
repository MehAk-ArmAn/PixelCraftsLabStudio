@extends('layouts.admin')

@section('title', 'Create Service')

@section('content')

<div class="admin-card">

<form method="POST" action="{{ route('admin.services.store') }}" enctype="multipart/form-data" class="admin-form">
@csrf

<label>Title</label>
<input type="text" name="title" required> {{-- title --}}

<label>Description</label>
<textarea name="description" required></textarea> {{-- desc --}}

<button class="btn primary">Create</button>

</form>

</div>

@endsection
