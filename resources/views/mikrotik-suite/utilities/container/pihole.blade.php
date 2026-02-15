@extends('layouts.app')

@section('content')
    <div class="container-fluid px-4 py-4">
        <div class="mb-4 d-flex flex-column align-items-center justify-content-center text-center">
            <h2 class="display-6 fw-bold mb-2 text-white" style="text-shadow: 0 2px 10px rgba(0,0,0,0.3);">
                <i class="bi bi-box-seam me-2 text-primary"></i> Pi-hole Container Installer
            </h2>
            <p class="lead opacity-75 mb-0" style="max-width: 700px;">
                Deploy Pi-hole DNS sinkhole in a container on your RouterOS (v7+ ARM/x86).
            </p>
        </div>

        <form id="piholeForm" data-route="{{ route('mikrotik-suite.utilities.container.pihole.generate') }}">
            <div class="row g-4 justify-content-center">
                <div class="col-lg-5">
                    <div class="glass-card h-100">
                        <div class="card-header border-0 bg-transparent pt-4 px-4">
                            <h5 class="card-title fw-bold text-white mb-0">Settings</h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="mb-3">
                                <label class="form-label text-white small text-uppercase">Container IP</label>
                                <input type="text" class="form-control" id="ip" name="ip_address" value="172.17.0.2"
                                    required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-white small text-uppercase">Gateway IP (Router)</label>
                                <input type="text" class="form-control" id="gw" name="gateway" value="172.17.0.1" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-white small text-uppercase">Pi-hole Web Password</label>
                                <input type="text" class="form-control" id="pass" name="password" value="admin" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-white small text-uppercase">Interface</label>
                                <input type="text" class="form-control" id="iface" name="interface" value="veth1" required>
                            </div>
                            <div class="row">
                                <div class="col-6 mb-3">
                                    <label class="form-label text-white small text-uppercase">Upstream DNS 1</label>
                                    <input type="text" class="form-control" name="dns1" value="8.8.8.8">
                                </div>
                                <div class="col-6 mb-3">
                                    <label class="form-label text-white small text-uppercase">Upstream DNS 2</label>
                                    <input type="text" class="form-control" name="dns2" value="1.1.1.1">
                                </div>
                            </div>

                            <div class="d-grid mt-4">
                                <button type="submit" class="btn btn-primary btn-lg shadow-lg">Generate Install
                                    Script</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="glass-card h-100">
                        <div class="card-header border-0 bg-transparent pt-4 px-4 d-flex justify-content-between">
                            <h5 class="card-title fw-bold text-white mb-0">RouterOS Script</h5>
                            <button type="button" class="btn btn-sm btn-outline-light" onclick="copyScript()">Copy</button>
                        </div>
                        <div class="card-body p-0">
                            <div
                                class="alert alert-info bg-info bg-opacity-10 border-0 text-info m-3 small border-start border-4 border-info">
                                <i class="bi bi-info-circle me-1"></i> Requires 'container' package to be installed on
                                RouterOS.
                            </div>
                            <pre class="m-0 p-4 text-warning bg-dark bg-opacity-50 flex-grow-1 font-monospace"
                                id="scriptOutput"
                                style="min-height:300px; max-height: 500px; overflow-y: auto;">// Script will appear here...</pre>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    @vite(['resources/js/pages/mikrotik-suite/utilities/container/pihole.js'])
@endpush
