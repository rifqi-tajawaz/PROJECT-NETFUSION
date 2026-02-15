@extends('layouts.app')

@section('content')
    <div class="container-fluid px-4 py-4">
        <div class="mb-4 d-flex flex-column align-items-center justify-content-center text-center">
            <h2 class="display-6 fw-bold mb-2 text-white" style="text-shadow: 0 2px 10px rgba(0,0,0,0.3);">
                <i class="bi bi-regex me-2 text-success"></i> Layer 7 Protocol
            </h2>
            <p class="lead opacity-75 mb-0" style="max-width: 700px;">
                Create Layer 7 Regex matchers for application filtering.
            </p>
        </div>

        <form id="l7Form">
            <div class="row g-4 justify-content-center">
                <div class="col-lg-5">
                    <div class="glass-card h-100">
                        <div class="card-header border-0 bg-transparent pt-4 px-4">
                            <h5 class="card-title fw-bold text-white mb-0">Matcher</h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="mb-3">
                                <label class="form-label text-white small text-uppercase">Name</label>
                                <input type="text" class="form-control" id="name" value="youtube_l7">
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-white small text-uppercase">Regex</label>
                                <input type="text" class="form-control" id="regex"
                                    value="^.+(youtube.com|googlevideo.com).*$">
                            </div>

                            <div class="d-grid mt-4">
                                <button type="submit" class="btn btn-success btn-lg shadow-lg">Generate L7</button>
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
                                class="alert alert-warning bg-warning bg-opacity-10 border-0 text-warning m-3 small border-start border-4 border-warning">
                                <i class="bi bi-exclamation-triangle me-1"></i> L7 matchers are CPU intensive. Use TLS Host
                                in v6.41+ instead if possible.
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
    @vite(['resources/js/pages/mikrotik-suite/security/advanced/layer7-protocol.js'])
@endpush
