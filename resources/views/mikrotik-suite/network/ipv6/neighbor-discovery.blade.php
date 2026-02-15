@extends('layouts.app')

@section('content')
    <div class="container-fluid px-4 py-4">
        <div class="mb-4 d-flex flex-column align-items-center justify-content-center text-center">
            <h2 class="display-6 fw-bold mb-2 text-white" style="text-shadow: 0 2px 10px rgba(0,0,0,0.3);">
                <i class="bi bi-broadcast me-2 text-warning"></i> IPv6 Neighbor Discovery
            </h2>
            <p class="lead opacity-75 mb-0" style="max-width: 700px;">
                Configure Router Advertisements (RA) for SLAAC.
            </p>
        </div>

        <form id="ndForm" data-route="{{ route('mikrotik-suite.network.ipv6.neighbor-discovery.generate') }}">
            <div class="row g-4 justify-content-center">
                <div class="col-lg-5">
                    <div class="glass-card h-100">
                        <div class="card-header border-0 bg-transparent pt-4 px-4">
                            <h5 class="card-title fw-bold text-white mb-0">RA Settings</h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="mb-3">
                                <label class="form-label text-white small text-uppercase">Interface</label>
                                <input type="text" class="form-control" name="interface" id="iface" value="bridge1">
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" name="managed" id="manage" value="1">
                                <label class="form-check-label text-white" for="manage">Managed Address Config (M Flag -
                                    DHCPv6)</label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" name="other" id="other" value="1" checked>
                                <label class="form-check-label text-white" for="other">Other Config (O Flag -
                                    DNS/etc)</label>
                            </div>
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" name="advertise_mac" id="advertise"
                                    value="1" checked>
                                <label class="form-check-label text-white" for="advertise">Advertise MAC Address</label>
                            </div>

                            <div class="mb-3">
                                <label class="form-label text-white small text-uppercase">RA Interval (sec)</label>
                                <div class="row">
                                    <div class="col-6"><input type="number" class="form-control" name="min_interval"
                                            id="min" value="200" placeholder="Min"></div>
                                    <div class="col-6"><input type="number" class="form-control" name="max_interval"
                                            id="max" value="600" placeholder="Max"></div>
                                </div>
                            </div>

                            <div class="d-grid mt-4">
                                <button type="submit" class="btn btn-warning text-dark btn-lg shadow-lg">
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
    @vite(['resources/js/pages/mikrotik-suite/network/ipv6/neighbor-discovery.js'])
@endpush
