@extends('layouts.app')

@section('content')
    <div class="container-fluid px-4 py-4">
        <div class="mb-4 d-flex flex-column align-items-center justify-content-center text-center">
            <h2 class="display-6 fw-bold mb-2 text-white" style="text-shadow: 0 2px 10px rgba(0,0,0,0.3);">
                <i class="bi bi-arrow-right-circle me-2 text-primary"></i> Forward Chain Security
            </h2>
            <p class="lead opacity-75 mb-0" style="max-width: 700px;">
                Control traffic passing THROUGH the router (LAN to WAN, WAN to LAN).
            </p>
        </div>

        <form id="fwdForm">
            <div class="row g-4 justify-content-center">
                <div class="col-lg-5">
                    <div class="glass-card h-100">
                        <div class="card-header border-0 bg-transparent pt-4 px-4">
                            <h5 class="card-title fw-bold text-white mb-0">Policy</h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="est" checked>
                                <label class="form-check-label text-white" for="est">Accept Established/Related</label>
                            </div>
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="inv" checked>
                                <label class="form-check-label text-white" for="inv">Drop Invalid</label>
                            </div>
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="fasttrack" checked>
                                <label class="form-check-label text-white" for="fasttrack">Enable FastTrack</label>
                            </div>
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="dropWan" checked>
                                <label class="form-check-label text-white" for="dropWan">Drop all from WAN not
                                    DSTNATed</label>
                            </div>

                            <div class="d-grid mt-4">
                                <button type="submit" class="btn btn-primary btn-lg shadow-lg">Generate Forward
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
    @vite(['resources/js/pages/mikrotik-suite/security/advanced/forward-chain.js'])
@endpush
