@extends('layouts.app')

@section('content')
    <div class="container-fluid px-4 py-4">
        <div class="mb-4 d-flex flex-column align-items-center justify-content-center text-center">
            <h2 class="display-6 fw-bold mb-2 text-white" style="text-shadow: 0 2px 10px rgba(0,0,0,0.3);">
                <i class="bi bi-heart-pulse me-2 text-danger"></i> Netwatch Alert
            </h2>
            <p class="lead opacity-75 mb-0" style="max-width: 700px;">
                Monitor network reachability (ICMP) and trigger actions when a host goes down or comes up.
            </p>
        </div>

        <form id="netwatchForm" data-route="{{ route('mikrotik-suite.monitoring.netwatch-alert.generate') }}">
            <div class="row g-4 justify-content-center">
                <div class="col-lg-5">
                    <div class="glass-card h-100">
                        <div class="card-header border-0 bg-transparent pt-4 px-4">
                            <h5 class="card-title fw-bold text-white mb-0">Configuration</h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="mb-3">
                                <label class="form-label text-white small text-uppercase">Host to Monitor</label>
                                <input type="text" class="form-control font-monospace" name="host" id="host"
                                    placeholder="8.8.8.8">
                                <small class="text-white-50">IP Address of the target.</small>
                            </div>
                            <div class="row g-3">
                                <div class="col-6">
                                    <label class="form-label text-white small text-uppercase">Interval</label>
                                    <input type="text" class="form-control" name="interval" id="interval" value="00:01:00">
                                </div>
                                <div class="col-6">
                                    <label class="form-label text-white small text-uppercase">Timeout</label>
                                    <input type="number" class="form-control" name="timeout" id="timeout" value="1000">
                                    <small class="text-white-50">ms</small>
                                </div>
                            </div>
                            <div class="mb-3 mt-3">
                                <label class="form-label text-white small text-uppercase">Notification</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="log_action" id="logAction"
                                        value="1" checked>
                                    <label class="form-check-label text-white" for="logAction">System Log</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="email_action" id="emailAction"
                                        value="1">
                                    <label class="form-check-label text-white" for="emailAction">Send Email</label>
                                </div>
                            </div>
                            <div class="mb-3" id="emailGroup" style="display:none;">
                                <label class="form-label text-white small text-uppercase">Email To</label>
                                <input type="email" class="form-control" name="email_to" id="emailTo"
                                    placeholder="admin@example.com">
                            </div>

                            <div class="d-grid mt-4">
                                <button type="submit" class="btn btn-danger btn-lg shadow-lg">
                                    <span class="btn-label">Generate Netwatch Script</span>
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
                            <pre class="m-0 p-4 text-warning bg-dark bg-opacity-50 h-100 font-monospace" id="scriptOutput"
                                style="min-height:300px;">// Script...</pre>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    @vite(['resources/js/pages/mikrotik-suite/monitoring/network-monitoring/netwatch-alert.js'])
@endpush
