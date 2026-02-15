@extends('layouts.app')

@section('content')
    <div class="container-fluid px-4 py-4">
        <div class="mb-4 d-flex flex-column align-items-center justify-content-center text-center">
            <h2 class="display-6 fw-bold mb-2 text-white" style="text-shadow: 0 2px 10px rgba(0,0,0,0.3);">
                <i class="bi bi-shield-shaded me-2 text-danger"></i> OpenVPN Server
            </h2>
            <p class="lead opacity-75 mb-0" style="max-width: 700px;">
                Open Source VPN standard. Highly secure and configurable.
            </p>
        </div>

        <form id="ovpnForm" data-route="{{ route('mikrotik-suite.connectivity.vpn.openvpn.generate') }}">
            <div class="row g-4">
                <div class="col-lg-5">
                    <div class="glass-card h-100">
                        <div class="card-header border-0 bg-transparent pt-4 px-4">
                            <h5 class="card-title fw-bold text-white mb-0">Configuration</h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="mb-3">
                                <label class="form-label text-white small text-uppercase">Profile Name</label>
                                <input type="text" class="form-control" name="profile_name" id="profileName"
                                    value="profile-ovpn">
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-white small text-uppercase">Local Address</label>
                                <input type="text" class="form-control font-monospace" name="local_address" id="localAddr"
                                    value="10.8.0.1">
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-white small text-uppercase">Remote Address (Pool)</label>
                                <input type="text" class="form-control font-monospace" name="remote_address" id="remoteAddr"
                                    value="10.8.0.10-10.8.0.254">
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-white small text-uppercase">Certificate</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="generate_cert" id="genCert"
                                        value="1" checked>
                                    <label class="form-check-label text-white" for="genCert">Generate Certificates</label>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-white small text-uppercase">Auth</label>
                                <select class="form-select" name="auth_alg" id="authAlg">
                                    <option value="sha1">SHA1</option>
                                    <option value="md5">MD5</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-white small text-uppercase">Cipher</label>
                                <select class="form-select" name="cipher_alg" id="cipherAlg">
                                    <option value="aes256">AES 256 (Secure)</option>
                                    <option value="aes128">AES 128 (Faster)</option>
                                    <option value="blowfish128">Blowfish 128</option>
                                </select>
                            </div>


                            <div class="d-grid mt-4">
                                <button type="submit" class="btn btn-primary btn-lg shadow-lg"
                                    style="background: linear-gradient(90deg, #dc3545, #fd7e14); border:none;">
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
    @vite(['resources/js/pages/mikrotik-suite/connectivity/vpn-configuration/openvpn.js'])
@endpush
