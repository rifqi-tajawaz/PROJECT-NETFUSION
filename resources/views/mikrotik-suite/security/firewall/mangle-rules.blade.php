@extends('layouts.app')

@section('content')
    <div class="container-fluid px-4 py-4">
        <div class="mb-4 d-flex flex-column align-items-center justify-content-center text-center">
            <h2 class="display-6 fw-bold mb-2 text-white" style="text-shadow: 0 2px 10px rgba(0,0,0,0.3);">
                <i class="bi bi-tag-fill me-2 text-danger"></i> Mangle Rules Generator
            </h2>
            <p class="lead opacity-75 mb-0" style="max-width: 700px;">
                Create Mangle rules for modifying packet properties (QoS, Routing Marks).
            </p>
        </div>

        <div class="glass-card p-5 text-center">
            <p class="text-white">Use QoS -> Application Routing for game/app specific mangling.</p>
            <a href="{{ route('mikrotik-suite.qos.application-routing.gaming-routes') }}" class="btn btn-outline-light">Go to QoS
                Rules</a>
        </div>
    </div>
@endsection
