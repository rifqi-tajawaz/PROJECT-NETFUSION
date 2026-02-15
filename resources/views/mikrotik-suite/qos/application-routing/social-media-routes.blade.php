@extends('layouts.app')



@section('content')
    <x-page-header title="Social Media Routes"
        subtitle="Separate or limitless Social Media traffic (Facebook, Instagram, TikTok).">
        <x-slot name="action">
            <div class="d-none d-md-block text-secondary small">
                <i class="bi bi-people me-1"></i> Content Priority
            </div>
        </x-slot>
    </x-page-header>

    <form id="socialForm" data-route="{{ route('mikrotik-suite.qos.application.social-media.generate') }}">
        <div class="row g-4 justify-content-center">
            <div class="col-lg-5">
                <x-card title="Platform" class="h-100">
                    <x-slot name="headerAction">
                        <i class="bi bi-phone text-info"></i>
                    </x-slot>

                    <div class="mb-3">
                        <label class="form-label small text-uppercase fw-bold text-secondary">Platform (Content/TLS
                            Host)</label>
                        <select class="form-select" id="platform" name="platform">
                            <option value="facebook">Facebook (.facebook.com, .fbcdn.net)</option>
                            <option value="instagram">Instagram (.instagram.com)</option>
                            <option value="tiktok">TikTok (.tiktok.com, .byteoversea.com)</option>
                            <option value="whatsapp">WhatsApp (.whatsapp.net)</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small text-uppercase fw-bold text-secondary">Action</label>
                        <select class="form-select" id="action" name="action">
                            <option value="mark">Mark Packet (for Queueing)</option>
                            <option value="route">Route to VPN/Gateway (PBR)</option>
                        </select>
                    </div>

                    <div class="d-grid mt-4">
                        <button type="submit" class="btn btn-info text-dark btn-lg shadow-info">Generate Rules</button>
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
    @vite(['resources/js/pages/mikrotik-suite/qos/application-routing/social-media-routes.js'])
@endpush
