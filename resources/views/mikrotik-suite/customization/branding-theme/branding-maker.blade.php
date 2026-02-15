@extends('layouts.app')

@section('content')
    <div class="container-fluid px-4 py-4">
        <div class="mb-4 d-flex flex-column align-items-center justify-content-center text-center">
            <h2 class="display-6 fw-bold mb-2 text-white" style="text-shadow: 0 2px 10px rgba(0,0,0,0.3);">
                <i class="bi bi-brush me-2 text-primary"></i> Branding Maker
            </h2>
            <p class="lead opacity-75 mb-0" style="max-width: 700px;">
                Customize your router's identity, login note, and console appearance.
            </p>
        </div>

        <form id="brandingForm" data-route="{{ route('mikrotik-suite.customization.branding-maker.generate') }}">
            <div class="row g-4">
                <div class="col-lg-5">
                    <div class="glass-card h-100">
                        <div class="card-header border-0 bg-transparent pt-4 px-4">
                            <h5 class="card-title fw-bold text-white mb-0">Identity & Login</h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="mb-3">
                                <label class="form-label text-white small text-uppercase">Router Identity</label>
                                <input type="text" class="form-control" name="identity" id="identity" value="MikroTik-Main">
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-white small text-uppercase">Login Note (Banner)</label>
                                <textarea class="form-control" name="note" id="note" rows="5"
                                    placeholder="Welcome to ...">Authorized Access Only!</textarea>
                                <div class="form-text text-white-50">Appears before login prompt.</div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-white small text-uppercase">ASCII Art Top</label>
                                <textarea class="form-control font-monospace" name="ascii" id="ascii" rows="4"
                                    placeholder="   __  __ ..."></textarea>
                            </div>

                            <div class="d-grid mt-4">
                                <button type="submit" class="btn btn-primary btn-lg shadow-lg"
                                    style="background: linear-gradient(90deg, #0d6efd, #0dcaf0); border:none;">
                                    <span class="btn-label"><i class="bi bi-code-square me-2"></i> Generate Branding
                                        Script</span>
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
                            <div class="bg-dark bg-opacity-50 p-3 h-100 d-flex flex-column" style="min-height: 400px;">
                                <div
                                    class="alert alert-info bg-info bg-opacity-10 border-info border-opacity-25 text-info mb-2 small">
                                    <i class="bi bi-info-circle me-2"></i> Paste this into a <strong>New Terminal</strong>
                                    window.
                                </div>
                                <pre class="m-0 p-3 text-warning font-monospace flex-grow-1" id="scriptOutput"
                                    style="overflow-y: auto;">// Script will appear here...</pre>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    @vite(['resources/js/pages/mikrotik-suite/customization/branding-theme/branding-maker.js'])
@endpush
