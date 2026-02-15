@extends('layouts.app')



@section('title', 'Advanced Firewall Protection')

@section('content')
    <x-page-header title="Advanced Protection" subtitle="Generate comprehensive firewall rulesets.">
        <x-slot name="action">
            <div class="d-none d-md-block text-secondary small">
                <i class="bi bi-shield-check me-1"></i> Firewall Hardening
            </div>
        </x-slot>
    </x-page-header>

    <div class="row g-4 mb-5">
        <!-- Configuration Panel -->
        <div class="col-lg-5">
            <x-card title="Configuration" subtitle="Setup your firewall parameters." class="h-100">
                <x-slot name="headerAction">
                    <i class="bi bi-sliders text-brand"></i>
                </x-slot>

                <form id="firewallForm" data-route="{{ route('mikrotik-suite.security.advanced-protection.generate') }}">
                    <!-- Standard Protection -->
                    <div class="mb-4">
                        <label class="text-uppercase fw-bold text-secondary small mb-3 d-block opacity-75">Basic
                            Hardening</label>


                        <div class="d-flex align-items-center justify-content-between p-3 rounded-3 bg-light border border-light mb-2 cursor-pointer hover-scale-sm"
                            onclick="document.getElementById('icmp').click()">
                            <div class="d-flex align-items-center gap-3">
                                <div class="bg-primary bg-opacity-10 p-2 rounded-circle text-primary">
                                    <span class="material-icons-outlined fs-5">network_ping</span>
                                </div>
                                <div>
                                    <h6 class="mb-0 fw-bold text-dark small">Limit ICMP</h6>
                                    <small class="text-muted" style="font-size: 0.75rem;">Allow Ping only (Drop
                                        Flood)</small>
                                </div>
                            </div>
                            <div class="form-check form-switch pointer-events-none">
                                <input class="form-check-input" type="checkbox" id="icmp" name="icmp" checked>
                            </div>
                        </div>

                        <div class="d-flex align-items-center justify-content-between p-3 rounded-3 bg-light border border-light mb-2 cursor-pointer hover-scale-sm"
                            onclick="document.getElementById('bogon').click()">
                            <div class="d-flex align-items-center gap-3">
                                <div class="bg-danger bg-opacity-10 p-2 rounded-circle text-danger">
                                    <span class="material-icons-outlined fs-5">block</span>
                                </div>
                                <div>
                                    <h6 class="mb-0 fw-bold text-dark small">Drop Bogon IPs</h6>
                                    <small class="text-muted" style="font-size: 0.75rem;">Block private IPs on
                                        WAN</small>
                                </div>
                            </div>
                            <div class="form-check form-switch pointer-events-none">
                                <input class="form-check-input" type="checkbox" id="bogon" name="bogon" checked>
                            </div>
                        </div>
                    </div>

                    <!-- Advanced Threat Detection -->
                    <div class="mb-4">
                        <label class="text-uppercase fw-bold text-secondary small mb-3 d-block opacity-75">Threat
                            Detection</label>

                        <div class="d-flex align-items-center justify-content-between p-3 rounded-3 bg-light border border-light mb-2 cursor-pointer hover-scale-sm"
                            onclick="document.getElementById('port_scan').click()">
                            <div class="d-flex align-items-center gap-3">
                                <div class="bg-warning bg-opacity-10 p-2 rounded-circle text-warning">
                                    <span class="material-icons-outlined fs-5">radar</span>
                                </div>
                                <div>
                                    <h6 class="mb-0 fw-bold text-dark small">Port Scan Detection</h6>
                                    <small class="text-muted" style="font-size: 0.75rem;">Add scanners to blacklist (2
                                        weeks)</small>
                                </div>
                            </div>
                            <div class="form-check form-switch pointer-events-none">
                                <input class="form-check-input" type="checkbox" id="port_scan" name="port_scan" checked>
                            </div>
                        </div>
                    </div>

                    <!-- Chains -->
                    <div class="mb-4">
                        <label class="text-uppercase fw-bold text-secondary small mb-3 d-block opacity-75">Chains &
                            Policy</label>

                        <div class="d-flex align-items-center justify-content-between p-3 rounded-3 bg-light border border-light mb-2 cursor-pointer hover-scale-sm"
                            onclick="document.getElementById('input_chain').click()">
                            <div class="d-flex align-items-center gap-3">
                                <div class="bg-info bg-opacity-10 p-2 rounded-circle text-info">
                                    <span class="material-icons-outlined fs-5">router</span>
                                </div>
                                <div>
                                    <h6 class="mb-0 fw-bold text-dark small">Input Chain (Router)</h6>
                                    <small class="text-muted" style="font-size: 0.75rem;">Protect the router
                                        itself</small>
                                </div>
                            </div>
                            <div class="form-check form-switch pointer-events-none">
                                <input class="form-check-input" type="checkbox" id="input_chain" name="input_chain" checked>
                            </div>
                        </div>

                        <div class="d-flex align-items-center justify-content-between p-3 rounded-3 bg-light border border-light mb-2 cursor-pointer hover-scale-sm"
                            onclick="document.getElementById('forward_chain').click()">
                            <div class="d-flex align-items-center gap-3">
                                <div class="bg-success bg-opacity-10 p-2 rounded-circle text-success">
                                    <span class="material-icons-outlined fs-5">lan</span>
                                </div>
                                <div>
                                    <h6 class="mb-0 fw-bold text-dark small">Forward Chain (LAN)</h6>
                                    <small class="text-muted" style="font-size: 0.75rem;">Protect network
                                        clients</small>
                                </div>
                            </div>
                            <div class="form-check form-switch pointer-events-none">
                                <input class="form-check-input" type="checkbox" id="forward_chain" name="forward_chain"
                                    checked>
                            </div>
                        </div>
                    </div>

                    <button type="button"
                        class="btn btn-brand w-100 rounded-3 py-2 fw-bold shadow-brand d-flex align-items-center justify-content-center gap-2"
                        onclick="generateFirewall()">
                        <span class="material-icons-outlined">auto_fix_high</span> Generate Rules
                    </button>
                </form>
            </x-card>
        </div>

        <!-- Output Panel -->
        <div class="col-lg-7">
            <x-card class="h-100 p-0" bodyClass="p-0 bg-dark h-100">
                <x-slot name="header">
                    <div
                        class="d-flex justify-content-between align-items-center w-100 px-4 py-3 bg-dark text-white border-bottom border-secondary border-opacity-25">
                        <span class="font-monospace small"><i class="bi bi-terminal me-2"></i>Terminal Output</span>
                        <div class="d-flex gap-2">
                            <button class="btn btn-sm btn-dark bg-opacity-25 border border-secondary text-white-50"
                                onclick="copyToClipboard()">
                                <i class="bi bi-clipboard me-1"></i> Copy
                            </button>
                        </div>
                    </div>
                </x-slot>

                <div class="position-relative overflow-auto custom-scrollbar h-100" style="max-height: 800px;">
                    <div class="code-editor d-flex">
                        <div class="code-gutter text-secondary p-3 text-end border-end border-secondary border-opacity-25 bg-dark-subtle"
                            style="min-width: 40px;" id="lineNumbers">1</div>
                        <div class="code-content text-warning font-monospace p-3 w-100 bg-dark" id="outputScript"
                            contenteditable="false" spellcheck="false" style="outline: none;"># Select options on the left
                            and click Generate...</div>
                    </div>
                </div>
            </x-card>
        </div>
    </div>

@endsection

@push('scripts')
    @vite(['resources/js/pages/mikrotik-suite/security/advanced-protection.js'])
@endpush
