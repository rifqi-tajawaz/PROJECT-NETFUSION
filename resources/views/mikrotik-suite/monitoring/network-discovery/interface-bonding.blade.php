@extends('layouts.app')

@section('content')
    <div class="container-fluid px-4 py-4">
        <div class="mb-4 d-flex flex-column align-items-center justify-content-center text-center">
            <h2 class="display-6 fw-bold mb-2 text-white" style="text-shadow: 0 2px 10px rgba(0,0,0,0.3);">
                <i class="bi bi-diagram-2 me-2 text-primary"></i> Interface Bonding
            </h2>
            <p class="lead opacity-75 mb-0" style="max-width: 700px;">
                Combine multiple network interfaces for higher throughput or redundancy (LACP, Balance-RR).
            </p>
        </div>

        <form id="bondingForm" data-route="{{ route('mikrotik-suite.monitoring.interface-bonding.generate') }}">
            <div class="row g-4 justify-content-center">
                <div class="col-lg-5">
                    <div class="glass-card h-100">
                        <div class="card-header border-0 bg-transparent pt-4 px-4">
                            <h5 class="card-title fw-bold text-white mb-0">Configuration</h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="mb-3">
                                <label class="form-label text-white small text-uppercase">Bonding Name</label>
                                <input type="text" class="form-control" name="bonding_name" id="bondName" value="bonding1">
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-white small text-uppercase">Slaves</label>
                                <input type="text" class="form-control" name="slaves" id="slaves" value="ether1,ether2">
                                <div class="form-text text-white-50">Comma separated interface names.</div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-white small text-uppercase">Mode</label>
                                <select class="form-select" name="mode" id="mode">
                                    <option value="802.3ad">802.3ad (LACP)</option>
                                    <option value="balance-rr">Balance-RR (Round Robin)</option>
                                    <option value="active-backup">Active Backup</option>
                                    <option value="balance-xor">Balance XOR</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-white small text-uppercase">Link Monitoring</label>
                                <select class="form-select" name="monitor" id="monitor">
                                    <option value="mii">MII (Media Independent Interface)</option>
                                    <option value="arp">ARP</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-white small text-uppercase">Monitor Interval</label>
                                <div class="input-group">
                                    <input type="number" class="form-control" name="interval" id="interval" value="100">
                                    <span class="input-group-text bg-dark border-secondary text-white">ms</span>
                                </div>
                            </div>

                            <div class="d-grid mt-4">
                                <button type="submit" class="btn btn-primary btn-lg shadow-lg">
                                    <span class="btn-label">Generate Bonding Script</span>
                                    <span class="btn-loader d-none"><span
                                            class="spinner-border spinner-border-sm me-2"></span>Generating...</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="glass-card h-100">
                        <div class="card-header border-0 bg-transparent pt-4 px-4 d-flex justify-content-between">
                            <h5 class="card-title fw-bold text-white mb-0">RouterOS Script</h5>
                            <button type="button" class="btn btn-sm btn-outline-light"
                                onclick="copyText('scriptOutput')">Copy</button>
                        </div>
                        <div class="card-body p-0">
                            <div class="h-100 d-flex flex-column">
                                <pre class="m-0 p-4 text-warning bg-dark bg-opacity-50 flex-grow-1 font-monospace"
                                    id="scriptOutput" style="min-height:300px;">// Script...</pre>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    @vite(['resources/js/pages/mikrotik-suite/monitoring/network-discovery/interface-bonding.js'])
@endpush
