@extends('layouts.app')

@section('content')
    <div class="container-fluid px-4 py-4">
        <div class="mb-4 d-flex flex-column align-items-center justify-content-center text-center">
            <h2 class="display-6 fw-bold mb-2 text-white" style="text-shadow: 0 2px 10px rgba(0,0,0,0.3);">
                <i class="bi bi-flashlight me-2 text-warning"></i> Packet Torch
            </h2>
            <p class="lead opacity-75 mb-0" style="max-width: 700px;">
                Real-time traffic monitoring tool generator.
            </p>
        </div>

        <form id="torchForm" data-route="{{ route('mikrotik-suite.monitoring.troubleshooting.packet-sniffer.generate') }}">
            <div class="row g-4 justify-content-center">
                <div class="col-lg-5">
                    <div class="glass-card h-100">
                        <div class="card-header border-0 bg-transparent pt-4 px-4">
                            <h5 class="card-title fw-bold text-white mb-0">Configuration</h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="mb-3">
                                <label class="form-label text-white small text-uppercase">Interface</label>
                                <input type="text" class="form-control" name="interface" id="iface" value="ether1">
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-white small text-uppercase">Src Address</label>
                                <input type="text" class="form-control" name="src_address" id="src" placeholder="0.0.0.0/0">
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-white small text-uppercase">Dst Address</label>
                                <input type="text" class="form-control" name="dst_address" id="dst" placeholder="0.0.0.0/0">
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-white small text-uppercase">Protocol</label>
                                <select class="form-select" name="protocol" id="proto">
                                    <option value="any">any</option>
                                    <option value="tcp">tcp</option>
                                    <option value="udp">udp</option>
                                    <option value="icmp">icmp</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-white small text-uppercase">Port</label>
                                <input type="text" class="form-control" name="port" id="port" placeholder="any">
                            </div>

                            <div class="d-grid mt-4">
                                <button type="submit" class="btn btn-warning btn-lg shadow-lg text-dark">
                                    <span class="btn-label">Generate Torch Command</span>
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
                            <h5 class="card-title fw-bold text-white mb-0">RouterOS Command</h5>
                            <button type="button" class="btn btn-sm btn-outline-light"
                                onclick="copyText('scriptOutput')">Copy</button>
                        </div>
                        <div class="card-body p-0">
                            <div
                                class="alert alert-info bg-info bg-opacity-25 border-0 text-white m-3 small border-start border-4 border-info">
                                <i class="bi bi-terminal me-1"></i> Paste this in Terminal to start live view. Press 'Q' to
                                quit.
                            </div>
                            <pre class="m-0 p-4 text-warning bg-dark bg-opacity-50 flex-grow-1 font-monospace"
                                id="scriptOutput" style="min-height:300px;">// Command...</pre>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    @vite(['resources/js/pages/mikrotik-suite/monitoring/troubleshooting/packet-sniffer.js'])
@endpush
