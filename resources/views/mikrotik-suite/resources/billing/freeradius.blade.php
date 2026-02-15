@extends('layouts.app')

@section('content')
    <div class="container-fluid px-4 py-4">
        <div class="mb-4 d-flex flex-column align-items-center justify-content-center text-center">
            <h2 class="display-6 fw-bold mb-2 text-white" style="text-shadow: 0 2px 10px rgba(0,0,0,0.3);">
                <i class="bi bi-server me-2 text-primary"></i> FreeRADIUS Client
            </h2>
            <p class="lead opacity-75 mb-0" style="max-width: 700px;">
                Configure RouterOS as a NAS client for FreeRADIUS/Daloradius/DMA.
            </p>
        </div>

        <form id="radiusForm" data-route="{{ route('mikrotik-suite.resources.billing.freeradius.generate') }}">
            <div class="row g-4 justify-content-center">
                <div class="col-lg-5">
                    <div class="glass-card h-100">
                        <div class="card-header border-0 bg-transparent pt-4 px-4">
                            <h5 class="card-title fw-bold text-white mb-0">AAA Settings</h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="mb-3">
                                <label class="form-label text-white small text-uppercase">RADIUS Server IP</label>
                                <input type="text" class="form-control" id="server" placeholder="192.168.88.254">
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-white small text-uppercase">Shared Secret</label>
                                <input type="text" class="form-control" id="secret" value="radius123">
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-white small text-uppercase">Service</label>
                                <div class="d-flex gap-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="svcHotspot" checked>
                                        <label class="form-check-label text-white small" for="svcHotspot">Hotspot</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="svcPpp">
                                        <label class="form-check-label text-white small" for="svcPpp">PPP</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="svcLogin">
                                        <label class="form-check-label text-white small" for="svcLogin">Login</label>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-white small text-uppercase">Timeout</label>
                                <div class="input-group">
                                    <input type="number" class="form-control" id="timeout" value="300">
                                    <span class="input-group-text bg-dark border-secondary text-white">ms</span>
                                </div>
                            </div>

                            <div class="d-grid mt-4">
                                <button type="submit" class="btn btn-primary btn-lg shadow-lg">Generate Radius
                                    Config</button>
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
    @vite(['resources/js/pages/mikrotik-suite/resources/billing/freeradius.js'])
@endpush
