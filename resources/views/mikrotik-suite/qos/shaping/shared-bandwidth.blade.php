@extends('layouts.app')



@section('content')
    <x-page-header title="Shared Bandwidth (USG Style)"
        subtitle="Configure multiple clients sharing a single parent queue limit.">
        <x-slot name="action">
            <div class="d-none d-md-block text-secondary small">
                <i class="bi bi-people me-1"></i> Group Policer
            </div>
        </x-slot>
    </x-page-header>

    <form id="sharedForm" data-route="{{ route('mikrotik-suite.qos.queues.shared.generate') }}">
        <div class="row g-4 justify-content-center">
            <div class="col-lg-5">
                <x-card title="Group Settings" class="h-100">
                    <x-slot name="headerAction">
                        <i class="bi bi-collection text-warning"></i>
                    </x-slot>

                    <div class="mb-3">
                        <label class="form-label small text-uppercase fw-bold text-secondary">Group Name</label>
                        <input type="text" class="form-control" id="name" name="name" value="Shared_Home">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small text-uppercase fw-bold text-secondary">Total Bandwidth</label>
                        <input type="text" class="form-control" id="total" name="total" value="50M">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small text-uppercase fw-bold text-secondary">Clients (IPs)</label>
                        <textarea class="form-control" id="clients" name="clients" rows="5"
                            placeholder="192.168.88.10&#10;192.168.88.11&#10;192.168.88.12"></textarea>
                    </div>

                    <div class="d-grid mt-4">
                        <button type="submit" class="btn btn-warning text-dark btn-lg shadow-warning">Generate Shared
                            Queue</button>
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
    @vite(['resources/js/pages/mikrotik-suite/qos/shaping/shared-bandwidth.js'])
@endpush
