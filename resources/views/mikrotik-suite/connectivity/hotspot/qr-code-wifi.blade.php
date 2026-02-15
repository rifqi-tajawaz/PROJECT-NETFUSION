@extends('layouts.app')



@section('content')
    <x-page-header title="WiFi QR Code Generator"
        subtitle="Create instant connection QR Codes for your Hotspot or WiFi. Print and place them for easy guest access.">
        <x-slot name="action">
            <div class="d-none d-md-block text-secondary small">
                <i class="bi bi-qr-code-scan me-1"></i> QR Generator
            </div>
        </x-slot>
    </x-page-header>

    <form id="qrForm" data-route="{{ route('mikrotik-suite.connectivity.hotspot.qr-code.generate') }}">
        <div class="row g-4">
            <!-- Configuration -->
            <div class="col-lg-5">
                <x-card title="Network Details" class="h-100">
                    <x-slot name="headerAction">
                        <i class="bi bi-sliders text-brand"></i>
                    </x-slot>

                    <div class="mb-3">
                        <label class="form-label text-secondary small text-uppercase fw-bold">SSID (Network
                            Name)</label>
                        <input type="text" class="form-control" name="ssid" id="ssid" placeholder="MyWiFi_Network" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-secondary small text-uppercase fw-bold">Encryption</label>
                        <select class="form-select" name="encryption" id="encryption">
                            <option value="WPA">WPA/WPA2/WPA3</option>
                            <option value="WEP">WEP</option>
                            <option value="nopass">None (Open)</option>
                        </select>
                    </div>

                    <div class="mb-3" id="passField">
                        <label class="form-label text-secondary small text-uppercase fw-bold">Password</label>
                        <div class="input-group">
                            <input type="text" class="form-control" name="password" id="password"
                                placeholder="SecurePassword123">
                            <button class="btn btn-outline-secondary" type="button" onclick="togglePass()"><i
                                    class="bi bi-eye"></i></button>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="hidden" id="hiddenSsid" value="1">
                            <label class="form-check-label text-secondary" for="hiddenSsid">Hidden SSID</label>
                        </div>
                    </div>

                    <div class="d-grid mt-4">
                        <button type="submit" class="btn btn-info btn-lg shadow-info rounded-3 text-white">
                            <span class="btn-label"><i class="bi bi-qr-code-scan me-2"></i> Generate QR Code</span>
                            <span class="btn-loader d-none"><span
                                    class="spinner-border spinner-border-sm me-2"></span>Generating...</span>
                        </button>
                    </div>
                </x-card>
            </div>

            <!-- Result -->
            <div class="col-lg-7">
                <x-card class="h-100 text-center d-flex align-items-center justify-content-center">
                    <div class="p-5" id="qrContainer" style="display:none;">
                        <div class="bg-white p-4 rounded-4 shadow-sm mb-4 d-inline-block">
                            <img id="qrImage" src="" alt="WiFi QR Code" class="img-fluid"
                                style="width: 250px; height: 250px;">
                        </div>
                        <h4 class="text-brand fw-bold mb-1" id="displaySsid">MyWiFi_Network</h4>
                        <p class="text-secondary opacity-75 mb-4" id="displayPass">Password: ********</p>

                        <div class="d-flex justify-content-center gap-3">
                            <button type="button" class="btn btn-outline-secondary rounded-pill px-4"
                                onclick="downloadQr()">
                                <i class="bi bi-download me-2"></i> Download
                            </button>
                            <button type="button" class="btn btn-brand rounded-pill px-4" onclick="printQr()">
                                <i class="bi bi-printer me-2"></i> Print
                            </button>
                        </div>
                    </div>

                    <div id="placeholder" class="text-secondary opacity-25 p-5">
                        <i class="bi bi-qr-code display-1 mb-3 d-block"></i>
                        <h4 class="fw-light">QR Code will appear here</h4>
                    </div>
                </x-card>
            </div>
        </div>
    </form>
@endsection

@push('scripts')
    @vite(['resources/js/pages/mikrotik-suite/connectivity/hotspot/qr-code-wifi.js'])
@endpush
