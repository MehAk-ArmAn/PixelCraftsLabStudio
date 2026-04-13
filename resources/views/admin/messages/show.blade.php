@extends('layouts.admin')

@section('title', 'Message')

@section('content')

<div class="admin-card">
    <h3>{{ $message->name }}</h3>
    <p>{{ $message->email }}</p>
    <p>{{ $message->message }}</p>
</div>

@endsection