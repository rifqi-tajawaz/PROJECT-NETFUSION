@extends('layouts.app')



@section('content')
    <x-page-header title="Burst Configurator"
        subtitle="Calculate and configure Burst limit, threshold, and time for traffic resizing.">
        <x-slot name="action">
            <div class="d-none d-md-block text-secondary small">
                <i class="bi bi-speedometer2 me-1"></i> Turbo Speed
            </div>
        </x-slot>
    </x-page-header>

    <form id="burstForm" data-route="{{ route('mikrotik-suite.qos.queues.burst.generate') }}">
        <div class="row g-4 justify-content-center">
            <div class="col-lg-5">
                <x-card title="Burst Parameters" class="h-100">
                    <x-slot name="headerAction">
                        <i class="bi bi-lightning text-primary"></i>
                    </x-slot>

                    <div class="mb-3">
                        <label class="form-label small text-uppercase fw-bold text-secondary">Max Limit (Normal
                            Speed)</label>
                        <input type="text" class="form-control" id="limit" name="limit" value="5M">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small text-uppercase fw-bold text-secondary">Burst Limit (Peak
                            Speed)</label>
                        <input type="text" class="form-control" id="burstLimit" name="burstLimit" value="10M">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small text-uppercase fw-bold text-secondary">Burst Threshold
                            (Trigger)</label>
                        <input type="text" class="form-control" id="burstThreshold" name="burstThreshold" value="4M">
                        <div class="form-text text-secondary">Usually 75-80% of Max Limit.</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small text-uppercase fw-bold text-secondary">Burst Time</label>
                        <input type="number" class="form-control" id="burstTime" name="burstTime" value="16">
                        <div class="form-text text-secondary">Seconds (Average calculation window).</div>
                    </div>

                    <div class="d-grid mt-4">
                        <button type="submit" class="btn btn-primary btn-lg shadow-primary">Generate Burst Config</button>
                    </div>
                </x-card>
            </div>

            <div class="col-lg-6">
                <x-card title="RouterOS Script (Simple Queue)" class="h-100 p-0" bodyClass="p-0">
                    <x-slot name="headerAction">
                        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="copyText('scriptOutput')">
                            <i class="bi bi-clipboard"></i> Copy
                        </button>
                    </x-slot>

                    <div class="h-100 d-flex flex-column">
                        <div class="p-4 flex-grow-1">
                            <pre class="m-0 text-warning bg-dark bg-opacity-75 h-100 font-monospace p-3 rounded"
                                id="scriptOutput" style="min-height:200px;">// Script...</pre>
                        </div>
                        <div class="px-4 pb-4">
                            <h6 class="text-secondary small text-uppercase fw-bold">Burst Analysis</h6>
                            <p class="text-secondary small m-0" id="analysis">...</p>
                        </div>
                    </div>
                </x-card>
            </div>
        </div>
    </form>
@endsection

@push('scripts')
    @vite(['resources/js/pages/mikrotik-suite/qos/shaping/burst-configuration.js'])
@endpush
