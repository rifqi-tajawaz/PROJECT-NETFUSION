@extends('layouts.app')

@section('content')
    <div class="container-fluid px-4 py-4">
        <div class="mb-4 d-flex flex-column align-items-center justify-content-center text-center">
            <h2 class="display-6 fw-bold mb-2 text-white" style="text-shadow: 0 2px 10px rgba(0,0,0,0.3);">
                <i class="bi bi-signpost-split me-2 text-success"></i> Static Route Generator
            </h2>
            <p class="lead opacity-75 mb-0" style="max-width: 700px;">
                Create basic static routes for destination reachability.
            </p>
        </div>

        <form id="routeForm" data-route="{{ route('mikrotik-suite.network.routing.static-route-generator.generate') }}">
            <div class="row g-4 justify-content-center">
                <div class="col-lg-5">
                    <div class="glass-card h-100">
                        <div class="card-header border-0 bg-transparent pt-4 px-4">
                            <h5 class="card-title fw-bold text-white mb-0">Route Details</h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="mb-3">
                                <label class="form-label text-white small text-uppercase">Destination Network</label>
                                <input type="text" class="form-control" name="destination" id="dst"
                                    placeholder="192.168.100.0/24">
                                <div class="form-text text-white-50">Use 0.0.0.0/0 for Default Route.</div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-white small text-uppercase">Gateway</label>
                                <input type="text" class="form-control" name="gateway" id="gw" placeholder="10.1.1.1">
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-white small text-uppercase">Distance</label>
                                <input type="number" class="form-control" name="distance" id="dist" value="1">
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-white small text-uppercase">Comment</label>
                                <input type="text" class="form-control" name="comment" id="comment" value="Static Route">
                            </div>

                            <div class="d-grid mt-4">
                                <button type="submit" class="btn btn-success btn-lg shadow-lg">
                                    <span class="btn-label">Generate Route Config</span>
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
    @vite(['resources/js/pages/mikrotik-suite/network/config/static-route-generator.js'])
@endpush
