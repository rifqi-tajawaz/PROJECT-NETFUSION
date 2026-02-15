@extends('layouts.app')

@section('content')
    <div class="container-fluid px-4 py-4">
        <div class="mb-4 d-flex flex-column align-items-center justify-content-center text-center">
            <h2 class="display-6 fw-bold mb-2 text-white" style="text-shadow: 0 2px 10px rgba(0,0,0,0.3);">
                <i class="bi bi-hdd-network me-2 text-primary"></i> DMA Softlab Radius
            </h2>
            <p class="lead opacity-75 mb-0" style="max-width: 700px;">
                Configuration for DMA Softlab Radius Manager.
            </p>
        </div>

        <div class="glass-card p-5 text-center">
            <p class="text-white">DMA Radius usually requires standard RADIUS Client setup.</p>
            <a href="{{ route('mikrotik-suite.integration.billing.freeradius') }}" class="btn btn-primary">Use Standard RADIUS
                Configurator</a>
        </div>
    </div>
@endsection
