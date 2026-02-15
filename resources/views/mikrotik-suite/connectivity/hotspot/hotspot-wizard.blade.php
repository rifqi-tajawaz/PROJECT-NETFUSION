@extends('layouts.app')



@section('content')
    <x-page-header title="Hotspot Wizard"
        subtitle="Generate a complete Hostpot Setup script including IP Pool, Profile, User, and Walled Garden.">
        <x-slot name="action">
            <div class="d-none d-md-block text-secondary small">
                <i class="bi bi-magic me-1"></i> Wizard Mode
            </div>
        </x-slot>
    </x-page-header>

    <form id="wizardForm" data-route="{{ route('mikrotik-suite.connectivity.hotspot.wizard.generate') }}">
        <div class="row g-4">
            <div class="col-lg-5">
                <x-card title="Setup" class="h-100">
                    <x-slot name="headerAction">
                        <i class="bi bi-sliders text-brand"></i>
                    </x-slot>

                    <div class="mb-3">
                        <label class="form-label text-secondary small text-uppercase fw-bold">Interface</label>
                        <input type="text" class="form-control" name="iface" id="iface" value="bridge-hs">
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-secondary small text-uppercase fw-bold">Local Network</label>
                        <input type="text" class="form-control font-monospace" name="local_net" id="localNet"
                            value="10.5.50.1/24">
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-secondary small text-uppercase fw-bold">Pool Range</label>
                        <input type="text" class="form-control font-monospace" name="pool_range" id="poolRange"
                            value="10.5.50.10-10.5.50.254">
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-secondary small text-uppercase fw-bold">DNS Name</label>
                        <input type="text" class="form-control" name="dns_name" id="dnsName" value="wifi.login">
                    </div>
                    <div class="d-grid mt-4">
                        <button type="submit" class="btn btn-brand btn-lg shadow-brand rounded-3">
                            <span class="btn-label">Generate</span>
                            <span class="btn-loader d-none"><span
                                    class="spinner-border spinner-border-sm me-2"></span>Generating...</span>
                        </button>
                    </div>
                </x-card>
            </div>

            <div class="col-lg-7">
                <x-card title="Script" class="h-100 p-0" bodyClass="p-0">
                    <x-slot name="headerAction">
                        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="copyText('scriptOutput')">
                            <i class="bi bi-clipboard"></i> Copy
                        </button>
                    </x-slot>

                    <pre class="m-0 p-4 text-warning bg-dark bg-opacity-75 h-100"
                        style="min-height:300px; overflow-y: auto; font-family: 'JetBrains Mono', monospace;"
                        id="scriptOutput">// Script...</pre>
                </x-card>
            </div>
        </div>
    </form>
@endsection

@push('scripts')
    @vite(['resources/js/pages/mikrotik-suite/connectivity/hotspot/hotspot-wizard.js'])
@endpush
