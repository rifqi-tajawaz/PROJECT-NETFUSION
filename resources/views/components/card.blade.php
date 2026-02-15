@props(['title' => null, 'class' => '', 'bodyClass' => '', 'headerAction' => null])

<div {{ $attributes->merge(['class' => 'card border-0 shadow-sm rounded-4 overflow-hidden ' . $class]) }}>
    @if($title || $headerAction)
        <div
            class="card-header bg-transparent border-bottom border-light px-4 py-3 d-flex align-items-center justify-content-between">
            @if($title)
                <h5 class="mb-0 fw-bold text-dark">{{ $title }}</h5>
            @endif

            @if($headerAction)
                <div>
                    {{ $headerAction }}
                </div>
            @endif
        </div>
    @endif

    <div class="card-body {{ $bodyClass }}">
        {{ $slot }}
    </div>

    @if(isset($footer))
        <div class="card-footer bg-transparent border-top border-light px-4 py-3">
            {{ $footer }}
        </div>
    @endif
</div>
