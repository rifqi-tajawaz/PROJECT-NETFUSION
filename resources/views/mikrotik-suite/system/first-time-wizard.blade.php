@extends('layouts.app')



@section('content')
    <x-page-header title="First Time Wizard"
        subtitle="Quickly configure accurate IP, DNS, Gateway, and Wireless settings for a new router.">
        <x-slot name="action">
            <div class="d-none d-md-block text-secondary small">
                <i class="bi bi-magic me-1"></i> Quick Setup
            </div>
        </x-slot>
    </x-page-header>

    <form id="wizardForm" data-route="{{ route('mikrotik-suite.system.first-time-wizard.generate') }}">
        <div class="row g-4 justify-content-center">
            <div class="col-lg-5">
                <x-card title="Configuration" class="h-100">
                    <x-slot name="headerAction">
                        <i class="bi bi-sliders text-primary"></i>
                    </x-slot>

                    <h6 class="text-uppercase small fw-bold text-secondary mb-3">System</h6>
                    <div class="mb-3">
                        <label class="form-label small text-uppercase fw-bold text-secondary">Router Identity</label>
                        <input type="text" class="form-control" name="identity" value="MikroTik-Main">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small text-uppercase fw-bold text-secondary">New Admin Password</label>
                        <input type="text" class="form-control" name="admin_password" placeholder="SecurePassword123!">
                    </div>

                    <h6 class="text-uppercase small fw-bold text-secondary mt-4 mb-3">Network</h6>
                    <div class="mb-3">
                        <label class="form-label small text-uppercase fw-bold text-secondary">Timezone</label>
                        <select class="form-select" name="timezone">
                            <option value="Asia/Jakarta">Asia/Jakarta</option>
                            <option value="Asia/Makassar">Asia/Makassar</option>
                            <option value="Asia/Jayapura">Asia/Jayapura</option>
                            <option value="UTC">UTC</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small text-uppercase fw-bold text-secondary">DNS Server</label>
                        <input type="text" class="form-control" name="dns_server" value="8.8.8.8,1.1.1.1">
                    </div>

                    <h6 class="text-uppercase small fw-bold text-secondary mt-4 mb-3">Wireless (Optional)</h6>
                    <div class="mb-3">
                        <label class="form-label small text-uppercase fw-bold text-secondary">Guest SSID</label>
                        <input type="text" class="form-control" name="guest_ssid" placeholder="Guest-WiFi">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small text-uppercase fw-bold text-secondary">Guest Password</label>
                        <input type="text" class="form-control" name="guest_password" placeholder="guest1234">
                    </div>

                    <div class="d-grid mt-4">
                        <button type="submit" class="btn btn-primary btn-lg shadow-primary">Generate Setup Script</button>
                    </div>
                </x-card>
            </div>

            <div class="col-lg-6">
                <x-card title="RouterOS Script" class="h-100 p-0" bodyClass="p-0">
                    <x-slot name="headerAction">
                        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="copyText('scriptOutput')">
                            <i class="bi bi-clipboard"></i> Copy
                        </button>
                    </x-slot>

                    <pre class="m-0 p-4 text-warning bg-dark bg-opacity-75 h-100 font-monospace" id="scriptOutput"
                        style="min-height:300px;">// Script...</pre>
                </x-card>
            </div>
        </div>
    </form>
@endsection

@push('scripts')
    @vite(['resources/js/pages/mikrotik-suite/system/first-time-wizard.js'])
@endpush
