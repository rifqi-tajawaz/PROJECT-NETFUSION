@extends('layouts.app')

@section('content')
    <div class="container-fluid px-4 py-4">
        <div class="mb-4 d-flex flex-column align-items-center justify-content-center text-center">
            <h2 class="display-6 fw-bold mb-2 text-white" style="text-shadow: 0 2px 10px rgba(0,0,0,0.3);">
                <i class="bi bi-file-earmark-code me-2 text-primary"></i> RSC Beautifier
            </h2>
            <p class="lead opacity-75 mb-0" style="max-width: 700px;">
                Format and indent your RouterOS scripts for better readability.
            </p>
        </div>

        <div class="row g-4 h-100">
            <div class="col-lg-6">
                <div class="glass-card h-100 d-flex flex-column">
                    <div class="card-header border-0 bg-transparent pt-4 px-4">
                        <h5 class="card-title fw-bold text-white mb-0">Input Script</h5>
                    </div>
                    <div class="card-body p-4 flex-grow-1">
                        <textarea class="form-control h-100 font-monospace text-white bg-dark border-secondary"
                            id="inputScript" rows="15"
                            placeholder="/ip address add address=1.1.1.1/24 interface=ether1 comment=WAN /ip route add gateway=1.1.1.2 ..."></textarea>
                    </div>
                    <div class="card-footer bg-transparent border-0 p-4">
                        <button class="btn btn-primary w-100 rounded-pill" onclick="beautify()">
                            <i class="bi bi-magic me-2"></i> Format Code
                        </button>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="glass-card h-100 d-flex flex-column">
                    <div class="card-header border-0 bg-transparent pt-4 px-4 d-flex justify-content-between">
                        <h5 class="card-title fw-bold text-white mb-0">Formatted Output</h5>
                        <button class="btn btn-sm btn-outline-light" onclick="copyText('outputScript')">Copy</button>
                    </div>
                    <div class="card-body p-4 flex-grow-1">
                        <pre class="h-100 font-monospace text-warning m-0" id="outputScript"
                            style="white-space: pre-wrap; overflow-y: scroll;">// Formatted code...</pre>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    @vite(['resources/js/pages/mikrotik-suite/customization/special-tools/rsc-beautifier.js'])
@endpush
