@extends('layouts.app')

@section('content')
    <div class="container-fluid px-4 py-4">
        <div class="mb-4 d-flex flex-column align-items-center justify-content-center text-center">
            <h2 class="display-6 fw-bold mb-2 text-white" style="text-shadow: 0 2px 10px rgba(0,0,0,0.3);">
                <i class="bi bi-shield-check me-2 text-success"></i> AdGuard Home Installer
            </h2>
            <p class="lead opacity-75 mb-0" style="max-width: 700px;">
                Deploy AdGuard Home in a container on your RouterOS.
            </p>
        </div>

        <form id="agForm" data-route="{{ route('mikrotik-suite.utilities.container.adguard.generate') }}">
            <div class="row g-4 justify-content-center">
                <div class="col-lg-5">
                    <div class="glass-card h-100">
                        <div class="card-header border-0 bg-transparent pt-4 px-4">
                            <h5 class="card-title fw-bold text-white mb-0">Settings</h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="mb-3">
                                <label class="form-label text-white small text-uppercase">Container IP</label>
                                <input type="text" class="form-control" id="ip" name="ip_address" value="172.17.0.3"
                                    required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-white small text-uppercase">Gateway IP</label>
                                <input type="text" class="form-control" id="gw" name="gateway" value="172.17.0.1" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-white small text-uppercase">Interface</label>
                                <input type="text" class="form-control" id="iface" name="interface" value="veth2" required>
                            </div>

                            <div class="d-grid mt-4">
                                <button type="submit" class="btn btn-success btn-lg shadow-lg">Generate Script</button>
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
    @vite(['resources/js/pages/mikrotik-suite/utilities/container/adguard.js'])
@endpush
