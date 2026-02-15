@extends('layouts.app')

@section('content')
    <div class="container-fluid px-4 py-4">
        <div class="mb-4 d-flex flex-column align-items-center justify-content-center text-center">
            <h2 class="display-6 fw-bold mb-2 text-white" style="text-shadow: 0 2px 10px rgba(0,0,0,0.3);">
                <i class="bi bi-speedometer me-2 text-danger"></i> CPU Profiling
            </h2>
            <p class="lead opacity-75 mb-0" style="max-width: 700px;">
                Identify processes consuming high CPU usage.
            </p>
        </div>

        <form id="profileForm" data-route="{{ route('mikrotik-suite.monitoring.troubleshooting.cpu-profiling.generate') }}">
            <div class="row g-4 justify-content-center">
                <div class="col-lg-5">
                    <div class="glass-card h-100">
                        <div class="card-header border-0 bg-transparent pt-4 px-4">
                            <h5 class="card-title fw-bold text-white mb-0">Settings</h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="mb-3">
                                <label class="form-label text-white small text-uppercase">Duration</label>
                                <select class="form-select" name="duration" id="duration">
                                    <option value="1s">1 second (Snapshot)</option>
                                    <option value="10s">10 seconds</option>
                                    <option value="1m">1 minute</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-white small text-uppercase">CPU Core</label>
                                <select class="form-select" name="cpu_core" id="cpu">
                                    <option value="all">All</option>
                                    <option value="0">CPU 0</option>
                                    <option value="1">CPU 1</option>
                                    <!-- Dynamic would be better but static for now -->
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-white small text-uppercase">Sort By</label>
                                <select class="form-select" name="sort_by" id="sort">
                                    <option value="usage">Usage</option>
                                    <option value="name">Name</option>
                                    <option value="total">Total</option>
                                </select>
                            </div>

                            <div class="d-grid mt-4">
                                <button type="submit" class="btn btn-danger btn-lg shadow-lg">
                                    <span class="btn-label">Generate Profile Command</span>
                                    <span class="btn-loader d-none"><span
                                            class="spinner-border spinner-border-sm me-2"></span>Generating...</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="glass-card h-100">
                        <div class="card-header border-0 bg-transparent pt-4 px-4 d-flex justify-content-between">
                            <h5 class="card-title fw-bold text-white mb-0">RouterOS Command</h5>
                            <button type="button" class="btn btn-sm btn-outline-light"
                                onclick="copyText('scriptOutput')">Copy</button>
                        </div>
                        <div class="card-body p-0">
                            <pre class="m-0 p-4 text-warning bg-dark bg-opacity-50 h-100 font-monospace" id="scriptOutput"
                                style="min-height:250px;">// Command...</pre>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    @vite(['resources/js/pages/mikrotik-suite/monitoring/troubleshooting/cpu-profiling.js'])
@endpush
