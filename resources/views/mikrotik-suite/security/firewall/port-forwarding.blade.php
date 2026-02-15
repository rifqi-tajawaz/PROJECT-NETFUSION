@extends('layouts.app')

@section('content')
    <div class="container-fluid px-4 py-4">
        <div class="mb-4 d-flex flex-column align-items-center justify-content-center text-center">
            <h2 class="display-6 fw-bold mb-2 text-white" style="text-shadow: 0 2px 10px rgba(0,0,0,0.3);">
                <i class="bi bi-arrow-return-right me-2 text-primary"></i> Port Forwarding (DST-NAT)
            </h2>
            <p class="lead opacity-75 mb-0" style="max-width: 700px;">
                Expose internal services to the outside world safely.
            </p>
        </div>

        <form id="pfForm">
            <div class="row g-4 justify-content-center">
                <div class="col-lg-5">
                    <div class="glass-card h-100">
                        <div class="card-header border-0 bg-transparent pt-4 px-4">
                            <h5 class="card-title fw-bold text-white mb-0">Service Details</h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="mb-3">
                                <label class="form-label text-white small text-uppercase">WAN Interface</label>
                                <input type="text" class="form-control" id="wan" value="ether1">
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-white small text-uppercase">Public Port</label>
                                <input type="number" class="form-control" id="pubPort" placeholder="8080">
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-white small text-uppercase">Internal IP</label>
                                <input type="text" class="form-control" id="intIp" placeholder="192.168.88.10">
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-white small text-uppercase">Internal Port</label>
                                <input type="number" class="form-control" id="intPort" placeholder="80">
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-white small text-uppercase">Protocol</label>
                                <select class="form-select" id="proto">
                                    <option value="tcp">TCP</option>
                                    <option value="udp">UDP</option>
                                </select>
                            </div>

                            <div class="d-grid mt-4">
                                <button type="submit" class="btn btn-primary btn-lg shadow-lg">Generate NAT Rule</button>
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
                                <i class="bi bi-info-circle me-1"></i> Ensure you have a 'Hairpin NAT' rule if you need to
                                access this from inside.
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
    @vite(['resources/js/pages/mikrotik-suite/security/firewall/port-forwarding.js'])
@endpush
