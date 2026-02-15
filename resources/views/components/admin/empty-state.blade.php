@props([
    'icon' => 'inbox',
    'title' => '',
    'description' => '',
    'buttonText' => null,
    'buttonLink' => null,
])

<div class="empty-data-state">
    <span class="material-icons-outlined empty-icon">{{ $icon }}</span>
    <h6 class="mt-3">{{ $title }}</h6>
    <p>{{ $description }}</p>
    @if($buttonText && $buttonLink)
        <a href="{{ $buttonLink }}"
            class="btn btn-brand rounded-pill px-4 fw-bold shadow-sm mt-3">
            <span class="material-icons-outlined me-2 fs-6">add</span> {{ $buttonText }}
        </a>
    @endif
</div>
