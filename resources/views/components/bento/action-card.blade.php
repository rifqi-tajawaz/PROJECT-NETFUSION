@props([
    'title' => '',
    'description' => '',
    'icon' => 'rocket_launch',
    'btnText' => 'Get Started',
    'btnLink' => '#',
    'btnTheme' => 'primary',
    'theme' => 'primary',
    'badge' => null,
])

@php
    $themes = [
        'primary' => ['gradient' => 'linear-gradient(135deg, rgba(95, 45, 237, 0.1) 0%, rgba(95, 45, 237, 0.02) 100%)', 'icon' => '#5f2ded'],
        'success' => ['gradient' => 'linear-gradient(135deg, rgba(25, 135, 84, 0.1) 0%, rgba(25, 135, 84, 0.02) 100%)', 'icon' => '#198754'],
        'warning' => ['gradient' => 'linear-gradient(135deg, rgba(255, 193, 7, 0.1) 0%, rgba(255, 193, 7, 0.02) 100%)', 'icon' => '#ffc107'],
        'info' => ['gradient' => 'linear-gradient(135deg, rgba(13, 202, 240, 0.1) 0%, rgba(13, 202, 240, 0.02) 100%)', 'icon' => '#0dcaf0'],
    ];
    $t = $themes[$theme] ?? $themes['primary'];
@endphp

<div class="bento-card clickable {{ $theme }} {{ $attributes->get('class') ?? '' }}">
    
    <div class="bento-decoration top-right">
        <span class="bento-decoration-icon {{ $theme }}">
            {{ $icon }}
        </span>
    </div>

    <div class="bento-flex-center flex-column h-100">
        <div class="bento-icon {{ $theme }} lg mb-3">
            <span class="material-icons-outlined">{{ $icon }}</span>
        </div>
        
        @if($badge)
            <span class="bento-badge {{ $theme }} mb-2">{{ $badge }}</span>
        @endif

        <h4 class="bento-title text-center mb-2">{{ $title }}</h4>
        <p class="bento-body text-center mb-4">{{ $description }}</p>
        
        <a href="{!! $btnLink !!}" class="bento-btn {{ $btnTheme }}">
            {{ $btnText }}
            <span class="material-icons-outlined fs-6">arrow_forward</span>
        </a>
    </div>

    <div class="bento-glow {{ $theme }}"></div>
</div>
