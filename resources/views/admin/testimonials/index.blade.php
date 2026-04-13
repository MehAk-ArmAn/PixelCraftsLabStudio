@extends('layouts.admin')

@section('title', 'Testimonials')

@section('content')

<div class="admin-card">
    <div style="display:flex; justify-content:space-between; align-items:center; gap:12px; margin-bottom:18px; flex-wrap:wrap;">
        <h2 style="margin:0;">All Testimonials</h2>
        <a href="{{ route('admin.testimonials.create') }}" class="btn primary">+ Add Testimonial</a>
    </div>

    <table class="admin-table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Designation</th>
                <th>Status</th>
                <th>Sort</th>
                <th>Actions</th>
            </tr>
        </thead>

        <tbody>
        @forelse($testimonials as $testimonial)
            <tr>
                <td>{{ $testimonial->name }}</td>
                <td>{{ $testimonial->designation ?: '-' }}</td>
                <td>{{ $testimonial->is_active ? 'Active' : 'Hidden' }}</td>
                <td>{{ $testimonial->sort_order }}</td>
                <td class="table-actions">
                    <a href="{{ route('admin.testimonials.edit', $testimonial) }}" class="btn primary">Edit</a>

                    <form method="POST" action="{{ route('admin.testimonials.destroy', $testimonial) }}">
                        @csrf
                        @method('DELETE')
                        <button class="btn danger" type="submit" onclick="return confirm('Delete this testimonial?')">Delete</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="5">No testimonials found.</td>
            </tr>
        @endforelse
        </tbody>
    </table>

    <div style="margin-top:16px;">
        {{ $testimonials->links() }}
    </div>
</div>

@endsection
