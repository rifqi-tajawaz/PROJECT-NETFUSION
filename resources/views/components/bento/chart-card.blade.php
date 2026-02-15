@props([
    'title' => '',
    'chartType' => 'line',
    'data' => [],
    'labels' => [],
    'icon' => 'show_chart',
    'theme' => 'primary',
    'height' => 200,
])

@php
    $themes = [
        'primary' => ['gradient' => 'linear-gradient(135deg, rgba(95, 45, 237, 0.08) 0%, rgba(95, 45, 237, 0.02) 100%)', 'color' => '#5f2ded'],
        'success' => ['gradient' => 'linear-gradient(135deg, rgba(25, 135, 84, 0.08) 0%, rgba(25, 135, 84, 0.02) 100%)', 'color' => '#198754'],
        'warning' => ['gradient' => 'linear-gradient(135deg, rgba(255, 193, 7, 0.08) 0%, rgba(255, 193, 7, 0.02) 100%)', 'color' => '#ffc107'],
        'info' => ['gradient' => 'linear-gradient(135deg, rgba(13, 202, 240, 0.08) 0%, rgba(13, 202, 240, 0.02) 100%)', 'color' => '#0dcaf0'],
    ];
    $t = $themes[$theme] ?? $themes['primary'];
    $chartId = 'chart_' . uniqid();
@endphp

<div class="bento-card {{ $theme }} {{ $attributes->get('class') ?? '' }}">
    
    <div class="bento-flex-between mb-4">
        <div>
            <h4 class="bento-title mb-0">{{ $title }}</h4>
        </div>
        <div class="bento-icon {{ $theme }} sm">
            <span class="material-icons-outlined">{{ $icon }}</span>
        </div>
    </div>

    <div class="bento-glow {{ $theme }}"></div>
</div>
