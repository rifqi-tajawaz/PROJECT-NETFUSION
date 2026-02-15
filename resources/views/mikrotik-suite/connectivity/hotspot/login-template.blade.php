@extends('layouts.app')



@section('content')
    <x-page-header title="Login Page Templates"
        subtitle="Download premium, responsive Hotspot login pages for your MikroTik router.">
        <x-slot name="action">
            <div class="d-none d-md-block text-secondary small">
                <i class="bi bi-palette me-1"></i> Premium Themes
            </div>
        </x-slot>
    </x-page-header>

    <div class="row g-4">
        <!-- Template 1 -->
        <div class="col-md-4">
            <x-card class="h-100 p-0" bodyClass="p-0">
                <div class="position-relative">
                    <div style="height: 200px; background: linear-gradient(45deg, #1e1e2f, #2d2d44);"
                        class="w-100 rounded-top-2 d-flex align-items-center justify-content-center">
                        <i class="bi bi-shop display-1 text-white-50"></i>
                    </div>
                    <span class="badge bg-primary position-absolute top-0 end-0 m-3">Free</span>
                </div>
                <div class="card-body p-4">
                    <h5 class="card-title fw-bold">Cafe Standard</h5>
                    <p class="text-secondary small mb-4">Clean, light-themed design perfect for cafes and restaurants.
                        Features a carousel and easy login form.</p>
                    <button class="btn btn-outline-secondary w-100 rounded-pill"
                        onclick="alert('Downloading Cafe Template...')"><i class="bi bi-download me-2"></i> Download
                        ZIP</button>
                    <button
                        class="btn btn-sm btn-link text-secondary text-decoration-none d-block mx-auto mt-2">Preview</button>
                </div>
            </x-card>
        </div>

        <!-- Template 2 -->
        <div class="col-md-4">
            <x-card class="h-100 p-0" bodyClass="p-0">
                <div class="position-relative">
                    <div style="height: 200px; background: linear-gradient(45deg, #0f0c29, #302b63);"
                        class="w-100 rounded-top-2 d-flex align-items-center justify-content-center">
                        <i class="bi bi-joystick display-1 text-white-50"></i>
                    </div>
                    <span class="badge bg-warning text-dark position-absolute top-0 end-0 m-3">Premium</span>
                </div>
                <div class="card-body p-4">
                    <h5 class="card-title fw-bold">Gaming Dark</h5>
                    <p class="text-secondary small mb-4">Dark mode aesthetic with neon accents. Optimized for game
                        centers and cyber cafes.</p>
                    <button class="btn btn-brand w-100 rounded-pill shadow-brand"
                        onclick="alert('Downloading Gaming Template...')"><i class="bi bi-download me-2"></i> Download
                        ZIP</button>
                    <button
                        class="btn btn-sm btn-link text-secondary text-decoration-none d-block mx-auto mt-2">Preview</button>
                </div>
            </x-card>
        </div>

        <!-- Template 3 -->
        <div class="col-md-4">
            <x-card class="h-100 p-0" bodyClass="p-0">
                <div class="position-relative">
                    <div style="height: 200px; background: linear-gradient(45deg, #11998e, #38ef7d);"
                        class="w-100 rounded-top-2 d-flex align-items-center justify-content-center">
                        <i class="bi bi-building display-1 text-white-50"></i>
                    </div>
                    <span class="badge bg-success position-absolute top-0 end-0 m-3">New</span>
                </div>
                <div class="card-body p-4">
                    <h5 class="card-title fw-bold">Corporate Glass</h5>
                    <p class="text-secondary small mb-4">Professional glassmorphism design for hotels and offices.
                        Corporate branding ready.</p>
                    <button class="btn btn-outline-secondary w-100 rounded-pill"
                        onclick="alert('Downloading Corporate Template...')"><i class="bi bi-download me-2"></i>
                        Download ZIP</button>
                    <button
                        class="btn btn-sm btn-link text-secondary text-decoration-none d-block mx-auto mt-2">Preview</button>
                </div>
            </x-card>
        </div>
    </div>
@endsection
