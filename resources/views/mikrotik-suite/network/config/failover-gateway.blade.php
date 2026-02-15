@extends('layouts.app')

@section('content')
    <div class="container-fluid px-4 py-4">
        <div class="mb-4 d-flex flex-column align-items-center justify-content-center text-center">
            <h2 class="display-6 fw-bold mb-2 text-white" style="text-shadow: 0 2px 10px rgba(0,0,0,0.3);">
                <i class="bi bi-arrow-left-right me-2 text-primary"></i> Failover Gateway
            </h2>
            <p class="lead opacity-75 mb-0" style="max-width: 700px;">
                Configure dual WAN failover with recursive routing check.
            </p>
        </div>

        <form id="failoverForm" data-route="{{ route('mikrotik-suite.network.routing.failover-gateway.generate') }}">
            <div class="row g-4 justify-content-center">
                <div class="col-lg-5">
                    <div class="glass-card h-100">
                        <div class="card-header border-0 bg-transparent pt-4 px-4">
                            <h5 class="card-title fw-bold text-white mb-0">ISP Details</h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="mb-3">
                                <label class="form-label text-white small text-uppercase">ISP 1 Gateway (Primary)</label>
                                <input type="text" class="form-control" name="gateway_1" id="gw1" placeholder="192.168.1.1">
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-white small text-uppercase">Check Host 1</label>
                                <input type="text" class="form-control" name="check_host_1" id="chk1" value="8.8.8.8">
                                <div class="form-text text-white-50">Public IP routed via ISP 1</div>
                            </div>
                            <hr class="border-secondary opacity-25">
                            <div class="mb-3">
                                <label class="form-label text-white small text-uppercase">ISP 2 Gateway (Backup)</label>
                                <input type="text" class="form-control" name="gateway_2" id="gw2" placeholder="192.168.2.1">
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-white small text-uppercase">Check Host 2</label>
                                <input type="text" class="form-control" name="check_host_2" id="chk2" value="1.1.1.1">
                                <div class="form-text text-white-50">Public IP routed via ISP 2</div>
                            </div>

                            <div class="d-grid mt-4">
                                <button type="submit" class="btn btn-primary btn-lg shadow-lg">
                                    <span class="btn-label">Generate Failover Script</span>
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
    @vite(['resources/js/pages/mikrotik-suite/network/config/failover-gateway.js'])
@endpush
