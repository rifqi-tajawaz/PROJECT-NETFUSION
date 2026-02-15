@extends('layouts.app')

@section('content')
    <div class="container-fluid px-4 py-4">
        <div class="mb-4 d-flex flex-column align-items-center justify-content-center text-center">
            <h2 class="display-6 fw-bold mb-2 text-white" style="text-shadow: 0 2px 10px rgba(0,0,0,0.3);">
                <i class="bi bi-hdd-network me-2 text-primary"></i> MPLS / VPLS Generator
            </h2>
            <p class="lead opacity-75 mb-0" style="max-width: 700px;">
                Configure basic LDP and VPLS tunnels for Layer 2 extension over IP.
            </p>
        </div>

        <form id="vplsForm" data-route="{{ route('mikrotik-suite.network.enterprise.ldp-vpls.generate') }}">
            <div class="row g-4 justify-content-center">
                <div class="col-lg-5">
                    <div class="glass-card h-100">
                        <div class="card-header border-0 bg-transparent pt-4 px-4">
                            <h5 class="card-title fw-bold text-white mb-0">LDP & VPLS</h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="mb-3">
                                <label class="form-label text-white small text-uppercase">Router ID (Loopback IP)</label>
                                <input type="text" class="form-control" name="router_id" id="rid" placeholder="10.255.0.1">
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-white small text-uppercase">Transport Interface</label>
                                <input type="text" class="form-control" name="interface" id="iface" value="ether1">
                            </div>

                            <hr class="border-secondary opacity-25">

                            <div class="mb-3">
                                <label class="form-label text-white small text-uppercase">Remote Peer IP</label>
                                <input type="text" class="form-control" name="remote_peer" id="peer"
                                    placeholder="10.255.0.2">
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-white small text-uppercase">VPLS ID</label>
                                <input type="number" class="form-control" name="vpls_id" id="vID" value="100">
                            </div>

                            <div class="d-grid mt-4">
                                <button type="submit" class="btn btn-primary btn-lg shadow-lg">
                                    <span class="btn-label">Generate MPLS Config</span>
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
                            <div
                                class="alert alert-info bg-info bg-opacity-10 border-0 text-info m-3 small border-start border-4 border-info">
                                <i class="bi bi-info-circle me-1"></i> Ensure OSPF is running and Loopback IPs are
                                reachable.
                            </div>
                            <pre class="m-0 p-4 text-warning bg-dark bg-opacity-50 flex-grow-1 font-monospace"
                                id="scriptOutput" style="min-height:300px;">// Script...</pre>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    @vite(['resources/js/pages/mikrotik-suite/network/enterprise/ldp-vpls.js'])
@endpush
