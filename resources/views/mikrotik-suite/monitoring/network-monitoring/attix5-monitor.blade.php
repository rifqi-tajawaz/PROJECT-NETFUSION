@extends('layouts.app')

@section('content')
    <div class="container-fluid px-4 py-4">
        <div class="mb-4 d-flex flex-column align-items-center justify-content-center text-center">
            <h2 class="display-6 fw-bold mb-2 text-white" style="text-shadow: 0 2px 10px rgba(0,0,0,0.3);">
                <i class="bi bi-hdd-rack me-2 text-success"></i> Backup Service Monitor
            </h2>
            <p class="lead opacity-75 mb-0" style="max-width: 700px;">
                Monitor connections to cloud backup services (e.g. Redstor/Attix5) or generic backup servers.
            </p>
        </div>

        <form id="backupForm" data-route="{{ route('mikrotik-suite.monitoring.attix5-monitor.generate') }}">
            <div class="row g-4 justify-content-center">
                <div class="col-lg-5">
                    <div class="glass-card h-100">
                        <div class="card-header border-0 bg-transparent pt-4 px-4">
                            <h5 class="card-title fw-bold text-white mb-0">Configuration</h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="mb-3">
                                <label class="form-label text-white small text-uppercase">Service Name</label>
                                <input type="text" class="form-control" name="service_name" id="svcName"
                                    value="Redstor Backup">
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-white small text-uppercase">Server IP / Host</label>
                                <input type="text" class="form-control" name="server_host" id="host"
                                    placeholder="backup.example.com">
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-white small text-uppercase">Port</label>
                                <input type="number" class="form-control" name="port" id="port" value="443">
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-white small text-uppercase">Check Method</label>
                                <select class="form-select" name="method" id="method">
                                    <option value="tcp">TCP Connection Check</option>
                                    <option value="icmp">Ping (ICMP)</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-white small text-uppercase">Alert Email</label>
                                <input type="email" class="form-control" name="email" id="email"
                                    placeholder="admin@isp.com">
                            </div>

                            <div class="d-grid mt-4">
                                <button type="submit" class="btn btn-success btn-lg shadow-lg">
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
    @vite(['resources/js/pages/mikrotik-suite/monitoring/network-monitoring/attix5-monitor.js'])
@endpush
