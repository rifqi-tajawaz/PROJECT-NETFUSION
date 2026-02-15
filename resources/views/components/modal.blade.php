@props([
    'id',
    'title',
    'size' => 'md', // sm, md, lg, xl
    'centered' => true,
    'scrollable' => false,
    'formAction' => null
])

<div class="modal fade" id="{{ $id }}" tabindex="-1" aria-labelledby="{{ $id }}Label" aria-hidden="true">
    <div class="modal-dialog modal-{{ $size }} {{ $centered ? 'modal-dialog-centered' : '' }} {{ $scrollable ? 'modal-dialog-scrollable' : '' }}">
        <div class="modal-content overflow-hidden border-0 shadow-lg rounded-4">
            
            {{-- Header --}}
            <div class="modal-header border-bottom-0 bg-light-subtle px-4 py-3">
                <h5 class="modal-title fw-bold text-dark" id="{{ $id }}Label">{{ $title }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
    
                {{-- Body wrapped in form if action provided --}}
                @if($formAction)
                    <form action="{{ $formAction }}" method="POST">
                            @csrf
                @endif

            <div class="modal-body px-4 py-4">
                {{ $slot }}
                </div>

                {{-- Footer --}}
            @if(isset($footer))
                        <div class="modal-footer border-top-0 bg-light-subtle px-4 py-3">
                {{ $footer }}
                        </div>
            @endif

            @if($formAction)
                </form>
            @endif
            
        </div>
    </div>
</div>
