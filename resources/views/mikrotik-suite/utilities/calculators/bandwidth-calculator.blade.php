@extends('layouts.app')

@section('content')
    <div class="container-fluid px-4 py-4">
        <!-- Hero Header -->
        <div class="mb-4 d-flex flex-column align-items-center justify-content-center text-center">
            <h2 class="display-6 fw-bold mb-2 text-white" style="text-shadow: 0 2px 10px rgba(0,0,0,0.3);">
                <i class="bi bi-speedometer2 me-2 text-warning"></i> Bandwidth Allocator
            </h2>
            <p class="lead opacity-75 mb-0" style="max-width: 700px;">
                Plan your service tiers, calculate overbooking ratios, and visualize capacity usage.
            </p>
        </div>

        <form id="bandwidthForm" data-route="{{ route('mikrotik-suite.utilities.calculators.bandwidth.calculate') }}">
            <div class="row g-4">
                <!-- Configuration -->
                <div class="col-lg-5">
                    <!-- Uplink -->
                    <div class="glass-card mb-4">
                        <div class="card-header border-0 bg-transparent pt-4 px-4">
                            <h5 class="card-title fw-bold text-white mb-0">
                                <i class="bi bi-cloud-arrow-up me-2 text-primary"></i>Uplink Capacity
                            </h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="row g-3">
                                <div class="col-6">
                                    <label class="form-label text-white small text-uppercase">Total Download (Mbps)</label>
                                    <input type="number" class="form-control" name="total_down" id="totalDown" value="100"
                                        required>
                                </div>
                                <div class="col-6">
                                    <label class="form-label text-white small text-uppercase">Total Upload (Mbps)</label>
                                    <input type="number" class="form-control" name="total_up" id="totalUp" value="20"
                                        required>
                                </div>
                                <div class="col-6">
                                    <label class="form-label text-white small text-uppercase">Reserved Down (%)</label>
                                    <input type="number" class="form-control" name="res_down_pct" id="resDownPct" value="10"
                                        required>
                                </div>
                                <div class="col-6">
                                    <label class="form-label text-white small text-uppercase">Reserved Up (%)</label>
                                    <input type="number" class="form-control" name="res_up_pct" id="resUpPct" value="10"
                                        required>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tiers -->
                    <div class="glass-card">
                        <div
                            class="card-header border-0 bg-transparent pt-4 px-4 d-flex justify-content-between align-items-center">
                            <h5 class="card-title fw-bold text-white mb-0">
                                <i class="bi bi-collection me-2 text-success"></i>Service Tiers
                            </h5>
                            <button type="button" class="btn btn-sm btn-outline-light rounded-pill" id="addTierBtn"><i
                                    class="bi bi-plus"></i>
                                Add</button>
                        </div>
                        <div class="card-body p-4">
                            <div class="table-responsive" style="max-height: 300px; overflow-y:auto;">
                                <table class="table table-dark table-hover mb-0">
                                    <thead>
                                        <tr class="text-secondary small text-uppercase">
                                            <th>Name</th>
                                            <th>Down</th>
                                            <th>Up</th>
                                            <th>Clients</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody id="tiersBody">
                                        <!-- JS Populated -->
                                    </tbody>
                                </table>
                            </div>
                            <div class="d-grid mt-3">
                                <button type="submit" class="btn btn-primary shadow-lg" id="calcBtn"
                                    style="background: linear-gradient(90deg, #fd7e14, #d63384);">
                                    <i class="bi bi-lightning-charge me-2"></i> Calculate & Visualize
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Results -->
                <div class="col-lg-7">
                    <div class="glass-card h-100">
                        <div class="card-header border-0 bg-transparent pt-4 px-4">
                            <h5 class="card-title fw-bold text-white mb-0">
                                <i class="bi bi-pie-chart me-2 text-purple"></i>Analysis
                            </h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <div class="position-relative" style="height: 250px;">
                                        <canvas id="downChart"></canvas>
                                    </div>
                                    <p class="text-center text-muted small mt-2">Download Distribution</p>
                                </div>
                                <div class="col-md-6">
                                    <div class="position-relative" style="height: 250px;">
                                        <canvas id="upChart"></canvas>
                                    </div>
                                    <p class="text-center text-muted small mt-2">Upload Distribution</p>
                                </div>
                            </div>

                            <div class="bg-dark bg-opacity-25 p-3 rounded-3 border border-secondary mb-3">
                                <h6 class="text-white small text-uppercase mb-3">Summary Stats (Mbps)</h6>
                                <div class="table-responsive">
                                    <table class="table table-sm text-white mb-0">
                                        <thead>
                                            <tr class="text-secondary">
                                                <th>Metric</th>
                                                <th class="text-end">Download</th>
                                                <th class="text-end">Upload</th>
                                            </tr>
                                        </thead>
                                        <tbody class="font-monospace">
                                            <tr>
                                                <td>Total Capacity</td>
                                                <td class="text-end" id="sumTotalDown">-</td>
                                                <td class="text-end" id="sumTotalUp">-</td>
                                            </tr>
                                            <tr>
                                                <td class="text-warning">Reserved</td>
                                                <td class="text-end text-warning" id="sumResDown">-</td>
                                                <td class="text-end text-warning" id="sumResUp">-</td>
                                            </tr>
                                            <tr>
                                                <td class="text-info">Sold (Allocated)</td>
                                                <td class="text-end text-info" id="sumSoldDown">-</td>
                                                <td class="text-end text-info" id="sumSoldUp">-</td>
                                            </tr>
                                            <tr class="fw-bold fs-5">
                                                <td class="text-success">Available / (Over)</td>
                                                <td class="text-end text-success" id="sumAvailDown">-</td>
                                                <td class="text-end text-success" id="sumAvailUp">-</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @vite(['resources/js/pages/mikrotik-suite/utilities/calculators/bandwidth-calculator.js'])
@endpush
