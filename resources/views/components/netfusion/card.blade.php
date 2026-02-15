@props([
    'title' => null,
    'subtitle' => null,
    'icon' => null,
    'footer' => null,
    'className' => ''
])

<div {{ $attributes->merge(['class' => 'card rounded-4 shadow-sm ' . $className]) }}>
    @if($title || $icon)
        <div class="card-header bg-transparent border-0 pt-4 px-4">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    @if($icon)
                        <div class="badge-icon mb-2">
                            <i class="material-icons-outlined fs-5">{{ $icon }}</i>
                        </div>
                    @endif
                    @if($title)
                        <h5 class="mb-1 fw-bold text-body-emphasis">{{ $title }}</h5>
                    @endif
                    @if($subtitle)
                        <p class="mb-0 text-muted small">{{ $subtitle }}</p>
                    @endif
                </div>
            </div>
        </div>
    @endif

    <div class="card-body p-4">
        {{ $slot }}
    </div>

    @if($footer)
        <div class="card-footer bg-transparent border-top-0 px-4 pb-4">
            {{ $footer }}
        </div>
    @endif
</div>
