@extends('layouts.app')

@section('content')
    <div class="container-fluid px-4 py-4">
        <div class="mb-4 d-flex flex-column align-items-center justify-content-center text-center">
            <h2 class="display-6 fw-bold mb-2 text-white" style="text-shadow: 0 2px 10px rgba(0,0,0,0.3);">
                <i class="bi bi-slash-circle me-2 text-danger"></i> AdBlock Installer
            </h2>
            <p class="lead opacity-75 mb-0" style="max-width: 700px;">
                Script-based DNS adblocking (without containers).
            </p>
        </div>

        <form id="abForm" data-route="{{ route('mikrotik-suite.utilities.container.adblock.generate') }}">
            <div class="row g-4 justify-content-center">
                <div class="col-lg-5">
                    <div class="glass-card h-100">
                        <div class="card-header border-0 bg-transparent pt-4 px-4">
                            <h5 class="card-title fw-bold text-white mb-0">Info</h5>
                        </div>
                        <div class="card-body p-4 text-center">
                            <i class="bi bi-exclamation-triangle text-warning display-4 mb-3"></i>
                            <h6 class="text-white">Notice</h6>
                            <p class="text-secondary small">
                                This tool generates a script to fetch a hosts file. Importing large blocklists (50k+
                                entries)
                                directly into Mikrotik DNS Static can cause significant performance issues or crashes on
                                devices with low RAM.
                            </p>
                            <p class="text-secondary small">
                                <strong>Recommended:</strong> Use Pi-hole or AdGuard Home containers instead.
                            </p>

                            <div class="d-grid mt-4">
                                <button type="submit" class="btn btn-danger btn-lg shadow-lg">Generate Fetch Script</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="glass-card h-100">
                        <div class="card-header border-0 bg-transparent pt-4 px-4 d-flex justify-content-between">
                            <h5 class="card-title fw-bold text-white mb-0">RouterOS Script</h5>
                            <button type="button" class="btn btn-sm btn-outline-light" onclick="copyScript()">Copy</button>
                        </div>
                        <div class="card-body p-0">
                            <pre class="m-0 p-4 text-warning bg-dark bg-opacity-50 flex-grow-1 font-monospace"
                                id="scriptOutput"
                                style="min-height:300px; max-height: 500px; overflow-y: auto;">// Script will appear here...</pre>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    @vite(['resources/js/pages/mikrotik-suite/utilities/container/adblock.js'])
@endpush
