@extends('layouts.app')

@section('content')
    <div class="container-fluid px-4 py-4">
        <!-- Hero Header -->
        <div class="mb-4 d-flex flex-column align-items-center justify-content-center text-center">
            <h2 class="display-6 fw-bold mb-2 text-white" style="text-shadow: 0 2px 10px rgba(0,0,0,0.3);">
                <i class="bi bi-shield-lock me-2 text-warning"></i> L2TP/IPsec Server
            </h2>
            <p class="lead opacity-75 mb-0" style="max-width: 700px;">
                Secure Remote Access Generator.
                Enable L2TP VPN with IPsec encryption for safe connectivity.
            </p>
        </div>

        <form id="l2tpForm" data-route="{{ route('mikrotik-suite.connectivity.vpn.l2tp-server.generate') }}">
            <div class="row g-4">
                <!-- Configuration -->
                <div class="col-lg-5">
                    <div class="glass-card h-100">
                        <div class="card-header border-0 bg-transparent pt-4 px-4">
                            <h5 class="card-title fw-bold text-white mb-0">
                                <i class="bi bi-sliders me-2 text-primary"></i>Server Settings
                            </h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="mb-3">
                                <label class="form-label text-white small text-uppercase fw-bold">IPsec Secret (Pre-Shared
                                    Key)</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-dark border-secondary text-secondary"><i
                                            class="bi bi-key"></i></span>
                                    <input type="text" class="form-control" name="ipsec_secret" id="ipsecSecret"
                                        placeholder="e.g. MySecureKey123" required>
                                    <button class="btn btn-outline-secondary" type="button" onclick="generateRandomKey()"><i
                                            class="bi bi-magic"></i></button>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label text-white small text-uppercase fw-bold">VPN Pool Range</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-dark border-secondary text-secondary"><i
                                            class="bi bi-hdd-network"></i></span>
                                    <input type="text" class="form-control" name="vpn_pool" id="vpnPool"
                                        value="192.168.89.10-192.168.89.100" placeholder="Start-End IP">
                                </div>
                                <small class="text-muted">Remote addresses assigned to clients.</small>
                            </div>

                            <div class="mb-3">
                                <label class="form-label text-white small text-uppercase fw-bold">Local Address
                                    (Gateway)</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-dark border-secondary text-secondary"><i
                                            class="bi bi-router"></i></span>
                                    <input type="text" class="form-control" name="local_address" id="localAddr"
                                        value="192.168.89.1" placeholder="Gateway IP">
                                </div>
                            </div>

                            <div class="mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="firewall" id="chkFirewall"
                                        value="1" checked>
                                    <label class="form-check-label text-white" for="chkFirewall">Add Firewall Rules (Input
                                        Chain)</label>
                                </div>
                                <small class="text-muted ms-4">Allows UDP 500, 1701, 4500 and IPsec-ESP.</small>
                            </div>

                            <div class="mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="create_user" id="checkUser"
                                        value="1" onchange="toggleUserFields()">
                                    <label class="form-check-label text-white" for="checkUser">Create First User</label>
                                </div>
                            </div>

                            <div id="userFields" style="display:none;" class="animate__animated animate__fadeIn">
                                <div class="p-3 rounded-3 bg-dark bg-opacity-25 border border-secondary mb-3">
                                    <div class="mb-2">
                                        <label class="form-label text-secondary small">Username</label>
                                        <input type="text" class="form-control form-control-sm" name="username" id="vpnUser"
                                            placeholder="vpnclient">
                                    </div>
                                    <div>
                                        <label class="form-label text-secondary small">Password</label>
                                        <input type="text" class="form-control form-control-sm" name="password" id="vpnPass"
                                            placeholder="password">
                                    </div>
                                </div>
                            </div>

                            <div class="d-grid mt-4">
                                <button type="submit" class="btn btn-primary btn-lg shadow-lg"
                                    style="background: linear-gradient(90deg, #ffc107, #fd7e14); border:none;">
                                    <span class="btn-label"><i class="bi bi-code-square me-2"></i> Generate
                                        Configuration</span>
                                    <span class="btn-loader d-none"><span
                                            class="spinner-border spinner-border-sm me-2"></span>Generating...</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Result -->
                <div class="col-lg-7">
                    <div class="glass-card h-100">
                        <div
                            class="card-header border-0 bg-transparent pt-4 px-4 d-flex justify-content-between align-items-center">
                            <h5 class="card-title fw-bold text-white mb-0">
                                <i class="bi bi-terminal me-2 text-success"></i>Generated Script
                            </h5>
                            <button type="button" class="btn btn-sm btn-outline-light" onclick="copyScript()">
                                <i class="bi bi-clipboard me-1"></i> Copy
                            </button>
                        </div>
                        <div class="card-body p-0">
                            <pre class="m-0 p-4 text-warning bg-dark bg-opacity-50"
                                style="max-height: 500px; overflow-y: auto; font-family: 'JetBrains Mono', monospace;"
                                id="scriptOutput">// Configure settings and click Generate...</pre>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    @vite(['resources/js/pages/mikrotik-suite/connectivity/vpn-configuration/l2tp-server.js'])
@endpush
