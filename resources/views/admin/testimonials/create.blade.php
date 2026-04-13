@extends('layouts.admin')

@section('title', 'Create Testimonial')

@section('content')

<div class="admin-card">
    <form method="POST" action="{{ route('admin.testimonials.store') }}" enctype="multipart/form-data" class="admin-form">
        @csrf

        <label>Name</label>
        <input type="text" name="name" value="{{ old('name') }}" required>

        <label>Designation</label>
        <input type="text" name="designation" value="{{ old('designation') }}" placeholder="Startup Founder, Business Owner, etc.">

        <label>Quote</label>
        <textarea name="quote" rows="6" required>{{ old('quote') }}</textarea>

        <label>Image</label>
        <input type="file" name="image">

        <label>Sort Order</label>
        <input type="number" name="sort_order" value="{{ old('sort_order', 0) }}">

        <label style="display:flex; align-items:center; gap:10px;">
            <input type="checkbox" name="is_active" value="1" {{ old('is_active', 1) ? 'checked' : '' }}>
            Active
        </label>

        <button class="btn primary" type="submit">Create</button>
    </form>
</div>

@endsection
