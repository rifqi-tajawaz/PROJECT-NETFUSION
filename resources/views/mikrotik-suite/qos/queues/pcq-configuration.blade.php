@extends('layouts.app')



@section('content')
    <x-page-header title="PCQ Types Config"
        subtitle="Configure PCQ (Per Connection Queue) Types for dynamic bandwidth equalization.">
        <x-slot name="action">
            <div class="d-none d-md-block text-secondary small">
                <i class="bi bi-collection me-1"></i> Equal Distribution
            </div>
        </x-slot>
    </x-page-header>

    <form id="pcqForm" data-route="{{ route('mikrotik-suite.qos.queues.pcq.generate') }}">
        <div class="row g-4 justify-content-center">
            <div class="col-lg-5">
                <x-card title="Parameters" class="h-100">
                    <x-slot name="headerAction">
                        <i class="bi bi-sliders text-info"></i>
                    </x-slot>

                    <div class="mb-3">
                        <label class="form-label small text-uppercase fw-bold text-secondary">Type Name</label>
                        <input type="text" class="form-control" id="name" name="name" value="pcq_download">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small text-uppercase fw-bold text-secondary">Rate (Per Substream)</label>
                        <input type="text" class="form-control" id="rate" name="rate" value="5M">
                        <div class="form-text text-secondary">Limit per user/connection. Use 0 for unlimited.</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small text-uppercase fw-bold text-secondary">Classifier</label>
                        <select class="form-select" id="class" name="class">
                            <option value="dst-address">Dst Address (Download)</option>
                            <option value="src-address">Src Address (Upload)</option>
                            <option value="dst-address,dst-port">Dst Address + Port</option>
                            <option value="src-address,src-port">Src Address + Port</option>
                        </select>
                    </div>

                    <div class="d-grid mt-4">
                        <button type="submit" class="btn btn-info text-dark btn-lg shadow-info">Generate PCQ Type</button>
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
    @vite(['resources/js/pages/mikrotik-suite/qos/queues/pcq-configuration.js'])
@endpush
