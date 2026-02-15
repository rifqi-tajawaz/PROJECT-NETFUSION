@extends('layouts.app')



@section('content')
    <x-page-header title="Expired User Notification"
        subtitle="Automatically remove expired Hostpot users and send notifications to admins.">
        <x-slot name="action">
            <div class="d-none d-md-block text-secondary small">
                <i class="bi bi-alarm me-1"></i> Auto-Scheduler
            </div>
        </x-slot>
    </x-page-header>

    <form id="expireForm" data-route="{{ route('mikrotik-suite.connectivity.hotspot.expired-notification.generate') }}">
        <div class="row g-4">
            <div class="col-lg-5">
                <x-card title="Scheduler Settings" class="h-100">
                    <x-slot name="headerAction">
                        <i class="bi bi-sliders text-brand"></i>
                    </x-slot>

                    <div class="mb-3">
                        <label class="form-label text-secondary small text-uppercase fw-bold">Check Interval</label>
                        <select class="form-select" name="interval" id="interval">
                            <option value="1h">Every 1 Hour</option>
                            <option value="30m">Every 30 Minutes</option>
                            <option value="1d">Every 24 Hours</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="remove_user" id="removeUser" value="1"
                                checked>
                            <label class="form-check-label text-secondary" for="removeUser">Remove User on
                                Expiry</label>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="log_event" id="logEvent" value="1"
                                checked>
                            <label class="form-check-label text-secondary" for="logEvent">Log to System Log</label>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-secondary small text-uppercase fw-bold">Comment Tag</label>
                        <input type="text" class="form-control" name="comment_tag" id="commentTag" value="expired">
                        <small class="text-muted">Users with this comment will be processed (if unchecked above, or
                            just marked)</small>
                    </div>

                    <div class="d-grid mt-4">
                        <button type="submit" class="btn btn-brand btn-lg shadow-brand rounded-3">
                            <span class="btn-label"><i class="bi bi-clock-history me-2"></i> Generate Scheduler</span>
                            <span class="btn-loader d-none"><span
                                    class="spinner-border spinner-border-sm me-2"></span>Generating...</span>
                        </button>
                    </div>
                </x-card>
            </div>

            <div class="col-lg-7">
                <x-card title="RouterOS Script" class="h-100 p-0" bodyClass="p-0">
                    <x-slot name="headerAction">
                        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="copyText('scriptOutput')">
                            <i class="bi bi-clipboard"></i> Copy
                        </button>
                    </x-slot>

                    <pre class="m-0 p-4 text-warning bg-dark bg-opacity-75 h-100"
                        style="min-height:300px; overflow-y: auto; font-family: 'JetBrains Mono', monospace;"
                        id="scriptOutput">// Script will appear here...</pre>
                </x-card>
            </div>
        </div>
    </form>
@endsection

@push('scripts')
    @vite(['resources/js/pages/mikrotik-suite/connectivity/hotspot/expired-notification.js'])
@endpush
