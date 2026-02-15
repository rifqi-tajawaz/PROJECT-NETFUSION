@extends('layouts.app')

@section('content')
    <div class="container-fluid px-4 py-4">
        <div class="mb-4 d-flex flex-column align-items-center justify-content-center text-center">
            <h2 class="display-6 fw-bold mb-2 text-white" style="text-shadow: 0 2px 10px rgba(0,0,0,0.3);">
                <i class="bi bi-list-columns me-2 text-warning"></i> Lockpack Creator
            </h2>
            <p class="lead opacity-75 mb-0" style="max-width: 700px;">
                Generate custom Scan Lists for your Wireless Interfaces.
            </p>
        </div>

        <div class="row g-4 justify-content-center">
            <div class="col-lg-4">
                <div class="glass-card h-100">
                    <div class="card-header border-0 bg-transparent pt-4 px-4">
                        <h5 class="card-title fw-bold text-white mb-0">Configuration</h5>
                    </div>
                    <div class="card-body p-4">
                        <form id="lockpackForm" data-route="{{ route('mikrotik-suite.wireless.lockpack.generate') }}">
                            <div class="mb-3">
                                <label class="form-label text-white small text-uppercase">Interface</label>
                                <input type="text" class="form-control" name="interface" id="ifaceName" value="wlan1">
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-white small text-uppercase">Band Preset</label>
                                <select class="form-select" id="bandSelect">
                                    <option value="5ghz">5 GHz Standard (5180-5825)</option>
                                    <option value="2.4ghz">2.4 GHz Standard (2412-2462)</option>
                                    <option value="custom">Custom Range</option>
                                </select>
                            </div>

                            <div class="row g-2 mb-3">
                                <div class="col-6">
                                    <label class="form-label text-white small text-uppercase">Start (MHz)</label>
                                    <input type="number" class="form-control" id="startFreq" value="5180">
                                </div>
                                <div class="col-6">
                                    <label class="form-label text-white small text-uppercase">End (MHz)</label>
                                    <input type="number" class="form-control" id="endFreq" value="5825">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label text-white small text-uppercase">Step (MHz)</label>
                                <select class="form-select" id="stepSelect">
                                    <option value="5">5 MHz</option>
                                    <option value="10">10 MHz</option>
                                    <option value="20">20 MHz</option>
                                    <option value="40">40 MHz</option>
                                </select>
                            </div>

                            <div class="form-check form-switch mb-4">
                                <input class="form-check-input" type="checkbox" id="superchannelcheck">
                                <label class="form-check-label text-white" for="superchannelcheck">Include Superchannel
                                    Range</label>
                            </div>

                            <div class="d-grid">
                                <button type="button" class="btn btn-primary btn-lg shadow-lg" id="generateListBtn"
                                    style="background: linear-gradient(90deg, #fd7e14, #ffc107); border:none;">
                                    <i class="bi bi-list-nested me-2"></i> Generate List
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="row g-4 h-100">
                    <div class="col-12">
                        <div class="glass-card">
                            <div
                                class="card-header border-0 bg-transparent pt-4 px-4 d-flex justify-content-between align-items-center">
                                <h5 class="card-title fw-bold text-white mb-0">Select Channels</h5>
                                <div>
                                    <button class="btn btn-sm btn-outline-light me-2" id="selectAllBtn">Select All</button>
                                    <button class="btn btn-sm btn-outline-light" id="selectNoneBtn">None</button>
                                </div>
                            </div>
                            <div class="card-body p-4">
                                <div class="d-flex flex-wrap gap-2" id="channelContainer"
                                    style="max-height: 250px; overflow-y: auto;">
                                    <div class="text-white-50 fst-italic">Click "Generate List" first...</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="glass-card h-100">
                            <div class="card-header border-0 bg-transparent pt-4 px-4 d-flex justify-content-between">
                                <h5 class="card-title fw-bold text-white mb-0">Script Output</h5>
                                <button class="btn btn-sm btn-outline-light" id="copyBtn">Copy</button>
                            </div>
                            <div class="card-body p-0 position-relative">
                                <pre class="m-0 p-4 text-warning bg-dark bg-opacity-50 h-100 fw-bold font-monospace"
                                    id="scriptOutput">// Script will appear here...</pre>

                                <div class="position-absolute bottom-0 end-0 p-3">
                                    <button class="btn btn-success btn-lg shadow" id="generateScriptBtn">
                                        <span class="btn-label"><i class="bi bi-magic me-2"></i> Create Script</span>
                                        <span class="btn-loader d-none"><span
                                                class="spinner-border spinner-border-sm me-2"></span>Creating...</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @vite('resources/js/pages/mikrotik-suite/wireless/lockpack-creator.js')
@endsection
