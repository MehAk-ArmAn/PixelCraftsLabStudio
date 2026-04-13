@extends('layouts.admin')

@section('title', 'Add Team Member')

@section('content')

<div class="admin-card">

<form method="POST" action="{{ route('admin.team.store') }}" enctype="multipart/form-data" class="admin-form">
@csrf

<input name="name" placeholder="Name"> {{-- name --}}
<input name="role" placeholder="Role"> {{-- role --}}
<textarea name="bio" placeholder="Bio"></textarea> {{-- bio --}}
<input type="file" name="image"> {{-- image --}}

<button class="btn primary">Create</button>

</form>

</div>

@endsection