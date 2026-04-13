@extends('layouts.admin')

@section('title', 'Create Project')

@section('content')

<div class="admin-card">

    <form class="admin-form"
          method="POST"
          action="{{ route('admin.portfolio.store') }}"
          enctype="multipart/form-data">

        @csrf

        <label>Title</label>
        <input name="title" placeholder="Project title">

        <label>Description</label>
        <textarea name="description" rows="5" placeholder="Project description"></textarea>

        <label>Link</label>
        <input name="link" placeholder="https://...">

        <label>Image</label>
        <input type="file" name="image">

        <button class="btn primary">Create</button>

    </form>

</div>

@endsection