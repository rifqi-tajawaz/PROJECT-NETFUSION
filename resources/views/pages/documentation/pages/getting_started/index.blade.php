{{-- Breadcrumb --}}
<nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('documentation.show', 'index') }}"
                class="text-primary text-decoration-none">Docs</a></li>
        <li class="breadcrumb-item active" aria-current="page">Introduction</li>
    </ol>
</nav>

{{-- Content Header --}}
<div class="d-flex align-items-center mb-5">
    <div
        class="d-inline-flex align-items-center justify-content-center bg-primary bg-opacity-10 text-primary rounded-4 me-4 p-3 shadow-sm">
        <span class="material-icons-outlined fs-1">waving_hand</span>
    </div>
    <div>
        <h2 class="fw-bold text-dark mb-2">Welcome to the Docs</h2>
        <p class="text-muted mb-0 lead">Your creative toolbox for network management excellence.</p>
    </div>
</div>

<hr class="border-secondary opacity-10 my-5">

{{-- Content Section: Quick Start Grid --}}
<h5 class="fw-bold mb-4">Quick Start Guide</h5>
<div class="row row-cols-1 row-cols-md-2 g-4">
    <div class="col">
        <div class="card h-100 border rounded-4 hover-shadow transition-300 cursor-pointer bg-light border-0">
            <div class="card-body p-4">
                <div class="mb-3 text-primary">
                    <span class="material-icons-outlined fs-2">rocket_launch</span>
                </div>
                <h5 class="fw-bold">Getting Started</h5>
                <p class="text-muted small mb-0">Learn the basics of navigating the dashboard and
                    setting up your first Mikrotik connection.</p>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="card h-100 border rounded-4 hover-shadow transition-300 cursor-pointer bg-light border-0">
            <div class="card-body p-4">
                <div class="mb-3 text-success">
                    <span class="material-icons-outlined fs-2">shield</span>
                </div>
                <h5 class="fw-bold">Security Best Practices</h5>
                <p class="text-muted small mb-0">Understand how to use our System Hardening tools to
                    protect your network infrastructure.</p>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="card h-100 border rounded-4 hover-shadow transition-300 cursor-pointer bg-light border-0">
            <div class="card-body p-4">
                <div class="mb-3 text-warning">
                    <span class="material-icons-outlined fs-2">code</span>
                </div>
                <h5 class="fw-bold">Script Generators</h5>
                <p class="text-muted small mb-0">Master the art of generating automated scripts for
                    Queue, Hotspot, and PCC Load Balancing.</p>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="card h-100 border rounded-4 hover-shadow transition-300 cursor-pointer bg-light border-0">
            <div class="card-body p-4">
                <div class="mb-3 text-info">
                    <span class="material-icons-outlined fs-2">api</span>
                </div>
                <h5 class="fw-bold">API Reference</h5>
                <p class="text-muted small mb-0">Technical details for integrating with the Mikrotik API
                    and handling responses.</p>
            </div>
        </div>
    </div>
</div>

{{-- Callout --}}
<div
    class="mt-5 p-4 rounded-4 bg-primary bg-opacity-10 border border-primary border-opacity-10 d-flex align-items-start">
    <span class="material-icons-outlined text-primary me-3 mt-1">tips_and_updates</span>
    <div>
        <h6 class="fw-bold text-primary mb-1">Coming Soon</h6>
        <p class="text-dark opacity-75 small mb-0">
            We are working hard to document every single tool in this suite.
            In the meantime, if you have questions, please use the <a href="{{ route('support') }}"
                class="fw-bold text-primary text-decoration-underline">Support Page</a>.
        </p>
    </div>
</div>