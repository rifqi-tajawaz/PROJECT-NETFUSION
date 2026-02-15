@extends('layouts.app')

@section('title', __('netfusion.generate_wizard'))

@section('content')
    <div class="container-fluid px-4 py-4" style="min-height: 100vh;">
        <!-- Header -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-4 gap-3">
            <div class="text-center text-md-start">
                <h3 class="fw-bold text-dark mb-1">{{ __('netfusion.voucher_generator') }}</h3>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 justify-content-center justify-content-md-start">
                        <li class="breadcrumb-item"><a href="{{ route('mikrotik-suite.netfusion.dashboard') }}"
                                class="text-decoration-none text-muted">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('mikrotik-suite.netfusion.users.index') }}"
                                class="text-decoration-none text-muted">{{ __('netfusion.hotspot_users') }}</a></li>
                        <li class="breadcrumb-item active text-primary" aria-current="page">{{ __('netfusion.generate_batch') }}</li>
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
                                <i class="bi bi-qr-code-scan fs-4"></i>
                            </div>
                            <div>
                                <h5 class="fw-bold mb-1 text-dark">{{ __('netfusion.batch_configuration') }}</h5>
                                <small class="text-muted d-block lh-sm">{{ __('netfusion.create_multiple_users') }}</small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card-body px-4 py-4 px-md-5 py-md-5">
                        <form action="{{ route('mikrotik-suite.netfusion.users.store-batch') }}" method="POST" id="generateUserForm">
                            @csrf
                            
                            @if(session('error'))
                                <div class="alert alert-danger border-0 shadow-sm rounded-4 mb-4 d-flex align-items-center gap-3 p-3">
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
                                <!-- Col 1: Basic Config -->
                                <div class="col-md-6">
                                    <h6 class="text-uppercase text-muted small fw-bold ls-1 mb-4"><i class="bi bi-sliders me-2"></i>{{ __('netfusion.core_settings') }}</h6>
                                    
                                    <!-- Quantity -->
                                    <div class="mb-4">
                                        <label class="form-label small fw-bold text-secondary">{{ __('netfusion.quantity') }}</label>
                                        <div class="input-group">
                                            <button class="btn btn-light border text-secondary" type="button" onclick="stepDown('qtyInput')">
                                                <i class="bi bi-dash-lg"></i>
                                            </button>
                                            <input type="number" name="qty" id="qtyInput" class="form-control bg-light border-start-0 border-end-0 text-center fw-bold text-dark" 
                                                value="10" min="1" max="500">
                                            <button class="btn btn-light border text-secondary" type="button" onclick="stepUp('qtyInput')">
                                                <i class="bi bi-plus-lg"></i>
                                            </button>
                                        </div>
                                        <div class="form-text small text-muted">{{ __('netfusion.qty_help') }}</div>
                                    </div>

                                    <!-- Server -->
                                    <div class="mb-4">
                                        <label class="form-label small fw-bold text-secondary">{{ __('netfusion.server') }}</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light border-end-0 text-secondary"><i class="bi bi-server"></i></span>
                                            <select name="server" class="form-select bg-light border-start-0 ps-0 fw-bold text-dark" style="cursor: pointer;">
                                                <option value="all">{{ __('netfusion.server_all') }}</option>
                                                @foreach($servers as $server)
                                                    <option value="{{ $server['name'] }}">{{ $server['name'] }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <!-- User Mode -->
                                    <div class="mb-4">
                                        <label class="form-label small fw-bold text-secondary">{{ __('netfusion.user_mode') }}</label>
                                        <div class="row g-2">
                                            <div class="col-6">
                                                <input type="radio" class="btn-check" name="mode" id="mode1" value="up" checked>
                                                <label class="btn btn-outline-light text-dark border w-100 p-3 text-start hover-shadow position-relative" for="mode1">
                                                    <i class="bi bi-person-badge fs-4 mb-2 d-block text-primary"></i>
                                                    <span class="fw-bold d-block">{{ __('netfusion.user_pass') }}</span>
                                                    <small class="text-muted d-block" style="font-size: 0.75rem;">{{ __('netfusion.login_user_pass') }}</small>
                                                </label>
                                            </div>
                                            <div class="col-6">
                                                <input type="radio" class="btn-check" name="mode" id="mode2" value="vc">
                                                <label class="btn btn-outline-light text-dark border w-100 p-3 text-start hover-shadow position-relative" for="mode2">
                                                    <i class="bi bi-ticket-perforated fs-4 mb-2 d-block text-success"></i>
                                                    <span class="fw-bold d-block">{{ __('netfusion.voucher_code') }}</span>
                                                    <small class="text-muted d-block" style="font-size: 0.75rem;">{{ __('netfusion.login_code_only') }}</small>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    
                                     <!-- Profile -->
                                     <div class="mb-4">
                                        <label class="form-label small fw-bold text-secondary">{{ __('netfusion.user_profile') }} <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light border-end-0 text-secondary"><i class="bi bi-speedometer2"></i></span>
                                            <select name="profile" class="form-select bg-light border-start-0 ps-0 fw-bold text-dark" required style="cursor: pointer;">
                                                <option value="" disabled selected>{{ __('netfusion.select_profile') }}</option>
                                                @foreach($profiles as $profile)
                                                    <option value="{{ $profile['name'] }}">{{ $profile['name'] }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <!-- Col 2: Format & Pattern -->
                                <div class="col-md-6 border-start-md ps-md-5">
                                    <h6 class="text-uppercase text-muted small fw-bold ls-1 mb-4 mt-4 mt-md-0"><i class="bi bi-input-cursor-text me-2"></i>{{ __('netfusion.pattern_limits') }}</h6>

                                    <div class="row g-3 mb-4">
                                        <div class="col-md-6">
                                            <label class="form-label small fw-bold text-secondary">{{ __('netfusion.length') }}</label>
                                            <select name="user_len" class="form-select bg-light fw-bold text-dark">
                                                @for($i = 3; $i <= 8; $i++)
                                                    <option value="{{ $i }}" {{ $i == 4 ? 'selected' : '' }}>{{ $i }} {{ __('netfusion.characters') }}</option>
                                                @endfor
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label small fw-bold text-secondary">{{ __('netfusion.prefix') }}</label>
                                            <input type="text" name="prefix" class="form-control bg-light fw-bold text-dark" placeholder="{{ __('netfusion.optional') }}">
                                        </div>
                                    </div>

                                    <div class="mb-4">
                                        <label class="form-label small fw-bold text-secondary">{{ __('netfusion.character_type') }}</label>
                                        <select name="char_type" class="form-select bg-light fw-bold text-dark">
                                            <option value="mix" selected>{{ __('netfusion.mix') }}</option>
                                            <option value="mix1">{{ __('netfusion.mix_upper') }}</option>
                                            <option value="mix2">{{ __('netfusion.mix_case') }}</option>
                                            <option value="lower">{{ __('netfusion.lower') }}</option>
                                            <option value="upper">{{ __('netfusion.upper') }}</option>
                                            <option value="upplow">{{ __('netfusion.upplow') }}</option>
                                            <option value="num">{{ __('netfusion.number') }}</option>
                                        </select>
                                    </div>

                                    <div class="row g-3 mb-4">
                                        <div class="col-12">
                                            <label class="form-label small fw-bold text-secondary">{{ __('netfusion.time_limit') }}</label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light border-end-0 text-secondary"><i class="bi bi-clock"></i></span>
                                                <input type="text" name="limit_uptime" class="form-control bg-light border-start-0 ps-0 fw-bold text-dark" 
                                                    placeholder="{{ __('netfusion.optional_time_limit_placeholder') }}">
                                            </div>
                                        </div>
                                        
                                        <div class="col-12">
                                            <label class="form-label small fw-bold text-secondary">{{ __('netfusion.data_volume_limit') }}</label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light border-end-0 text-secondary"><i class="bi bi-hdd-network"></i></span>
                                                <input type="number" name="limit_bytes_total" class="form-control bg-light border-start-0 ps-0 fw-bold text-dark" 
                                                    placeholder="{{ __('netfusion.data_limit_placeholder') }}">
                                                <select name="limit_bytes_unit" class="form-select bg-light border text-center fw-bold text-secondary" style="max-width: 90px; cursor: pointer;">
                                                    <option value="MB" selected>MB</option>
                                                    <option value="GB">GB</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-4">
                                        <label class="form-label small fw-bold text-secondary">{{ __('netfusion.comment_note') }}</label>
                                        <textarea name="comment" class="form-control bg-light border border-secondary border-opacity-25 rounded-4 p-3" rows="2" 
                                            placeholder="{{ __('netfusion.batch_note_placeholder') }}"></textarea>
                                    </div>
                                </div>
                            </div>
                            
                            <hr class="border-secondary border-opacity-10 my-5">

                            <div class="d-flex flex-column flex-md-row justify-content-end gap-3">
                                <a href="{{ route('mikrotik-suite.netfusion.users.index') }}" class="btn btn-light rounded-pill px-4 fw-bold text-secondary w-100 w-md-auto">{{ __('netfusion.cancel') }}</a>
                                <button type="submit" id="submitBtn" class="btn btn-primary rounded-pill px-5 py-2 fw-bold shadow-lg hover-scale w-100 w-md-auto">
                                    <span class="btn-text"><i class="bi bi-magic me-2"></i>{{ __('netfusion.generate_users') }}</span>
                                    <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                                    <span class="loading-text d-none ms-2">{{ __('netfusion.generating') }}</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Loading State
            const form = document.querySelector('#generateUserForm');
            const submitBtn = document.querySelector('#submitBtn');
            const btnText = submitBtn.querySelector('.btn-text');
            const spinner = submitBtn.querySelector('.spinner-border');
            const loadingText = submitBtn.querySelector('.loading-text');

            form.addEventListener('submit', function() {
                if(form.checkValidity()) {
                    submitBtn.disabled = true;
                    btnText.classList.add('d-none');
                    spinner.classList.remove('d-none');
                    loadingText.classList.remove('d-none');
                }
            });
        });

        function stepUp(id) {
            document.getElementById(id).stepUp();
        }
        function stepDown(id) {
            document.getElementById(id).stepDown();
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
            border-color: #ced4da; /* Darker border for visibility */
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

        .hover-shadow:hover {
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.05) !important;
            border-color: #0d6efd !important;
        }
        
        .btn-check:checked + .btn-outline-light {
            background-color: #f8f9fa;
            border-color: #0d6efd !important;
            color: #0d6efd !important;
            box-shadow: 0 0.5rem 1rem rgba(13, 110, 253, 0.1) !important;
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
