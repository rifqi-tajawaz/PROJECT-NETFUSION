@extends('layouts.app')



@section('content')
    <x-page-header title="Streaming Optimization"
        subtitle="Identify and manage streaming traffic (YouTube, Netflix) to prevent congestion.">
        <x-slot name="action">
            <div class="d-none d-md-block text-secondary small">
                <i class="bi bi-play-circle me-1"></i> Video Traffic
            </div>
        </x-slot>
    </x-page-header>

    <form id="streamForm" data-route="{{ route('mikrotik-suite.qos.application.streaming.generate') }}">
        <div class="row g-4 justify-content-center">
            <div class="col-lg-5">
                <x-card title="Streaming Service" class="h-100">
                    <x-slot name="headerAction">
                        <i class="bi bi-youtube text-danger"></i>
                    </x-slot>

                    <div class="mb-3">
                        <label class="form-label small text-uppercase fw-bold text-secondary">Service</label>
                        <select class="form-select" id="service" name="service">
                            <option value="youtube">YouTube (googlevideo.com)</option>
                            <option value="netflix">Netflix (nflxvideo.net)</option>
                        </select>
                    </div>

                    <div class="d-grid mt-4">
                        <button type="submit" class="btn btn-danger btn-lg shadow-danger text-white">Generate
                            Mangle</button>
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
    @vite(['resources/js/pages/mikrotik-suite/qos/application-routing/streaming-routes.js'])
@endpush
