@extends('layouts.app')



@section('content')
    <x-page-header title="Scheduler Builder" subtitle="Create automated tasks to run at specific intervals.">
        <x-slot name="action">
            <div class="d-none d-md-block text-secondary small">
                <i class="bi bi-calendar-check me-1"></i> Custom Tasks
            </div>
        </x-slot>
    </x-page-header>

    <form id="schedulerForm" data-route="{{ route('mikrotik-suite.system.automation.scheduler.generate') }}">
        <div class="row g-4 justify-content-center">
            <div class="col-lg-5">
                <x-card title="Task Details" class="h-100">
                    <x-slot name="headerAction">
                        <i class="bi bi-pencil-square text-primary"></i>
                    </x-slot>

                    <div class="mb-3">
                        <label class="form-label small text-uppercase fw-bold text-secondary">Name</label>
                        <input type="text" class="form-control" id="name" name="name" value="DailyReboot">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small text-uppercase fw-bold text-secondary">Interval</label>
                        <input type="text" class="form-control" id="interval" name="interval" value="1d">
                        <div class="form-text text-secondary">e.g. 1d, 1h, 30m, 12:00:00</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small text-uppercase fw-bold text-secondary">Start Time</label>
                        <input type="time" class="form-control" id="start" name="start_time" value="03:00">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small text-uppercase fw-bold text-secondary">Script Source</label>
                        <textarea class="form-control font-monospace" id="script" name="on_event" rows="4"
                            placeholder="/system reboot"></textarea>
                    </div>

                    <div class="d-grid mt-4">
                        <button type="submit" class="btn btn-primary btn-lg shadow-primary">Generate Scheduler</button>
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
    @vite(['resources/js/pages/mikrotik-suite/system/automation/scheduler-builder.js'])
@endpush
