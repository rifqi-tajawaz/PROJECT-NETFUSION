@extends('layouts.app')

@section('content')
    <div class="container-fluid px-4 py-4">
        <div class="mb-4 d-flex flex-column align-items-center justify-content-center text-center">
            <h2 class="display-6 fw-bold mb-2 text-white" style="text-shadow: 0 2px 10px rgba(0,0,0,0.3);">
                <i class="bi bi-hdd-rack me-2 text-warning"></i> PPPoE Server Wizard
            </h2>
            <p class="lead opacity-75 mb-0" style="max-width: 700px;">
                Quickly configure a PPPoE Server with IP Pools, Profiles, and Security settings for ISP deployment.
            </p>
        </div>

        <form id="pppoeForm" data-route="{{ route('mikrotik-suite.connectivity.pppoe.server.generate') }}">
            <div class="row g-4">
                <div class="col-lg-5">
                    <div class="glass-card h-100">
                        <div class="card-header border-0 bg-transparent pt-4 px-4">
                            <h5 class="card-title fw-bold text-white mb-0">Server Config</h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="mb-3">
                                <label class="form-label text-white small text-uppercase">Service Name</label>
                                <input type="text" class="form-control" name="service_name" id="serviceName"
                                    value="pppoe-service1">
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-white small text-uppercase">Interface</label>
                                <input type="text" class="form-control" name="interface" id="interface" value="ether2">
                                <small class="text-muted">MikroTik Interface Name (e.g. ether2, bridge-local)</small>
                            </div>
                            <div class="mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="one_session" id="oneSession"
                                        value="1" checked>
                                    <label class="form-check-label text-white" for="oneSession">One Session Per Host</label>
                                </div>
                            </div>

                            <hr class="border-secondary opacity-50 my-4">

                            <div class="mb-3">
                                <label class="form-label text-white small text-uppercase">Profile Name</label>
                                <input type="text" class="form-control" name="profile_name" id="profileName"
                                    value="profile-pppoe">
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-white small text-uppercase">Local Address (Gateway)</label>
                                <input type="text" class="form-control font-monospace" name="local_addr" id="localAddr"
                                    value="10.50.50.1">
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-white small text-uppercase">Remote Address (Pool)</label>
                                <input type="text" class="form-control font-monospace" name="remote_addr" id="remoteAddr"
                                    value="10.50.50.10-10.50.50.254">
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-white small text-uppercase">DNS Servers</label>
                                <input type="text" class="form-control font-monospace" name="dns1" id="dns1"
                                    value="8.8.8.8">
                                <input type="text" class="form-control font-monospace mt-2" name="dns2" id="dns2"
                                    value="8.8.4.4">
                            </div>

                            <div class="d-grid mt-4">
                                <button type="submit" class="btn btn-primary btn-lg shadow-lg"
                                    style="background: linear-gradient(90deg, #fd7e14, #d63384); border:none;">
                                    <span class="btn-label"><i class="bi bi-code-square me-2"></i> Generate Script</span>
                                    <span class="btn-loader d-none"><span
                                            class="spinner-border spinner-border-sm me-2"></span>Generating...</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-7">
                    <div class="glass-card h-100">
                        <div class="card-header border-0 bg-transparent pt-4 px-4 d-flex justify-content-between">
                            <h5 class="card-title fw-bold text-white mb-0">RouterOS Script</h5>
                            <button type="button" class="btn btn-sm btn-outline-light" onclick="copyText('scriptOutput')"><i
                                    class="bi bi-clipboard"></i> Copy</button>
                        </div>
                        <div class="card-body p-0">
                            <pre class="m-0 p-4 text-warning bg-dark bg-opacity-50 h-100"
                                style="min-height:300px; overflow-y: auto; font-family: 'JetBrains Mono', monospace;"
                                id="scriptOutput">// Script will appear here...</pre>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    @vite(['resources/js/pages/mikrotik-suite/connectivity/pppoe/pppoe-server.js'])
@endpush
