@extends('layouts.admin')

@section('title', 'Testimonials')

@section('content')

<div class="admin-card">
    <a href="{{ route('admin.testimonials.create') }}" class="btn primary">+ Add</a>

    @foreach($testimonials as $t)
        <div class="admin-card">
            <h3>{{ $t->name }}</h3>

            @if(!empty($t->designation))
                <p><strong>{{ $t->designation }}</strong></p>
            @endif

            <p>{{ $t->quote }}</p>

            <a class="btn primary" href="{{ route('admin.testimonials.edit', $t) }}">Edit</a>

            <form method="POST" action="{{ route('admin.testimonials.destroy', $t) }}">
                @csrf
                @method('DELETE')
                <button class="btn danger" type="submit">Delete</button>
            </form>
        </div>
    @endforeach
</div>

@endsection
