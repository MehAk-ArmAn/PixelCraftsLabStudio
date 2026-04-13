@extends('layouts.admin')

@section('title', 'Portfolio')

@section('content')

<div class="admin-card">

    <a class="btn primary" href="{{ route('admin.portfolio.create') }}">
        + Add
    </a>

</div>

@foreach($projects as $p)

    <div class="admin-card">

        {{-- IMAGE (optional) --}}
        @if($p->image)
            <img src="{{ asset('storage/' . $p->image) }}"
                 style="width:100%; max-height:200px; object-fit:cover; border-radius:10px; margin-bottom:10px;">
        @endif

        <h3>{{ $p->title }}</h3>

        <p>{{ Str::limit($p->description, 120) }}</p>

        @if($p->link)
            <a class="btn primary" href="{{ $p->link }}" target="_blank">
                Visit
            </a>
        @endif

        <div class="btn-group" style="margin-top:10px;">

            <a class="btn primary" href="{{ route('admin.portfolio.edit', $p) }}">
                Edit
            </a>

            <form method="POST" action="{{ route('admin.portfolio.destroy', $p) }}">
                @csrf
                @method('DELETE')

                <button class="btn danger" onclick="return confirm('Delete this project?')">
                    Delete
                </button>
            </form>

        </div>

    </div>

@endforeach

@endsection