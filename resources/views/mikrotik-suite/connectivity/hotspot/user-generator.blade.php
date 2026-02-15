@extends('layouts.app')



@section('content')
    <x-page-header title="Hotspot User Generator" subtitle="Bulk create Hotspot vouchers/users securely">
        <x-slot name="action">
            <div class="d-none d-md-block text-secondary small">
                <i class="bi bi-info-circle me-1"></i> Generates RouterOS script & CSV
            </div>
        </x-slot>
    </x-page-header>

    <div class="row g-4">
        <!-- Configuration -->
        <div class="col-lg-4">
            <x-card title="Settings" class="h-100">
                <x-slot name="headerAction">
                    <i class="bi bi-gear text-brand"></i>
                </x-slot>

                <form id="hsgForm" data-route="{{ route('mikrotik-suite.connectivity.hotspot.user-generator.generate') }}">
                    <div class="mb-3">
                        <label class="form-label text-secondary small text-uppercase fw-bold">Quantity</label>
                        <input type="number" class="form-control" name="qty" id="qty" value="50" min="1" max="1000">
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-secondary small text-uppercase fw-bold">User Mode</label>
                        <select class="form-select" name="userModel" id="userMode">
                            <option value="user_pass">Username & Password</option>
                            <option value="user_eq_pass">Username = Password</option>
                        </select>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label text-secondary small text-uppercase fw-bold">Prefix</label>
                            <input type="text" class="form-control" name="prefix" id="prefix" placeholder="guest-">
                        </div>
                        <div class="col-6">
                            <label class="form-label text-secondary small text-uppercase fw-bold">Length</label>
                            <input type="number" class="form-control" name="length" id="length" value="6" min="3" max="20">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-secondary small text-uppercase fw-bold">Profile</label>
                        <input type="text" class="form-control" name="profile" id="profile" value="default">
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-secondary small text-uppercase fw-bold">Time Limit</label>
                        <input type="text" class="form-control" name="timeLimit" id="timeLimit"
                            placeholder="e.g. 1h (Optional)">
                        <small class="text-muted">MikroTik format: 30m, 1h, 1d</small>
                    </div>

                    <div class="d-grid mt-4">
                        <button type="submit" class="btn btn-brand btn-lg shadow-brand rounded-3">
                            <span class="btn-label"><i class="bi bi-lightning-charge me-2"></i> Generate Users</span>
                            <span class="btn-loader d-none"><span
                                    class="spinner-border spinner-border-sm me-2"></span>Generating...</span>
                        </button>
                    </div>
                </form>
            </x-card>
        </div>

        <!-- Output -->
        <div class="col-lg-8">
            <x-card class="h-100 p-0" bodyClass="p-0">
                {{-- Custom Tabs Header --}}
                <div class="card-header border-0 bg-transparent px-4 pt-4">
                    <ul class="nav nav-pills card-header-pills" id="hsgTabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="script-tab" data-bs-toggle="tab" href="#scriptVec"
                                role="tab">RouterOS Script</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="csv-tab" data-bs-toggle="tab" href="#csvVec" role="tab">CSV Data</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="preview-tab" data-bs-toggle="tab" href="#previewVec" role="tab">Card
                                Preview</a>
                        </li>
                    </ul>
                </div>

                <div class="card-body p-0">
                    <div class="tab-content">
                        <!-- Script Tab -->
                        <div class="tab-pane fade show active" id="scriptVec" role="tabpanel">
                            <pre class="m-0 p-4 text-warning bg-dark bg-opacity-75"
                                style="max-height: 500px; overflow-y: auto; font-family: 'JetBrains Mono', monospace;"
                                id="scriptOutput">// Generated script will appear here</pre>
                            <div class="p-3 border-top border-light d-flex justify-content-end bg-light-subtle">
                                <button type="button" class="btn btn-sm btn-outline-secondary"
                                    onclick="copyText('scriptOutput')">Copy Script</button>
                            </div>
                        </div>

                        <!-- CSV Tab -->
                        <div class="tab-pane fade" id="csvVec" role="tabpanel">
                            <pre class="m-0 p-4 text-info bg-dark bg-opacity-75"
                                style="max-height: 500px; overflow-y: auto; font-family: 'JetBrains Mono', monospace;"
                                id="csvOutput">Username,Password,Profile,Limit</pre>
                            <div class="p-3 border-top border-light d-flex justify-content-end bg-light-subtle">
                                <button type="button" class="btn btn-sm btn-outline-secondary"
                                    onclick="copyText('csvOutput')">Copy CSV</button>
                            </div>
                        </div>

                        <!-- Preview Tab (Visual) -->
                        <div class="tab-pane fade" id="previewVec" role="tabpanel">
                            <div class="p-4 bg-light" style="max-height: 500px; overflow-y: auto;">
                                <div class="row g-3" id="cardsContainer">
                                    <div class="col-12 text-center text-muted py-5">
                                        <i class="bi bi-inbox fs-1 d-block mb-3 opacity-25"></i>
                                        No users generated yet.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </x-card>
        </div>
    </div>
@endsection

@push('scripts')
    @vite(['resources/js/pages/mikrotik-suite/connectivity/hotspot/user-generator.js'])
@endpush
