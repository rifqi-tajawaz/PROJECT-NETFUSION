@extends('layouts.app')



@section('content')
    <x-page-header title="DHCP Rogue Detection" subtitle="Detect and alert on unauthorized DHCP servers on your network.">
        <x-slot name="action">
            <div class="d-none d-md-block text-secondary small">
                <i class="bi bi-exclamation-octagon-fill me-1"></i> Rogue Detection
            </div>
        </x-slot>
    </x-page-header>

    <form id="rogueForm">
        <div class="row g-4 justify-content-center">
            <div class="col-lg-5">
                <x-card title="Alert Settings" class="h-100">
                    <x-slot name="headerAction">
                        <i class="bi bi-shield-exclamation text-danger"></i>
                    </x-slot>

                    <div class="mb-3">
                        <label class="form-label text-secondary small text-uppercase fw-bold">Alert Interface</label>
                        <input type="text" class="form-control border-secondary border-opacity-25" id="iface"
                            value="bridge1">
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-secondary small text-uppercase fw-bold">Valid DHCP Server MAC</label>
                        <input type="text" class="form-control border-secondary border-opacity-25" id="validMac"
                            placeholder="00:11:22:33:44:55">
                        <div class="form-text text-secondary opacity-75">Optional. If empty, all DHCP offers triggers alert.
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-secondary small text-uppercase fw-bold">On Alert Script</label>
                        <input type="text" class="form-control border-secondary border-opacity-25" id="alert"
                            value=":log error \" Rogue DHCP detected on $interface!\"">
                    </div>

                    <div class="d-grid mt-4">
                        <button type="submit" class="btn btn-danger btn-lg shadow-danger rounded-3">Generate
                            Detection</button>
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
    </div>
@endsection

@push('scripts')
    @vite(['resources/js/pages/mikrotik-suite/security/dhcp-rogue.js'])
@endpush
