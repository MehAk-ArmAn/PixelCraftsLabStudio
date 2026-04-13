@extends('layouts.admin')

@section('title', 'Contacts')

@section('content')

<div class="admin-card">
    <div style="display:flex; justify-content:space-between; margin-bottom:15px;">
        <h2>All Contacts</h2>
    </div>

    <table class="admin-table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Subject</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>

        <tbody>
        @forelse($contacts as $contact)
            <tr>
                <td>{{ $contact->name }}</td>
                <td>{{ $contact->email }}</td>
                <td>{{ $contact->service }}</td>
                <td>{{ $contact->is_read ? 'Read' : 'Unread' }}</td>
                <td class="table-actions">
                    <a href="{{ route('admin.contacts.show', $contact) }}" class="btn primary">View</a>

                    <form method="POST" action="{{ route('admin.contacts.destroy', $contact) }}">
                        @csrf
                        @method('DELETE')
                        <button class="btn danger" type="submit">Delete</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="5">No contacts found.</td>
            </tr>
        @endforelse
        </tbody>
    </table>

    <div style="margin-top:16px;">
        {{ $contacts->links() }}
    </div>
</div>

@endsection
