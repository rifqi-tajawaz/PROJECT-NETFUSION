@extends('layouts.app')

@section('content')
    <x-page-header title="{{ __('mikrotik-suite.network.load_balancing.ecmp.title') }}" subtitle="{{ __('mikrotik-suite.network.load_balancing.ecmp.subtitle') }}">
        <x-slot name="action">
            <div class="d-flex align-items-center justify-content-center gap-2 px-3 py-2 rounded-pill bg-primary bg-opacity-10 border border-primary border-opacity-10 text-primary small fw-medium text-nowrap mx-auto ms-md-auto"
                style="width: auto !important;">
                <i class="bi bi-distribute-horizontal fs-6"></i>
                <span>{{ __('mikrotik-suite.network.load_balancing.ecmp.title') }}</span>
            </div>
        </x-slot>
    </x-page-header>

    <div class="row g-4 mb-3 mb-md-5">
        {{-- Configuration Panel --}}
        <div class="col-lg-5">
            <div class="glass-card-static p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h5 class="fw-bold text-dark mb-1">{{ __('mikrotik-suite.network.load_balancing.form.configuration') }}
                        </h5>
                        <p class="text-muted small mb-0">{{ __('mikrotik-suite.network.load_balancing.guide.setup_wan') }}</p>
                    </div>
                    <div class="d-flex justify-content-center align-items-center rounded-circle bg-primary bg-opacity-10 text-primary"
                        style="width: 40px; height: 40px;">
                        <i class="bi bi-sliders fs-5"></i>
                    </div>
                </div>

                <form id="ecmpForm" class="no-loader"
                    data-route="{{ route('mikrotik-suite.network.load-balancing.ecmp.generate') }}">
                    {{-- Global Settings --}}
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label
                                class="form-label text-secondary small fw-bold text-uppercase">{{ __('mikrotik-suite.network.load_balancing.form.wan_lines') }}</label>
                            <input type="number" class="form-control glass-input" name="wan_count" id="wanCount" value="2"
                                min="2" max="20"
                                placeholder="{{ __('mikrotik-suite.network.load_balancing.form.number_of_wans') }}">
                        </div>
                        <div class="col-md-6">
                            <label
                                class="form-label text-secondary small fw-bold text-uppercase">{{ __('mikrotik-suite.network.load_balancing.form.routeros_version') }}</label>
                            <select class="form-select glass-select" name="ros_version" id="rosVersion">
                                <option value="v7.xx">RouterOS v7.xx</option>
                                <option value="v6.xx" selected>RouterOS v6.xx</option>
                            </select>
                        </div>
                    </div>

                    {{-- Local Target (ECMP might strictly use 0.0.0.0/0 usually, but maybe user wants PBR?) --}}
                    {{-- Keeping it for UI Consistency --}}


                    {{-- Advanced Toggles --}}
                    <div class="d-flex flex-column flex-md-row gap-3 mb-4">
                        <div class="form-check form-switch custom-switch">
                            <input class="form-check-input" type="checkbox" name="feature_failover" value="1"
                                id="featureFailover">
                            <label class="form-check-label text-secondary fw-semibold small" for="featureFailover">
                                {{ __('mikrotik-suite.network.load_balancing.form.recursive_gateway') }}
                            </label>
                        </div>
                        <div class="form-check form-switch custom-switch">
                            <input class="form-check-input" type="checkbox" name="feature_ratio" value="1"
                                id="featureRatio">
                            <label class="form-check-label text-secondary fw-semibold small" for="featureRatio">
                                {{ __('mikrotik-suite.network.load_balancing.form.bandwidth_ratio') }}
                            </label>
                        </div>
                    </div>

                    {{-- Gateway Guide --}}
                    <div class="alert alert-light border-0 bg-primary bg-opacity-10 text-primary rounded-3 p-3 mb-4 d-flex align-items-start"
                        role="alert">
                        <i class="bi bi-info-circle-fill me-3 fs-5 mt-1"></i>
                        <div>
                            <h6 class="fw-bold mb-1">{{ __('mikrotik-suite.network.load_balancing.guide.title') }}</h6>
                            <p class="small mb-0 opacity-75">
                                {!! __('mikrotik-suite.network.load_balancing.guide.text') !!}
                            </p>
                        </div>
                    </div>

                    <div class="table-responsive mb-4 custom-scrollbar">
                        <table class="table table-glass align-middle mb-0">
                            <thead>
                                <tr>
                                    <th class="py-2 text-uppercase text-center" style="width: 25%;">
                                        {{ __('mikrotik-suite.network.load_balancing.form.wan_interface') }}</th>
                                    <th class="py-2 text-uppercase text-center" style="width: 25%;">
                                        {{ __('mikrotik-suite.network.load_balancing.form.gateway_ip') }}</th>
                                    <th class="py-2 text-uppercase text-center" style="width: 25%;">
                                        {{ __('mikrotik-suite.network.load_balancing.form.speed_mbps') }}</th>
                                    <th class="py-2 text-uppercase text-center" style="width: 25%;">
                                        {{ __('mikrotik-suite.network.load_balancing.form.ip_dns_check') }}</th>
                                </tr>
                            </thead>
                            <tbody id="wanContainer">
                                {{-- JS Injected --}}
                            </tbody>
                        </table>
                    </div>

                    {{-- Actions --}}
                    <button type="submit" class="btn btn-primary w-100 rounded-pill py-2 fw-bold shadow-sm mb-2">
                        <i
                            class="bi bi-lightning-charge-fill me-2"></i>{{ __('mikrotik-suite.network.load_balancing.actions.generate_script') }}
                    </button>
                    <button type="button" class="btn btn-light w-100 rounded-pill py-2 fw-bold text-secondary border"
                        id="btnReset">
                        {{ __('mikrotik-suite.network.load_balancing.actions.reset_all') }}
                    </button>
                </form>
            </div>
        </div>

        {{-- Script Output --}}
        <div class="col-lg-7">
            <div class="glass-card-static h-100 d-flex flex-column overflow-hidden">
                <div class="d-flex justify-content-between align-items-center px-3 py-2 border-bottom border-dark bg-dark">
                    <div class="d-flex align-items-center gap-2">
                        <span class="rounded-circle bg-danger" style="width: 10px; height: 10px;"></span>
                        <span class="rounded-circle bg-warning" style="width: 10px; height: 10px;"></span>
                        <span class="rounded-circle bg-success" style="width: 10px; height: 10px;"></span>
                        <span class="font-monospace small text-muted fw-bold ms-2">terminal-output</span>
                    </div>
                    <button class="btn btn-sm btn-dark border border-secondary text-light fw-bold py-0"
                        onclick="copyText('scriptOutput')">
                        <i class="bi bi-clipboard me-1"></i> Copy
                    </button>
                </div>

                <div class="position-relative overflow-hidden h-100 bg-dark">
                    <div class="code-editor d-flex h-100 border-0">
                        <div class="code-gutter border-end border-secondary" id="lineNumbers">1</div>
                        <div class="code-content custom-scrollbar" id="scriptOutput" contenteditable="false"
                            spellcheck="false"># Generated routeros script will appear here...</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('css')
    @vite(['resources/sass/pages/mikrotik-suite/network/load-balancing.scss'])
@endpush

@push('scripts')
    @vite(['resources/js/pages/mikrotik-suite/network/load-balancing/ecmp.js'])
@endpush
