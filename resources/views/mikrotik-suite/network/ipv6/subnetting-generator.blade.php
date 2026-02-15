@extends('layouts.app')

@section('content')
    <div class="container-fluid px-4 py-4">
        <div class="mb-4 d-flex flex-column align-items-center justify-content-center text-center">
            <h2 class="display-6 fw-bold mb-2 text-white" style="text-shadow: 0 2px 10px rgba(0,0,0,0.3);">
                <i class="bi bi-diagram-3-fill me-2 text-success"></i> IPv6 Subnet Calculator (Coming Soon)
            </h2>
            <p class="lead opacity-75 mb-0" style="max-width: 700px;">
                A tool for subdividing IPv6 /48 or /56 blocks into /64 subnets.
            </p>
        </div>

        <div class="glass-card p-5 text-center">
            <p class="text-white-50">Logic implementation pending. Please use EUI-64 Calculator for now.</p>
        </div>
    </div>
@endsection
