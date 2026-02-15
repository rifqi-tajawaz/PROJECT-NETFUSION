@extends('layouts.guest')
@section('title', 'Recovery Mode')

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
                <h3 class="fw-bold mb-1">Recovery Mode</h3>
                <p class="text-muted small">Enter one of your emergency recovery codes.</p>
            </div>

            <form method="POST" action="{{ route('two-factor.recovery.verify') }}">
                @csrf

                <div class="mb-4">
                    <label for="recovery_code"
                        class="form-label small text-muted text-uppercase fw-bold text-center w-100">Recovery Code</label>
                    <input id="recovery_code" type="text"
                        class="form-control form-control-lg text-center fw-bold bg-dark border-secondary text-white"
                        name="recovery_code" required autocomplete="off" autofocus placeholder="XXXXXXXX-XXXXXXXX"
                        style="letter-spacing: 0.1em; font-size: 1.2rem; background: rgba(0,0,0,0.3) !important;">
                    @error('recovery_code')
                        <span class="text-danger small mt-1 d-block text-center">{{ $message }}</span>
                    @enderror
                </div>

                <div class="d-grid mb-4">
                    <button type="submit" class="btn btn-primary-glow btn-lg">
                        Recover Account
                    </button>
                </div>

                <div class="text-center">
                    <p class="mb-0 text-muted small">Remember your code?</p>
                    <a href="{{ route('two-factor.challenge') }}" class="text-link-glow fw-bold text-decoration-none">Back to
                        Authenticator</a>
                </div>
            </form>

        </div>
    </div>
@endsection
