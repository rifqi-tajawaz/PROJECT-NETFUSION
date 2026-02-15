@foreach($items as $slug => $item)
    @php
        $isGroup = is_array($item) && isset($item['items']);
        $isActive = $slug === $currentSlug;
        // Check if group contains active item (recursive check)
        $hasActiveChild = false;
        if ($isGroup) {
            $iterator = new RecursiveIteratorIterator(new RecursiveArrayIterator($item['items']));
            foreach ($iterator as $key => $value) {
                if ($key === $currentSlug) {
                    $hasActiveChild = true;
                    break;
                }
            }
        }
    @endphp

    @if($isGroup)
        {{-- Accordion Group --}}
        <div class="accordion-item border-0 bg-transparent ps-2">
            <h2 class="accordion-header" id="heading-{{ $slug }}">
                <button
                    class="accordion-button shadow-none bg-transparent px-3 py-2 btn-sm {{ $hasActiveChild ? 'text-primary fw-bold' : 'collapsed text-secondary' }}"
                    type="button" data-bs-toggle="collapse" data-bs-target="#collapse-{{ $slug }}"
                    aria-expanded="{{ $hasActiveChild ? 'true' : 'false' }}" aria-controls="collapse-{{ $slug }}"
                    style="font-size: 0.8rem;">
                    <span class="opacity-75 me-2">•</span>
                    {{ $item['title'] }}
                    <i class="bi bi-chevron-left ms-auto transition-transform {{ $hasActiveChild ? 'rotate-down' : '' }}"
                        style="font-size: 0.7rem;"></i>
                </button>
            </h2>
            <div id="collapse-{{ $slug }}" class="accordion-collapse collapse {{ $hasActiveChild ? 'show' : '' }}"
                aria-labelledby="heading-{{ $slug }}">
                <div class="accordion-body p-0 ps-3">
                    {{-- Recursive Call --}}
                    @include('pages.documentation.partials.sidebar_item', ['items' => $item['items'], 'currentSlug' => $currentSlug])
                </div>
            </div>
        </div>
    @else
        {{-- Single Item --}}
        <a href="{{ route('documentation.show', $slug) }}"
            class="list-group-item list-group-item-action border-0 px-3 py-2 d-flex align-items-center rounded-2 mb-1 {{ $isActive ? 'bg-primary bg-opacity-10 text-primary fw-bold' : 'text-secondary hover-bg-light' }}">
            <i class="bi bi-circle-fill me-3 {{ $isActive ? 'text-primary' : 'text-secondary opacity-25' }}"
                style="font-size: 5px; margin-left: 0.5rem;"></i>
            <span style="font-size: 0.8rem;">{{ $item }}</span>
        </a>
    @endif
@endforeach