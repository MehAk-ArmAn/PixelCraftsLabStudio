@extends('layouts.admin')

@section('title', 'Team Members')

@section('content')

<div class="admin-card">

<div style="display:flex; justify-content:space-between;">
    <h2>Team Members</h2>
    <a class="btn primary" href="{{ route('admin.team.create') }}">+ Add Member</a>
</div>

<div class="admin-grid">

@foreach($team as $member)
    <div class="admin-card">

        @if($member->image)
            <img src="{{ asset('storage/'.$member->image) }}" width="80">
        @endif

        <h3>{{ $member->name }}</h3>
        <p><strong>{{ $member->role }}</strong></p>
        <p>{{ $member->bio }}</p>

        <div class="btn-group">

            <a href="{{ route('admin.team.edit', $member) }}" class="btn primary">Edit</a>

            <form method="POST" action="{{ route('admin.team.destroy', $member) }}">
                @csrf @method('DELETE')
                <button class="btn danger">Delete</button>
            </form>

        </div>

    </div>
@endforeach

</div>

</div>

@endsection