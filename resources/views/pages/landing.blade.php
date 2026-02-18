@extends('layouts.app')

    <!-- Navbar -->
    @include('partials.navbar')
    
{{-- Page specific CSS --}}
@section('styles')
    <link rel="stylesheet" href="{{ asset('css/landing.css') }}">
    <script src="{{ asset('js/landing.js') }}" defer></script>
    <link rel="shortcut icon" href="{{ asset('favicon/1/Profile_pic/Transparent.gif') }}" type="image/x-icon">
@endsection

@section('content')

    @include('components.big-form')

@endsection
