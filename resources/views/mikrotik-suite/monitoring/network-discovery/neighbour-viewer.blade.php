@extends('layouts.app')

@section('content')
    <div class="container-fluid px-4 py-4">
        <div class="mb-4 d-flex flex-column align-items-center justify-content-center text-center">
            <h2 class="display-6 fw-bold mb-2 text-white" style="text-shadow: 0 2px 10px rgba(0,0,0,0.3);">
                <i class="bi bi-people me-2 text-primary"></i> Neighbour Viewer
            </h2>
            <p class="lead opacity-75 mb-0" style="max-width: 700px;">
                Configure Layer 2 Discovery settings (MNDP, CDP, LLDP).
            </p>
        </div>

        <form id="ndForm" data-route="{{ route('mikrotik-suite.monitoring.neighbour-viewer.generate') }}">
            <div class="row g-4 justify-content-center">
                <div class="col-lg-5">
                    <div class="glass-card h-100">
                        <div class="card-header border-0 bg-transparent pt-4 px-4">
                            <h5 class="card-title fw-bold text-white mb-0">Discovery Settings</h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="mb-3">
                                <label class="form-label text-white small text-uppercase">Discovery Interfaces</label>
                                <select class="form-select" name="interface_list" id="ifaceList">
                                    <option value="all">all</option>
                                    <option value="LAN">LAN (List)</option>
                                    <option value="WAN">WAN (List)</option>
                                    <option value="none">none</option>
                                </select>
                                <div class="form-text text-white-50">Select interface list to broadcast L2 discovery.</div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label text-white small text-uppercase">Protocols (Global)</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="cdp" id="cdp" value="1" checked>
                                    <label class="form-check-label text-white" for="cdp">CDP (Cisco)</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="lldp" id="lldp" value="1" checked>
                                    <label class="form-check-label text-white" for="lldp">LLDP (Standard)</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="mndp" id="mndp" value="1" checked>
                                    <label class="form-check-label text-white" for="mndp">MNDP (MikroTik)</label>
                                </div>
                            </div>

                            <div class="d-grid mt-4">
                                <button type="submit" class="btn btn-primary btn-lg shadow-lg">
                                    <span class="btn-label">Generate Config</span>
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
                                <div class="alert alert-info bg-info bg-opacity-10 border-0 text-info m-3 small">
                                    <i class="bi bi-info-circle me-1"></i> Use <code>/ip neighbor print</code> to view
                                    neighbors.
                                </div>
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
    @vite(['resources/js/pages/mikrotik-suite/monitoring/network-discovery/neighbour-viewer.js'])
@endpush
