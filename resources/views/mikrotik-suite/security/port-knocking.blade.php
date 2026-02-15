@extends('layouts.app')



@section('title', 'Port Knocking Generator')

@section('content')
    <x-page-header title="Port Knocking Generator" subtitle="Protect services with packet logic.">
        <x-slot name="action">
            <div class="d-none d-md-block text-secondary small">
                <span class="material-icons-outlined align-middle me-1">lock</span> Security
            </div>
        </x-slot>
    </x-page-header>

    <div class="row g-4 mb-4">
        <!-- Input Configuration Card -->
        <div class="col-lg-5">
            <x-card title="Configuration" subtitle="Protect services with packet logic" class="h-100">
                <x-slot name="headerAction">
                    <span class="material-icons-outlined text-brand">tune</span>
                </x-slot>

                <form id="pkForm">
                    @csrf

                    <!-- Interface & Mode -->
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label text-secondary small text-uppercase fw-bold">Interface</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-light text-secondary"><span
                                        class="material-icons-outlined fs-6">router</span></span>
                                <input type="text" name="interface" class="form-control bg-light border-light"
                                    value="ether1" placeholder="e.g. ether1" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-secondary small text-uppercase fw-bold">Method</label>
                            <div class="input-group">
                                class="material-icons-outlined fs-6">settings_ethernet</span></span>
                                <select name="mode" id="knockMode" class="form-select bg-light border-light">
                                    <option value="icmp">ICMP Packet Size</option>
                                    <option value="port">Port Sequence (TCP)</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- ICMP Section -->
                    <div id="method-icmp" class="mb-3">
                        <label class="text-uppercase fw-bold text-brand small mb-2 d-block opacity-75">
                            <span class="material-icons-outlined align-middle fs-6 me-1">network_check</span>
                            Packet Pattern
                        </label>

                        <div class="row g-2">
                            <div class="col-6">
                                <label class="form-label text-secondary small">Knock 1 (Bytes)</label>
                                <input type="number" name="packet1" class="form-control bg-light border-light" value="100">
                            </div>
                            <div class="col-6">
                                <label class="form-label text-secondary small">Knock 2 (Bytes)</label>
                                <input type="number" name="packet2" class="form-control bg-light border-light" value="200">
                            </div>
                        </div>
                    </div>

                    <!-- Port Sequence Section (Hidden by Default) -->
                    <div id="method-port" class="d-none mb-3">
                        <label class="text-uppercase fw-bold text-brand small mb-2 d-block opacity-75">
                            <span class="material-icons-outlined align-middle fs-6 me-1">tag</span>
                            Port Sequence
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-light text-secondary"><span
                                    class="material-icons-outlined fs-6">list</span></span>
                            <input type="text" name="knock_ports" class="form-control bg-light border-light"
                                value="1234,5678,9000" placeholder="e.g. 1234,5678">
                        </div>
                    </div>

                    <hr class="my-4 opacity-10 border-secondary">

                    <div class="mb-3">
                        <label class="form-label text-secondary small text-uppercase fw-bold">Unlock Services</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-light text-secondary"><span
                                    class="material-icons-outlined fs-6">dns</span></span>
                            <input type="text" name="ports" class="form-control bg-light border-light" value="22,8291"
                                placeholder="e.g. 22,80,443" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-secondary small text-uppercase fw-bold">Duration</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-light text-secondary"><span
                                    class="material-icons-outlined fs-6">timer</span></span>
                            <input type="text" name="duration" class="form-control bg-light border-light" value="00:30:00"
                                required>
                        </div>
                    </div>

                    id="safeMode" checked>
                    <label class="form-check-label small fw-bold text-secondary" for="safeMode">Safe Mode
                        (Anti-Lockout)</label>
        </div>

        <div class="d-grid">
            <button type="button" class="btn btn-brand py-2 shadow-brand rounded-3 fw-bold"
                data-action="{{ route('mikrotik-suite.config.security.advanced-protection.generate-port-knocking') }}">
                Generate Secure Script
            </button>
        </div>
        </form>
        </x-card>
    </div>

    <!-- Output Column -->
    <div class="col-lg-7">
        <x-card class="h-100 p-0" bodyClass="p-0 bg-dark h-100">
            <x-slot name="header">
                <div
                    class="d-flex justify-content-between align-items-center w-100 px-4 py-3 bg-dark text-white border-bottom border-secondary border-opacity-25">
                    <span class="font-monospace small"><i class="bi bi-terminal me-2"></i>Terminal Output</span>
                    <div class="d-flex gap-2">
                        <button class="btn btn-sm btn-dark bg-opacity-25 border border-secondary text-white-50"
                            onclick="copyText('scriptOutput')">
                            <i class="bi bi-clipboard me-1"></i> Copy
                        </button>
                    </div>
                </div>
            </x-slot>

            <div class="position-relative overflow-auto custom-scrollbar h-100" style="max-height: 800px;">
                <div class="code-editor d-flex">
                    <div class="code-gutter text-secondary p-3 text-end border-end border-secondary border-opacity-25 bg-dark-subtle"
                        style="min-width: 40px;" id="lineNumbers">1</div>
                    <div class="code-content text-warning font-monospace p-3 w-100 bg-dark" id="scriptOutput"
                        contenteditable="false" spellcheck="false" style="outline: none;"># Generated
                        routeros script will appear here...</div>
                </div>
            </div>
        </x-card>
    </div>
    </div>
@endsection

@push('scripts')
    @vite(['resources/js/pages/mikrotik-suite/security/port-knocking.js'])
@endpush