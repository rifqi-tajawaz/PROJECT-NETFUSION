@extends('layouts.app')

@section('content')
    <div class="container-fluid px-4 py-4">
        <div class="mb-4 d-flex flex-column align-items-center justify-content-center text-center">
            <h2 class="display-6 fw-bold mb-2 text-white" style="text-shadow: 0 2px 10px rgba(0,0,0,0.3);">
                <i class="bi bi-activity me-2 text-primary"></i> Traffic Monitor
            </h2>
            <p class="lead opacity-75 mb-0" style="max-width: 700px;">
                Trigger events when interface traffic exceeds a certain threshold.
            </p>
        </div>

        <form id="tmonForm" data-route="{{ route('mikrotik-suite.monitoring.traffic-monitor.generate') }}">
            <div class="row g-4 justify-content-center">
                <div class="col-lg-5">
                    <div class="glass-card h-100">
                        <div class="card-header border-0 bg-transparent pt-4 px-4">
                            <h5 class="card-title fw-bold text-white mb-0">Configuration</h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="mb-3">
                                <label class="form-label text-white small text-uppercase">Interface</label>
                                <input type="text" class="form-control" name="interface" id="iface" value="ether1-wan">
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-white small text-uppercase">Traffic Type</label>
                                <select class="form-select" name="traffic_type" id="type">
                                    <option value="received">Received (Download)</option>
                                    <option value="transmitted">Transmitted (Upload)</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-white small text-uppercase">Threshold</label>
                                <div class="input-group">
                                    <input type="number" class="form-control" name="threshold" id="threshold" value="50">
                                    <span class="input-group-text bg-dark border-secondary text-white">Mbps</span>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-white small text-uppercase">Trigger On</label>
                                <select class="form-select" name="trigger" id="trigger">
                                    <option value="above">Above Threshold</option>
                                    <option value="below">Below Threshold</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-white small text-uppercase">Event Name</label>
                                <input type="text" class="form-control" name="event_name" id="name"
                                    value="high-traffic-alert">
                            </div>

                            <div class="d-grid mt-4">
                                <button type="submit" class="btn btn-primary btn-lg shadow-lg">
                                    <span class="btn-label">Generate Monitor Script</span>
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
                            <h5 class="card-title fw-bold text-white mb-0">RouterOS Script</h5>
                            <button type="button" class="btn btn-sm btn-outline-light"
                                onclick="copyText('scriptOutput')">Copy</button>
                        </div>
                        <div class="card-body p-0">
                            <div class="h-100 d-flex flex-column">
                                <pre class="m-0 p-4 text-warning bg-dark bg-opacity-50 flex-grow-1 font-monospace"
                                    id="scriptOutput" style="min-height:300px;">// Script...</pre>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    @vite(['resources/js/pages/mikrotik-suite/monitoring/network-monitoring/traffic-monitor.js'])
@endpush
