@extends('layouts.app')

@section('content')
    <div class="container-fluid px-4 py-4">
        <div class="mb-4 d-flex flex-column align-items-center justify-content-center text-center">
            <h2 class="display-6 fw-bold mb-2 text-white" style="text-shadow: 0 2px 10px rgba(0,0,0,0.3);">
                <i class="bi bi-graph-up-arrow me-2 text-success"></i> Queue Simulator
            </h2>
            <p class="lead opacity-75 mb-0" style="max-width: 700px;">
                Visualize how PCQ/HTB or algorithms prioritize traffic.
            </p>
        </div>

        <div class="glass-card p-5 text-center">
            <div class="spinner-border text-primary mb-3" role="status"></div>
            <h5 class="text-white">Simulation Engine Loading...</h5>
            <p class="text-white opacity-75">This advanced visualizer is under development.</p>
            <a href="{{ route('mikrotik-suite.qos.queues.simple-queue') }}" class="btn btn-outline-light mt-3">Configure Real
                Queues</a>
        </div>
    </div>
@endsection
