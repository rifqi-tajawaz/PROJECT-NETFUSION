@props([
    'icon' => 'help_outline',
    'title' => '',
    'description' => '',
    'buttonText' => null,
    'buttonLink' => null,
    'show' => false,
])

@if($show)
    <div class="d-flex flex-column align-items-center justify-content-center h-100 text-center p-5">
        <div class="mb-4 p-4 bg-light rounded-circle shadow-sm">
            <span class="material-icons-outlined fs-1 text-primary">{{ $icon }}</span>
        </div>
        <h3 class="fw-bold mb-3 text-dark">{{ $title }}</h3>
        <p class="lead text-secondary mb-4">
            {{ $description }}
        </p>
        @if($buttonText && $buttonLink)
            <a href="{{ $buttonLink }}" class="btn btn-brand rounded-pill px-5 py-3 d-flex align-items-center gap-2 transition-hover shadow-brand glow-effect">
                {{ $buttonText }}
            </a>
        @endif
    </div>
@endif
