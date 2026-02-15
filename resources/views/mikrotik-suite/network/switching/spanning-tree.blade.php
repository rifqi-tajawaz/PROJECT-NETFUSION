@extends('layouts.app')

@section('content')
    <div class="container-fluid px-4 py-4">
        <div class="mb-4 d-flex flex-column align-items-center justify-content-center text-center">
            <h2 class="display-6 fw-bold mb-2 text-white" style="text-shadow: 0 2px 10px rgba(0,0,0,0.3);">
                <i class="bi bi-diagram-3 me-2 text-warning"></i> Spanning Tree Protocol
            </h2>
            <p class="lead opacity-75 mb-0" style="max-width: 700px;">
                Configure STP/RSTP/MSTP on Bridges to prevent loops.
            </p>
        </div>

        <form id="stpForm" data-route="{{ route('mikrotik-suite.network.switching.spanning-tree.generate') }}">
            <div class="row g-4 justify-content-center">
                <div class="col-lg-5">
                    <div class="glass-card h-100">
                        <div class="card-header border-0 bg-transparent pt-4 px-4">
                            <h5 class="card-title fw-bold text-white mb-0">Bridge Settings</h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="mb-3">
                                <label class="form-label text-white small text-uppercase">Bridge Interface</label>
                                <input type="text" class="form-control" name="bridge_name" id="bridge" value="bridge1">
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-white small text-uppercase">Protocol Mode</label>
                                <select class="form-select" name="protocol_mode" id="mode">
                                    <option value="rstp">RSTP (Rapid STP) - Default</option>
                                    <option value="mstp">MSTP (Multiple STP)</option>
                                    <option value="stp">STP (Legacy)</option>
                                    <option value="none">None (DANGEROUS)</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-white small text-uppercase">Priority (Root Selection)</label>
                                <select class="form-select" name="priority" id="prio">
                                    <option value="0x1000">Primary Root (0x1000)</option>
                                    <option value="0x2000">Secondary Root (0x2000)</option>
                                    <option value="0x8000" selected>Default (0x8000)</option>
                                    <option value="custom">Custom</option>
                                </select>
                            </div>

                            <div class="d-grid mt-4">
                                <button type="submit" class="btn btn-warning text-dark btn-lg shadow-lg">
                                    <span class="btn-label">Generate STP Config</span>
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
                            <pre class="m-0 p-4 text-warning bg-dark bg-opacity-50 h-100 font-monospace" id="scriptOutput"
                                style="min-height:300px;">// Script...</pre>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    @vite(['resources/js/pages/mikrotik-suite/network/switching/spanning-tree.js'])
@endpush
