@extends('layouts.guest')
@section('title', __('auth.verify_email'))

@section('content')
    <!-- Background Effects -->
    <div class="auth-bg">
        <div class="orb orb-1"></div>
        <div class="orb orb-2"></div>
    </div>

    <div class="d-flex align-items-center justify-content-center min-vh-100 p-4">

        <div class="glass-card w-100 p-4 p-md-5 animate__animated animate__fadeInUp" style="max-width: 450px;">

            <div class="text-center mb-5">
                <div class="mx-auto mb-4 d-flex align-items-center justify-content-center rounded-circle"
                    style="width: 80px; height: 80px; background: rgba(99, 102, 241, 0.2);">
                    <i class="bi bi-envelope-check-fill fs-1 text-primary"></i>
                </div>
                <h3 class="fw-bold mb-1">{{ __('auth.verify_email') }}</h3>
                <p class="text-muted small mb-0">{{ __('auth.verify_email_sent') }}</p>
                <p class="fw-bold text-white mt-1">{{ auth()->user()->email }}</p>
            </div>

            @if (session('resent'))
                <div class="alert alert-success bg-success bg-opacity-10 border-success border-opacity-25 text-success small mb-4 rounded-3 text-center"
                    role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i> {{ __('auth.verify_email_fresh') }}
                </div>
            @endif

            @if ($errors->has('otp'))
                <div class="alert alert-danger bg-danger bg-opacity-10 border-danger border-opacity-25 text-danger small mb-4 rounded-3 text-center"
                    role="alert">
                    <i class="bi bi-exclamation-circle-fill me-2"></i> {{ $errors->first('otp') }}
                </div>
            @endif

            <form method="POST" action="{{ route('verification.verify.otp') }}">
                @csrf

                <div class="mb-4">
                    <label for="otp"
                        class="form-label small text-muted text-uppercase fw-bold text-center w-100">{{ __('auth.verification_code') }}</label>
                    <input type="text"
                        class="form-control form-control-lg text-center fw-bold bg-dark border-secondary text-white"
                        id="otp" name="otp" placeholder="123456" maxlength="6" pattern="\d{6}" required autofocus
                        style="letter-spacing: 0.5em; font-size: 1.5rem; background: rgba(0,0,0,0.3) !important;">
                </div>

                <div class="d-grid mb-4">
                    <button type="submit" class="btn btn-primary-glow btn-lg">
                        {{ __('auth.verify_account') }}
                    </button>
                </div>

                <div
                    class="d-flex justify-content-between align-items-center pt-3 border-top border-secondary border-opacity-25">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn btn-link text-decoration-none text-muted small p-0 hover-white">
                            {{ __('auth.logout') }}
                        </button>
                    </form>

                    <form method="POST" action="{{ route('verification.resend') }}">
                        @csrf
                        <button type="submit" class="btn btn-link text-decoration-none text-link-glow fw-bold small p-0">
                            {{ __('auth.resend_code') }}
                        </button>
                    </form>
                </div>

            </form>
        </div>
    </div>
@endsection

@push('script')
    <script>
        document.getElementById('otp').addEventListener('input', function (e) {
            this.value = this.value.replace(/[^0-9]/g, '');
        });
    </script>
@endpush
