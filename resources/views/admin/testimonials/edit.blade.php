@extends('layouts.admin')

@section('title', 'Edit Testimonial')

@section('content')

<div class="admin-card">
    <form method="POST" action="{{ route('admin.testimonials.update', $testimonial) }}" enctype="multipart/form-data" class="admin-form">
        @csrf
        @method('PUT')

        <label>Name</label>
        <input type="text" name="name" value="{{ old('name', $testimonial->name) }}" required>

        <label>Designation</label>
        <input type="text" name="designation" value="{{ old('designation', $testimonial->designation) }}">

        <label>Quote</label>
        <textarea name="quote" rows="6" required>{{ old('quote', $testimonial->quote) }}</textarea>

        <label>Current Image</label>
        @if($testimonial->image)
            <img src="{{ asset('storage/' . $testimonial->image) }}" style="width:100px; border-radius:12px; margin-bottom:10px;">
        @else
            <p style="margin:0 0 10px;">No image uploaded.</p>
        @endif

        <label>Change Image</label>
        <input type="file" name="image">

        <label>Sort Order</label>
        <input type="number" name="sort_order" value="{{ old('sort_order', $testimonial->sort_order) }}">

        <label style="display:flex; align-items:center; gap:10px;">
            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $testimonial->is_active) ? 'checked' : '' }}>
            Active
        </label>

        <button class="btn primary" type="submit">Update</button>
    </form>
</div>

@endsection
