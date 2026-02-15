@props([
    'show' => false,
    'title' => '',
    'description' => '',
    'buttonText' => null,
    'buttonLink' => null,
])

@if($show)
    <tr>
        <td colspan="5" class="px-4 py-5 text-center">
            <div class="d-flex flex-column align-items-center justify-content-center py-4">
                <div class="mb-3 p-3 bg-light rounded-circle">
                    <span class="material-icons-outlined fs-1 text-secondary opacity-50">support_agent</span>
                </div>
                <h5 class="text-dark fw-bold mb-2">{{ $title }}</h5>
                <p class="text-secondary mb-4 col-md-6 mx-auto">
                    {{ $description }}
                </p>
                @if($buttonText && $buttonLink)
                    <a href="{{ $buttonLink }}"
                        class="btn btn-brand rounded-pill px-4 fw-bold shadow-sm">
                        <span class="material-icons-outlined me-2 fs-6">add</span> {{ $buttonText }}
                    </a>
                @endif
            </div>
        </td>
    </tr>
@endif
