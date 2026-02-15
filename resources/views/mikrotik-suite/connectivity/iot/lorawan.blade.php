@extends('layouts.app')

@section('content')
    <div class="container-fluid px-4 py-4">
        <div class="mb-4 d-flex flex-column align-items-center justify-content-center text-center">
            <h2 class="display-6 fw-bold mb-2 text-theme-main" style="text-shadow: 0 2px 10px rgba(0,0,0,0.3);">
                <i class="bi bi-hdd-network me-2 text-primary"></i> LoRaWAN Gateway
            </h2>
            <p class="lead opacity-75 mb-0" style="max-width: 700px;">
                Configure LoRaWAN packet forwarder or server (TTN/ChirpStack).
            </p>
        </div>

        <div class="row g-4 justify-content-center">
            <!-- Configuration Column -->
            <div class="col-lg-5">
                <form id="loraForm" class="no-loader h-100"
                    data-route="{{ route('mikrotik-suite.connectivity.iot.lorawan-gateway.generate') }}">
                    <div class="glass-card h-100 d-flex flex-column">
                        <div
                            class="card-header border-0 bg-transparent py-3 px-4 d-flex align-items-center justify-content-between">
                            <h5 class="card-title fw-bold text-theme-main mb-0 fs-6">
                                <i class="bi bi-sliders me-2 text-primary"></i>Server Settings
                            </h5>
                            <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3">LoRa</span>
                        </div>

                        <div class="card-body p-4 custom-scrollbar"
                            style="overflow-y: auto; max-height: calc(100vh - 250px);">
                            <div class="mb-3">
                                <label class="form-label text-theme-main fw-bold text-uppercase">Server Name</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-transparent border-end-0 text-theme-secondary"><i
                                            class="bi bi-tag"></i></span>
                                    <input type="text" class="form-control border-start-0 ps-0" name="name" id="name"
                                        value="TTN-EU" placeholder="TTN-EU">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-theme-main fw-bold text-uppercase">Server Address</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-transparent border-end-0 text-theme-secondary"><i
                                            class="bi bi-cloud"></i></span>
                                    <input type="text" class="form-control border-start-0 ps-0" name="addr" id="addr"
                                        value="eu1.cloud.thethings.network" placeholder="eu1.cloud.thethings.network">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-theme-main fw-bold text-uppercase">UDP Ports</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-transparent border-end-0 text-theme-secondary"><i
                                            class="bi bi-arrow-left-right"></i></span>
                                    <input type="text" class="form-control border-start-0 ps-0" name="ports" id="ports"
                                        value="1700" placeholder="1700">
                                </div>
                            </div>
                        </div>

                        <!-- Footer Actions -->
                        <div class="card-footer border-0 bg-transparent p-4 pt-0">
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary btn-lg shadow-primary hover-scale">
                                    <span class="btn-label"><i class="bi bi-lightning-charge me-2"></i>Generate LoRa
                                        Config</span>
                                    <span class="btn-loader d-none"><span
                                            class="spinner-border spinner-border-sm me-2"></span>Generating...</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Terminal Column -->
            <div class="col-lg-7">
                <div class="code-window h-100 d-flex flex-column">
                    <div class="code-header">
                        <div class="d-flex align-items-center gap-3">
                            <div class="code-dots">
                                <div class="dot dot-red"></div>
                                <div class="dot dot-yellow"></div>
                                <div class="dot dot-green"></div>
                            </div>
                            <span class="code-title font-monospace ms-2">script.rsc</span>
                        </div>
                        <div class="d-flex gap-2">
                            <button class="btn btn-sm btn-dark border border-secondary text-white-50 py-0 px-2"
                                onclick="copyText('scriptOutput')" title="Copy to Clipboard">
                                <i class="bi bi-clipboard me-1"></i> Copy
                            </button>
                        </div>
                    </div>

                    <div class="code-content custom-scrollbar flex-grow-1 p-0 position-relative">
                        <div
                            class="alert alert-info bg-dark bg-opacity-25 border-0 border-start border-4 border-info rounded-0 mb-0 p-3">
                            <small class="text-info"><i class="bi bi-info-circle me-2"></i>Requires 'lora' package installed
                                on RouterOS.</small>
                        </div>
                        <pre class="m-0 p-3 text-warning font-monospace" id="scriptOutput"
                            style="white-space: pre-wrap; font-size: 0.85rem; line-height: 1.6;"># Generated script will appear here...</pre>
                    </div>
                    <div class="p-2 bg-dark border-top border-secondary d-flex justify-content-between align-items-center">
                        <small class="text-muted font-monospace ms-2">RouterOS Script</small>
                        <small class="text-muted font-monospace me-2">UTF-8</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    @vite(['resources/js/pages/mikrotik-suite/connectivity/iot/lorawan.js'])
@endpush
