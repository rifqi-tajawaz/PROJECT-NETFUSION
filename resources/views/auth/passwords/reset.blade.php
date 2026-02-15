@extends('layouts.guest')
@section('title', __('auth.reset_password'))

@section('content')
    <!-- Background Effects -->
    <div class="auth-bg">
        <div class="orb orb-1"></div>
        <div class="orb orb-2"></div>
    </div>

    <div class="d-flex align-items-center justify-content-center min-vh-100 p-4">

        <div class="glass-card w-100 p-4 p-md-5 animate__animated animate__fadeInUp" style="max-width: 450px;">

            <!-- Logo & Header -->
            <div class="text-center mb-5">
                <img src="{{ URL::asset('build/images/logo1.png') }}" class="mb-4" width="80" alt="Logo"
                    style="filter: brightness(200%);">
                <h3 class="fw-bold mb-1">{{ __('auth.reset_password') }}</h3>
                <p class="text-muted small">{{ __('auth.reset_message') }}</p>
            </div>
            <!-- Decorative Header Strip -->
            <div class="position-absolute top-0 start-0 w-100"
                style="height: 4px; background: linear-gradient(90deg, var(--bs-primary), var(--bs-secondary));"></div>
        </div>
    </div>
@endsection

@push('script')
    @vite(['resources/js/pages/auth/auth.js'])
@endpush
