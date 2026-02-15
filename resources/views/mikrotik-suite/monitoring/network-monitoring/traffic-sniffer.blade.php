@extends('layouts.app')

@section('content')
    <div class="container-fluid px-4 py-4">
        <div class="mb-4 d-flex flex-column align-items-center justify-content-center text-center">
            <h2 class="display-6 fw-bold mb-2 text-white" style="text-shadow: 0 2px 10px rgba(0,0,0,0.3);">
                <i class="bi bi-hdd-network me-2 text-info"></i> Traffic Sniffer
            </h2>
            <p class="lead opacity-75 mb-0" style="max-width: 700px;">
                Configure Packet Sniffer to stream data to Wireshark (TZSP) or save to file.
            </p>
        </div>

        <form id="sniffForm" data-route="{{ route('mikrotik-suite.monitoring.traffic-sniffer.generate') }}">
            <div class="row g-4 justify-content-center">
                <div class="col-lg-5">
                    <div class="glass-card h-100">
                        <div class="card-header border-0 bg-transparent pt-4 px-4">
                            <h5 class="card-title fw-bold text-white mb-0">Configuration</h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="mb-3">
                                <label class="form-label text-white small text-uppercase">Target Interface</label>
                                <input type="text" class="form-control" name="interface" id="iface" value="all">
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-white small text-uppercase">Filter Protocol</label>
                                <select class="form-select" name="protocol" id="proto">
                                    <option value="all">All</option>
                                    <option value="icmp">ICMP (Ping)</option>
                                    <option value="tcp">TCP</option>
                                    <option value="udp">UDP</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-white small text-uppercase">Output Mode</label>
                                <select class="form-select" name="output_mode" id="output">
                                    <option value="stream">Stream to Wireshark (TZSP)</option>
                                    <option value="file">Save to File</option>
                                </select>
                            </div>

                            <div id="streamOpts">
                                <div class="mb-3">
                                    <label class="form-label text-white small text-uppercase">Wireshark IP</label>
                                    <input type="text" class="form-control" name="server_ip" id="server"
                                        placeholder="192.168.88.254">
                                    <div class="form-text text-white-50">Your PC's IP address running Wireshark. Port 37008.
                                    </div>
                                </div>
                            </div>
                            <div id="fileOpts" style="display:none;">
                                <div class="mb-3">
                                    <label class="form-label text-white small text-uppercase">Filename</label>
                                    <input type="text" class="form-control" name="filename" id="filename"
                                        value="capture.pcap">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label text-white small text-uppercase">File Limit</label>
                                    <input type="text" class="form-control" name="limit" id="limit" value="10000">
                                    <small class="text-white-50">KB</small>
                                </div>
                            </div>

                            <div class="d-grid mt-4">
                                <button type="submit" class="btn btn-info btn-lg shadow-lg">
                                    <span class="btn-label">Generate Sniff Command</span>
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
                            <div
                                class="alert alert-info bg-info bg-opacity-25 border-0 text-white m-3 small border-start border-4 border-info">
                                <i class="bi bi-info-circle me-1"></i> Paste this in Terminal. Sniffer will start
                                immediately.
                            </div>
                            <pre class="m-0 p-4 text-warning bg-dark bg-opacity-50 flex-grow-1 font-monospace"
                                id="scriptOutput" style="min-height:250px;">// Script...</pre>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    @vite(['resources/js/pages/mikrotik-suite/monitoring/network-monitoring/traffic-sniffer.js'])
@endpush
