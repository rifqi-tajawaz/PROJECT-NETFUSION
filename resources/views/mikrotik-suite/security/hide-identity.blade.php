@extends('layouts.app')



@section('content')
    <x-page-header title="Hide Router Identity" subtitle="Reduce visibility by disabling discovery protocols and banners.">
        <x-slot name="action">
            <div class="d-none d-md-block text-secondary small">
                <i class="bi bi-incognito me-1"></i> Stealth Mode
            </div>
        </x-slot>
    </x-page-header>

    <form id="hideForm">
        <div class="row g-4 justify-content-center">
            <div class="col-lg-5">
                <x-card title="Settings" class="h-100">
                    <x-slot name="headerAction">
                        <i class="bi bi-eye-slash text-dark"></i>
                    </x-slot>

                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="neigh" checked>
                        <label class="form-check-label text-secondary" for="neigh">Disable Neighbor Discovery
                            (MNDP/CDP/LLDP)</label>
                    </div>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="bw" checked>
                        <label class="form-check-label text-secondary" for="bw">Disable Bandwidth Server</label>
                    </div>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="dns" checked>
                        <label class="form-check-label text-secondary" for="dns">Disable DNS Allow Remote Requests (if
                            unnecessary)</label>
                    </div>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="proxy" checked>
                        <label class="form-check-label text-secondary" for="proxy">Disable Socks/Web Proxy</label>
                    </div>

                    <div class="d-grid mt-4">
                        <button type="submit" class="btn btn-dark btn-lg shadow-lg rounded-3">Generate Stealth
                            Script</button>
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
    @vite(['resources/js/pages/mikrotik-suite/security/hide-identity.js'])
@endpush
