@extends('layouts.app')

@section('content')
    <div class="container-fluid px-4 py-4">
        <div class="mb-4 d-flex flex-column align-items-center justify-content-center text-center">
            <h2 class="display-6 fw-bold mb-2 text-white" style="text-shadow: 0 2px 10px rgba(0,0,0,0.3);">
                <i class="bi bi-palette2 me-2 text-success"></i> Custom Template Builder
            </h2>
            <p class="lead opacity-75 mb-0" style="max-width: 700px;">
                Modify text, colors, and logos of base templates visually.
            </p>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="glass-card p-5 text-center">
                    <i class="bi bi-cone-striped display-1 text-warning mb-4"></i>
                    <h3 class="text-white">Under Construction</h3>
                    <p class="text-white-50 mb-4">We are building a drag-and-drop HTML editor for Hotspot pages. Check back
                        soon!</p>
                    <a href="{{ route('mikrotik-suite.customization.login-template-v7') }}"
                        class="btn btn-primary rounded-pill px-4">Browse Pre-made Templates</a>
                </div>
            </div>
        </div>
    </div>
@endsection
