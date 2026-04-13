@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/pages/admin-dashboard.css') }}">
@endsection

@section('content')
<div class="admin-dashboard">

    <!-- HEADER -->
    <div class="admin-dashboard__header">
        <h1>Dashboard</h1>
        <p>Welcome back, boss 😈</p>
    </div>

    <!-- GRID -->
    <div class="admin-dashboard__grid">

        <!-- CONTACTS -->
        <a href="{{ route('admin.contacts.index') }}" class="admin-card">
            <h3>Contacts</h3>
            <p>{{ $contactCount }}</p>
            <span>View all →</span>
        </a>

        <!-- UNREAD -->
        <a href="{{ route('admin.messages.index') }}" class="admin-card">
            <h3>Unread</h3>
            <p>{{ $unreadCount }}</p>
            <span>Check messages →</span>
        </a>

        <!-- SERVICES -->
        <a href="{{ route('admin.services.index') }}" class="admin-card">
            <h3>Services</h3>
            <p>{{ $serviceCount }}</p>
            <span>Manage →</span>
        </a>

        <!-- PORTFOLIO -->
        <a href="{{ route('admin.portfolio.index') }}" class="admin-card">
            <h3>Portfolio</h3>
            <p>{{ $portfolioCount }}</p>
            <span>View projects →</span>
        </a>

        <!-- TESTIMONIALS -->
        <a href="{{ route('admin.testimonials.index') }}" class="admin-card">
            <h3>Testimonials</h3>
            <p>{{ $testimonialCount }}</p>
            <span>Manage →</span>
        </a>

        <!-- TEAM -->
        <a href="{{ route('admin.team.index') }}" class="admin-card">
            <h3>Team</h3>
            <p>{{ $teamCount }}</p>
            <span>Manage →</span>
        </a>

    </div>

    <!-- QUICK ACTIONS -->
    <div class="admin-dashboard__actions">

        <a href="{{ route('admin.services.create') }}" class="action-btn primary">
            + Add Service
        </a>

        <a href="{{ route('admin.portfolio.create') }}" class="action-btn secondary">
            + Add Project
        </a>

        <a href="{{ route('admin.settings.edit') }}" class="action-btn glass">
            ⚙ Settings
        </a>

        <form action="{{ route('admin.logout') }}" method="POST">
            @csrf
            <button class="action-btn danger">Logout</button>
        </form>

    </div>

</div>
@endsection