@extends('layouts.app')

@section('content')
    <div class="container-fluid px-4 py-4">
        <div class="mb-4 d-flex flex-column align-items-center justify-content-center text-center">
            <h2 class="display-6 fw-bold mb-2 text-white" style="text-shadow: 0 2px 10px rgba(0,0,0,0.3);">
                <i class="bi bi-shield-lock me-2 text-primary"></i> DNS over HTTPS (DoH)
            </h2>
            <p class="lead opacity-75 mb-0" style="max-width: 700px;">
                Secure your DNS queries by encrypting them via HTTPS. Prevents ISP tracking and spoofing.
            </p>
        </div>

        <div class="row g-4 justify-content-center">
            <div class="col-lg-5">
                <div class="glass-card h-100">
                    <div class="card-header border-0 bg-transparent pt-4 px-4">
                        <h5 class="card-title fw-bold text-white mb-0">Provider Selection</h5>
                    </div>
                    <div class="card-body p-4">
                        <form id="dohForm" data-route="{{ route('mikrotik-suite.monitoring.dns-over-https.generate') }}">
                            <div class="mb-3">
                                <label class="form-label text-white small text-uppercase">Provider</label>
                                <select class="form-select" name="provider" id="provider">
                                    <option value="cloudflare">Cloudflare (1.1.1.1)</option>
                                    <option value="google">Google (8.8.8.8)</option>
                                    <option value="quad9">Quad9 (9.9.9.9)</option>
                                    <option value="nextdns">NextDNS (Custom)</option>
                                    <option value="custom">Custom URL</option>
                                </select>
                            </div>

                            <div id="customGroup" style="display:none;" class="mb-3">
                                <label class="form-label text-white small text-uppercase">DoH URL</label>
                                <input type="url" class="form-control" name="custom_url" id="customUrl"
                                    placeholder="https://dns.example.com/dns-query">
                            </div>

                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="verify_cert" id="verifyCert"
                                        value="1" checked>
                                    <label class="form-check-label text-white" for="verifyCert">Verify Certificate
                                        (Recommended)</label>
                                </div>
                                <div class="form-text text-white-50 small">Requires "roots.crt" certificate imported.</div>
                            </div>

                            <div class="d-grid mt-4">
                                <button type="submit" class="btn btn-primary btn-lg shadow-lg">
                                    <span class="btn-label">Generate DoH Configuration</span>
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
                            style="min-height:300px;">// Script...</pre>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    @vite(['resources/js/pages/mikrotik-suite/monitoring/dns-time/dns-over-https.js'])
@endpush
