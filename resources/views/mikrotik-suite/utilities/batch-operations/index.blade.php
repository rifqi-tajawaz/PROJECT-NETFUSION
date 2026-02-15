@extends('layouts.app')

@section('content')
    <div class="container-fluid px-4 py-4">
        <div class="mb-4 d-flex flex-column align-items-center justify-content-center text-center">
            <h2 class="display-6 fw-bold mb-2 text-white" style="text-shadow: 0 2px 10px rgba(0,0,0,0.3);">
                <i class="bi bi-collection-play me-2 text-primary"></i> Batch Operations
            </h2>
            <p class="lead opacity-75 mb-0" style="max-width: 700px;">
                Execute tools in batch mode (Simulation/Placeholder).
            </p>
        </div>

        <div class="glass-card p-5 text-center">
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="p-4 border rounded border-secondary bg-black bg-opacity-25">
                        <h5 class="text-white">Batch Backup</h5>
                        <p class="text-white-50">Generate backup for multiple routers.</p>
                        <button class="btn btn-sm btn-outline-light" disabled>Coming Soon</button>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="p-4 border rounded border-secondary bg-black bg-opacity-25">
                        <h5 class="text-white">Batch DNS Ping</h5>
                        <p class="text-white-50">Ping multiple domains at once.</p>
                        <button class="btn btn-sm btn-outline-light" disabled>Coming Soon</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
