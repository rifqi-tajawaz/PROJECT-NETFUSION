@extends('layouts.app')

@section('content')
    <div class="container-fluid px-4 py-4">
        <div class="mb-4 d-flex flex-column align-items-center justify-content-center text-center">
            <h2 class="display-6 fw-bold mb-2 text-white" style="text-shadow: 0 2px 10px rgba(0,0,0,0.3);">
                <i class="bi bi-unlock me-2 text-danger"></i> Frequency Unlocker
            </h2>
            <p class="lead opacity-75 mb-0" style="max-width: 700px;">
                Generate scripts to unlock Superchannel frequencies or apply advanced patches.
            </p>
        </div>

        <!-- Tabs -->
        <ul class="nav nav-pills justify-content-center mb-4" id="unlockTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active rounded-pill px-4 fw-bold mx-2" id="script-tab" data-bs-toggle="pill"
                    data-bs-target="#script-content" type="button" role="tab">
                    <i class="bi bi-code-square me-2"></i>Script Generator
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link rounded-pill px-4 fw-bold mx-2" id="patch-tab" data-bs-toggle="pill"
                    data-bs-target="#patch-content" type="button" role="tab">
                    <i class="bi bi-box-seam me-2"></i>Advanced Patch
                </button>
            </li>
        </ul>

        <div class="tab-content" id="unlockTabContent">

            <!-- TAB 1: Script Generator -->
            <div class="tab-pane fade show active" id="script-content" role="tabpanel">
                <div class="row g-4 justify-content-center">
                    <div class="col-lg-4">
                        <div class="glass-card h-100">
                            <div class="card-header border-0 bg-transparent pt-4 px-4">
                                <h5 class="card-title fw-bold text-white mb-0">Configuration</h5>
                            </div>
                            <div class="card-body p-4">
                                <form id="scriptForm" data-route="{{ route('mikrotik-suite.wireless.freq-unlock.generate') }}">
                                    <div class="mb-3">
                                        <label class="form-label text-white small text-uppercase">Interface</label>
                                        <input type="text" class="form-control" name="interface" id="interface"
                                            value="wlan1">
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label text-white small text-uppercase">Frequency Mode</label>
                                        <select class="form-select" name="mode" id="freqMode">
                                            <option value="superchannel">Superchannel</option>
                                            <option value="manual-tx-power">Manual TX Power</option>
                                            <option value="regulatory-domain">Regulatory Domain</option>
                                        </select>
                                        <div class="form-text text-warning"><i class="bi bi-exclamation-triangle"></i>
                                            Superchannel allows non-standard frequencies.</div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label text-white small text-uppercase">Country</label>
                                        <select class="form-select" name="country" id="country">
                                            <option value="no_country_set">No Country Set (Global)</option>
                                            <option value="indonesia">Indonesia</option>
                                            <option value="united states">United States</option>
                                            <option value="japan">Japan</option>
                                            <option value="etsi">ETSI (Europe)</option>
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label text-white small text-uppercase">Channel Width</label>
                                        <select class="form-select" name="width" id="width">
                                            <option value="20/40mhz-Ce">20/40 MHz Ce</option>
                                            <option value="20/40mhz-eC">20/40 MHz eC</option>
                                            <option value="20mhz">20 MHz</option>
                                        </select>
                                    </div>

                                    <div class="d-grid mt-4">
                                        <button type="submit" class="btn btn-primary btn-lg shadow-lg"
                                            style="background: linear-gradient(90deg, #dc3545, #fd7e14); border:none;">
                                            <span class="btn-label"><i class="bi bi-code-square me-2"></i> Generate
                                                Script</span>
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
                                <h5 class="card-title fw-bold text-white mb-0">Generated Script</h5>
                                <button class="btn btn-sm btn-outline-light"
                                    onclick="copyText('outputScript')">Copy</button>
                            </div>
                            <div class="card-body p-0">
                                <pre class="m-0 p-4 text-warning bg-dark bg-opacity-50 h-100 fw-bold"
                                    style="min-height:300px; font-family: 'JetBrains Mono', monospace;"
                                    id="outputScript">// Script will appear here...</pre>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- TAB 2: Patch -->
            <div class="tab-pane fade" id="patch-content" role="tabpanel">
                <div class="glass-card p-5 mx-auto" style="max-width: 900px;">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h3 class="text-white fw-bold mb-3">Core File Patcher</h3>
                            <p class="text-white-50">This method involves replacing the wireless package with a patched
                                version to unlock all frequencies permanently. Use with caution.</p>

                            <div class="d-flex flex-column gap-3 mt-4">
                                <div class="d-flex gap-3">
                                    <span
                                        class="badge rounded-circle bg-primary d-flex align-items-center justify-content-center"
                                        style="width:30px; height:30px;">1</span>
                                    <div class="text-white">Download the .NPK file below.</div>
                                </div>
                                <div class="d-flex gap-3">
                                    <span
                                        class="badge rounded-circle bg-primary d-flex align-items-center justify-content-center"
                                        style="width:30px; height:30px;">2</span>
                                    <div class="text-white">Upload to RouterOS "Files" menu.</div>
                                </div>
                                <div class="d-flex gap-3">
                                    <span
                                        class="badge rounded-circle bg-primary d-flex align-items-center justify-content-center"
                                        style="width:30px; height:30px;">3</span>
                                    <div class="text-white">Reboot router. The package will install automatically.</div>
                                </div>
                            </div>

                            <div class="mt-4 p-3 bg-danger bg-opacity-25 border border-danger rounded-3 text-white">
                                <i class="bi bi-exclamation-octagon-fill me-2"></i> <strong>Disclaimer:</strong> Use at your
                                own risk. We are not responsible for bricked devices or legal issues regarding frequency
                                usage.
                            </div>
                        </div>
                        <div class="col-md-4 text-center">
                            <div class="p-4 bg-dark bg-opacity-50 rounded-4">
                                <i class="bi bi-cloud-arrow-down display-1 text-primary"></i>
                                <h5 class="text-white mt-3">Download Patch</h5>
                                <div class="d-grid gap-2 mt-4">
                                    <a href="https://github.com/buananetpbun/unlock-mikrotik-wireless-frequency-mode/raw/main/routeros-open-lock.npk"
                                        target="_blank" class="btn btn-primary rounded-pill"><i
                                            class="bi bi-file-earmark-zip me-2"></i> Download .NPK</a>
                                    <a href="https://github.com/buananetpbun/unlock-mikrotik-wireless-frequency-mode/raw/main/routeros-open-lock.rar"
                                        target="_blank" class="btn btn-outline-light rounded-pill"><i
                                            class="bi bi-file-zip me-2"></i> Download .RAR</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @vite('resources/js/pages/mikrotik-suite/wireless/frequency-unlock.js')
@endsection
