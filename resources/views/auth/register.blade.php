@extends('layouts.guest')
@section('title', __('auth.sign_up'))

@section('content')
    <!-- Background Effects -->
    <div class="auth-bg">
        <div class="orb orb-1"></div>
        <div class="orb orb-2"></div>
    </div>

    <div class="d-flex align-items-center justify-content-center min-vh-100 p-4">

        <div class="glass-card w-100 p-4 p-md-5 animate__animated animate__fadeInUp" style="max-width: 500px;">

            <!-- Logo & Header -->
            <div class="text-center mb-5">
                <img src="{{ URL::asset('build/images/logo1.png') }}" class="mb-4" width="80" alt="Logo"
                    style="filter: brightness(200%);">
                <h3 class="fw-bold mb-1">{{ __('auth.create_account') }}</h3>
                <p class="text-muted small">{{ __('auth.join_message') }}</p>
            </div>

            <form method="POST" action="{{ route('register') }}">
                @csrf
                <input type="hidden" name="g-recaptcha-response" id="g-recaptcha-response">

                <!-- Name Input -->
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="name" name="name" placeholder="Full Name" required
                        value="{{ old('name') }}" autofocus>
                    <label for="name">{{ __('auth.full_name') }}</label>
                    @error('name') <span class="text-danger small ms-2">{{ $message }}</span> @enderror
                </div>

                <!-- Email Input -->
                <div class="form-floating mb-3">
                    <input type="email" class="form-control" id="email" name="email" placeholder="name@example.com" required
                        value="{{ old('email') }}">
                    <label for="email">{{ __('auth.email_address') }}</label>
                    @error('email') <span class="text-danger small ms-2">{{ $message }}</span> @enderror
                </div>

                <!-- Password Input -->
                <div class="form-floating mb-3 position-relative">
                    <input type="password" class="form-control" id="password" name="password" placeholder="Password"
                        required>
                    <label for="password">{{ __('auth.password_label') }}</label>
                    <span class="position-absolute top-50 end-0 translate-middle-y me-3 text-muted cursor-pointer"
                        onclick="togglePassword('password')">
                        <i class="bi bi-eye-slash-fill"></i>
                    </span>
                    @error('password') <span class="text-danger small ms-2">{{ $message }}</span> @enderror
                </div>

                <!-- Confirm Password -->
                <div class="form-floating mb-4 position-relative">
                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation"
                        placeholder="Confirm Password" required>
                    <label for="password_confirmation">{{ __('auth.confirm_password') }}</label>
                </div>

                <!-- Terms -->
                <div class="form-check mb-4">
                    <input class="form-check-input" type="checkbox" id="terms" name="terms" required {{ old('terms') ? 'checked' : '' }}>
                    <label class="form-check-label small text-muted" for="terms">
                        {{ __('auth.i_agree_to') }} <a href="#" class="text-link-glow">{{ __('auth.terms_conditions') }}</a>
                    </label>
                </div>

                <!-- Submit -->
                <div class="d-grid mb-4">
                    <button type="submit" class="btn btn-primary-glow btn-lg">
                        {{ __('auth.sign_up') }}
                    </button>
                </div>

                <!-- Social Login -->
                <div class="text-center mb-4">
                    <div class="d-flex align-items-center mb-3">
                        <hr class="flex-grow-1 my-0 opacity-25 border-light">
                        <span class="px-3 text-muted small fw-bold">{{ __('auth.or_join_with') }}</span>
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
                    <p class="text-muted small mb-0">{{ __('auth.already_have_account') }}
                        <a href="{{ route('login') }}" class="text-link-glow fw-bold ms-1">{{ __('auth.sign_in') }}</a>
                    </p>
                </div>

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
    <script src="https://www.google.com/recaptcha/api.js?render={{ env('RECAPTCHA_SITE_KEY') }}"></script>
    <script>
        grecaptcha.ready(function () {
            grecaptcha.execute('{{ env('RECAPTCHA_SITE_KEY') }}', { action: 'register' }).then(function (token) {
                document.getElementById('g-recaptcha-response').value = token;
            });
        });
    </script>
@endpush
