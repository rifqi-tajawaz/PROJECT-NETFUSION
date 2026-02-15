@props([
    'title' => '',
    'channels' => [],
    'icon' => 'contact_phone',
    'theme' => 'primary',
    'size' => '2x1',
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

<div class="bento-card bento-{{ $size }} {{ $theme }} {{ $attributes->get('class') ?? '' }}">
    
    <div class="bento-decoration top-right">
        <span class="bento-decoration-icon {{ $theme }}">
            {{ $icon }}
        </span>
    </div>

    <div class="bento-flex-center gap-3 mb-4">
        <div class="bento-icon {{ $theme }} bento-animate-float">
            <span class="material-icons-outlined">{{ $icon }}</span>
        </div>
        <h4 class="bento-title mb-0">{{ $title }}</h4>
    </div>

    @if(count($channels) > 0)
        <div class="d-flex flex-column gap-2">
            @foreach($channels as $channel)
                <a href="{!! $channel['url'] !!}"
                    class="bento-channel-link {{ $theme }}">
                    
                    <div class="bento-icon {{ $channel['theme'] ?? $theme }} sm">
                        <span class="material-icons-outlined">{{ $channel['icon'] }}</span>
                    </div>
                    <div class="flex-grow-1">
                        <span class="bento-subtitle">{{ $channel['label'] }}</span>
                        <p class="bento-small mb-0">{{ $channel['value'] }}</p>
                    </div>
                    <span class="material-icons-outlined text-secondary">chevron_right</span>
                </a>
            @endforeach
        </div>
    @endif

    <div class="bento-glow {{ $theme }}"></div>
</div>
