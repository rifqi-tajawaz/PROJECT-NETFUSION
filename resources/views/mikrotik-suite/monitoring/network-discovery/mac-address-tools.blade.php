@extends('layouts.app')

@section('content')
    <div class="container-fluid px-4 py-4">
        <div class="mb-4 d-flex flex-column align-items-center justify-content-center text-center">
            <h2 class="display-6 fw-bold mb-2 text-white" style="text-shadow: 0 2px 10px rgba(0,0,0,0.3);">
                <i class="bi bi-upc-scan me-2 text-warning"></i> MAC Address Tools
            </h2>
            <p class="lead opacity-75 mb-0" style="max-width: 700px;">
                Utilities to scan, filter, and manage MAC addresses on your network.
            </p>
        </div>

        <div class="row g-4 justify-content-center">
            <!-- MAC Scanner -->
            <div class="col-lg-6">
                <div class="glass-card h-100">
                    <div class="card-header border-0 bg-transparent pt-4 px-4">
                        <h5 class="card-title fw-bold text-white mb-0">MAC Scanner (IP Scan)</h5>
                    </div>
                    <div class="card-body p-4">
                        <form id="scanForm" data-route="{{ route('mikrotik-suite.monitoring.mac-address-tools.scan') }}">
                            <div class="mb-3">
                                <label class="form-label text-white small text-uppercase">Interface</label>
                                <input type="text" class="form-control" name="interface" id="scanIface" value="ether1">
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-white small text-uppercase">Range IP</label>
                                <input type="text" class="form-control" name="range" id="scanRange" value="192.168.88.0/24">
                            </div>
                            <button type="submit" class="btn btn-warning w-100 text-dark">
                                <span class="btn-label">Generate Scan Command</span>
                                <span class="btn-loader d-none"><span
                                        class="spinner-border spinner-border-sm me-2"></span>Generating...</span>
                            </button>
                        </form>

                        <div class="mt-3 p-3 bg-dark bg-opacity-50 rounded-3 border border-secondary border-opacity-25">
                            <code class="text-info font-monospace" id="scanScript">// Command...</code>
                        </div>
                    </div>
                </div>
            </div>

            <!-- MAC Ping -->
            <div class="col-lg-6">
                <div class="glass-card h-100">
                    <div class="card-header border-0 bg-transparent pt-4 px-4">
                        <h5 class="card-title fw-bold text-white mb-0">MAC Ping / Telnet</h5>
                    </div>
                    <div class="card-body p-4">
                        <form id="pingForm" data-route="{{ route('mikrotik-suite.monitoring.mac-address-tools.ping') }}">
                            <div class="mb-3">
                                <label class="form-label text-white small text-uppercase">Target MAC</label>
                                <input type="text" class="form-control font-monospace" name="mac_address" id="pingMac"
                                    placeholder="00:11:22:33:44:55">
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-white small text-uppercase">Tool</label>
                                <select class="form-select" name="tool" id="pingTool">
                                    <option value="ping">MAC Ping</option>
                                    <option value="telnet">MAC Telnet</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-info w-100">
                                <span class="btn-label">Generate Tool Command</span>
                                <span class="btn-loader d-none"><span
                                        class="spinner-border spinner-border-sm me-2"></span>Generating...</span>
                            </button>
                        </form>

                        <div class="mt-3 p-3 bg-dark bg-opacity-50 rounded-3 border border-secondary border-opacity-25">
                            <code class="text-success font-monospace" id="pingScript">// Command...</code>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    @vite(['resources/js/pages/mikrotik-suite/monitoring/network-discovery/mac-address-tools.js'])
@endpush
