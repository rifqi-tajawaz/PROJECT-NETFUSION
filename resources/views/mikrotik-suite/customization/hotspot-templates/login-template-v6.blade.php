@extends('layouts.app')

@section('content')
    <div class="container-fluid px-4 py-4">
        <div class="mb-4 d-flex flex-column align-items-center justify-content-center text-center">
            <h2 class="display-6 fw-bold mb-2 text-white" style="text-shadow: 0 2px 10px rgba(0,0,0,0.3);">
                <i class="bi bi-shop-window me-2 text-primary"></i> RouterOS v6 Templates
            </h2>
            <p class="lead opacity-75 mb-0" style="max-width: 700px;">
                Legacy responsive login pages optimized for RouterOS 6.x.
            </p>
        </div>

        <div class="row g-4 justify-content-center">
            <!-- Template Item 1 -->
            <div class="col-md-4">
                <div class="glass-card h-100">
                    <div class="position-relative">
                        <div style="height: 180px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);"
                            class="w-100 rounded-top-4 d-flex align-items-center justify-content-center">
                            <i class="bi bi-window display-1 text-white-50"></i>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <h5 class="text-white fw-bold">Metro Tile</h5>
                        <p class="text-white-50 small mb-4">Windows 8 style tiles for easy touch navigation. Ideal for
                            mobile users.</p>
                        <button class="btn btn-outline-light w-100 rounded-pill"><i class="bi bi-download me-2"></i>
                            Download ZIP</button>
                    </div>
                </div>
            </div>

            <!-- Template Item 2 -->
            <div class="col-md-4">
                <div class="glass-card h-100">
                    <div class="position-relative">
                        <div style="height: 180px; background: linear-gradient(to top, #09203f 0%, #537895 100%);"
                            class="w-100 rounded-top-4 d-flex align-items-center justify-content-center">
                            <i class="bi bi-building display-1 text-white-50"></i>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <h5 class="text-white fw-bold">Classic Corporate</h5>
                        <p class="text-white-50 small mb-4">A simple, fast-loading grey theme for office environments.</p>
                        <button class="btn btn-outline-light w-100 rounded-pill"><i class="bi bi-download me-2"></i>
                            Download ZIP</button>
                    </div>
                </div>
            </div>

            <!-- Template Item 3 -->
            <div class="col-md-4">
                <div class="glass-card h-100">
                    <div class="position-relative">
                        <div style="height: 180px; background: linear-gradient(to right, #43e97b 0%, #38f9d7 100%);"
                            class="w-100 rounded-top-4 d-flex align-items-center justify-content-center">
                            <i class="bi bi-cup-straw display-1 text-white-50"></i>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <h5 class="text-white fw-bold">Green Cafe</h5>
                        <p class="text-white-50 small mb-4">Eco-friendly aesthetics with voucher code prioritization.</p>
                        <button class="btn btn-outline-light w-100 rounded-pill"><i class="bi bi-download me-2"></i>
                            Download ZIP</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
