@props([
    'title' => '',
    'items' => [],
    'icon' => 'info',
    'theme' => 'primary',
])

@php
    $themes = [
        'primary' => ['gradient' => 'linear-gradient(135deg, rgba(95, 45, 237, 0.08) 0%, rgba(95, 45, 237, 0.02) 100%)', 'icon' => '#5f2ded'],
        'success' => ['gradient' => 'linear-gradient(135deg, rgba(25, 135, 84, 0.08) 0%, rgba(25, 135, 84, 0.02) 100%)', 'icon' => '#198754'],
        'warning' => ['gradient' => 'linear-gradient(135deg, rgba(255, 193, 7, 0.08) 0%, rgba(255, 193, 7, 0.02) 100%)', 'icon' => '#ffc107'],
        'info' => ['gradient' => 'linear-gradient(135deg, rgba(13, 202, 240, 0.08) 0%, rgba(13, 202, 240, 0.02) 100%)', 'icon' => '#0dcaf0'],
    ];
    $t = $themes[$theme] ?? $themes['primary'];
@endphp

<div class="bento-card {{ $theme }} {{ $attributes->get('class') ?? '' }}">
    
    <div class="bento-flex-center gap-3 mb-4">
        <div class="bento-icon {{ $theme }} bento-animate-pulse">
            <span class="material-icons-outlined">{{ $icon }}</span>
        </div>
        <h4 class="bento-title mb-0">{{ $title }}</h4>
    </div>

    @if(count($items) > 0)
        <ul class="bento-list md">
            @foreach($items as $item)
                <li>
                    <div class="bento-list-icon">
                        <span class="material-icons-outlined fs-6">{{ $item['icon'] ?? 'check_circle' }}</span>
                    </div>
                    <div class="flex-grow-1">
                        <span class="bento-subtitle">{{ $item['label'] }}</span>
                        @if(isset($item['description']))
                            <p class="bento-small mb-0">{{ $item['description'] }}</p>
                        @endif
                    </div>
                </li>
            @endforeach
        </ul>
    @endif

    <div class="bento-glow {{ $theme }}"></div>
</div>
