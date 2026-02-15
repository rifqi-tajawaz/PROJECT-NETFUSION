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
                <p class="text-muted small">{{ __('auth.recover_message') }}</p>
            </div>

            @if (session('status'))
                <div class="alert alert-success bg-success bg-opacity-10 border-success border-opacity-25 text-success small mb-4 rounded-3"
                    role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i> {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}">
                @csrf
                <input type="hidden" name="g-recaptcha-response" id="g-recaptcha-response">

                <!-- Email Input -->
                <div class="form-floating mb-4">
                    <input type="email" class="form-control" id="email" name="email" placeholder="name@example.com" required
                        value="{{ old('email') }}">
                    <label for="email">{{ __('auth.email_address') }}</label>
                    @error('email') <span class="text-danger small ms-2">{{ $message }}</span> @enderror
                </div>

                <!-- Submit -->
                <div class="d-grid mb-4">
                    <button type="submit" class="btn btn-primary-glow btn-lg">
                        {{ __('auth.send_reset_link') }}
                    </button>
                </div>

                <!-- Footer -->
                <div class="text-center">
                    <p class="text-muted small mb-0">Remember your password?
                        <a href="{{ route('login') }}" class="text-link-glow fw-bold ms-1">{{ __('auth.sign_in') }}</a>
                    </p>
                </div>

            </form>
        </div>
    </div>
@endsection

@push('script')
    <script src="https://www.google.com/recaptcha/api.js?render={{ env('RECAPTCHA_SITE_KEY') }}"></script>
    <script>
        grecaptcha.ready(function () {
            grecaptcha.execute('{{ env('RECAPTCHA_SITE_KEY') }}', { action: 'forgot_password' }).then(function (token) {
                document.getElementById('g-recaptcha-response').value = token;
            });
        });
    </script>
@endpush

@push('script')
    <script src="{{ URL::asset('build/js/jquery.min.js') }}"></script>
@endpush
