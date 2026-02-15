@extends('layouts.app')



@section('content')
    <x-page-header title="Auto Reboot" subtitle="Schedule automatic router reboots.">
        <x-slot name="action">
            <div class="d-none d-md-block text-secondary small">
                <i class="bi bi-power me-1"></i> Scheduled Reboot
            </div>
        </x-slot>
    </x-page-header>

    <form id="rebootForm" data-route="{{ route('mikrotik-suite.system.automation.auto-reboot.generate') }}">
        <div class="row g-4 justify-content-center">
            <div class="col-lg-5">
                <x-card title="Schedule" class="h-100">
                    <x-slot name="headerAction">
                        <i class="bi bi-clock text-danger"></i>
                    </x-slot>

                    <div class="mb-3">
                        <label class="form-label small text-uppercase fw-bold text-secondary">Interval</label>
                        <select class="form-select" id="int" name="interval">
                            <option value="1d">Daily</option>
                            <option value="7d">Weekly</option>
                            <option value="30d">Monthly</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small text-uppercase fw-bold text-secondary">Time</label>
                        <input type="time" class="form-control" id="time" name="time" value="04:00">
                    </div>

                    <div class="d-grid mt-4">
                        <button type="submit" class="btn btn-danger btn-lg shadow-danger text-white">Generate Reboot
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

                    <div class="alert alert-warning m-3 small border-start border-4 border-warning">
                        <i class="bi bi-exclamation-triangle me-1"></i> Make sure the router's clock (SNTP) is correct!
                    </div>

                    <pre class="m-0 p-4 text-warning bg-dark bg-opacity-75 h-100 font-monospace" id="scriptOutput"
                        style="min-height:300px;">// Script...</pre>
                </x-card>
            </div>
        </div>
    </form>
@endsection

@push('scripts')
    @vite(['resources/js/pages/mikrotik-suite/system/automation/auto-reboot.js'])
@endpush
