@extends('layouts.app')

@section('content')
    <div class="container-fluid px-4 py-4">
        <div class="mb-4 d-flex flex-column align-items-center justify-content-center text-center">
            <h2 class="display-6 fw-bold mb-2 text-white" style="text-shadow: 0 2px 10px rgba(0,0,0,0.3);">
                <i class="bi bi-window-sidebar me-2 text-warning"></i> WebFig Skin Builder
            </h2>
            <p class="lead opacity-75 mb-0" style="max-width: 700px;">
                Create basic skin rules to hide menus or tabs in WebFig.
            </p>
        </div>

        <div class="row g-4">
            <div class="col-lg-5">
                <div class="glass-card h-100">
                    <div class="card-header border-0 bg-transparent pt-4 px-4">
                        <h5 class="card-title fw-bold text-white mb-0">Skin Rules</h5>
                    </div>
                    <div class="card-body p-4">
                        <form id="skinForm">
                            <div class="mb-3">
                                <label class="form-label text-white small text-uppercase">Group Name</label>
                                <input type="text" class="form-control" id="grpName" value="limited-users">
                            </div>

                            <hr class="border-secondary opacity-25">

                            <div class="mb-2 text-white small">Select menus to <strong>HIDE</strong>:</div>

                            <div class="row g-2">
                                <div class="col-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="wireless" id="h_wireless">
                                        <label class="form-check-label text-white-50" for="h_wireless">Wireless</label>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="interfaces"
                                            id="h_interfaces">
                                        <label class="form-check-label text-white-50" for="h_interfaces">Interfaces</label>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="ppp" id="h_ppp">
                                        <label class="form-check-label text-white-50" for="h_ppp">PPP</label>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="switch" id="h_switch">
                                        <label class="form-check-label text-white-50" for="h_switch">Switch</label>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="mesh" id="h_mesh">
                                        <label class="form-check-label text-white-50" for="h_mesh">Mesh</label>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="ip" id="h_ip">
                                        <label class="form-check-label text-white-50" for="h_ip">IP (All)</label>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="system" id="h_system">
                                        <label class="form-check-label text-white-50" for="h_system">System (All)</label>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="queues" id="h_queues">
                                        <label class="form-check-label text-white-50" for="h_queues">Queues</label>
                                    </div>
                                </div>
                            </div>

                            <div class="d-grid mt-4">
                                <button type="submit" class="btn btn-primary btn-lg shadow-lg">Generate Skin JSON</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-7">
                <div class="glass-card h-100">
                    <div class="card-header border-0 bg-transparent pt-4 px-4 d-flex justify-content-between">
                        <h5 class="card-title fw-bold text-white mb-0">WebFig JSON</h5>
                        <button class="btn btn-sm btn-outline-light" onclick="copyText('skinCheck')">Copy</button>
                    </div>
                    <div class="card-body p-0">
                        <div class="p-3 bg-dark bg-opacity-50 h-100 font-monospace text-warning overflow-auto"
                            id="skinCheck" style="white-space:pre-wrap;">// JSON output...</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    @vite(['resources/js/pages/mikrotik-suite/customization/branding-theme/webfig-skin.js'])
@endpush
