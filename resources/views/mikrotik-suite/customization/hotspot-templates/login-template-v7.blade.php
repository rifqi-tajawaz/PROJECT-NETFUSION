@extends('layouts.app')

@section('content')
    <div class="container-fluid px-4 py-4">
        <div class="mb-4 d-flex flex-column align-items-center justify-content-center text-center">
            <h2 class="display-6 fw-bold mb-2 text-white" style="text-shadow: 0 2px 10px rgba(0,0,0,0.3);">
                <i class="bi bi-phone me-2 text-info"></i> RouterOS v7 Templates
            </h2>
            <p class="lead opacity-75 mb-0" style="max-width: 700px;">
                Modern, secure, HTTP/HTTPS compliant themes for RouterOS 7.x.
            </p>
        </div>

        <div class="row g-4 justify-content-center">
            <!-- Template Item 1 -->
            <div class="col-md-4">
                <div class="glass-card h-100">
                    <div class="position-relative">
                        <span class="badge bg-danger position-absolute top-0 end-0 m-3">Trending</span>
                        <div style="height: 180px; background: linear-gradient(to bottom, #323232 0%, #3F3F3F 40%, #1C1C1C 150%), linear-gradient(to top, rgba(255,255,255,0.40) 0%, rgba(0,0,0,0.25) 200%); background-blend-mode: multiply;"
                            class="w-100 rounded-top-4 d-flex align-items-center justify-content-center">
                            <i class="bi bi-controller display-1 text-white-50"></i>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <h5 class="text-white fw-bold">Dark Glitch</h5>
                        <p class="text-white-50 small mb-4">Cyberpunk aesthetic with glitch animations. Perfect for gaming
                            lounges.</p>
                        <button class="btn btn-primary w-100 rounded-pill shadow-lg"><i class="bi bi-download me-2"></i>
                            Download ZIP</button>
                    </div>
                </div>
            </div>

            <!-- Template Item 2 -->
            <div class="col-md-4">
                <div class="glass-card h-100">
                    <div class="position-relative">
                        <div style="height: 180px; background: linear-gradient(120deg, #a1c4fd 0%, #c2e9fb 100%);"
                            class="w-100 rounded-top-4 d-flex align-items-center justify-content-center">
                            <i class="bi bi-airplane display-1 text-white-50"></i>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <h5 class="text-white fw-bold">AirPort Clean</h5>
                        <p class="text-white-50 small mb-4">Minimalist, multi-lingual support, and high-contrast
                            accessibility.</p>
                        <button class="btn btn-outline-light w-100 rounded-pill"><i class="bi bi-download me-2"></i>
                            Download ZIP</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
