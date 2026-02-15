@extends('layouts.app')

@section('content')
    <div class="container-fluid px-4 py-4">
        <div class="mb-4 d-flex flex-column align-items-center justify-content-center text-center">
            <h2 class="display-6 fw-bold mb-2 text-white" style="text-shadow: 0 2px 10px rgba(0,0,0,0.3);">
                <i class="bi bi-shield-check me-2 text-success"></i> Management VLAN
            </h2>
            <p class="lead opacity-75 mb-0" style="max-width: 700px;">
                Secure router access by creating a dedicated Management interface on a VLAN.
            </p>
        </div>

        <form id="mgmtForm" data-route="{{ route('mikrotik-suite.network.switching.management-vlan.generate') }}">
            <div class="row g-4 justify-content-center">
                <div class="col-lg-5">
                    <div class="glass-card h-100">
                        <div class="card-header border-0 bg-transparent pt-4 px-4">
                            <h5 class="card-title fw-bold text-white mb-0">Settings</h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="mb-3">
                                <label class="form-label text-white small text-uppercase">VLAN ID</label>
                                <input type="number" class="form-control" name="vlan_id" id="vid" value="99">
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-white small text-uppercase">Parent Interface</label>
                                <input type="text" class="form-control" name="parent_interface" id="parent" value="bridge1">
                                <div class="form-text text-white-50">Usually the bridge if using filtering, or physical
                                    port.</div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-white small text-uppercase">IP Address</label>
                                <input type="text" class="form-control" name="ip_address" id="ip" value="192.168.99.1/24">
                            </div>

                            <div class="d-grid mt-4">
                                <button type="submit" class="btn btn-success btn-lg shadow-lg">
                                    <span class="btn-label">Generate Management Config</span>
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
    @vite(['resources/js/pages/mikrotik-suite/network/vlan/management-vlan.js'])
@endpush
