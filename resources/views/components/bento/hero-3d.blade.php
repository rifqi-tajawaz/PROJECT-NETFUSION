@props([
    'title' => '',
    'subtitle' => '',
    'badge' => null,
    'badgeIcon' => null,
    'icon' => 'dashboard',
    'theme' => 'primary',
    'size' => 'lg',
])

@php
    $themes = [
        'primary' => [
            'bg' => 'rgba(95, 45, 237, 0.08)',
            'text' => '#5f2ded',
            'gradient' => 'linear-gradient(135deg, rgba(95, 45, 237, 0.15) 0%, rgba(95, 45, 237, 0.02) 100%)',
        ],
        'success' => [
            'bg' => 'rgba(25, 135, 84, 0.08)',
            'text' => '#198754',
            'gradient' => 'linear-gradient(135deg, rgba(25, 135, 84, 0.15) 0%, rgba(25, 135, 84, 0.02) 100%)',
        ],
        'warning' => [
            'bg' => 'rgba(255, 193, 7, 0.08)',
            'text' => '#ffc107',
            'gradient' => 'linear-gradient(135deg, rgba(255, 193, 7, 0.15) 0%, rgba(255, 193, 7, 0.02) 100%)',
        ],
        'info' => [
            'bg' => 'rgba(13, 202, 240, 0.08)',
            'text' => '#0dcaf0',
            'gradient' => 'linear-gradient(135deg, rgba(13, 202, 240, 0.15) 0%, rgba(13, 202, 240, 0.02) 100%)',
        ],
    ];
    $t = $themes[$theme] ?? $themes['primary'];
    $sizeClasses = [
        'sm' => 'bento-icon sm',
        'md' => 'bento-icon',
        'lg' => 'bento-icon lg',
        'xl' => 'bento-icon xl',
    ];
    $iconClass = $sizeClasses[$size] ?? 'bento-icon';
@endphp

<div class="bento-card bento-clay {{ $theme }} {{ $attributes->get('class') ?? '' }}">
    
    @if($badge)
        <div class="mb-3">
            <span class="bento-badge {{ $theme }}">
                @if($badgeIcon)
                    <span class="material-icons-outlined fs-6">{{ $badgeIcon }}</span>
                @endif
                {{ $badge }}
            </span>
        </div>
    @endif

    <div class="bento-flex-center mb-3">
        <div class="{!! $iconClass !!} {{ $theme }} bento-animate-float">
            <span class="material-icons-outlined">{{ $icon }}</span>
        </div>
    </div>

    <h2 class="bento-title text-center mb-2">{{ $title }}</h2>
    
    @if($subtitle)
        <p class="bento-body text-center">{{ $subtitle }}</p>
    @endif

    <div class="bento-glow {{ $theme }}"></div>

    {{ $slot ?? '' }}
</div>
