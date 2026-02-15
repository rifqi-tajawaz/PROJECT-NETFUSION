@props([
    'title' => '',
    'description' => '',
    'icon' => 'star',
    'theme' => 'primary',
    'size' => '2x1',
    'clickable' => false,
])

@php
    $themes = [
        'primary' => ['gradient' => 'linear-gradient(135deg, rgba(95, 45, 237, 0.1) 0%, rgba(95, 45, 237, 0.02) 100%)', 'icon' => '#5f2ded'],
        'success' => ['gradient' => 'linear-gradient(135deg, rgba(25, 135, 84, 0.1) 0%, rgba(25, 135, 84, 0.02) 100%)', 'icon' => '#198754'],
        'warning' => ['gradient' => 'linear-gradient(135deg, rgba(255, 193, 7, 0.1) 0%, rgba(255, 193, 7, 0.02) 100%)', 'icon' => '#ffc107'],
        'info' => ['gradient' => 'linear-gradient(135deg, rgba(13, 202, 240, 0.1) 0%, rgba(13, 202, 240, 0.02) 100%)', 'icon' => '#0dcaf0'],
    ];
    $t = $themes[$theme] ?? $themes['primary'];
    $clickableClass = $clickable ? 'clickable' : '';
@endphp

<div class="bento-card {{ $size }} {{ $theme }} {{ $clickableClass }} {{ $attributes->get('class') ?? '' }}">
    
    <div class="bento-decoration top-right">
        <span class="bento-decoration-icon {{ $theme }}">
            {{ $icon }}
        </span>
    </div>

    <div class="bento-icon {{ $theme }} mb-3 bento-animate-float">
        <span class="material-icons-outlined">{{ $icon }}</span>
    </div>

    <h4 class="bento-title mb-2">{{ $title }}</h4>
    <p class="bento-body">{{ $description }}</p>

    <div class="bento-glow {{ $theme }}"></div>
</div>
