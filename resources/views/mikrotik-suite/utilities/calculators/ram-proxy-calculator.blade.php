@extends('layouts.app')

@section('content')
    <div class="container-fluid px-4 py-4">
        <!-- Hero Header -->
        <div class="mb-4 d-flex flex-column align-items-center justify-content-center text-center">
            <h2 class="display-6 fw-bold mb-2 text-white" style="text-shadow: 0 2px 10px rgba(0,0,0,0.3);">
                <i class="bi bi-hdd-rack me-2 text-info"></i> Web Proxy Cache Calculator
            </h2>
            <p class="lead opacity-75 mb-0" style="max-width: 700px;">
                Optimize your MikroTik Web Proxy settings.
                Calculate Max RAM Cache Size and estimate object storage capacity.
            </p>
        </div>

        <form id="proxyForm" data-route="{{ route('mikrotik-suite.utilities.calculators.ram-proxy.calculate') }}">
            <div class="row g-4 justify-content-center">
                <!-- Inputs -->
                <div class="col-lg-5">
                    <div class="glass-card h-100">
                        <div class="card-header border-0 bg-transparent pt-4 px-4">
                            <h5 class="card-title fw-bold text-white mb-0">
                                <i class="bi bi-memory me-2 text-primary"></i>System Resources
                            </h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="mb-3">
                                <label class="form-label text-white small text-uppercase fw-bold">Total RAM Size</label>
                                <div class="input-group">
                                    <input type="number" class="form-control border border-secondary border-opacity-25"
                                        id="totalRam" name="total_ram" value="256" placeholder="e.g. 256" required>
                                    <select class="form-select bg-dark text-white border-secondary" id="ramUnit"
                                        name="ram_unit" style="max-width: 100px;">
                                        <option value="MB" selected>MB</option>
                                        <option value="GB">GB</option>
                                    </select>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label text-white small text-uppercase fw-bold">Desired Cache
                                    Usage</label>
                                <input type="range" class="form-range" id="usageRatio" name="usage_ratio" min="10" max="80"
                                    value="30" oninput="document.getElementById('ratioVal').textContent = this.value + '%'">
                                <div class="d-flex justify-content-between text-muted small">
                                    <span>Safe (10%)</span>
                                    <span class="text-warning fw-bold" id="ratioVal">30%</span>
                                    <span>Aggressive (80%)</span>
                                </div>
                                <small class="text-muted">Percentage of RAM dedicated to Proxy Cache.</small>
                            </div>

                            <div class="mb-3">
                                <label class="form-label text-white small text-uppercase fw-bold">Avg. Object Size
                                    (Est.)</label>
                                <div class="input-group">
                                    <input type="number" class="form-control border border-secondary border-opacity-25"
                                        id="avgObjSize" name="avg_obj_size" value="25" placeholder="e.g. 25" required>
                                    <span class="input-group-text bg-dark border-secondary text-secondary">KB</span>
                                </div>
                            </div>

                            <div class="d-grid mt-4">
                                <button type="submit" class="btn btn-primary btn-lg shadow-lg"
                                    style="background: linear-gradient(90deg, #0dcaf0, #0d6efd); border:none;">
                                    <i class="bi bi-cpu me-2"></i> Calculate Settings
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Results -->
                <div class="col-lg-6">
                    <div class="glass-card h-100 bg-white bg-opacity-5">
                        <div class="card-header border-0 bg-transparent pt-4 px-4">
                            <h5 class="card-title fw-bold text-white mb-0">
                                <i class="bi bi-clipboard-check me-2 text-success"></i>Recommended Settings
                            </h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="row g-3 mb-4">
                                <div class="col-6">
                                    <div class="p-3 rounded-3 bg-dark bg-opacity-25 border border-secondary">
                                        <span class="d-block text-secondary small text-uppercase">Max Cache Size</span>
                                        <span class="fs-4 fw-bold text-white" id="resCacheSize">---</span>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="p-3 rounded-3 bg-dark bg-opacity-25 border border-secondary">
                                        <span class="d-block text-secondary small text-uppercase">Est. Objects</span>
                                        <span class="fs-4 fw-bold text-info" id="resObjects">---</span>
                                    </div>
                                </div>
                            </div>

                            <h6 class="text-uppercase text-muted fs-12 fw-bold mb-3">RouterOS Script</h6>
                            <div class="bg-dark text-light p-3 rounded font-monospace small position-relative">
                                <code class="text-light" id="cmdScript">
                                            /ip proxy set enabled=yes port=8080 ...
                                        </code>
                                <button type="button"
                                    class="btn btn-sm btn-icon position-absolute top-0 end-0 mt-2 me-2 text-muted hover-white"
                                    onclick="copyScript()">
                                    <i class="bi bi-clipboard"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    @vite(['resources/js/pages/mikrotik-suite/utilities/calculators/ram-proxy-calculator.js'])
@endpush
