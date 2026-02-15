@extends('layouts.app')

@section('content')
    <div class="container-fluid px-4 py-4">
        <div class="mb-4 d-flex flex-column align-items-center justify-content-center text-center">
            <h2 class="display-6 fw-bold mb-2 text-white" style="text-shadow: 0 2px 10px rgba(0,0,0,0.3);">
                <i class="bi bi-globe me-2 text-primary"></i> BGP Generator
            </h2>
            <p class="lead opacity-75 mb-0" style="max-width: 700px;">
                Configure basic BGP Peering and Network Advertisement.
            </p>
        </div>

        <form id="bgpForm" data-route="{{ route('mikrotik-suite.network.routing.bgp-generator.generate') }}">
            <div class="row g-4 justify-content-center">
                <div class="col-lg-5">
                    <div class="glass-card h-100">
                        <div class="card-header border-0 bg-transparent pt-4 px-4">
                            <h5 class="card-title fw-bold text-white mb-0">Peering Details</h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="mb-3">
                                <label class="form-label text-white small text-uppercase">Local ASN</label>
                                <input type="number" class="form-control" name="local_as" id="localAs" placeholder="65001">
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-white small text-uppercase">Router ID</label>
                                <input type="text" class="form-control" name="router_id" id="routerId"
                                    placeholder="1.1.1.1">
                            </div>
                            <hr class="border-secondary opacity-25">
                            <div class="mb-3">
                                <label class="form-label text-white small text-uppercase">Remote ASN</label>
                                <input type="number" class="form-control" name="remote_as" id="remoteAs"
                                    placeholder="65002">
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-white small text-uppercase">Remote IP</label>
                                <input type="text" class="form-control" name="remote_ip" id="remoteIp"
                                    placeholder="2.2.2.2">
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-white small text-uppercase">Network to Advertise</label>
                                <input type="text" class="form-control" name="network" id="network"
                                    placeholder="192.168.0.0/22">
                            </div>

                            <div class="d-grid mt-4">
                                <button type="submit" class="btn btn-primary btn-lg shadow-lg">
                                    <span class="btn-label">Generate BGP Config</span>
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
    @vite(['resources/js/pages/mikrotik-suite/network/config/bgp-generator.js'])
@endpush
