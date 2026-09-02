@extends('errors.layout', ['code' => 419, 'title' => 'Page expired', 'accent' => '#5B2394', 'accent2' => '#8B45FF', 'motif' => 'wait'])
@section('eyebrow', 'Error 419 · Session expired')
@section('headline')Your session has <em>expired</em>.@endsection
@section('body')For security, this page timed out while it was open. Reload it and your next attempt will go through — nothing you submitted was saved.@endsection
@section('extra-action')<a class="btn" href="{{ url()->current() }}">Reload page</a>@endsection
