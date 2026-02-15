@extends('layouts.app')

@section('content')
    <div class="container-fluid px-4 py-4">
        <!-- Hero Header -->
        <div class="mb-4 d-flex flex-column align-items-center justify-content-center text-center">
            <h2 class="display-6 fw-bold mb-2 text-white" style="text-shadow: 0 2px 10px rgba(0,0,0,0.3);">
                <i class="bi bi-key-fill me-2 text-success"></i> PPPoE Secrets Generator
            </h2>
            <p class="lead opacity-75 mb-0" style="max-width: 700px;">
                Bulk generate PPPoE user accounts (Secrets).
                Create ready-to-use scripts for your ISP clients.
            </p>
        </div>

        <form id="pppoeForm" data-route="{{ route('mikrotik-suite.connectivity.pppoe.secrets-generator.generate') }}">
            <div class="row g-4">
                <!-- Configuration -->
                <div class="col-lg-4">
                    <div class="glass-card h-100">
                        <div class="card-header border-0 bg-transparent pt-4 px-4">
                            <h5 class="card-title fw-bold text-white mb-0">
                                <i class="bi bi-sliders me-2 text-primary"></i>Config
                            </h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="mb-3">
                                <label class="form-label text-white small text-uppercase fw-bold">Quantity</label>
                                <input type="number" class="form-control" name="qty" id="qty" value="10" min="1" max="1000">
                            </div>

                            <div class="row g-2 mb-3">
                                <div class="col-6">
                                    <label class="form-label text-white small text-uppercase fw-bold">Prefix</label>
                                    <input type="text" class="form-control" name="prefix" id="prefix" placeholder="home-">
                                </div>
                                <div class="col-6">
                                    <label class="form-label text-white small text-uppercase fw-bold">Length</label>
                                    <input type="number" class="form-control" name="length" id="length" value="6" min="3"
                                        max="20">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label text-white small text-uppercase fw-bold">Profile</label>
                                <input type="text" class="form-control" name="profile" id="profile" value="default">
                            </div>

                            <div class="mb-3">
                                <label class="form-label text-white small text-uppercase fw-bold">Remote Address
                                    (Optional)</label>
                                <input type="text" class="form-control" name="remote_addr" id="remoteAddr"
                                    placeholder="e.g. 192.168.10.">
                                <small class="text-muted">If set, will append sequential IPs (10.2, 10.3...). If empty, uses
                                    Profile pool.</small>
                            </div>

                            <div class="mb-3">
                                <label class="form-label text-white small text-uppercase fw-bold">Comment</label>
                                <input type="text" class="form-control" name="comment" id="comment" placeholder="Batch-1">
                            </div>

                            <div class="d-grid mt-4">
                                <button type="submit" class="btn btn-primary btn-lg shadow-lg"
                                    style="background: linear-gradient(90deg, #198754, #20c997); border:none;">
                                    <span class="btn-label"><i class="bi bi-check-lg me-2"></i> Generate Secrets</span>
                                    <span class="btn-loader d-none"><span
                                            class="spinner-border spinner-border-sm me-2"></span>Generating...</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Output -->
                <div class="col-lg-8">
                    <div class="glass-card h-100">
                        <div class="card-header border-0 bg-transparent pt-4 px-4">
                            <ul class="nav nav-pills card-header-pills" id="pppoeTabs" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="script-tab" data-bs-toggle="tab" href="#scriptVec"
                                        role="tab">RouterOS Script</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="csv-tab" data-bs-toggle="tab" href="#csvVec" role="tab">CSV
                                        Data</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="list-tab" data-bs-toggle="tab" href="#listVec" role="tab">List
                                        View</a>
                                </li>
                            </ul>
                        </div>
                        <div class="card-body p-0">
                            <div class="tab-content">
                                <!-- Script Tab -->
                                <div class="tab-pane fade show active" id="scriptVec" role="tabpanel">
                                    <pre class="m-0 p-4 text-warning bg-dark bg-opacity-50"
                                        style="max-height: 500px; overflow-y: auto; font-family: 'JetBrains Mono', monospace;"
                                        id="scriptOutput">// Script output...</pre>
                                    <div class="p-3 border-top border-secondary d-flex justify-content-end">
                                        <button type="button" class="btn btn-sm btn-outline-light"
                                            onclick="copyText('scriptOutput')">Copy Script</button>
                                    </div>
                                </div>

                                <!-- CSV Tab -->
                                <div class="tab-pane fade" id="csvVec" role="tabpanel">
                                    <pre class="m-0 p-4 text-info bg-dark bg-opacity-50"
                                        style="max-height: 500px; overflow-y: auto; font-family: 'JetBrains Mono', monospace;"
                                        id="csvOutput">Username,Password,Service,Profile</pre>
                                    <div class="p-3 border-top border-secondary d-flex justify-content-end">
                                        <button type="button" class="btn btn-sm btn-outline-light"
                                            onclick="copyText('csvOutput')">Copy CSV</button>
                                    </div>
                                </div>

                                <!-- List Tab -->
                                <div class="tab-pane fade" id="listVec" role="tabpanel">
                                    <div class="p-0 table-responsive">
                                        <table class="table table-dark table-hover mb-0">
                                            <thead>
                                                <tr>
                                                    <th class="ps-4">Username</th>
                                                    <th>Password</th>
                                                    <th>Profile</th>
                                                    <th>Remote Address</th>
                                                </tr>
                                            </thead>
                                            <tbody id="listBody" class="font-monospace small">
                                                <tr>
                                                    <td colspan="4" class="text-center p-4 text-muted">No data</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
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
    @vite(['resources/js/pages/mikrotik-suite/connectivity/pppoe/secrets-generator.js'])
@endpush
