@extends('layouts.app')

@section('content')
    <div class="container-fluid px-4 py-4">
        <!-- Hero Header -->
        <div class="mb-4 d-flex flex-column align-items-center justify-content-center text-center">
            <h2 class="display-6 fw-bold mb-2 text-white" style="text-shadow: 0 2px 10px rgba(0,0,0,0.3);">
                <i class="bi bi-123 me-2 text-primary"></i> NTH Load Balancing
            </h2>
            <p class="lead opacity-75 mb-0" style="max-width: 700px;">
                Generate NTH (N-th Packet) Load Balancing script.
                distributes new connections in a round-robin fashion.
            </p>
        </div>

        <form id="nthForm" data-route="{{ route('mikrotik-suite.utilities.calculators.lb-nth.calculate') }}">
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
                            <!-- Number of WANs -->
                            <div class="mb-4">
                                <label class="form-label text-white small text-uppercase fw-bold">Number of WANs</label>
                                <div class="range-slider-container">
                                    <input type="range" class="form-range" id="wanCount" min="2" max="8" value="2"
                                        oninput="updateWanInputs()">
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
                                    <span class="badge bg-primary fs-5" id="wanCountDisplay">2 WANs</span>
                                </div>
                            </div>

                            <!-- LAN Interface -->
                            <div class="mb-4">
                                <label class="form-label text-white small text-uppercase fw-bold">LAN Interface</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-dark border-secondary text-secondary"><i
                                            class="bi bi-laptop"></i></span>
                                    <input type="text" class="form-control border border-secondary border-opacity-25"
                                        id="lanInterface" name="lan_interface" value="ether-local"
                                        placeholder="e.g. bridge-local" required>
                                </div>
                            </div>

                            <!-- Options -->
                            <div class="mb-4">
                                <label class="form-label text-white small text-uppercase fw-bold">Options</label>
                                <div class="form-check form-switch mb-2">
                                    <input class="form-check-input" type="checkbox" id="chkFailover" name="failover"
                                        value="1" checked>
                                    <label class="form-check-label text-white" for="chkFailover">Include Failover
                                        Rules</label>
                                </div>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-lg shadow-lg"
                                    style="background: linear-gradient(90deg, #0d6efd, #0dcaf0); border:none;">
                                    <i class="bi bi-code-square me-2"></i> Generate Script
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- WAN Details -->
                <div class="col-lg-7">
                    <div class="glass-card h-100">
                        <div
                            class="card-header border-0 bg-transparent pt-4 px-4 d-flex justify-content-between align-items-center">
                            <h5 class="card-title fw-bold text-white mb-0">
                                <i class="bi bi-hdd-network me-2 text-warning"></i>WAN Interfaces
                            </h5>
                            <small class="text-muted">Enter Gateway IPs & Interfaces</small>
                        </div>
                        <div class="card-body p-4">
                            <div id="wanInputsContainer" class="row g-3">
                                <!-- Dynamic -->
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
    @vite(['resources/js/pages/mikrotik-suite/utilities/calculators/lb-nth-calculator.js'])
@endpush
