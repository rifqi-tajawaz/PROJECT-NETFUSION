@extends('layouts.app')

@section('content')
    <div class="container-fluid px-4 py-4">
        <div class="mb-4 d-flex flex-column align-items-center justify-content-center text-center">
            <h2 class="display-6 fw-bold mb-2 text-white" style="text-shadow: 0 2px 10px rgba(0,0,0,0.3);">
                <i class="bi bi-shuffle me-2 text-warning"></i> Port Static Routing (1:1 NAT)
            </h2>
            <p class="lead opacity-75 mb-0" style="max-width: 700px;">
                Map a Public IP directly to a Private IP (DMZ / 1:1 NAT).
            </p>
        </div>

        <form id="nat11Form">
            <div class="row g-4 justify-content-center">
                <div class="col-lg-5">
                    <div class="glass-card h-100">
                        <div class="card-header border-0 bg-transparent pt-4 px-4">
                            <h5 class="card-title fw-bold text-white mb-0">NAT Mapping</h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="mb-3">
                                <label class="form-label text-white small text-uppercase">Public IP (WAN Alias)</label>
                                <input type="text" class="form-control" id="pubIp" placeholder="203.0.113.5">
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-white small text-uppercase">Private IP (LAN)</label>
                                <input type="text" class="form-control" id="privIp" placeholder="192.168.88.5">
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-white small text-uppercase">WAN Interface</label>
                                <input type="text" class="form-control" id="wan" value="ether1">
                            </div>

                            <div class="d-grid mt-4">
                                <button type="submit" class="btn btn-warning text-dark btn-lg shadow-lg">Generate 1:1
                                    Rules</button>
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
                                <i class="bi bi-info-circle me-1"></i> Requires both DST-NAT (Incoming) and SRC-NAT
                                (Outgoing).
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
    @vite(['resources/js/pages/mikrotik-suite/security/firewall/port-static-routing.js'])
@endpush
