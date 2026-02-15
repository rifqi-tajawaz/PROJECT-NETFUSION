@extends('layouts.app')



@section('content')
    <x-page-header title="Anti-Tethering (Block Sharing)"
        subtitle="Prevent users from sharing their Hotspot internet connection via Mobile Hotspot/Tethering by manipulating TTL.">
        <x-slot name="action">
            <div class="d-none d-md-block text-secondary small">
                <i class="bi bi-shield-lock me-1"></i> TTL Manipulation
            </div>
        </x-slot>
    </x-page-header>

    <form id="blockForm" data-route="{{ route('mikrotik-suite.connectivity.hotspot.block-sharing.generate') }}">
        <div class="row g-4 justify-content-center">
            <div class="col-lg-6">
                <x-card title="Configuration" class="h-100">
                    <x-slot name="headerAction">
                        <i class="bi bi-sliders text-brand"></i>
                    </x-slot>

                    <p class="text-secondary small">This script adds Mangle rules to enforce a Time To Live (TTL) of 1.
                        Packets leaving the phone will have TTL=1, so when the phone tries to route them to a
                        tethered device, the TTL drops to 0 and the packet is dropped.</p>

                    <div class="d-grid mt-4">
                        <button type="submit" class="btn btn-danger btn-lg shadow-danger rounded-3 text-white">
                            <span class="btn-label"><i class="bi bi-shield-x me-2"></i> Generate Blocking Script</span>
                            <span class="btn-loader d-none"><span
                                    class="spinner-border spinner-border-sm me-2"></span>Generating...</span>
                        </button>
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

                    <pre class="m-0 p-4 text-warning bg-dark bg-opacity-75 h-100"
                        style="min-height:300px; overflow-y: auto; font-family: 'JetBrains Mono', monospace;"
                        id="scriptOutput">// Script will appear here...</pre>
                </x-card>
            </div>
        </div>
    </form>
@endsection

@push('scripts')
    @vite(['resources/js/pages/mikrotik-suite/connectivity/hotspot/block-sharing.js'])
@endpush
