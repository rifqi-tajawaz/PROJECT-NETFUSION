@props([
    'placeholder' => 'Search...',
    'action' => '',
    'method' => 'GET',
    'theme' => 'primary',
    'icon' => 'search',
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

<div class="bento-card bento-1x1 {{ $theme }} {{ $attributes->get('class') ?? '' }}">
    
    <div class="bento-decoration top-right">
        <span class="bento-decoration-icon {{ $theme }}">
            {{ $icon }}
        </span>
    </div>

    <div class="bento-flex-center flex-column h-100">
        <div class="bento-icon {{ $theme }} mb-3 bento-animate-pulse">
            <span class="material-icons-outlined">{{ $icon }}</span>
        </div>
        
        <form action="{!! $action !!}" method="{{ $method }}" class="w-100">
            <div class="position-relative">
                <input type="text"
                    name="search"
                    class="bento-input ps-5 {{ $theme }}"
                    placeholder="{!! $placeholder !!}"
                    value="{{ request('search') ?? '' }}">
                <span class="material-icons-outlined position-absolute top-50 start-0 translate-middle-y ms-3 bento-search-icon {{ $theme }}">
                    {{ $icon }}
                </span>
            </div>
        </form>
    </div>

    <div class="bento-glow {{ $theme }}"></div>
</div>
