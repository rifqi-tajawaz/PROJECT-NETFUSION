@props([
    'show' => false,
    'title' => '',
    'description' => '',
    'buttonText' => null,
    'buttonLink' => null,
])

@if($show)
    <tr>
        <td colspan="5" class="px-4 py-5">
            <div class="empty-data-state">
                <span class="material-icons-outlined empty-icon">description</span>
                <h6 class="mt-3">{{ $title }}</h6>
                <p>{{ $description }}</p>
                @if($buttonText && $buttonLink)
                    <a href="{{ $buttonLink }}"
                        class="btn btn-brand rounded-pill px-4 fw-bold shadow-sm mt-3">
                        <span class="material-icons-outlined me-2 fs-6">add</span> {{ $buttonText }}
                    </a>
                @endif
            </div>
        </td>
    </tr>
@endif
