@extends('layouts.app')



@section('content')
    <x-page-header title="Content Filter" subtitle="Block websites using TLS Host or Content matchers (Basic Web Filter).">
        <x-slot name="action">
            <div class="d-none d-md-block text-secondary small">
                <i class="bi bi-filter-circle me-1"></i> Web Filtering
            </div>
        </x-slot>
    </x-page-header>

    <form id="contentForm">
        <div class="row g-4 justify-content-center">
            <div class="col-lg-5">
                <x-card title="Sites to Block" class="h-100">
                    <x-slot name="headerAction">
                        <i class="bi bi-shield-slash text-warning"></i>
                    </x-slot>

                    <div class="mb-3">
                        <label class="form-label text-secondary small text-uppercase fw-bold">Keywords / Domains</label>
                        <textarea class="form-control border-secondary border-opacity-25" id="keywords" rows="5"
                            placeholder="facebook.com&#10;youtube.com&#10;porn"></textarea>
                        <div class="form-text text-secondary opacity-75">One per line.</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-secondary small text-uppercase fw-bold">Method</label>
                        <select class="form-select border-secondary border-opacity-25" id="method">
                            <option value="tls">TLS Host (Exact/Partial Domain)</option>
                            <option value="content">Content (Anywhere in packet - High CPU)</option>
                        </select>
                    </div>

                    <div class="d-grid mt-4">
                        <button type="submit" class="btn btn-warning btn-lg shadow-warning rounded-3 text-dark">Generate
                            Block
                            Rules</button>
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
    @vite(['resources/js/pages/mikrotik-suite/security/content-filter.js'])
@endpush
