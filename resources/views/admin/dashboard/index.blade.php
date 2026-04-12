@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/pages/admin-dashboard.css') }}">
@endsection

@section('content')
<div class="admin-dashboard">

    <div class="admin-dashboard__header">
        <h1>Dashboard</h1>
        <p>Welcome back, boss 😈</p>
    </div>

    <div class="admin-dashboard__grid">

        <div class="admin-card">
            <h3>Contacts</h3>
            <p>{{ $contactCount }}</p> <!-- contacts -->
        </div>

        <div class="admin-card">
            <h3>Unread</h3>
            <p>{{ $unreadCount }}</p> <!-- unread -->
        </div>

        <div class="admin-card">
            <h3>Services</h3>
            <p>{{ $serviceCount }}</p> <!-- services -->
        </div>

        <div class="admin-card">
            <h3>Portfolio</h3>
            <p>{{ $portfolioCount }}</p> <!-- portfolio -->
        </div>

        <div class="admin-card">
            <h3>Testimonials</h3>
            <p>{{ $testimonialCount }}</p> <!-- testimonials -->
        </div>

        <div class="admin-card">
            <h3>Team</h3>
            <p>{{ $teamCount }}</p> <!-- team -->
        </div>

    </div>

</div>
@endsection