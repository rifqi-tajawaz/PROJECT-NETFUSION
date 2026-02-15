@extends('layouts.app')

@section('content')
    <div class="container-fluid px-4 py-4">
        <!-- Hero Header -->
        <div class="mb-4 d-flex flex-column align-items-center justify-content-center text-center">
            <h2 class="display-6 fw-bold mb-2 text-white" style="text-shadow: 0 2px 10px rgba(0,0,0,0.3);">
                <i class="bi bi-speedometer2 me-2 text-primary"></i> Burst Calculator
            </h2>
            <p class="lead opacity-75 mb-0" style="max-width: 700px;">
                Calculate and visualize MikroTik Queues Burst settings.
                Understand how Max Limit, Burst Limit, Threshold, and Time interact.
            </p>
        </div>

        <form id="burstForm" data-route="{{ route('mikrotik-suite.utilities.calculators.burst.calculate') }}">
            <div class="row g-4">
                <!-- Inputs -->
                <div class="col-lg-4">
                    <div class="glass-card h-100">
                        <div class="card-header border-0 bg-transparent pt-4 px-4">
                            <h5 class="card-title fw-bold text-white mb-0">
                                <i class="bi bi-sliders me-2 text-primary"></i>Parameters
                            </h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="mb-3">
                                <label class="form-label text-white small text-uppercase fw-bold">Max Limit
                                    (Upload/Download)</label>
                                <div class="input-group">
                                    <input type="number" class="form-control border border-secondary border-opacity-25"
                                        id="maxLimit" name="max_limit" value="5" placeholder="e.g. 5" required>
                                    <span class="input-group-text bg-dark border-secondary text-secondary">Mbps</span>
                                </div>
                                <small class="text-muted">Guaranteed/Normal speed cap.</small>
                            </div>

                            <div class="mb-3">
                                <label class="form-label text-white small text-uppercase fw-bold">Burst Limit</label>
                                <div class="input-group">
                                    <input type="number" class="form-control border border-secondary border-opacity-25"
                                        id="burstLimit" name="burst_limit" value="10" placeholder="e.g. 10" required>
                                    <span class="input-group-text bg-dark border-secondary text-secondary">Mbps</span>
                                </div>
                                <small class="text-muted">Top speed during burst.</small>
                            </div>

                            <div class="mb-3">
                                <label class="form-label text-white small text-uppercase fw-bold">Burst Threshold</label>
                                <div class="input-group">
                                    <input type="number" class="form-control border border-secondary border-opacity-25"
                                        id="burstThreshold" name="burst_threshold" value="3" placeholder="e.g. 3" required>
                                    <span class="input-group-text bg-dark border-secondary text-secondary">Mbps</span>
                                </div>
                                <small class="text-muted">Average speed must be below this to recharge burst.</small>
                            </div>

                            <div class="mb-3">
                                <label class="form-label text-white small text-uppercase fw-bold">Burst Time</label>
                                <div class="input-group">
                                    <input type="number" class="form-control border border-secondary border-opacity-25"
                                        id="burstTime" name="burst_time" value="16" placeholder="e.g. 16" required>
                                    <span class="input-group-text bg-dark border-secondary text-secondary">Seconds</span>
                                </div>
                                <small class="text-muted">Period for calculating average data rate.</small>
                            </div>

                            <div class="d-grid mt-4">
                                <button type="submit" class="btn btn-primary btn-lg shadow-lg"
                                    style="background: linear-gradient(90deg, #6610f2, #0d6efd); border:none;">
                                    <i class="bi bi-calculator me-2"></i> Calculate
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Visualization / Results -->
                <div class="col-lg-8">
                    <div class="glass-card mb-4">
                        <div class="card-header border-0 bg-transparent pt-4 px-4">
                            <h5 class="card-title fw-bold text-white mb-0">
                                <i class="bi bi-graph-up me-2 text-warning"></i>Burst Behavior
                            </h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="alert alert-light bg-opacity-10 text-white border-0 shadow-sm">
                                <h6 class="fw-bold"><i class="bi bi-info-circle me-2"></i>How it works:</h6>
                                <p class="mb-0 small opacity-75">
                                    Burst allows a user to exceed <strong>Max Limit</strong> up to <strong>Burst
                                        Limit</strong> for a short period.
                                    The burst duration depends on the average usage over <strong>Burst Time</strong>.
                                    <br>Actual Burst Duration ≈ <span id="resDuration"
                                        class="text-warning fw-bold">...</span> seconds.
                                </p>
                            </div>

                            <div class="mt-4">
                                <h6 class="text-uppercase text-muted fs-12 fw-bold mb-3">Generated Queue Simple</h6>
                                <div class="bg-dark text-light p-3 rounded font-monospace small position-relative">
                                    <code class="text-light" id="cmdQueue">
                                                Click Calculate...
                                            </code>
                                    <button type="button"
                                        class="btn btn-sm btn-icon position-absolute top-0 end-0 mt-2 me-2 text-muted hover-white"
                                        onclick="copyQueue()">
                                        <i class="bi bi-clipboard"></i>
                                    </button>
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
    @vite(['resources/js/pages/mikrotik-suite/utilities/calculators/burst-calculator.js'])
@endpush
