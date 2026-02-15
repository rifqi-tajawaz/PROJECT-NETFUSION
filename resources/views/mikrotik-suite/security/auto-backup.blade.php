@extends('layouts.app')



@section('content')
    <x-page-header title="Auto Backup" subtitle="Configure automatic email backups for your router configuration.">
        <x-slot name="action">
            <div class="d-none d-md-block text-secondary small">
                <i class="bi bi-clock-history me-1"></i> Scheduled Backups
            </div>
        </x-slot>
    </x-page-header>

    <form id="backupForm">
        <div class="row g-4 justify-content-center">
            <div class="col-lg-5">
                <x-card title="Email Settings" class="h-100">
                    <x-slot name="headerAction">
                        <i class="bi bi-envelope text-brand"></i>
                    </x-slot>

                    <div class="mb-3">
                        <label class="form-label text-secondary small text-uppercase fw-bold">Email</label>
                        <input type="email" class="form-control border-secondary border-opacity-25" id="email"
                            placeholder="admin@example.com">
                        <div class="form-text text-secondary opacity-75">Requires properly configured /tool e-mail</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-secondary small text-uppercase fw-bold">Schedule Interval</label>
                        <input type="text" class="form-control border-secondary border-opacity-25" id="interval" value="7d">
                    </div>

                    <div class="d-grid mt-4">
                        <button type="submit" class="btn btn-brand btn-lg shadow-brand rounded-3">Generate Backup
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

                    <div
                        class="alert alert-info bg-dark bg-opacity-75 border-0 text-info m-3 small border-start border-4 border-info">
                        <i class="bi bi-info-circle me-1"></i> Make sure SMTP is configured in /tool e-mail first.
                    </div>
                    <pre class="m-0 p-4 text-warning bg-dark bg-opacity-75 flex-grow-1 font-monospace" id="scriptOutput"
                        style="min-height:300px;">// Script...</pre>
                </x-card>
            </div>
        </div>
    </form>
@endsection

@push('scripts')
    @vite(['resources/js/pages/mikrotik-suite/security/auto-backup.js'])
@endpush
