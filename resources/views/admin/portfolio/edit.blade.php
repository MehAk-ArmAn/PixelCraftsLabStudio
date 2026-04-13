@extends('layouts.admin')

@section('title', 'Edit Project')

@section('content')

<div class="admin-card">

    <form class="admin-form"
          method="POST"
          action="{{ route('admin.portfolio.update', $portfolio) }}"
          enctype="multipart/form-data">

        @csrf
        @method('PUT')

        <label>Title</label>
        <input name="title" value="{{ $portfolio->title }}">

        <label>Description</label>
        <textarea name="description" rows="5">{{ $portfolio->description }}</textarea>

        <label>Link</label>
        <input name="link" value="{{ $portfolio->link }}">

        <label>Current Image</label>
        @if($portfolio->image)
            <img src="{{ asset('storage/' . $portfolio->image) }}"
                 style="width:120px;border-radius:10px;margin-bottom:10px;">
        @endif

        <label>Change Image</label>
        <input type="file" name="image">

        <button class="btn primary">Update</button>

    </form>

</div>

@endsection