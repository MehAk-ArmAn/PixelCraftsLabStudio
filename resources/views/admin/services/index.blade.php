@extends('layouts.admin')

@section('title', 'Services')

@section('content')

<div class="admin-card">

    <div style="display:flex; justify-content:space-between; margin-bottom:15px;">
        <h2>All Services</h2>
        <a href="{{ route('admin.services.create') }}" class="btn primary">+ Add</a>
    </div>

    <table class="admin-table">
        <thead>
            <tr>
                <th>Title</th>
                <th>Actions</th>
            </tr>
        </thead>

        <tbody>
        @foreach($services as $service)
            <tr>
                <td>{{ $service->title }}</td>

                <td>
                    <a href="{{ route('admin.services.edit', $service) }}" class="btn primary">Edit</a>

                    <form method="POST" action="{{ route('admin.services.destroy', $service) }}" style="display:inline;">
                        @csrf @method('DELETE')
                        <button class="btn danger">Delete</button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>

    </table>

</div>

@endsection
