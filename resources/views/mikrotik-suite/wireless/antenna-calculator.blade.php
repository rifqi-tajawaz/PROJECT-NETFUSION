@extends('layouts.app')

@section('content')
    <div class="container-fluid px-4 py-4">
        <div class="mb-4 d-flex flex-column align-items-center justify-content-center text-center">
            <h2 class="display-6 fw-bold mb-2 text-white" style="text-shadow: 0 2px 10px rgba(0,0,0,0.3);">
                <i class="bi bi-broadcast me-2 text-warning"></i> Antenna Height
            </h2>
            <p class="lead opacity-75 mb-0" style="max-width: 700px;">
                Calculate the Fresnel Zone radius to ensure clear line-of-sight for your wireless links.
            </p>
        </div>

        <form id="antennaForm" data-route="{{ route('mikrotik-suite.wireless.antenna.calculate') }}">
            <div class="row g-4">
                <!-- Input -->
                <div class="col-lg-4">
                    <div class="glass-card h-100">
                        <div class="card-header border-0 bg-transparent pt-4 px-4">
                            <h5 class="card-title fw-bold text-white mb-0">Link Parameters</h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="mb-3">
                                <label class="form-label text-white small text-uppercase">Distance (km)</label>
                                <input type="number" class="form-control" name="distance" id="distance" value="5"
                                    step="0.1">
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-white small text-uppercase">Frequency (GHz)</label>
                                <input type="number" class="form-control" name="frequency" id="frequency" value="5.8"
                                    step="0.1">
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-white small text-uppercase">Obstacle Height (m)</label>
                                <input type="number" class="form-control" name="obstacle_height" id="obstacle" value="10">
                                <small class="text-white-50">Height of trees/buildings in the path</small>
                            </div>

                            <div class="d-grid mt-4">
                                <button type="submit" class="btn btn-primary btn-lg shadow-lg"
                                    style="background: linear-gradient(90deg, #fd7e14, #ffc107); border:none;">
                                    <span class="btn-label"><i class="bi bi-calculator me-2"></i> Calculate</span>
                                    <span class="btn-loader d-none"><span
                                            class="spinner-border spinner-border-sm me-2"></span>Checking...</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Visualizer & Results -->
                <div class="col-lg-8">
                    <div class="glass-card h-100 position-relative overflow-hidden">
                        <div class="card-header border-0 bg-transparent pt-4 px-4">
                            <h5 class="card-title fw-bold text-white mb-0">Analysis</h5>
                        </div>
                        <div class="card-body p-4">
                            <!-- Stats -->
                            <div class="row g-3 mb-4">
                                <div class="col-md-4">
                                    <div class="p-3 bg-dark bg-opacity-50 rounded-3 border border-secondary text-center">
                                        <small class="text-muted text-uppercase">Fresnel Radius (60%)</small>
                                        <h3 class="text-white mt-2 mb-0" id="fresnelRadius">0 m</h3>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="p-3 bg-dark bg-opacity-50 rounded-3 border border-secondary text-center">
                                        <small class="text-muted text-uppercase">Rec. Antenna Height</small>
                                        <h3 class="text-warning mt-2 mb-0" id="recHeight">0 m</h3>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="p-3 bg-dark bg-opacity-50 rounded-3 border border-secondary text-center">
                                        <small class="text-muted text-uppercase">Earth Curvature</small>
                                        <h3 class="text-info mt-2 mb-0" id="earthCurve">0 m</h3>
                                    </div>
                                </div>
                            </div>

                            <!-- Canvas -->
                            <div class="position-relative rounded-3 overflow-hidden bg-dark bg-opacity-25 border border-secondary"
                                style="height: 300px;">
                                <canvas id="fresnelCanvas" width="800" height="300" class="w-100 h-100"></canvas>
                            </div>
                            <p class="text-center text-white-50 small mt-2">Visual representation of the 1st Fresnel Zone
                                and Earth curvature (exaggerated).</p>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    @vite('resources/js/pages/mikrotik-suite/wireless/antenna-calculator.js')
@endsection
