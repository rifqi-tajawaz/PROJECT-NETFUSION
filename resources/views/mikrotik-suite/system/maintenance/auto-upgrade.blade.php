@extends('layouts.app')



@section('content')
    <x-page-header title="Auto Upgrade" subtitle="Configure RouterOS to check and install updates automatically.">
        <x-slot name="action">
            <div class="d-none d-md-block text-secondary small">
                <i class="bi bi-arrow-up-circle me-1"></i> Update Manager
            </div>
        </x-slot>
    </x-page-header>

    <form id="autoUpgradeForm" data-route="{{ route('mikrotik-suite.system.maintenance.auto-upgrade.generate') }}">
        <div class="row g-4 justify-content-center">
            <div class="col-lg-5">
                <x-card title="Update Channel" class="h-100">
                    <x-slot name="headerAction">
                        <i class="bi bi-broadcast text-success"></i>
                    </x-slot>

                    <div class="mb-3">
                        <label class="form-label small text-uppercase fw-bold text-secondary">Channel</label>
                        <select class="form-select" id="channel" name="channel">
                            <option value="stable">Stable (Recommended)</option>
                            <option value="long-term">Long Term</option>
                            <option value="testing">Testing</option>
                            <option value="development">Development</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small text-uppercase fw-bold text-secondary">Schedule Time</label>
                        <input type="text" class="form-control" id="time" name="time" value="03:00:00"
                            placeholder="HH:MM:SS">
                    </div>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="fw" name="firmware" checked>
                        <label class="form-check-label text-secondary" for="fw">Upgrade RouterBOARD Firmware too</label>
                    </div>

                    <div class="d-grid mt-4">
                        <button type="submit" class="btn btn-success btn-lg shadow-success text-white">Generate Upgrade
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

                    <div class="alert alert-warning m-3 small border-start border-4 border-warning">
                        <i class="bi bi-exclamation-octagon me-1"></i> Running this will reboot the router if an update is
                        found!
                    </div>

                    <pre class="m-0 p-4 text-warning bg-dark bg-opacity-75 h-100 font-monospace" id="scriptOutput"
                        style="min-height:300px;">// Script...</pre>
                </x-card>
            </div>
        </div>
    </form>
@endsection

@push('scripts')
    @vite(['resources/js/pages/mikrotik-suite/system/maintenance/auto-upgrade.js'])
@endpush
