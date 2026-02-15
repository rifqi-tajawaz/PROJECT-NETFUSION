@extends('layouts.app')

@section('content')
    <div class="container-fluid px-4 py-4">
        <div class="mb-4 d-flex flex-column align-items-center justify-content-center text-center">
            <h2 class="display-6 fw-bold mb-2 text-white" style="text-shadow: 0 2px 10px rgba(0,0,0,0.3);">
                <i class="bi bi-door-open-fill me-2 text-info"></i> Port Knocking
            </h2>
            <p class="lead opacity-75 mb-0" style="max-width: 700px;">
                Hide services behind a secret knock sequence.
            </p>
        </div>

        <form id="knockForm">
            <div class="row g-4 justify-content-center">
                <div class="col-lg-5">
                    <div class="glass-card h-100">
                        <div class="card-header border-0 bg-transparent pt-4 px-4">
                            <h5 class="card-title fw-bold text-white mb-0">Sequence</h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="mb-3">
                                <label class="form-label text-white small text-uppercase">Knock Ports</label>
                                <input type="text" class="form-control" id="ports" value="1234,5678,9000">
                                <div class="form-text text-white-50">Comma separated sequence (TCP).</div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-white small text-uppercase">Target Port to Open</label>
                                <input type="number" class="form-control" id="target" value="8291">
                                <div class="form-text text-white-50">e.g. 8291 (Winbox), 22 (SSH).</div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-white small text-uppercase">Timeout</label>
                                <input type="text" class="form-control" id="timeout" value="1m">
                            </div>

                            <div class="d-grid mt-4">
                                <button type="submit" class="btn btn-info text-dark btn-lg shadow-lg">Generate Knock
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
                                <i class="bi bi-info-circle me-1"></i> Knock the ports in order to add your IP to the
                                allowed list for the Target Port.
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

@vite(['resources/js/pages/mikrotik-suite/security/advanced/port-knocking.js'])
@endpush
