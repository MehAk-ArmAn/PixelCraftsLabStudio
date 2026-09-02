@extends('errors.layout', ['code' => 500, 'title' => 'Something went wrong', 'accent' => '#FF5F1F', 'accent2' => '#8B45FF', 'motif' => 'broken'])
@section('eyebrow', 'Error 500 · Server error')
@section('headline')Something went <em>off-grid</em>.@endsection
@section('body')This one is on us, not on you. It has been logged and someone will look at it. Try again in a moment — it may well have been a one-off.@endsection
