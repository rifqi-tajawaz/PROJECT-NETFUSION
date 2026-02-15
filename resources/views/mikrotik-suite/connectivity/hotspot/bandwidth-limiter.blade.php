@extends('layouts.app')



@section('content')
    <x-page-header title="Bandwidth Limiter"
        subtitle="Restrict internet speed for specific IP addresses or Walled Garden hosts.">
        <x-slot name="action">
            <div class="d-none d-md-block text-secondary small">
                <i class="bi bi-speedometer me-1"></i> Simple Queue Generator
            </div>
        </x-slot>
    </x-page-header>

    <form id="limitForm" data-route="{{ route('mikrotik-suite.connectivity.hotspot.bandwidth-limiter.generate') }}">
        <div class="row g-4">
            <div class="col-lg-5">
                <x-card title="Rule Config" class="h-100">
                    <x-slot name="headerAction">
                        <i class="bi bi-sliders text-brand"></i>
                    </x-slot>

                    <div class="mb-3">
                        <label class="form-label text-secondary small text-uppercase fw-bold">Target Object</label>
                        <input type="text" class="form-control" name="target" id="target"
                            placeholder="192.168.88.10 or 192.168.88.0/24">
                    </div>
                    <div class="row g-3">
                        <div class="col-6">
                            <label class="form-label text-secondary small text-uppercase fw-bold">Upload Max</label>
                            <div class="input-group">
                                <input type="number" class="form-control" name="max_up" id="maxUp" value="1">
                                <span class="input-group-text bg-light border-start-0 text-secondary">Mbps</span>
                            </div>
                        </div>
                        <div class="col-6">
                            <label class="form-label text-secondary small text-uppercase fw-bold">Download Max</label>
                            <div class="input-group">
                                <input type="number" class="form-control" name="max_down" id="maxDown" value="5">
                                <span class="input-group-text bg-light border-start-0 text-secondary">Mbps</span>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3 mt-3">
                        <label class="form-label text-secondary small text-uppercase fw-bold">Comment</label>
                        <input type="text" class="form-control" name="comment" id="comment" value="Limited User">
                    </div>

                    <div class="d-grid mt-4">
                        <button type="submit" class="btn btn-success btn-lg shadow-success rounded-3 text-white">
                            <span class="btn-label"><i class="bi bi-plus-circle me-2"></i> Create Limit</span>
                            <span class="btn-loader d-none"><span
                                    class="spinner-border spinner-border-sm me-2"></span>Generating...</span>
                        </button>
                    </div>
                </x-card>
            </div>

            <div class="col-lg-7">
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
    @vite(['resources/js/pages/mikrotik-suite/connectivity/hotspot/bandwidth-limiter.js'])
@endpush
