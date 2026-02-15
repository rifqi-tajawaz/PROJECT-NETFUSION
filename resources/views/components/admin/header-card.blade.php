@props([
    'title' => '',
    'subtitle' => '',
    'icon' => 'dashboard',
    'badge' => null,
    'badgeIcon' => null,
])

<div class="card admin-header-card shadow-sm">
    <div class="card-body text-center">
        @if($badge)
            <div class="mb-3">
                <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill fw-bold small">
                    @if($badgeIcon)<span class="material-icons-outlined me-1 fs-6">{{ $badgeIcon }}</span>@endif {{ $badge }}
                </span>
            </div>
        @endif

        <div class="position-relative d-inline-block">
            <span class="material-icons-outlined position-absolute top-0 start-0 translate-middle">{{ $icon }}</span>
            <h4 class="mb-2 position-relative">{{ $title }}</h4>
        </div>
        @if($subtitle)
            <p class="mb-0">{{ $subtitle }}</p>
        @endif
    </div>
</div>
