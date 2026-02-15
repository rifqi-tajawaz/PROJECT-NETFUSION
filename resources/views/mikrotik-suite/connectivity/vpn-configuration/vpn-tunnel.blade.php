@extends('layouts.app')

@section('content')
    <div class="container-fluid px-4 py-4">
        <div class="mb-4 d-flex flex-column align-items-center justify-content-center text-center">
            <h2 class="display-6 fw-bold mb-2 text-white" style="text-shadow: 0 2px 10px rgba(0,0,0,0.3);">
                <i class="bi bi-diagram-3 me-2 text-primary"></i> Site-to-Site Tunnel
            </h2>
            <p class="lead opacity-75 mb-0" style="max-width: 700px;">
                Connect remote sites using GRE, IPIP, or EoIP tunnels.
            </p>
        </div>

        <form id="tunnelForm" data-route="{{ route('mikrotik-suite.connectivity.vpn.tunnel.generate') }}">
            <div class="row g-4">
                <div class="col-lg-5">
                    <div class="glass-card h-100">
                        <div class="card-header border-0 bg-transparent pt-4 px-4">
                            <h5 class="card-title fw-bold text-white mb-0">Configuration</h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="mb-3">
                                <label class="form-label text-white small text-uppercase">Tunnel Type</label>
                                <select class="form-select" name="type" id="tunnelType">
                                    <option value="gre">GRE (Generic Routing Encapsulation)</option>
                                    <option value="ipip">IPIP (IP in IP)</option>
                                    <option value="eoip">EoIP (Ethernet over IP)</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-white small text-uppercase">Interface Name</label>
                                <input type="text" class="form-control" name="interface_name" id="ifaceName"
                                    value="gre-tunnel1">
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-white small text-uppercase">Local Address</label>
                                <input type="text" class="form-control font-monospace" name="local_address" id="localAddr"
                                    placeholder="WAN IP (Optional)">
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-white small text-uppercase">Remote Address (Endpoint)</label>
                                <input type="text" class="form-control font-monospace" name="remote_address" id="remoteAddr"
                                    placeholder="Remote WAN IP" required>
                            </div>

                            <div class="mb-3 d-none" id="eoipIdField">
                                <label class="form-label text-white small text-uppercase">Tunnel ID</label>
                                <input type="number" class="form-control" name="tunnel_id" id="tunnelId" value="10">
                            </div>

                            <div class="mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="use_ipsec" id="ipsecSecret"
                                        value="1">
                                    <label class="form-check-label text-white" for="ipsecSecret">Use IPsec Secret</label>
                                </div>
                                <div class="mt-2 d-none" id="secretField">
                                    <input type="password" class="form-control font-monospace" name="ipsec_secret"
                                        id="secretKey" placeholder="IPsec Secret Key">
                                </div>
                            </div>


                            <div class="d-grid mt-4">
                                <button type="submit" class="btn btn-primary btn-lg shadow-lg"
                                    style="background: linear-gradient(90deg, #0d6efd, #0dcaf0); border:none;">
                                    <span class="btn-label"><i class="bi bi-code-square me-2"></i> Generate Script</span>
                                    <span class="btn-loader d-none"><span
                                            class="spinner-border spinner-border-sm me-2"></span>Generating...</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-7">
                    <div class="glass-card h-100">
                        <div class="card-header border-0 bg-transparent pt-4 px-4 d-flex justify-content-between">
                            <h5 class="card-title fw-bold text-white mb-0">RouterOS Script</h5>
                            <button type="button" class="btn btn-sm btn-outline-light" onclick="copyText('scriptOutput')"><i
                                    class="bi bi-clipboard"></i> Copy</button>
                        </div>
                        <div class="card-body p-0">
                            <pre class="m-0 p-4 text-warning bg-dark bg-opacity-50 h-100"
                                style="min-height:300px; overflow-y: auto; font-family: 'JetBrains Mono', monospace;"
                                id="scriptOutput">// Script will appear here...</pre>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    @vite(['resources/js/pages/mikrotik-suite/connectivity/vpn-configuration/vpn-tunnel.js'])
@endpush
