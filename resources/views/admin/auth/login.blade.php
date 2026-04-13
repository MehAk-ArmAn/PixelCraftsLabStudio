@extends('layouts.app')

@section('title', 'Admin Login')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/pages/admin-login.css') }}">
@endsection

@section('content')
    <div class="admin-login">
        <div class="admin-login__card">
            <h1>Admin Access</h1>
            <p>Only authorized legends allowed.</p>

            <form method="POST" action="{{ route('admin.login.submit') }}">
                @csrf

                <div class="admin-login__field">
                    <input type="email" name="email" placeholder="Email" required> <!-- email -->
                </div>

                <div class="admin-login__field">
                    <input type="password" name="password" placeholder="Password" required> <!-- password -->
                </div>

                <button type="submit" class="admin-login__btn">
                    Enter Dashboard
                </button>
            </form>
        </div>
    </div>
@endsection
