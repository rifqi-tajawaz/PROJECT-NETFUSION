@extends('layouts.app')

@section('content')
    <div class="container-fluid px-4 py-4">
        <div class="mb-4 d-flex flex-column align-items-center justify-content-center text-center">
            <h2 class="display-6 fw-bold mb-2 text-white" style="text-shadow: 0 2px 10px rgba(0,0,0,0.3);">
                <i class="bi bi-clock-history me-2 text-warning"></i> NTP Client
            </h2>
            <p class="lead opacity-75 mb-0" style="max-width: 700px;">
                Synchronize your router's clock with reliable Network Time Protocol servers.
            </p>
        </div>

        <form id="ntpForm" data-route="{{ route('mikrotik-suite.monitoring.ntp-client.generate') }}">
            <div class="row g-4 justify-content-center">
                <div class="col-lg-5">
                    <div class="glass-card h-100">
                        <div class="card-header border-0 bg-transparent pt-4 px-4">
                            <h5 class="card-title fw-bold text-white mb-0">Settings</h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="mb-3">
                                <label class="form-label text-white small text-uppercase">Time Zone</label>
                                <input type="text" class="form-control" name="timezone" id="tz" value="Asia/Jakarta">
                                <div class="form-text text-white-50">e.g. Asia/Jakarta, Europe/London</div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-white small text-uppercase">Primary Server</label>
                                <input type="text" class="form-control" name="primary_ntp" id="primary"
                                    value="0.id.pool.ntp.org">
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-white small text-uppercase">Secondary Server</label>
                                <input type="text" class="form-control" name="secondary_ntp" id="secondary"
                                    value="1.id.pool.ntp.org">
                            </div>
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" name="server_mode" id="serverMode"
                                    value="1">
                                <label class="form-check-label text-white" for="serverMode">Enable NTP Server (Serve time to
                                    LAN)</label>
                            </div>

                            <div class="d-grid mt-4">
                                <button type="submit" class="btn btn-warning btn-lg shadow-lg text-dark">
                                    <span class="btn-label">Generate NTP Config</span>
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
    @vite(['resources/js/pages/mikrotik-suite/monitoring/dns-time/ntp-client.js'])
@endpush
