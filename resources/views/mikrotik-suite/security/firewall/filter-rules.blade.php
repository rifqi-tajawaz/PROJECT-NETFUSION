@extends('layouts.app')

@section('content')
    <div class="container-fluid px-4 py-4">
        <div class="mb-4 d-flex flex-column align-items-center justify-content-center text-center">
            <h2 class="display-6 fw-bold mb-2 text-white" style="text-shadow: 0 2px 10px rgba(0,0,0,0.3);">
                <i class="bi bi-funnel me-2 text-info"></i> Filter Rules Generator
            </h2>
            <p class="lead opacity-75 mb-0" style="max-width: 700px;">
                Create custom Filter rules for Input, Forward, or Output chains.
            </p>
        </div>

        <div class="glass-card p-5 text-center">
            <p class="text-white">Use the Advanced Security -> Input/Forward Chain tools for specific standard
                configurations.</p>
            <a href="{{ route('mikrotik-suite.security.advanced.input-chain') }}" class="btn btn-outline-light">Go to Input
                Chain</a>
            <a href="{{ route('mikrotik-suite.security.advanced.forward-chain') }}" class="btn btn-outline-light">Go to Forward
                Chain</a>
        </div>
    </div>
@endsection
