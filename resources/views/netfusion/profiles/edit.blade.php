@extends('layouts.app')

@section('title', __('netfusion.edit_profile'))

@section('content')
    <div class="container-fluid px-4 py-4" style="min-height: 100vh;">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-8 col-xl-6">
                <!-- Header -->
                 <div class="d-flex align-items-center mb-4 gap-3">
                    <a href="{{ route('mikrotik-suite.netfusion.profiles.index') }}"
                        class="btn btn-light rounded-circle shadow-sm hover-scale d-flex align-items-center justify-content-center" 
                        style="width: 40px; height: 40px;">
                        <i class="bi bi-arrow-left text-dark"></i>
                    </a>
                    <div>
                         <h4 class="fw-bold text-dark mb-0">{{ __('netfusion.edit_profile') }}</h4>
                         <p class="text-muted small mb-0">{{ __('netfusion.update_hotspot_package') }}</p>
                    </div>
                </div>

                <div class="glass-card border-0">
                    <div class="card-header border-bottom-0 bg-transparent pt-4 px-4 pb-0">
                        <div class="d-flex align-items-center gap-3 mb-2">
                             <div class="rounded-circle bg-info bg-opacity-10 text-info p-3 d-flex align-items-center justify-content-center"
                                style="width: 50px; height: 50px;">
                                <i class="bi bi-pencil-square fs-4"></i>
                            </div>
                            <h5 class="fw-bold mb-0 text-dark">{{ $profile['name'] }}</h5>
                        </div>
                    </div>
                    
                    <div class="card-body p-4">
                        <form action="{{ route('mikrotik-suite.netfusion.profiles.update', $profile['.id']) }}" method="POST">
                            @csrf
                            @method('PUT')
                            
                            @if(session('error'))
                                <div class="alert alert-danger border-0 shadow-sm rounded-4 mb-4 bg-danger bg-opacity-10 d-flex align-items-center gap-3">
                                    <i class="bi bi-exclamation-triangle-fill text-danger fs-5"></i>
                                    <div class="text-danger small fw-bold">{{ session('error') }}</div>
                                </div>
                            @endif

                            <!-- General Settings -->
                            <h6 class="text-uppercase text-muted fw-bold small mb-3 ls-1"><i class="bi bi-sliders me-2"></i>{{ __('netfusion.general_settings') }}</h6>

                            <div class="form-floating mb-3">
                                <input type="text" name="name" class="form-control bg-light border border-secondary border-opacity-25 fw-bold text-dark" id="name"
                                    placeholder="Name" value="{{ $profile['name'] }}" required>
                                <label for="name">{{ __('netfusion.profile_name_placeholder') }}</label>
                            </div>

                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="text" name="address_pool" class="form-control bg-light border border-secondary border-opacity-25 fw-bold text-dark"
                                            id="address_pool" placeholder="Pool"
                                            value="{{ $profile['address-pool'] ?? '' }}">
                                        <label for="address_pool">{{ __('netfusion.address_pool') }}</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="number" name="shared_users" class="form-control bg-light border border-secondary border-opacity-25 fw-bold text-dark"
                                            id="shared_users" value="{{ $profile['shared-users'] ?? '1' }}" min="1">
                                        <label for="shared_users">{{ __('netfusion.shared_users') }}</label>
                                    </div>
                                </div>
                            </div>

                            <hr class="border-secondary border-opacity-10 my-4">

                            <!-- Rate Limits & Timeouts -->
                            <h6 class="text-uppercase text-muted fw-bold small mb-3 ls-1"><i class="bi bi-speedometer2 me-2"></i>{{ __('netfusion.limits_timeouts') }}</h6>

                            <div class="form-floating mb-3">
                                <input type="text" name="rate_limit" class="form-control bg-light border border-secondary border-opacity-25 fw-bold text-dark" id="rate_limit"
                                    placeholder="Rx/Tx" value="{{ $profile['rate-limit'] ?? '' }}">
                                <label for="rate_limit">{{ __('netfusion.rate_limit_placeholder') }}</label>
                            </div>

                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="text" name="session_timeout" class="form-control bg-light border border-secondary border-opacity-25 fw-bold text-dark"
                                            id="session_timeout" placeholder="Time"
                                            value="{{ $profile['session-timeout'] ?? '' }}">
                                        <label for="session_timeout">{{ __('netfusion.session_timeout_placeholder') }}</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="text" name="keepalive_timeout" class="form-control bg-light border border-secondary border-opacity-25 fw-bold text-dark"
                                            id="keepalive_timeout" placeholder="Time"
                                            value="{{ $profile['keepalive-timeout'] ?? '' }}">
                                        <label for="keepalive_timeout">{{ __('netfusion.keepalive_timeout_placeholder') }}</label>
                                    </div>
                                </div>
                            </div>

                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="text" name="status_autorefresh" class="form-control bg-light border border-secondary border-opacity-25 fw-bold text-dark"
                                            id="status_autorefresh" value="{{ $profile['status-autorefresh'] ?? '1m' }}">
                                        <label for="status_autorefresh">{{ __('netfusion.status_autorefresh') }}</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <select name="transparent_proxy" class="form-select bg-light border border-secondary border-opacity-25 fw-bold text-dark"
                                            id="transparent_proxy">
                                            <option value="no" {{ ($profile['transparent-proxy'] ?? 'no') === 'no' ? 'selected' : '' }}>{{ __('netfusion.no') }}</option>
                                            <option value="yes" {{ ($profile['transparent-proxy'] ?? 'no') === 'yes' ? 'selected' : '' }}>{{ __('netfusion.yes') }}</option>
                                        </select>
                                        <label for="transparent_proxy">{{ __('netfusion.transparent_proxy') }}</label>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex gap-3 pt-2">
                                <a href="{{ route('mikrotik-suite.netfusion.profiles.index') }}" class="btn btn-light rounded-pill px-4 fw-bold w-100">{{ __('netfusion.cancel') }}</a>
                                <button type="submit" class="btn btn-primary rounded-pill px-4 shadow-lg hover-scale fw-bold w-100">
                                    <i class="bi bi-check-lg me-2"></i>{{ __('netfusion.update_profile') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <style>
        .glass-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.5);
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.05);
            border-radius: 24px;
        }

        .hover-scale:hover {
            transform: scale(1.05);
            transition: transform 0.2s;
        }
        
        .form-control:focus, .form-select:focus {
            box-shadow: none;
            background-color: #fff !important;
            border-color: #0d6efd;
        }
    </style>
@endsection
