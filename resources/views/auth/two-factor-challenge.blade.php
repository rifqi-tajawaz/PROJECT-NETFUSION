@extends('layouts.guest')
@section('title', 'Two-Factor Authentication')

@section('content')
    <!-- Background Effects -->
    <div class="auth-bg">
        <div class="orb orb-1"></div>
        <div class="orb orb-2"></div>
    </div>

    <div class="d-flex align-items-center justify-content-center min-vh-100 p-4">

        <div class="glass-card w-100 p-4 p-md-5 animate__animated animate__fadeInUp" style="max-width: 450px;">

            <div class="text-center mb-5">
                <img src="{{ URL::asset('build/images/logo1.png') }}" class="mb-4" width="80" alt="Logo"
                    style="filter: brightness(200%);">
                <h3 class="fw-bold mb-1">2FA Challenge</h3>
                <p class="text-muted small">{{ __('auth.two_factor_message') }}</p>
            </div>

            <form method="POST" action="{{ route('two-factor.verify') }}">
                @csrf

                <div class="mb-4">
                    <label for="code"
                        class="form-label small text-muted text-uppercase fw-bold text-center w-100">Authentication
                        Code</label>
                    <input id="code" type="text"
                        class="form-control form-control-lg text-center fw-bold bg-dark border-secondary text-white"
                        name="code" required autocomplete="one-time-code" autofocus placeholder="000 000" maxlength="6"
                        style="letter-spacing: 0.5em; font-size: 1.5rem; background: rgba(0,0,0,0.3) !important;">
                    @error('code')
                        <span class="text-danger small mt-1 d-block text-center">{{ $message }}</span>
                    @enderror
                </div>

                <div class="d-grid mb-4">
                    <button type="submit" class="btn btn-primary-glow btn-lg">
                        Verify & Login
                    </button>
                </div>

                <div class="text-center">
                    <p class="mb-0 text-muted small">Cannot access your app?</p>
                    <a href="{{ route('two-factor.recovery') }}" class="text-link-glow fw-bold text-decoration-none">Use
                        Recovery Code</a>
                </div>
            </form>

        </div>
    </div>
@endsection
