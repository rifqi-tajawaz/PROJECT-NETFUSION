@extends('layouts.guest')
@section('title', __('auth.sign_in'))

@section('content')
    <!-- Background Effects -->
    <div class="auth-bg">
        <div class="orb orb-1"></div>
        <div class="orb orb-2"></div>
    </div>

    <div class="d-flex align-items-center justify-content-center min-vh-100 p-4">

        <div class="glass-card w-100 p-4 p-md-5 animate__animated animate__fadeInUp" style="max-width: 500px;">

            <!-- Logo & Header -->
            <div class="text-center mb-4">
                <img src="{{ URL::asset('build/images/logo1.png') }}" class="mb-4" width="80" alt="Logo"
                    style="filter: brightness(200%);">
                <h3 class="fw-bold mb-1">{{ __('auth.welcome_back') }}</h3>
                <p class="text-muted small">{{ __('auth.welcome_message') }}</p>
            </div>

            <form method="POST" action="{{ route('login.post') }}">
                @csrf
                <input type="hidden" name="g-recaptcha-response" id="g-recaptcha-response">

                <!-- Email Input -->
                <div class="form-floating mb-3">
                    <input type="email" class="form-control" id="email" name="email" placeholder="name@example.com"
                        required>
                    <label for="email">{{ __('auth.email_address') }}</label>
                    @error('email')
                        <span class="invalid-feedback d-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <!-- Password Input -->
                <div class="form-floating mb-3 position-relative">
                    <input type="password" class="form-control" id="password" name="password" placeholder="Password"
                        required>
                    <label for="password">{{ __('auth.password_label') }}</label>
                    <span
                        class="position-absolute top-50 end-0 translate-middle-y me-3 text-muted cursor-pointer password-toggle-icon"
                        data-target="password">
                        <i class="bi bi-eye-slash-fill"></i>
                    </span>
                    @error('password')
                        <span class="invalid-feedback d-block mt-2" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <!-- Remember & Forgot -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="rememberMe" name="remember" {{ old('remember') ? 'checked' : '' }}>
                        <label class="form-check-label small text-muted"
                            for="rememberMe">{{ __('auth.remember_me') }}</label>
                    </div>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}"
                            class="text-link-glow small fw-bold">{{ __('auth.forgot_password') }}</a>
                    @endif
                </div>

                <!-- Submit -->
                <div class="d-grid mb-4">
                    <button type="submit" class="btn btn-primary-glow btn-lg">
                        {{ __('auth.sign_in') }}
                    </button>
                </div>

                <!-- Social Login -->
                <div class="text-center mb-4">
                    <div class="d-flex align-items-center mb-3">
                        <hr class="flex-grow-1 my-0 opacity-25 border-light">
                        <span class="px-3 text-muted small fw-bold">{{ __('auth.or_login_with') }}</span>
                        <hr class="flex-grow-1 my-0 opacity-25 border-light">
                    </div>
                    <div class="d-flex gap-2 justify-content-center">
                        @foreach(['google', 'facebook', 'apple', 'github'] as $provider)
                            <a href="{{ route('social.redirect', $provider) }}"
                                class="btn btn-dark rounded-circle shadow-sm d-flex align-items-center justify-content-center border border-light border-opacity-10"
                                style="width: 48px; height: 48px; background: rgba(255,255,255,0.05);">
                                <i class="bi bi-{{ $provider }} fs-5 text-white"></i>
                            </a>
                        @endforeach
                    </div>
                </div>

                <!-- Footer -->
                <div class="text-center">
                    <p class="text-muted small mb-0">{{ __('auth.no_account') }}
                        <a href="{{ route('register') }}" class="text-link-glow fw-bold ms-1">{{ __('auth.sign_up') }}</a>
                    </p>
                </div>

            </form>
        </div>
    </div>
@endsection

@push('script')
    @vite(['resources/js/pages/auth/auth.js'])
@endpush