@props([
    'title' => '',
    'value' => '',
    'trend' => null,
    'trendValue' => null,
    'icon' => 'trending_up',
    'theme' => 'primary',
])

@php
    $themes = [
        'primary' => ['bg' => 'bg-primary bg-opacity-10', 'text' => 'text-primary', 'icon' => '#5f2ded'],
        'success' => ['bg' => 'bg-success bg-opacity-10', 'text' => 'text-success', 'icon' => '#198754'],
        'warning' => ['bg' => 'bg-warning bg-opacity-10', 'text' => 'text-warning', 'icon' => '#ffc107'],
        'danger' => ['bg' => 'bg-danger bg-opacity-10', 'text' => 'text-danger', 'icon' => '#dc3545'],
        'info' => ['bg' => 'bg-info bg-opacity-10', 'text' => 'text-info', 'icon' => '#0dcaf0'],
    ];
    $t = $themes[$theme] ?? $themes['primary'];
    $trendUp = $trend === 'up' || $trend === 'increase';
    $trendDown = $trend === 'down' || $trend === 'decrease';
@endphp

<div class="bento-card {{ $attributes->get('class') ?? '' }}">
    <div class="bento-flex-between mb-3">
        <div>
            <h6 class="bento-small text-uppercase mb-1">{{ $title }}</h6>
            <h3 class="bento-title mb-0">{{ $value }}</h3>
        </div>
        <div class="bento-icon {{ $t['bg'] }} {{ $t['text'] }}">
            <span class="material-icons-outlined">{{ $icon }}</span>
        </div>
    </div>

    @if($trend)
        <div class="d-flex align-items-center gap-2">
            <span class="bento-badge {{ $trendUp ? 'success' : ($trendDown ? 'danger' : 'info') }}">
                @if($trendUp)
                    <span class="material-icons-outlined fs-6">trending_up</span>
                @elseif($trendDown)
                    <span class="material-icons-outlined fs-6">trending_down</span>
                @else
                    <span class="material-icons-outlined fs-6">trending_flat</span>
                @endif
                @if($trendValue) {{ $trendValue }} @endif
            </span>
            <span class="bento-small">{{ $trendUp ? 'vs last month' : ($trendDown ? 'vs last month' : 'vs last month') }}</span>
        </div>
    @endif

    <div class="bento-glow {{ $theme }}"></div>
</div>
