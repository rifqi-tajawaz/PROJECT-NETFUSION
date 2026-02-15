@extends('layouts.app')

@section('content')
    <div class="container-fluid px-4 py-4">
        <div class="mb-4 d-flex flex-column align-items-center justify-content-center text-center">
            <h2 class="display-6 fw-bold mb-2 text-white" style="text-shadow: 0 2px 10px rgba(0,0,0,0.3);">
                <i class="bi bi-stoplights me-2 text-warning"></i> Traffic Engineering (TE)
            </h2>
            <p class="lead opacity-75 mb-0" style="max-width: 700px;">
                Configure MPLS Traffic Engineering tunnels for explicit path control.
            </p>
        </div>

        <form id="teForm" data-route="{{ route('mikrotik-suite.network.enterprise.traffic-engineering.generate') }}">
            <div class="row g-4 justify-content-center">
                <div class="col-lg-5">
                    <div class="glass-card h-100">
                        <div class="card-header border-0 bg-transparent pt-4 px-4">
                            <h5 class="card-title fw-bold text-white mb-0">Tunnel Settings</h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="mb-3">
                                <label class="form-label text-white small text-uppercase">Tunnel Name</label>
                                <input type="text" class="form-control" name="tunnel_name" id="name" value="te-tunnel1">
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-white small text-uppercase">To Address (Remote
                                    Loopback)</label>
                                <input type="text" class="form-control" name="to_address" id="to" placeholder="10.255.0.2">
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-white small text-uppercase">Bandwidth</label>
                                <div class="input-group">
                                    <input type="number" class="form-control" name="bandwidth" id="bw" value="10">
                                    <span class="input-group-text bg-dark border-secondary text-white">Mbps</span>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-white small text-uppercase">Primary Path</label>
                                <input type="text" class="form-control" name="primary_path" id="path" value="dynamic">
                                <div class="form-text text-white-50">Name of explicit path or 'dynamic'.</div>
                            </div>

                            <div class="d-grid mt-4">
                                <button type="submit" class="btn btn-warning text-dark btn-lg shadow-lg">
                                    <span class="btn-label">Generate TE Config</span>
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
    @vite(['resources/js/pages/mikrotik-suite/network/enterprise/traffic-engineering.js'])
@endpush
