@props([
    'title' => '',
    'items' => [],
    'icon' => 'list',
    'theme' => 'primary',
    'size' => '2x2',
    'emptyMessage' => 'No items found',
])

@php
    $themes = [
        'primary' => ['gradient' => 'linear-gradient(135deg, rgba(95, 45, 237, 0.06) 0%, rgba(95, 45, 237, 0.01) 100%)', 'icon' => '#5f2ded'],
        'success' => ['gradient' => 'linear-gradient(135deg, rgba(25, 135, 84, 0.06) 0%, rgba(25, 135, 84, 0.01) 100%)', 'icon' => '#198754'],
        'warning' => ['gradient' => 'linear-gradient(135deg, rgba(255, 193, 7, 0.06) 0%, rgba(255, 193, 7, 0.01) 100%)', 'icon' => '#ffc107'],
        'info' => ['gradient' => 'linear-gradient(135deg, rgba(13, 202, 240, 0.06) 0%, rgba(13, 202, 240, 0.01) 100%)', 'icon' => '#0dcaf0'],
    ];
    $t = $themes[$theme] ?? $themes['primary'];
@endphp

<div class="bento-card bento-{{ $size }} {{ $theme }} {{ $attributes->get('class') ?? '' }}">
    
    <div class="bento-decoration top-right">
        <span class="bento-decoration-icon {{ $theme }}">
            {{ $icon }}
        </span>
    </div>

    <div class="bento-flex-between mb-3">
        <div class="d-flex align-items-center gap-2">
            <div class="bento-icon {{ $theme }} sm">
                <span class="material-icons-outlined">{{ $icon }}</span>
            </div>
            <h4 class="bento-title mb-0">{{ $title }}</h4>
        </div>
        <span class="bento-badge {{ $theme }}">
            {{ count($items) }} items
        </span>
    </div>

    @if(count($items) > 0)
        <ul class="bento-list sm bento-overflow-auto">
            @foreach($items as $item)
                <li class="clickable" @if(isset($item['link'])) data-link="{!! $item['link'] !!}" @endif>
                    <div class="bento-list-icon">
                        <span class="material-icons-outlined fs-6">{{ $item['icon'] ?? 'check_circle' }}</span>
                    </div>
                    <div class="flex-grow-1">
                        <span class="bento-subtitle">{{ $item['label'] }}</span>
                        @if(isset($item['meta']))
                            <p class="bento-small mb-0">{{ $item['meta'] }}</p>
                        @endif
                    </div>
                    @if(isset($item['action']))
                        <a href="{!! $item['action'] !!}" class="btn btn-icon ms-auto">
                            <span class="material-icons-outlined fs-6">chevron_right</span>
                        </a>
                    @endif
                </li>
            @endforeach
        </ul>
    @else
        <div class="bento-flex-center flex-column h-100 text-center">
            <span class="bento-empty-icon {{ $theme }}">
                inbox
            </span>
            <p class="bento-body">{{ $emptyMessage }}</p>
        </div>
    @endif

    <div class="bento-glow {{ $theme }}"></div>
</div>