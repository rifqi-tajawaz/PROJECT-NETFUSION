@extends('layouts.guest')
@section('title', __('auth.confirm_password'))

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
                <h3 class="fw-bold mb-1">{{ __('auth.confirm_password') }}</h3>
                <p class="text-muted small">{{ __('auth.confirm_password_message') }}</p>
            </div>

            <form method="POST" action="{{ route('password.confirm') }}">
                @csrf

                <div class="form-floating mb-4 position-relative">
                    <input type="password" class="form-control" id="password" name="password" placeholder="Password"
                        required autocomplete="current-password" autofocus>
                    <label for="password">{{ __('auth.password_label') }}</label>
                    <span class="position-absolute top-50 end-0 translate-middle-y me-3 text-muted cursor-pointer"
                        onclick="togglePassword('password')">
                        <i class="bi bi-eye-slash-fill"></i>
                    </span>
                    @error('password') <span class="text-danger small ms-2">{{ $message }}</span> @enderror
                </div>

                <div class="d-grid mb-4">
                    <button type="submit" class="btn btn-primary-glow btn-lg">
                        {{ __('auth.confirm_password') }}
                    </button>
                </div>

                @if (Route::has('password.request'))
                    <div class="text-center">
                        <a href="{{ route('password.request') }}" class="text-link-glow fw-bold text-decoration-none small">
                            {{ __('auth.forgot_password') }}
                        </a>
                    </div>
                @endif

            </form>
        </div>
    </div>
@endsection

@push('script')
    <script>
        function togglePassword(id) {
            const input = document.getElementById(id);
            const icon = input.nextElementSibling.querySelector('i');
            if (input.type === "password") {
                input.type = "text";
                icon.classList.remove("bi-eye-slash-fill");
                icon.classList.add("bi-eye-fill");
            } else {
                input.type = "password";
                icon.classList.remove("bi-eye-fill");
                icon.classList.add("bi-eye-slash-fill");
            }
        }
    </script>
@endpush
