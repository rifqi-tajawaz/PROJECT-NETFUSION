@extends('layouts.app')

@section('title', __('netfusion.add_user'))

@section('content')
    <div class="container-fluid px-4 py-4" style="min-height: 100vh;">
        <!-- Header -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-4 gap-3">
            <div class="text-center text-md-start">
                <h3 class="fw-bold text-dark mb-1">{{ __('netfusion.new_hotspot_user') }}</h3>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 justify-content-center justify-content-md-start">
                        <li class="breadcrumb-item"><a href="{{ route('mikrotik-suite.netfusion.dashboard') }}"
                                class="text-decoration-none text-muted">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('mikrotik-suite.netfusion.users.index') }}"
                                class="text-decoration-none text-muted">{{ __('netfusion.hotspot_users') }}</a></li>
                        <li class="breadcrumb-item active text-primary" aria-current="page">{{ __('netfusion.add_user') }}
                        </li>
                    </ol>
                </nav>
            </div>
            <a href="{{ route('mikrotik-suite.netfusion.users.index') }}"
                class="btn btn-light rounded-pill shadow-sm px-4 fw-bold text-secondary w-100 w-md-auto">
                <i class="bi bi-arrow-left me-2"></i>{{ __('netfusion.back') }}
            </a>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="glass-card border-0 p-0">
                    <div class="card-header bg-transparent border-bottom px-4 py-4 px-md-5">
                        <div class="d-flex align-items-center gap-3">
                            <div class="bg-primary bg-opacity-10 text-primary rounded-circle p-3 d-flex align-items-center justify-content-center"
                                style="width: 50px; height: 50px;">
                                <i class="bi bi-person-plus-fill fs-4"></i>
                            </div>
                            <div>
                                <h5 class="fw-bold mb-1 text-dark">{{ __('netfusion.user_configuration') }}</h5>
                                <small class="text-muted d-block lh-sm">{{ __('netfusion.create_new_user_limits') }}</small>
                            </div>
                        </div>
                    </div>

                    <div class="card-body px-4 py-4 px-md-5 py-md-5">
                        <form action="{{ route('mikrotik-suite.netfusion.users.store') }}" method="POST" id="createUserForm">
                            @csrf
                            @if(session('success'))
                                <div
                                    class="alert alert-success border-0 shadow-sm rounded-4 mb-4 d-flex align-items-center gap-3 p-3">
                                    <div class="bg-success bg-opacity-10 text-success rounded-circle p-2 d-flex align-items-center justify-content-center"
                                        style="width: 40px; height: 40px;">
                                        <i class="bi bi-check-circle-fill fs-5"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="fw-bold mb-0 text-success">{{ __('netfusion.success') }}</h6>
                                        <small class="text-muted">{{ session('success') }}</small>
                                    </div>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif
                            @if(session('error'))
                                <div
                                    class="alert alert-danger border-0 shadow-sm rounded-4 mb-4 d-flex align-items-center gap-3 p-3">
                                    <div class="bg-danger bg-opacity-10 text-danger rounded-circle p-2 d-flex align-items-center justify-content-center"
                                        style="width: 40px; height: 40px;">
                                        <i class="bi bi-exclamation-triangle fs-5"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="fw-bold mb-0 text-danger">Error</h6>
                                        <small class="text-muted">{{ session('error') }}</small>
                                    </div>
                                </div>
                            @endif

                            <div class="row g-4 g-md-5">
                                <!-- Col 1: Credentials -->
                                <div class="col-md-6">
                                    <h6 class="text-uppercase text-muted small fw-bold ls-1 mb-4"><i
                                            class="bi bi-shield-lock me-2"></i>{{ __('netfusion.credentials') }}</h6>

                                    <div class="mb-4">
                                        <label
                                            class="form-label small fw-bold text-secondary">{{ __('netfusion.username') }}
                                            <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light border-end-0 text-secondary"><i
                                                    class="bi bi-person"></i></span>
                                            <input type="text"
                                                class="form-control bg-light border-start-0 ps-0 fw-bold text-dark"
                                                name="username" id="usernameInput"
                                                placeholder="{{ __('netfusion.username_placeholder') }}" required>
                                        </div>
                                    </div>

                                    <div class="mb-4">
                                        <label
                                            class="form-label small fw-bold text-secondary">{{ __('netfusion.password') }}
                                            <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light border-end-0 text-secondary"><i
                                                    class="bi bi-key"></i></span>
                                            <input type="password"
                                                class="form-control bg-light border-start-0 border-end-0 ps-0 fw-bold text-dark"
                                                name="password" id="passwordInput"
                                                placeholder="{{ __('netfusion.password_placeholder') }}" required>
                                            <button
                                                class="btn btn-light border-top border-bottom border-start-0 text-secondary"
                                                type="button" id="togglePassword" tabindex="-1">
                                                <i class="bi bi-eye-slash"></i>
                                            </button>
                                            <button class="btn btn-primary text-white shadow-sm" type="button"
                                                id="generatePassword" title="Generate Random" tabindex="-1">
                                                <i class="bi bi-magic"></i>
                                            </button>
                                        </div>
                                        <div class="form-text small text-muted d-flex justify-content-between">
                                            <span>{{ __('netfusion.secure_password_recommended') }}</span>
                                            <a href="#" class="text-decoration-none text-primary fw-bold"
                                                style="font-size: 0.8rem;"
                                                onclick="copyPassword(event)">{{ __('netfusion.copy') }}</a>
                                        </div>
                                    </div>

                                    <div class="mb-4">
                                        <label class="form-label small fw-bold text-secondary">{{ __('netfusion.profile') }}
                                            <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light border-end-0 text-secondary"><i
                                                    class="bi bi-speedometer2"></i></span>
                                            <select name="profile"
                                                class="form-select bg-light border-start-0 ps-0 fw-bold text-dark" required
                                                style="cursor: pointer;">
                                                <option value="" disabled selected>{{ __('netfusion.select_profile') }}
                                                </option>
                                                @foreach($profiles as $profile)
                                                    <option value="{{ $profile['name'] }}">{{ $profile['name'] }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="mb-4">
                                        <label class="form-label small fw-bold text-secondary">{{ __('netfusion.server') }}
                                            (Optional)</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light border-end-0 text-secondary"><i
                                                    class="bi bi-server"></i></span>
                                            <select name="server"
                                                class="form-select bg-light border-start-0 ps-0 fw-bold text-dark"
                                                style="cursor: pointer;">
                                                <option value="">{{ __('netfusion.all_servers') }}</option>
                                                @foreach($servers as $server)
                                                    <option value="{{ $server['name'] }}">{{ $server['name'] }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <!-- Col 2: Limits & Extras -->
                                <div class="col-md-6 border-start-md ps-md-5">
                                    <h6 class="text-uppercase text-muted small fw-bold ls-1 mb-4 mt-4 mt-md-0"><i
                                            class="bi bi-sliders me-2"></i>{{ __('netfusion.limits_metadata') }}</h6>

                                    <div class="row g-3 mb-4">
                                        <div class="col-12">
                                            <label
                                                class="form-label small fw-bold text-secondary">{{ __('netfusion.time_limit') }}</label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light border-end-0 text-secondary"><i
                                                        class="bi bi-clock"></i></span>
                                                <input type="text" name="limit_uptime"
                                                    class="form-control bg-light border-start-0 ps-0 fw-bold text-dark"
                                                    placeholder="{{ __('netfusion.time_limit_placeholder') }}">
                                            </div>
                                            <div class="form-text small text-muted">{{ __('netfusion.time_limit_help') }}
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <label
                                                class="form-label small fw-bold text-secondary">{{ __('netfusion.data_volume_limit') }}</label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light border-end-0 text-secondary"><i
                                                        class="bi bi-hdd-network"></i></span>
                                                <input type="number" name="limit_bytes_total"
                                                    class="form-control bg-light border-start-0 ps-0 fw-bold text-dark"
                                                    placeholder="{{ __('netfusion.data_limit_placeholder') }}">
                                                <select name="limit_bytes_unit"
                                                    class="form-select bg-light border text-center fw-bold text-secondary"
                                                    style="max-width: 90px; cursor: pointer;">
                                                    <option value="MB" selected>MB</option>
                                                    <option value="GB">GB</option>
                                                </select>
                                            </div>
                                            <div class="form-text small text-muted">{{ __('netfusion.data_limit_help') }}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-4">
                                        <label
                                            class="form-label small fw-bold text-secondary">{{ __('netfusion.comment_note') }}</label>
                                        <textarea name="comment"
                                            class="form-control bg-light border border-secondary border-opacity-25 rounded-4 p-3"
                                            rows="3" placeholder="{{ __('netfusion.comment_placeholder') }}"></textarea>
                                    </div>
                                </div>
                            </div>

                            <hr class="border-secondary border-opacity-10 my-5">

                            <div class="d-flex flex-column flex-md-row justify-content-end gap-3">
                                <a href="{{ route('mikrotik-suite.netfusion.users.index') }}"
                                    class="btn btn-light rounded-pill px-4 fw-bold text-secondary w-100 w-md-auto">{{ __('netfusion.cancel') }}</a>
                                <button type="submit" id="submitBtn"
                                    class="btn btn-primary rounded-pill px-5 py-2 fw-bold shadow-lg hover-scale w-100 w-md-auto">
                                    <span class="btn-text"><i
                                            class="bi bi-check-lg me-2"></i>{{ __('netfusion.create_user') }}</span>
                                    <span class="spinner-border spinner-border-sm d-none" role="status"
                                        aria-hidden="true"></span>
                                    <span class="loading-text d-none ms-2">{{ __('netfusion.creating') }}</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Password Toggle
            const togglePassword = document.querySelector('#togglePassword');
            const password = document.querySelector('#passwordInput');

            togglePassword.addEventListener('click', function () {
                const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
                password.setAttribute('type', type);
                this.querySelector('i').classList.toggle('bi-eye');
                this.querySelector('i').classList.toggle('bi-eye-slash');
            });

            // Random Password Generator
            const generatePassword = document.querySelector('#generatePassword');
            generatePassword.addEventListener('click', function () {
                const chars = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
                const passwordLength = 8;
                let randomPassword = "";
                for (let i = 0; i <= passwordLength; i++) {
                    let randomNumber = Math.floor(Math.random() * chars.length);
                    randomPassword += chars.substring(randomNumber, randomNumber + 1);
                }
                password.value = randomPassword;
                password.type = "text"; // Show the generated password
                togglePassword.querySelector('i').classList.remove('bi-eye-slash');
                togglePassword.querySelector('i').classList.add('bi-eye');
            });

            // Loading State
            const form = document.querySelector('#createUserForm');
            const submitBtn = document.querySelector('#submitBtn');
            const btnText = submitBtn.querySelector('.btn-text');
            const spinner = submitBtn.querySelector('.spinner-border');
            const loadingText = submitBtn.querySelector('.loading-text');

            form.addEventListener('submit', function () {
                if (form.checkValidity()) {
                    submitBtn.disabled = true;
                    btnText.classList.add('d-none');
                    spinner.classList.remove('d-none');
                    loadingText.classList.remove('d-none');
                }
            });

            // Auto-dismiss alerts
            const successAlert = document.querySelector('.alert-success');
            if (successAlert) {
                setTimeout(function () {
                    successAlert.style.transition = 'opacity 0.5s ease';
                    successAlert.style.opacity = '0';
                    setTimeout(function () {
                        successAlert.remove();
                    }, 500);
                }, 3000);
            }
        });

        function copyPassword(e) {
            e.preventDefault();
            const password = document.querySelector('#passwordInput');
            if (password.value) {
                navigator.clipboard.writeText(password.value);
                alert('{{ __('netfusion.password_copied') }}'); // Simple feedback
            }
        }
    </script>

    <style>
        .glass-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.5);
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.05);
            border-radius: 24px;
        }

        .form-control,
        .form-select,
        .input-group-text {
            border-color: #dee2e6;
            transition: all 0.2s;
        }

        .form-control:focus,
        .form-select:focus {
            box-shadow: none;
            border-color: #0d6efd;
            background-color: #fff !important;
        }

        .input-group:focus-within .input-group-text {
            border-color: #0d6efd;
            background-color: #fff !important;
        }

        .hover-scale:hover {
            transform: scale(1.02);
            transition: transform 0.2s;
        }

        @media (min-width: 768px) {
            .border-start-md {
                border-left: 1px solid #f1f3f5 !important;
            }

            .w-md-auto {
                width: auto !important;
            }
        }
    </style>
@endsection
