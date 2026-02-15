@props([
    'title' => '',
    'subtitle' => '',
    'badge' => null,
    'badgeIcon' => null,
    'actionLink' => null,
    'actionText' => '',
    'actionIcon' => 'arrow_back',
    'icons' => [],
])

@php
    $defaultIcons = [
        ['icon' => 'stars', 'color' => 'text-primary', 'pos' => 'top-0 start-0 m-4', 'anim' => 'animate-float-slow d-none d-md-block', 'size' => 'icon-md'],
        ['icon' => 'emoji_objects', 'color' => 'text-warning', 'pos' => 'top-0 end-0 m-5', 'anim' => 'animate-float', 'size' => 'icon-lg'],
        ['icon' => 'verified_user', 'color' => 'text-success', 'pos' => 'bottom-0 start-0 m-5 mb-4', 'anim' => 'animate-float d-none d-md-block', 'size' => 'icon-md'],
        ['icon' => 'support_agent', 'color' => 'text-info', 'pos' => 'bottom-0 end-0 m-4', 'anim' => 'animate-float-slow', 'size' => 'icon-lg'],
    ];
    $floatingIcons = !empty($icons) ? $icons : $defaultIcons;
@endphp

<div class="card rounded-3 border shadow mb-4 bg-white position-relative overflow-hidden support-hero-card">
    <div class="card-body p-4 p-lg-5 text-center position-relative z-index-1">

        {{-- Decorative Floating Icons --}}
        @foreach($floatingIcons as $fi)
            <div class="floating-icon {{ $fi['pos'] }} {{ $fi['anim'] }} {{ $fi['size'] }}">
                <span class="material-icons-outlined {{ $fi['color'] }}">{{ $fi['icon'] }}</span>
            </div>
        @endforeach

        @if($badge)
            <div class="mb-3">
                <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill fw-bold small">
                    @if($badgeIcon)<span class="material-icons-outlined me-1 fs-6">{{ $badgeIcon }}</span>@endif {{ $badge }}
                </span>
            </div>
        @endif

        <h2 class="display-3 fw-bolder mb-3 tracking-tight text-gradient-dark">{{ $title }}</h2>
        <p class="lead mb-4 text-secondary">{{ $subtitle }}</p>

        @if($actionLink && $actionText)
            <a href="{{ $actionLink }}" class="btn btn-outline-brand rounded-pill px-4 transition-hover">
                <span class="material-icons-outlined me-2">{{ $actionIcon }}</span> {{ $actionText }}
            </a>
        @endif
    </div>
</div>
