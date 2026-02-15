@extends('layouts.app')

@section('content')
    <div class="container-fluid px-4 py-4">
        <div class="mb-4 d-flex flex-column align-items-center justify-content-center text-center">
            <h2 class="display-6 fw-bold mb-2 text-white" style="text-shadow: 0 2px 10px rgba(0,0,0,0.3);">
                <i class="bi bi-diagram-2-fill me-2 text-warning"></i> OSPF Generator
            </h2>
            <p class="lead opacity-75 mb-0" style="max-width: 700px;">
                Configure Single Area OSPF (Area 0.0.0.0).
            </p>
        </div>

        <form id="ospfForm" data-route="{{ route('mikrotik-suite.network.routing.ospf-generator.generate') }}">
            <div class="row g-4 justify-content-center">
                <div class="col-lg-5">
                    <div class="glass-card h-100">
                        <div class="card-header border-0 bg-transparent pt-4 px-4">
                            <h5 class="card-title fw-bold text-white mb-0">OSPF Settings</h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="mb-3">
                                <label class="form-label text-white small text-uppercase">Router ID</label>
                                <input type="text" class="form-control" name="router_id" id="rid"
                                    placeholder="10.255.255.1">
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-white small text-uppercase">Network</label>
                                <input type="text" class="form-control" name="network" id="net" placeholder="10.0.0.0/8">
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-white small text-uppercase">Area</label>
                                <input type="text" class="form-control" name="area" id="area" value="backbone">
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="redistribute_connected" id="redist"
                                    value="1" checked>
                                <label class="form-check-label text-white" for="redist">Redistribute Connected
                                    Routes</label>
                            </div>
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" name="redistribute_default" id="def"
                                    value="1" checked>
                                <label class="form-check-label text-white" for="def">Redistribute Default Route (if
                                    installed)</label>
                            </div>

                            <div class="d-grid mt-4">
                                <button type="submit" class="btn btn-warning text-dark btn-lg shadow-lg">
                                    <span class="btn-label">Generate OSPF Config</span>
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
    @vite(['resources/js/pages/mikrotik-suite/network/config/ospf-generator.js'])
@endpush
