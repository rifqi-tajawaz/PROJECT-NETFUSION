@extends('layouts.app')

@section('content')
    <div class="container-fluid px-4 py-4">
        <!-- Hero Header -->
        <div class="mb-4 d-flex flex-column align-items-center justify-content-center text-center">
            <h2 class="display-6 fw-bold mb-2 text-white" style="text-shadow: 0 2px 10px rgba(0,0,0,0.3);">
                <i class="bi bi-diagram-2 me-2 text-primary"></i> WireGuard Setup
            </h2>
            <p class="lead opacity-75 mb-0" style="max-width: 700px;">
                Modern, Fast, and Secure VPN.
                Generate WireGuard Interface and Peer configurations.
            </p>
        </div>

        <form id="wgForm" data-route="{{ route('mikrotik-suite.connectivity.vpn.wireguard.generate') }}">
            <div class="row g-4">
                <!-- Server Config -->
                <div class="col-lg-5">
                    <div class="glass-card h-100">
                        <div class="card-header border-0 bg-transparent pt-4 px-4">
                            <h5 class="card-title fw-bold text-white mb-0">
                                <i class="bi bi-server me-2 text-info"></i>Server Configuration
                            </h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="mb-3">
                                <label class="form-label text-white small text-uppercase fw-bold">Listen Port</label>
                                <input type="number" class="form-control" name="listen_port" id="wgPort" value="13231">
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-white small text-uppercase fw-bold">Interface Name</label>
                                <input type="text" class="form-control" name="interface_name" id="wgName"
                                    value="wireguard1">
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-white small text-uppercase fw-bold">VPN Subnet
                                    (Calculator)</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" name="network_address" id="wgNetwork"
                                        value="10.10.10.1/24">
                                </div>
                                <small class="text-muted">MikroTik IP on WireGuard interface.</small>
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-white small text-uppercase fw-bold">Public IP / DNS</label>
                                <input type="text" class="form-control" id="wgEndpoint" placeholder="vpn.mydomain.com">
                                <small class="text-muted">For Client Config (Endpoint).</small>
                            </div>

                            <div class="d-grid mt-4">
                                <button type="submit" class="btn btn-primary btn-lg shadow-lg"
                                    style="background: linear-gradient(90deg, #6610f2, #0d6efd); border:none;">
                                    <span class="btn-label"><i class="bi bi-file-earmark-code me-2"></i> Generate
                                        Configs</span>
                                    <span class="btn-loader d-none"><span
                                            class="spinner-border spinner-border-sm me-2"></span>Generating...</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Peers / Clients -->
                <div class="col-lg-7">
                    <div class="glass-card h-100">
                        <div
                            class="card-header border-0 bg-transparent pt-4 px-4 d-flex justify-content-between align-items-center">
                            <h5 class="card-title fw-bold text-white mb-0">
                                <i class="bi bi-people me-2 text-warning"></i>Peers (Clients)
                            </h5>
                            <button type="button" class="btn btn-sm btn-outline-light" onclick="addPeer()">
                                <i class="bi bi-plus-lg"></i> Add Peer
                            </button>
                        </div>
                        <div class="card-body p-4">
                            <div id="peersContainer">
                                <!-- Peer 1 -->
                                <div class="peer-item mb-3 animate__animated animate__fadeIn">
                                    <div class="p-3 rounded-3"
                                        style="background: rgba(255,255,255,0.05); border: 1px solid var(--glass-border);">
                                        <div class="row g-2">
                                            <div class="col-md-4">
                                                <label class="small text-muted mb-1">Client Name</label>
                                                <input type="text" class="form-control form-control-sm peer-name"
                                                    value="Client-Phone">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="small text-muted mb-1">Allowed IPs (Client IP)</label>
                                                <input type="text" class="form-control form-control-sm peer-ip"
                                                    value="10.10.10.2/32">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="small text-muted mb-1">&nbsp;</label>
                                                <button type="button" class="btn btn-sm btn-danger w-100"
                                                    onclick="this.closest('.peer-item').remove()">Remove</button>
                                            </div>
                                            <div class="col-12 mt-2">
                                                <label class="small text-muted mb-1">Client Public Key (Optional)</label>
                                                <input type="text" class="form-control form-control-sm peer-pubkey"
                                                    placeholder="Paste Client Public Key here if known...">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Result -->
            <div class="row mt-4" id="resultContainer" style="display:none;">
                <div class="col-12">
                    <div class="glass-card">
                        <div class="card-header border-0 bg-transparent pt-4 px-4">
                            <ul class="nav nav-pills card-header-pills" id="wgTabs" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="server-tab" data-bs-toggle="tab" href="#server"
                                        role="tab">RouterOS Script</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="client-tab" data-bs-toggle="tab" href="#client"
                                        role="tab">Client Configs (QR Ready)</a>
                                </li>
                            </ul>
                        </div>
                        <div class="card-body p-0">
                            <div class="tab-content" id="wgTabContent">
                                <div class="tab-pane fade show active" id="server" role="tabpanel">
                                    <pre class="m-0 p-4 text-warning bg-dark bg-opacity-50"
                                        style="max-height: 500px; overflow-y: auto; font-family: 'JetBrains Mono', monospace;"
                                        id="serverScript"></pre>
                                    <div class="p-3 border-top border-secondary">
                                        <button class="btn btn-sm btn-outline-light" onclick="copyText('serverScript')">Copy
                                            RouterOS Script</button>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="client" role="tabpanel">
                                    <div class="p-4" id="clientConfigs">
                                        <p class="text-muted">Generate the RouterOS script first, then paste the Server
                                            Public Key below to complete client configs.</p>
                                        <div class="mb-3">
                                            <label class="form-label text-white">Server Public Key</label>
                                            <input type="text" class="form-control" id="serverPubKeyInput"
                                                placeholder="Paste generated Server Public Key from MikroTik here..."
                                                oninput="updateClientConfigs()">
                                        </div>
                                        <hr class="border-secondary">
                                        <div id="clientConfigOutput"></div>
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
    @vite(['resources/js/pages/mikrotik-suite/connectivity/vpn-configuration/wireguard.js'])
@endpush
