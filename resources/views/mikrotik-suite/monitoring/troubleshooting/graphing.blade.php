@extends('layouts.app')

@section('content')
    <div class="container-fluid px-4 py-4">
        <div class="mb-4 d-flex flex-column align-items-center justify-content-center text-center">
            <h2 class="display-6 fw-bold mb-2 text-white" style="text-shadow: 0 2px 10px rgba(0,0,0,0.3);">
                <i class="bi bi-graph-up me-2 text-primary"></i> Graphing Tools
            </h2>
            <p class="lead opacity-75 mb-0" style="max-width: 700px;">
                Configure historical usage graphs for Interfaces, Queues, and Resources.
            </p>
        </div>

        <form id="graphForm" data-route="{{ route('mikrotik-suite.monitoring.troubleshooting.graphing.generate') }}">
            <div class="row g-4 justify-content-center">
                <div class="col-lg-5">
                    <div class="glass-card h-100">
                        <div class="card-header border-0 bg-transparent pt-4 px-4">
                            <h5 class="card-title fw-bold text-white mb-0">Configuration</h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="mb-3">
                                <label class="form-label text-white small text-uppercase">Enable Graphs For:</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="resource_graph" id="resGraph"
                                        value="1" checked>
                                    <label class="form-check-label text-white" for="resGraph">System Resources (CPU, RAM,
                                        HDD)</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="interface_graph" id="ifaceGraph"
                                        value="1">
                                    <label class="form-check-label text-white" for="ifaceGraph">Interfaces</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="queue_graph" id="queueGraph"
                                        value="1">
                                    <label class="form-check-label text-white" for="queueGraph">Simple Queues</label>
                                </div>
                            </div>

                            <div id="ifaceOpts" style="display:none;" class="mb-3">
                                <label class="form-label text-white small text-uppercase">Interface Target</label>
                                <input type="text" class="form-control" name="interface_target" id="iface" value="all">
                            </div>

                            <div class="mb-3">
                                <label class="form-label text-white small text-uppercase">Allowed Address</label>
                                <input type="text" class="form-control" name="allowed_address" id="allow"
                                    value="192.168.88.0/24">
                                <div class="form-text text-white-50">Who can view graphs via HTTP.</div>
                            </div>

                            <div class="d-grid mt-4">
                                <button type="submit" class="btn btn-primary btn-lg shadow-lg">
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
    @vite(['resources/js/pages/mikrotik-suite/monitoring/troubleshooting/graphing.js'])
@endpush
