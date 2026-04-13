@extends('layouts.admin')

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
        <a href="{{ route('admin.contacts.index') }}" class="admin-card">
            <h3>Contacts</h3>
            <p>{{ $contactCount ?? 0 }}</p>
            <span>View all →</span>
        </a>

        <a href="{{ route('admin.messages.index') }}" class="admin-card">
            <h3>Unread</h3>
            <p>{{ $unreadCount ?? 0 }}</p>
            <span>Check messages →</span>
        </a>

        <a href="{{ route('admin.services.index') }}" class="admin-card">
            <h3>Services</h3>
            <p>{{ $serviceCount ?? 0 }}</p>
            <span>Manage →</span>
        </a>

        <a href="{{ route('admin.portfolio.index') }}" class="admin-card">
            <h3>Portfolio</h3>
            <p>{{ $portfolioCount ?? 0 }}</p>
            <span>View projects →</span>
        </a>

        <a href="{{ route('admin.testimonials.index') }}" class="admin-card">
            <h3>Testimonials</h3>
            <p>{{ $testimonialCount ?? 0 }}</p>
            <span>Manage →</span>
        </a>

        <a href="{{ route('admin.team.index') }}" class="admin-card">
            <h3>Team</h3>
            <p>{{ $teamCount ?? 0 }}</p>
            <span>Manage →</span>
        </a>
    </div>

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
            <button class="action-btn danger" type="submit">Logout</button>
        </form>
    </div>

</div>
@endsection
