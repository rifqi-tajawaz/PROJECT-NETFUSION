@extends('layouts.app')



@section('content')
    <x-page-header title="Identity" subtitle="Set the router's identity (hostname).">
        <x-slot name="action">
            <div class="d-none d-md-block text-secondary small">
                <i class="bi bi-tag me-1"></i> Hostname
            </div>
        </x-slot>
    </x-page-header>

    <form id="identityForm" data-route="{{ route('mikrotik-suite.system.identity.generate') }}">
        <div class="row g-4 justify-content-center">
            <div class="col-lg-5">
                <x-card title="Name" class="h-100">
                    <x-slot name="headerAction">
                        <i class="bi bi-card-text text-primary"></i>
                    </x-slot>

                    <div class="mb-3">
                        <label class="form-label small text-uppercase fw-bold text-secondary">New Identity</label>
                        <input type="text" class="form-control" id="ident" name="identity" value="MikroTik-Main">
                    </div>

                    <div class="d-grid mt-4">
                        <button type="submit" class="btn btn-primary btn-lg shadow-primary">Set Identity</button>
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
    @vite(['resources/js/pages/mikrotik-suite/system/identity/identity.js'])
@endpush
