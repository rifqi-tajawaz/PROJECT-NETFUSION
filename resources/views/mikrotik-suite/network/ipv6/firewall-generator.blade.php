@extends('layouts.app')

@section('content')
    <div class="container-fluid px-4 py-4">
        <div class="mb-4 d-flex flex-column align-items-center justify-content-center text-center">
            <h2 class="display-6 fw-bold mb-2 text-white" style="text-shadow: 0 2px 10px rgba(0,0,0,0.3);">
                <i class="bi bi-shield-lock-fill me-2 text-primary"></i> IPv6 Firewall Generator
            </h2>
            <p class="lead opacity-75 mb-0" style="max-width: 700px;">
                Create a baseline Statefull IPv6 Firewall preventing unauthorized access.
            </p>
        </div>

        <div class="row g-4 justify-content-center">
            <div class="col-lg-6">
                <div class="glass-card h-100">
                    <div class="card-header border-0 bg-transparent pt-4 px-4">
                        <h5 class="card-title fw-bold text-white mb-0">Baseline Rules</h5>
                    </div>
                    <div class="card-body p-4">
                        <form id="fwForm" data-route="{{ route('mikrotik-suite.network.ipv6.firewall-generator.generate') }}">
                            <p class="text-white">This tool generates a standard MikroTik CPE/Router baseline firewall for
                                IPv6:
                            </p>
                            <ul class="text-white opacity-75">
                                <li>Accept Established/Related</li>
                                <li>Drop Invalid</li>
                                <li>Accept ICMPv6 (Essential for ND/SLAAC)</li>
                                <li>Accept UDP Traceroute & DHCPv6</li>
                                <li>Drop Everything Else (Input & Forward)</li>
                            </ul>
                            <p class="text-white small">LAN Interface: <span class="text-success fw-bold">LAN-List</span> |
                                WAN
                                Interface: <span class="text-danger fw-bold">WAN-List</span></p>

                            <div class="d-grid mt-4">
                                <button type="submit" class="btn btn-primary btn-lg shadow-lg">
                                    <span class="btn-label">Generate Baseline Firewall</span>
                                    <span class="btn-loader d-none"><span
                                            class="spinner-border spinner-border-sm me-2"></span>Generating...</span>
                                </button>
                            </div>
                        </form>
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
                        <pre class="m-0 p-4 text-warning bg-dark bg-opacity-50 h-100 font-monospace" id="scriptOutput"
                            style="min-height:400px;">// Click Generate...</pre>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    @vite(['resources/js/pages/mikrotik-suite/network/ipv6/firewall-generator.js'])
@endpush
