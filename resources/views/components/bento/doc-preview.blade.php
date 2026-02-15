@props([
    'doc' => null,
    'compact' => false,
])

@php
    $cardSize = $compact ? 'bento-1x1' : 'bento-2x1';
    $categoryIcon = 'menu_book';
    if($doc->category) {
        $categoryIcon = $doc->category->icon ?? 'menu_book';
    }
@endphp

<div class="bento-card clickable {{ $cardSize }} {{ $attributes->get('class') ?? '' }}"
     data-href="{!! route('documentation.view', $doc->slug) !!}">
    
    <div class="bento-decoration top-right">
        <span class="material-icons-outlined" style="font-size: 5rem; color: #5f2ded; opacity: 0.08;">
            {{ $categoryIcon }}
        </span>
    </div>

    <div class="bento-flex-between mb-3">
        <div class="bento-icon primary sm bento-animate-float">
            <span class="material-icons-outlined">{{ $categoryIcon }}</span>
        </div>
        @if($doc->is_published)
            <span class="bento-badge success">Published</span>
        @else
            <span class="bento-badge secondary">Draft</span>
        @endif
    </div>

    <h4 class="bento-title mb-2">{{ Str::limit($doc->title, $compact ? 25 : 40) }}</h4>
    
    @if(!$compact)
        <p class="bento-body mb-3">{{ Str::limit($doc->excerpt ?? strip_tags($doc->content), 80) }}</p>
        
        <div class="bento-flex-between">
            <span class="bento-badge primary">
                <span class="material-icons-outlined fs-6">folder</span>
                {{ $doc->category }}
            </span>
            <span class="bento-small text-secondary">
                <span class="material-icons-outlined fs-6">schedule</span>
                {{ $doc->updated_at->diffForHumans() }}
            </span>
        </div>
    @endif

    <div class="bento-glow primary"></div>
</div>
