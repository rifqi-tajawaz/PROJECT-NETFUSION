@extends('layouts.app')

@section('content')
    <div class="container-fluid px-4 py-4">
        <div class="mb-4 d-flex flex-column align-items-center justify-content-center text-center">
            <h2 class="display-6 fw-bold mb-2 text-white" style="text-shadow: 0 2px 10px rgba(0,0,0,0.3);">
                <i class="bi bi-calculator me-2 text-primary"></i> Link Budget Calculator
            </h2>
            <p class="lead opacity-75 mb-0" style="max-width: 700px;">
                Calculate Received Signal Level (RSL) and Link Margin to ensure reliable wireless connectivity.
            </p>
        </div>

        <form id="calcForm" data-route="{{ route('mikrotik-suite.wireless.link-budget.calculate') }}">
            <div class="row g-4">
                <!-- Left: Inputs -->
                <div class="col-lg-4">
                    <div class="glass-card h-100">
                        <div class="card-header border-0 bg-transparent pt-4 px-4">
                            <h5 class="card-title fw-bold text-white mb-0">Link Parameters</h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="mb-3">
                                <label class="form-label text-white small text-uppercase">Frequency (MHz)</label>
                                <input type="number" class="form-control input-param" name="frequency" id="freq"
                                    value="5800">
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-white small text-uppercase">Distance (km)</label>
                                <input type="number" class="form-control input-param" name="distance" id="dist" value="5"
                                    step="0.1">
                            </div>

                            <hr class="border-secondary opacity-25 my-4">

                            <div class="row g-3">
                                <div class="col-6">
                                    <label class="form-label text-white small text-uppercase">TX Power (dBm)</label>
                                    <input type="number" class="form-control input-param" name="tx_power" id="txPower"
                                        value="27">
                                </div>
                                <div class="col-6">
                                    <label class="form-label text-white small text-uppercase">RX Sensitivity (dBm)</label>
                                    <input type="number" class="form-control input-param" name="sensitivity" id="sens"
                                        value="-80">
                                </div>
                                <div class="col-6">
                                    <label class="form-label text-white small text-uppercase">TX Gain (dBi)</label>
                                    <input type="number" class="form-control input-param" name="tx_gain" id="txGain"
                                        value="24">
                                </div>
                                <div class="col-6">
                                    <label class="form-label text-white small text-uppercase">RX Gain (dBi)</label>
                                    <input type="number" class="form-control input-param" name="rx_gain" id="rxGain"
                                        value="24">
                                </div>
                                <div class="col-12">
                                    <label class="form-label text-white small text-uppercase">Total Cable Loss (dB)</label>
                                    <input type="number" class="form-control input-param" name="cable_loss" id="cableLoss"
                                        value="2">
                                </div>
                            </div>

                            <div class="d-grid mt-4">
                                <button type="submit" class="btn btn-primary btn-lg shadow-lg"
                                    style="background: linear-gradient(90deg, #fd7e14, #ffc107); border:none;">
                                    <span class="btn-label"><i class="bi bi-calculator me-2"></i> Calculate</span>
                                    <span class="btn-loader d-none"><span
                                            class="spinner-border spinner-border-sm me-2"></span>Adjusting...</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right: Results -->
                <div class="col-lg-8">
                    <div class="row g-4 h-100">
                        <!-- Gauge and Summary -->
                        <div class="col-md-6">
                            <div class="glass-card h-100 d-flex flex-column align-items-center justify-content-center p-4">
                                <h6 class="text-white-50 text-uppercase mb-4">Estimated Signal Level</h6>

                                <div class="position-relative mb-3" style="width: 200px; height: 100px; overflow:hidden;">
                                    <div class="position-absolute w-100 h-100 rounded-circle border border-5 border-secondary opacity-25"
                                        style="clip-path: polygon(0 0, 100% 0, 100% 50%, 0 50%); margin-top:100px;"></div>
                                    <!-- Background arc hack? No let's use SVG -->
                                    <svg viewBox="0 0 100 50" class="w-100 h-100">
                                        <path d="M 10 50 A 40 40 0 0 1 90 50" fill="none" stroke="#343a40" stroke-width="10"
                                            stroke-linecap="round" />
                                        <path id="signalArc" d="M 10 50 A 40 40 0 0 1 90 50" fill="none" stroke="#0d6efd"
                                            stroke-width="10" stroke-linecap="round" stroke-dasharray="0 126" />
                                    </svg>
                                    <div class="position-absolute top-100 start-50 translate-middle text-center"
                                        style="margin-top:-20px;">
                                        <h2 class="text-white fw-bold mb-0" id="resRSL">-00</h2>
                                        <small class="text-muted">dBm</small>
                                    </div>
                                </div>

                                <div class="mt-4">
                                    <span class="badge bg-secondary px-4 py-2 rounded-pill fs-6"
                                        id="verdictBadge">Thinking...</span>
                                </div>
                            </div>
                        </div>

                        <!-- Metrics -->
                        <div class="col-md-6">
                            <div class="glass-card h-100 p-4">
                                <h6 class="text-white-50 text-uppercase mb-4">Detailed Metrics</h6>

                                <div class="d-flex flex-column gap-3">
                                    <div
                                        class="p-3 rounded-3 bg-dark bg-opacity-50 border border-secondary border-opacity-25 d-flex justify-content-between align-items-center">
                                        <div>
                                            <small class="text-muted d-block">Free Space Path Loss</small>
                                            <strong class="text-white fs-5" id="resFSPL">0.0 dB</strong>
                                        </div>
                                        <i class="bi bi-sort-down fs-4 text-warning"></i>
                                    </div>
                                    <div class="p-3 rounded-3 bg-dark bg-opacity-50 border border-secondary border-opacity-25 d-flex justify-content-between align-items-center"
                                        id="marginBox">
                                        <div>
                                            <small class="text-muted d-block">Link Margin</small>
                                            <strong class="text-white fs-5" id="resMargin">0.0 dB</strong>
                                        </div>
                                        <i class="bi bi-shield-check fs-4 text-success"></i>
                                    </div>
                                    <div
                                        class="p-3 rounded-3 bg-dark bg-opacity-50 border border-secondary border-opacity-25 d-flex justify-content-between align-items-center">
                                        <div>
                                            <small class="text-muted d-block">Fresnel Radius (60%)</small>
                                            <strong class="text-white fs-5" id="resF1">0.0 m</strong>
                                        </div>
                                        <i class="bi bi-circle fs-4 text-info"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Visual -->
                        <div class="col-12">
                            <div class="glass-card p-4">
                                <h6 class="text-white-50 text-uppercase mb-4">Topology Visualization</h6>
                                <div class="rounded-3 overflow-hidden border border-secondary border-opacity-25 bg-dark bg-opacity-50 position-relative"
                                    id="diagram-container" style="height:250px;">
                                    <!-- SVG -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    @vite('resources/js/pages/mikrotik-suite/wireless/link-budget-calculator.js')
@endsection
