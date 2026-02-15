@extends('layouts.app')



@section('content')
    <x-page-header title="Simple Queue Generator"
        subtitle="Create simple queues for bandwidth limiting at the interface or IP level.">
        <x-slot name="action">
            <div class="d-flex align-items-center justify-content-center gap-2 px-3 py-2 rounded-pill bg-primary bg-opacity-10 border border-primary border-opacity-10 text-primary small fw-medium text-nowrap mx-auto ms-md-auto"
                style="width: auto !important;">
                <i class="bi bi-list-ol fs-6"></i>
                <span>Bandwidth Limit</span>
            </div>
        </x-slot>
    </x-page-header>

    <form id="queueForm" data-route="{{ route('mikrotik-suite.qos.queues.simple.generate') }}">
        <div class="row g-4 justify-content-center">
            <div class="col-lg-5">
                <x-card title="Target Settings" class="h-100">
                    <x-slot name="headerAction">
                        <i class="bi bi-bullseye text-primary"></i>
                    </x-slot>

                    <div class="mb-3">
                        <label class="form-label small text-uppercase fw-bold text-secondary">Name</label>
                        <input type="text" class="form-control" id="name" name="name" value="client-queue">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small text-uppercase fw-bold text-secondary">Target Address/Net</label>
                        <input type="text" class="form-control" id="target" name="target" placeholder="192.168.88.10">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small text-uppercase fw-bold text-secondary">Max Limit (Up/Down)</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="maxUp" name="maxUp" placeholder="5M">
                            <span class="input-group-text bg-light text-secondary">/</span>
                            <input type="text" class="form-control" id="maxDown" name="maxDown" placeholder="10M">
                        </div>
                    </div>

                    <div class="d-grid mt-4">
                        <button type="submit" class="btn btn-primary btn-lg shadow-primary">Generate Queue</button>
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
    @vite(['resources/js/pages/mikrotik-suite/qos/queues/simple-queue.js'])
@endpush
