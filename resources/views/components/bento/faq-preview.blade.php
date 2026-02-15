@props([
    'faq' => null,
    'compact' => false,
])

@php
    $cardSize = $compact ? 'bento-1x1' : 'bento-1x2';
    $statusColor = $faq->is_active ? 'success' : 'secondary';
@endphp

<div class="bento-card clickable {{ $cardSize }} {{ $attributes->get('class') ?? '' }}"
     onclick="window.location.href='#faq-{{ $faq->id }}'">
    
    <div class="bento-decoration top-right">
        <span class="material-icons-outlined" style="font-size: 5rem; color: #198754; opacity: 0.08;">
            help_outline
        </span>
    </div>

    <div class="bento-flex-between mb-3">
        <div class="bento-icon {{ $statusColor }} sm bento-animate-pulse">
            <span class="material-icons-outlined">help_outline</span>
        </div>
        @if($faq->is_active)
            <span class="bento-badge {{ $statusColor }}">Active</span>
        @else
            <span class="bento-badge {{ $statusColor }}">Inactive</span>
        @endif
    </div>

    <h4 class="bento-title mb-2">{{ Str::limit($faq->question, $compact ? 25 : 40) }}</h4>
    
    @if(!$compact)
        <p class="bento-body bento-text-clamp-2 mb-3">{{ Str::limit(strip_tags($faq->answer), 100) }}</p>
        
        <div class="bento-flex-between">
            <span class="bento-badge primary">
                <span class="material-icons-outlined fs-6">category</span>
                {{ $faq->category }}
            </span>
            <span class="bento-small text-secondary">
                <span class="material-icons-outlined fs-6">schedule</span>
                {{ $faq->updated_at->diffForHumans() }}
            </span>
        </div>
    @endif

    <div class="bento-glow {{ $statusColor }}"></div>
</div>
