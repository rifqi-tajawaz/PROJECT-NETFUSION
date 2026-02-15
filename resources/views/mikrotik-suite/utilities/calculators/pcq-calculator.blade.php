@extends('layouts.app')

@section('content')
    <div class="container-fluid px-4 py-4">
        <!-- Hero Header -->
        <div class="mb-4 d-flex flex-column align-items-center justify-content-center text-center">
            <h2 class="display-6 fw-bold mb-2 text-white" style="text-shadow: 0 2px 10px rgba(0,0,0,0.3);">
                <i class="bi bi-sliders me-2 text-primary"></i> PCQ Queue Calculator
            </h2>
            <p class="lead opacity-75 mb-0" style="max-width: 700px;">
                Calculate optimal Per Connection Queue (PCQ) buffer sizes and limits for fair bandwidth distribution.
            </p>
        </div>

        <form id="pcqForm" data-route="{{ route('mikrotik-suite.utilities.calculators.pcq.calculate') }}">
            <div class="row g-4 justify-content-center">
                <!-- Configuration -->
                <div class="col-lg-5">
                    <div class="glass-card h-100">
                        <div class="card-header border-0 bg-transparent pt-4 px-4">
                            <h5 class="card-title fw-bold text-white mb-0">
                                <i class="bi bi-input-cursor-text me-2 text-info"></i>Configuration
                            </h5>
                        </div>
                        <div class="card-body p-4">
                            <!-- Network Capacity -->
                            <div class="row g-3 mb-3">
                                <div class="col-12">
                                    <label class="form-label text-white small text-uppercase fw-bold">Active User
                                        Estimate</label>
                                    <input type="number" class="form-control border border-secondary border-opacity-25"
                                        id="activeUsers" name="active_users" value="50" min="1" required>
                                </div>
                                <div class="col-6">
                                    <label class="form-label text-white small text-uppercase">Total Down (Mbps)</label>
                                    <input type="number" class="form-control border border-secondary border-opacity-25"
                                        id="totalDown" name="total_down" value="100" required>
                                </div>
                                <div class="col-6">
                                    <label class="form-label text-white small text-uppercase">Total Up (Mbps)</label>
                                    <input type="number" class="form-control border border-secondary border-opacity-25"
                                        id="totalUp" name="total_up" value="20" required>
                                </div>
                            </div>

                            <hr class="border-secondary opacity-50 my-4">

                            <!-- Target Per User -->
                            <div class="row g-3">
                                <div class="col-12">
                                    <h6 class="text-white small text-uppercase fw-bold mb-0">Target Speed Per User</h6>
                                </div>
                                <div class="col-6">
                                    <label class="text-white-50 small">Download</label>
                                    <div class="input-group">
                                        <input type="number" class="form-control border border-secondary border-opacity-25"
                                            id="rateDown" name="rate_down" value="5" required>
                                        <span class="input-group-text bg-dark border-secondary text-white-50">Mbps</span>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <label class="text-white-50 small">Upload</label>
                                    <div class="input-group">
                                        <input type="number" class="form-control border border-secondary border-opacity-25"
                                            id="rateUp" name="rate_up" value="1" required>
                                        <span class="input-group-text bg-dark border-secondary text-white-50">Mbps</span>
                                    </div>
                                </div>
                            </div>

                            <div class="d-grid mt-4">
                                <button type="submit" class="btn btn-primary btn-lg shadow-lg"
                                    style="background: linear-gradient(90deg, #0d6efd, #6610f2); border:none;">
                                    <i class="bi bi-calculator me-2"></i> Calculate
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Result -->
                <div class="col-lg-7">
                    <div class="glass-card h-100">
                        <div class="card-header border-0 bg-transparent pt-4 px-4">
                            <h5 class="card-title fw-bold text-white mb-0">
                                <i class="bi bi-lightning-charge me-2 text-warning"></i>Optimization Results
                            </h5>
                        </div>
                        <div class="card-body p-4">
                            <div id="resultsContent" style="display:none;" class="animate__animated animate__fadeIn">
                                <div class="row g-3 mb-4">
                                    <!-- Down Limit -->
                                    <div class="col-md-3 col-6">
                                        <div
                                            class="p-3 bg-dark bg-opacity-50 rounded-3 border border-secondary text-center h-100">
                                            <small class="text-info d-block text-uppercase" style="font-size:0.7rem;">Queue
                                                Limit (Down)</small>
                                            <h4 class="text-white font-monospace mb-0 mt-2" id="resLimitDown">...</h4>
                                            <small class="text-muted">KiB</small>
                                        </div>
                                    </div>
                                    <!-- Up Limit -->
                                    <div class="col-md-3 col-6">
                                        <div
                                            class="p-3 bg-dark bg-opacity-50 rounded-3 border border-secondary text-center h-100">
                                            <small class="text-warning d-block text-uppercase"
                                                style="font-size:0.7rem;">Queue Limit (Up)</small>
                                            <h4 class="text-white font-monospace mb-0 mt-2" id="resLimitUp">...</h4>
                                            <small class="text-muted">KiB</small>
                                        </div>
                                    </div>
                                    <!-- Total Down -->
                                    <div class="col-md-3 col-6">
                                        <div
                                            class="p-3 bg-dark bg-opacity-50 rounded-3 border border-secondary text-center h-100">
                                            <small class="text-secondary d-block text-uppercase"
                                                style="font-size:0.7rem;">Total Buffer (Down)</small>
                                            <h5 class="text-white font-monospace mb-0 mt-2" id="resTotalDown">...</h5>
                                            <small class="text-muted">KiB</small>
                                        </div>
                                    </div>
                                    <!-- Total Up -->
                                    <div class="col-md-3 col-6">
                                        <div
                                            class="p-3 bg-dark bg-opacity-50 rounded-3 border border-secondary text-center h-100">
                                            <small class="text-secondary d-block text-uppercase"
                                                style="font-size:0.7rem;">Total Buffer (Up)</small>
                                            <h5 class="text-white font-monospace mb-0 mt-2" id="resTotalUp">...</h5>
                                            <small class="text-muted">KiB</small>
                                        </div>
                                    </div>
                                </div>

                                <!-- Script -->
                                <div class="position-relative">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="text-white small text-uppercase">RouterOS Script</span>
                                        <div id="copySuccess" class="text-success small fw-bold"
                                            style="display:none; transition: all 0.3s ease;">Copied!</div>
                                        <button type="button" class="btn btn-xs btn-outline-light py-0"
                                            onclick="copyText('pcqScript')">Copy</button>
                                    </div>
                                    <pre class="m-0 p-3 text-success bg-black bg-opacity-50 rounded-3 border border-secondary fw-bold"
                                        style="font-family: 'JetBrains Mono', monospace; font-size: 0.85rem; overflow-x:auto;"
                                        id="pcqScript"></pre>
                                </div>
                            </div>

                            <div id="placeholder" class="text-center text-muted py-5 opacity-50">
                                <i class="bi bi-sliders display-1 mb-3 d-block"></i>
                                <p>Calculate optimal queue sizes</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    @vite(['resources/js/pages/mikrotik-suite/utilities/calculators/pcq-calculator.js'])
@endpush
