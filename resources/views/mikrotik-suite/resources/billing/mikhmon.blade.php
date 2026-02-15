@extends('layouts.app')

@section('content')
    <div class="container-fluid px-4 py-4">
        <div class="mb-4 d-flex flex-column align-items-center justify-content-center text-center">
            <h2 class="display-6 fw-bold mb-2 text-white" style="text-shadow: 0 2px 10px rgba(0,0,0,0.3);">
                <i class="bi bi-wallet2 me-2 text-success"></i> Mikhmon Integration
            </h2>
            <p class="lead opacity-75 mb-0" style="max-width: 700px;">
                Configure RouterOS for Mikhail Monitor (Mikhmon) API access.
            </p>
        </div>

        <form id="mikhmonForm" data-route="{{ route('mikrotik-suite.resources.billing.mikhmon.generate') }}">
            <div class="row g-4 justify-content-center">
                <div class="col-lg-5">
                    <div class="glass-card h-100">
                        <div class="card-header border-0 bg-transparent pt-4 px-4">
                            <h5 class="card-title fw-bold text-white mb-0">API User Settings</h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="mb-3">
                                <label class="form-label text-white small text-uppercase">Username</label>
                                <input type="text" class="form-control" id="user" value="mikhmon">
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-white small text-uppercase">Password</label>
                                <input type="text" class="form-control" id="pass" value="1234">
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-white small text-uppercase">Group</label>
                                <select class="form-select" id="group">
                                    <option value="full">Full (Read/Write)</option>
                                    <option value="write">Write</option>
                                    <option value="read">Read (Might not work for user creation)</option>
                                </select>
                            </div>
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="apiService" checked>
                                <label class="form-check-label text-white" for="apiService">Enable API Service (Port
                                    8728)</label>
                            </div>

                            <div class="d-grid mt-4">
                                <button type="submit" class="btn btn-success btn-lg shadow-lg">Generate Config</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="glass-card h-100">
                        <div class="card-header border-0 bg-transparent pt-4 px-4 d-flex justify-content-between">
                            <h5 class="card-title fw-bold text-white mb-0">RouterOS Script</h5>
                            <button type="button" class="btn btn-sm btn-outline-light"
                                onclick="copyText('scriptOutput')">Copy</button>
                        </div>
                        <div class="card-body p-0">
                            <div
                                class="alert alert-info bg-info bg-opacity-10 border-0 text-info m-3 small border-start border-4 border-info">
                                <i class="bi bi-info-circle me-1"></i> Paste this into Terminal to create the API user.
                            </div>
                            <pre class="m-0 p-4 text-warning bg-dark bg-opacity-50 flex-grow-1 font-monospace"
                                id="scriptOutput" style="min-height:300px;">// Script...</pre>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    @vite(['resources/js/pages/mikrotik-suite/resources/billing/mikhmon.js'])
@endpush
