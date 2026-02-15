@extends('layouts.app')



@section('content')
    <x-page-header title="Banner Generator" subtitle="Create a custom login note (banner) for Terminal/Winbox users.">
        <x-slot name="action">
            <div class="d-none d-md-block text-secondary small">
                <i class="bi bi-chat-square-text me-1"></i> Login Message
            </div>
        </x-slot>
    </x-page-header>

    <form id="bannerForm" data-route="{{ route('mikrotik-suite.system.banner.generate') }}">
        <div class="row g-4 justify-content-center">
            <div class="col-lg-5">
                <x-card title="Note Content" class="h-100">
                    <x-slot name="headerAction">
                        <i class="bi bi-file-text text-warning"></i>
                    </x-slot>

                    <div class="mb-3">
                        <label class="form-label small text-uppercase fw-bold text-secondary">Banner Text</label>
                        <textarea class="form-control font-monospace" id="note" name="banner" rows="10"
                            placeholder="WARNING: AUTHORIZED ACCESS ONLY"></textarea>
                    </div>

                    <div class="d-grid mt-4">
                        <button type="submit" class="btn btn-warning text-dark btn-lg shadow-warning">Generate
                            Banner</button>
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
    @vite(['resources/js/pages/mikrotik-suite/system/identity/banner.js'])
@endpush
