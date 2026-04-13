@extends('layouts.admin')

@section('title', 'Messages')

@section('content')

<div class="admin-card">

    @foreach($messages as $m)
        <div class="admin-card">

            <h3>{{ $m->name }}</h3>
            <p>{{ $m->email }}</p>

            <div class="btn-group">

                <!-- VIEW -->
                <a class="btn primary" href="{{ route('admin.messages.show', $m) }}">
                    View
                </a>

                <!-- DELETE -->
                <form action="{{ route('admin.messages.destroy', $m) }}" method="POST">
                    @csrf
                    @method('DELETE')

                    <button class="btn danger" onclick="return confirm('Delete this message?')">
                        Delete
                    </button>
                </form>

            </div>

        </div>
    @endforeach

</div>

@endsection