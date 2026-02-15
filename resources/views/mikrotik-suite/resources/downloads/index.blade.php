@extends('layouts.app')

@section('content')
    <div class="container-fluid px-4 py-4">
        <div class="mb-4 d-flex flex-column align-items-center justify-content-center text-center">
            <h2 class="display-6 fw-bold mb-2 text-white" style="text-shadow: 0 2px 10px rgba(0,0,0,0.3);">
                <i class="bi bi-cloud-arrow-down me-2 text-primary"></i> Downloads Center
            </h2>
            <p class="lead opacity-75 mb-0" style="max-width: 700px;">
                Essential tools for MikroTik management and configuration.
            </p>
        </div>

        <div class="row g-4">
            <!-- Winbox -->
            <div class="col-md-6 col-lg-4">
                <div class="glass-card h-100 hover-scale text-center p-4">
                    <div class="mb-3 text-primary">
                        <i class="bi bi-window-desktop display-4"></i>
                    </div>
                    <h5 class="text-white fw-bold">Winbox</h5>
                    <p class="text-white-50 small mb-4">The ultimate GUI management tool for RouterOS.</p>
                    <div class="d-flex justify-content-center gap-2">
                        <a href="https://mt.lv/winbox64" target="_blank" class="btn btn-sm btn-outline-light">64-bit</a>
                        <a href="https://mt.lv/winbox" target="_blank" class="btn btn-sm btn-outline-light">32-bit</a>
                    </div>
                </div>
            </div>

            <!-- Netinstall -->
            <div class="col-md-6 col-lg-4">
                <div class="glass-card h-100 hover-scale text-center p-4">
                    <div class="mb-3 text-danger">
                        <i class="bi bi-tools display-4"></i>
                    </div>
                    <h5 class="text-white fw-bold">Netinstall</h5>
                    <p class="text-white-50 small mb-4">Recovery tool for re-flashing RouterOS.</p>
                    <div class="d-flex justify-content-center gap-2">
                        <a href="https://mikrotik.com/download" target="_blank"
                            class="btn btn-sm btn-outline-light">Download</a>
                    </div>
                </div>
            </div>

            <!-- The Dude -->
            <div class="col-md-6 col-lg-4">
                <div class="glass-card h-100 hover-scale text-center p-4">
                    <div class="mb-3 text-success">
                        <i class="bi bi-diagram-2 display-4"></i>
                    </div>
                    <h5 class="text-white fw-bold">The Dude</h5>
                    <p class="text-white-50 small mb-4">Network monitoring application.</p>
                    <div class="d-flex justify-content-center gap-2">
                        <a href="https://mikrotik.com/download" target="_blank"
                            class="btn btn-sm btn-outline-light">Download</a>
                    </div>
                </div>
            </div>

            <!-- BTest -->
            <div class="col-md-6 col-lg-4">
                <div class="glass-card h-100 hover-scale text-center p-4">
                    <div class="mb-3 text-info">
                        <i class="bi bi-speedometer display-4"></i>
                    </div>
                    <h5 class="text-white fw-bold">Bandwidth Test</h5>
                    <p class="text-white-50 small mb-4">Windows tool for throughput testing.</p>
                    <div class="d-flex justify-content-center gap-2">
                        <a href="https://mikrotik.com/download" target="_blank"
                            class="btn btn-sm btn-outline-light">Download</a>
                    </div>
                </div>
            </div>

            <!-- Mobile Apps -->
            <div class="col-md-6 col-lg-8">
                <div class="glass-card h-100 text-center p-4">
                    <div class="mb-3 text-warning">
                        <i class="bi bi-phone display-4"></i>
                    </div>
                    <h5 class="text-white fw-bold">Mobile Apps</h5>
                    <p class="text-white-50 small mb-4">Manage your router on the go.</p>
                    <div class="d-flex justify-content-center gap-3">
                        <a href="https://play.google.com/store/apps/details?id=com.mikrotik.android.tikapp" target="_blank"
                            class="btn btn-sm btn-outline-warning"><i class="bi bi-google-play"></i> Android</a>
                        <a href="https://apps.apple.com/us/app/mikrotik/id1323064830" target="_blank"
                            class="btn btn-sm btn-outline-light"><i class="bi bi-apple"></i> iOS</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
