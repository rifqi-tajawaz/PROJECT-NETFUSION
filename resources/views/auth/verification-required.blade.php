@extends('layouts.guest')
@section('title', 'Device Verification Required')

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
                    style="width: 80px; height: 80px; background: rgba(255, 193, 7, 0.2);">
                    <i class="bi bi-shield-lock-fill fs-1 text-warning"></i>
                </div>
                <h3 class="fw-bold mb-1">Device Verification</h3>
                <p class="text-muted small mb-0">We noticed a login from a new device.</p>
                @if(session('auth.pending_verification.email'))
                    <p class="fw-bold text-white mt-1">{{ session('auth.pending_verification.email') }}</p>
                @endif
                <p class="text-muted small mt-2">Please enter the verification code sent to your email to continue.</p>
            </div>

            @if (session('error'))
                <div class="alert alert-danger bg-danger bg-opacity-10 border-danger border-opacity-25 text-danger small mb-4 rounded-3 text-center"
                    role="alert">
                    <i class="bi bi-exclamation-circle-fill me-2"></i> {{ session('error') }}
                </div>
            @endif

            @if (session('success'))
                <div class="alert alert-success bg-success bg-opacity-10 border-success border-opacity-25 text-success small mb-4 rounded-3 text-center"
                    role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                </div>
            @endif

            <form method="POST" action="{{ route('auth.verify-device') }}">
                @csrf

                <div class="mb-4">
                    <label for="verification_code"
                        class="form-label small text-muted text-uppercase fw-bold text-center w-100">Verification
                        Code</label>
                    <input type="text"
                        class="form-control form-control-lg text-center fw-bold bg-dark border-secondary text-white"
                        id="verification_code" name="verification_code" placeholder="123456" maxlength="6" pattern="\d{6}"
                        required autofocus
                        style="letter-spacing: 0.5em; font-size: 1.5rem; background: rgba(0,0,0,0.3) !important;">
                    @error('verification_code')
                        <span class="invalid-feedback d-block text-center mt-2" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="d-grid mb-4">
                    <button type="submit" class="btn btn-primary-glow btn-lg">
                        Verify Device
                    </button>
                </div>
            </form>

            <div
                class="d-flex justify-content-between align-items-center pt-3 border-top border-secondary border-opacity-25">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-link text-decoration-none text-muted small p-0 hover-white">
                        Cancel & Logout
                    </button>
                </form>
            </div>

        </div>
    </div>
@endsection

@push('script')
    <script>
        document.getElementById('verification_code').addEventListener('input', function (e) {
            this.value = this.value.replace(/[^0-9]/g, '');
        });
    </script>
@endpush
