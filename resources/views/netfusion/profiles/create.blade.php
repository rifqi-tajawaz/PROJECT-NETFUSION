@extends('layouts.app')

@section('title', __('netfusion.create_profile'))

@section('content')
    <div class="container-fluid px-4 py-4">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-8 col-xl-6">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-header border-bottom-0 bg-transparent pt-4 px-4 pb-0">
                        <div class="d-flex align-items-center mb-3">
                            <a href="{{ route('mikrotik-suite.netfusion.profiles.index') }}"
                                class="btn btn-light btn-sm rounded-circle me-3 shadow-sm">
                                <i class="bi bi-arrow-left"></i>
                            </a>
                            <h5 class="fw-bold mb-0">{{ __('netfusion.create_new_profile') }}</h5>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <form action="{{ route('mikrotik-suite.netfusion.profiles.store') }}" method="POST">
                            @csrf

                            <!-- General Settings -->
                            <h6 class="text-uppercase text-muted fw-bold small mb-3">{{ __('netfusion.general_settings') }}
                            </h6>

                            <div class="form-floating mb-3">
                                <input type="text" name="name"
                                    class="form-control bg-light border border-secondary border-opacity-25" id="name"
                                    placeholder="Name" required>
                                <label for="name">{{ __('netfusion.profile_name_placeholder') }}</label>
                            </div>

                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="text" name="address_pool"
                                            class="form-control bg-light border border-secondary border-opacity-25"
                                            id="address_pool" placeholder="Pool">
                                        <label for="address_pool">{{ __('netfusion.address_pool') }}
                                            ({{ __('netfusion.optional') }})</label>
                                    </div>
                                    <div class="form-text small"><i class="bi bi-info-circle me-1"></i>
                                        {{ __('netfusion.address_pool_help') }}</div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="number" name="shared_users"
                                            class="form-control bg-light border border-secondary border-opacity-25"
                                            id="shared_users" value="1" min="1">
                                        <label for="shared_users">{{ __('netfusion.shared_users') }}</label>
                                    </div>
                                </div>
                            </div>

                            <hr class="border-light my-4">

                            <!-- Rate Limits & Timeouts -->
                            <h6 class="text-uppercase text-muted fw-bold small mb-3">{{ __('netfusion.limits_timeouts') }}
                            </h6>

                            <div class="form-floating mb-3">
                                <input type="text" name="rate_limit"
                                    class="form-control bg-light border border-secondary border-opacity-25" id="rate_limit"
                                    placeholder="Rx/Tx">
                                <label for="rate_limit">{{ __('netfusion.rate_limit_placeholder') }}</label>
                            </div>

                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="text" name="session_timeout"
                                            class="form-control bg-light border border-secondary border-opacity-25"
                                            id="session_timeout" placeholder="Time">
                                        <label
                                            for="session_timeout">{{ __('netfusion.session_timeout_placeholder') }}</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="text" name="keepalive_timeout"
                                            class="form-control bg-light border border-secondary border-opacity-25"
                                            id="keepalive_timeout" placeholder="Time">
                                        <label
                                            for="keepalive_timeout">{{ __('netfusion.keepalive_timeout_placeholder') }}</label>
                                    </div>
                                </div>
                            </div>

                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="text" name="status_autorefresh"
                                            class="form-control bg-light border border-secondary border-opacity-25"
                                            id="status_autorefresh" value="1m">
                                        <label for="status_autorefresh">{{ __('netfusion.status_autorefresh') }}</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <select name="transparent_proxy"
                                            class="form-select bg-light border border-secondary border-opacity-25"
                                            id="transparent_proxy">
                                            <option value="no" selected>{{ __('netfusion.no') }}</option>
                                            <option value="yes">{{ __('netfusion.yes') }}</option>
                                        </select>
                                        <label for="transparent_proxy">{{ __('netfusion.transparent_proxy') }}</label>
                                    </div>
                                </div>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-lg rounded-pill shadow-sm fw-bold">
                                    <i class="bi bi-save me-2"></i>{{ __('netfusion.save_profile') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
