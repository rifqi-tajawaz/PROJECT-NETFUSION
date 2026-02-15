@extends('layouts.app')



@section('content')
    <x-page-header title="User Management" subtitle="Add or manage system users and groups securely.">
        <x-slot name="action">
            <div class="d-flex align-items-center justify-content-center gap-2 px-3 py-2 rounded-pill bg-primary bg-opacity-10 border border-primary border-opacity-10 text-primary small fw-medium text-nowrap mx-auto ms-md-auto"
                style="width: auto !important;">
                <i class="bi bi-people fs-6"></i>
                <span>Access Control</span>
            </div>
        </x-slot>
    </x-page-header>

    <form id="userManagementForm" data-route="{{ route('mikrotik-suite.system.maintenance.user-management.generate') }}">
        <div class="row g-4 justify-content-center">
            <div class="col-lg-5">
                <x-card title="New User" class="h-100">
                    <x-slot name="headerAction">
                        <i class="bi bi-person-plus text-info"></i>
                    </x-slot>

                    <div class="mb-3">
                        <label class="form-label small text-uppercase fw-bold text-secondary">Username</label>
                        <input type="text" class="form-control" id="uName" name="username">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small text-uppercase fw-bold text-secondary">Password</label>
                        <input type="text" class="form-control" id="uPass" name="password">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small text-uppercase fw-bold text-secondary">Group</label>
                        <select class="form-select" id="uGroup" name="group">
                            <option value="full">Full</option>
                            <option value="read">Read</option>
                            <option value="write">Write</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small text-uppercase fw-bold text-secondary">Allowed IP</label>
                        <input type="text" class="form-control" id="uIp" name="address" placeholder="192.168.88.0/24">
                        <div class="form-text text-secondary">Optional: Restrict login source.</div>
                    </div>

                    <div class="d-grid mt-4">
                        <button type="submit" class="btn btn-info text-dark btn-lg shadow-info">Generate Create
                            User</button>
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
                        <i class="bi bi-info-circle me-1"></i> Consider disabling the default 'admin' user after creating a
                        new full user.
                    </div>

                    <pre class="m-0 p-4 text-warning bg-dark bg-opacity-75 h-100 font-monospace" id="scriptOutput"
                        style="min-height:300px;">// Script...</pre>
                </x-card>
            </div>
        </div>
    </form>
@endsection

@push('scripts')
    @vite(['resources/js/pages/mikrotik-suite/system/maintenance/user-management.js'])
@endpush
