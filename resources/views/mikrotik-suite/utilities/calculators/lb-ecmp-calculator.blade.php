@extends('layouts.app')

@section('content')
    <div class="container-fluid px-4 py-4">
        <!-- Hero Header -->
        <div class="mb-4 d-flex flex-column align-items-center justify-content-center text-center">
            <h2 class="display-6 fw-bold mb-2 text-white" style="text-shadow: 0 2px 10px rgba(0,0,0,0.3);">
                <i class="bi bi-shuffle me-2 text-warning"></i> ECMP Load Balancing
            </h2>
            <p class="lead opacity-75 mb-0" style="max-width: 700px;">
                Generate Equal Cost Multi-Path (ECMP) script.
                Simple and effective load balancing by combining multiple gateways.
            </p>
        </div>

        <form id="ecmpForm" data-route="{{ route('mikrotik-suite.utilities.calculators.lb-ecmp.calculate') }}">
            <div class="row g-4">
                <!-- Configuration -->
                <div class="col-lg-5">
                    <div class="glass-card h-100">
                        <div class="card-header border-0 bg-transparent pt-4 px-4">
                            <h5 class="card-title fw-bold text-white mb-0">
                                <i class="bi bi-sliders me-2 text-primary"></i>Configuration
                            </h5>
                        </div>
                        <div class="card-body p-4">
                            <!-- Number of Gateways -->
                            <div class="mb-4">
                                <label class="form-label text-white small text-uppercase fw-bold">Number of Gateways</label>
                                <div class="range-slider-container">
                                    <input type="range" class="form-range" id="gwCount" min="2" max="8" value="2"
                                        oninput="updateGwInputs()">
                                    <div class="d-flex justify-content-between text-muted small mt-1">
                                        <span>2</span>
                                        <span>3</span>
                                        <span>4</span>
                                        <span>5</span>
                                        <span>6</span>
                                        <span>7</span>
                                        <span>8</span>
                                    </div>
                                </div>
                                <div class="text-center mt-2">
                                    <span class="badge bg-warning text-dark fs-5" id="gwCountDisplay">2 Gateways</span>
                                </div>
                            </div>

                            <!-- Options -->
                            <div class="mb-4">
                                <label class="form-label text-white small text-uppercase fw-bold">Options</label>
                                <div class="form-check form-switch mb-2">
                                    <input class="form-check-input" type="checkbox" id="chkCheckGateway"
                                        name="check_gateway" value="1" checked>
                                    <label class="form-check-label text-white" for="chkCheckGateway">Enable Check Gateway
                                        (Ping)</label>
                                </div>
                                <div class="form-check form-switch mb-2">
                                    <input class="form-check-input" type="checkbox" id="chkDns" name="dns" value="1"
                                        checked>
                                    <label class="form-check-label text-white" for="chkDns">Add Google DNS</label>
                                </div>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-lg shadow-lg"
                                    style="background: linear-gradient(90deg, #ffc107, #fd7e14); border:none;">
                                    <i class="bi bi-code-square me-2"></i> Generate Script
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Gateway Details -->
                <div class="col-lg-7">
                    <div class="glass-card h-100">
                        <div
                            class="card-header border-0 bg-transparent pt-4 px-4 d-flex justify-content-between align-items-center">
                            <h5 class="card-title fw-bold text-white mb-0">
                                <i class="bi bi-router me-2 text-info"></i>Gateways
                            </h5>
                            <small class="text-muted">Enter Gateway IPs</small>
                        </div>
                        <div class="card-body p-4">
                            <div id="gwInputsContainer" class="row g-3">
                                <!-- Helper Logic will populate -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Result -->
            <div class="row mt-4" id="resultContainer" style="display:none;">
                <div class="col-12">
                    <div class="glass-card">
                        <div
                            class="card-header border-0 bg-transparent pt-4 px-4 d-flex justify-content-between align-items-center">
                            <h5 class="card-title fw-bold text-white mb-0">
                                <i class="bi bi-terminal me-2 text-success"></i>Generated Script
                            </h5>
                            <button type="button" class="btn btn-sm btn-outline-light" onclick="copyScript()">
                                <i class="bi bi-clipboard me-1"></i> Copy
                            </button>
                        </div>
                        <div class="card-body p-0">
                            <pre class="m-0 p-4 text-warning bg-dark bg-opacity-50"
                                style="max-height: 500px; overflow-y: auto; font-family: 'JetBrains Mono', monospace;"
                                id="scriptOutput"></pre>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    @vite(['resources/js/pages/mikrotik-suite/utilities/calculators/lb-ecmp-calculator.js'])
@endpush
