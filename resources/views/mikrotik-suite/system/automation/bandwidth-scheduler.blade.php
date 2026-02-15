@extends('layouts.app')



@section('content')
    <x-page-header title="Bandwidth Scheduler"
        subtitle="Change bandwidth limits based on time of day (e.g. higher speed at night).">
        <x-slot name="action">
            <div class="d-none d-md-block text-secondary small">
                <i class="bi bi-clock me-1"></i> Time-Based Limits
            </div>
        </x-slot>
    </x-page-header>

    <form id="bwSchedForm" data-route="{{ route('mikrotik-suite.system.automation.bandwidth.generate') }}">
        <div class="row g-4 justify-content-center">
            <div class="col-lg-5">
                <x-card title="Rules" class="h-100">
                    <x-slot name="headerAction">
                        <i class="bi bi-speedometer2 text-info"></i>
                    </x-slot>

                    <div class="mb-3">
                        <label class="form-label small text-uppercase fw-bold text-secondary">Target Queue Name</label>
                        <input type="text" class="form-control" id="qname" name="queue_name" value="Home_Queue">
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label small text-uppercase fw-bold text-secondary">Day Limit</label>
                            <input type="text" class="form-control" id="dlim" name="day_limit" value="10M/10M">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label small text-uppercase fw-bold text-secondary">Day Start</label>
                            <input type="time" class="form-control" id="dtime" name="day_time" value="07:00">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label small text-uppercase fw-bold text-secondary">Night Limit</label>
                            <input type="text" class="form-control" id="nlim" name="night_limit" value="50M/50M">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label small text-uppercase fw-bold text-secondary">Night Start</label>
                            <input type="time" class="form-control" id="ntime" name="night_time" value="23:00">
                        </div>
                    </div>

                    <div class="d-grid mt-4">
                        <button type="submit" class="btn btn-info text-dark btn-lg shadow-info">Generate Schedule</button>
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

                    <div class="alert alert-info m-3 small border-start border-4 border-info">
                        <i class="bi bi-info-circle me-1"></i> Creates two Schedulers: one for day, one for night.
                    </div>
                    <pre class="m-0 p-4 text-warning bg-dark bg-opacity-75 h-100 font-monospace" id="scriptOutput"
                        style="min-height:300px;">// Script...</pre>
                </x-card>
            </div>
        </div>
    </form>
@endsection

@push('scripts')
    @vite(['resources/js/pages/mikrotik-suite/system/automation/bandwidth-scheduler.js'])
@endpush
