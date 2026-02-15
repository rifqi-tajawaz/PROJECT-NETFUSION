@props(['title', 'subtitle' => null, 'breadcrumbs' => []])

<div
    class="page-breadcrumb d-flex flex-column flex-sm-row align-items-center align-items-sm-center mb-4 gap-3 pt-3 pt-sm-0">

    {{-- Title & Subtitle Section --}}
    <div class="d-flex flex-column flex-grow-1 text-center text-sm-start">
        <h5
            class="mb-0 text-dark fw-bold d-flex align-items-center justify-content-center justify-content-sm-start gap-2">
            {{ $title }}
        </h5>
        @if($subtitle)
            <p class="mb-0 text-secondary small mt-1" style="max-width: 500px;" title="{{ $subtitle }}">
                {{ $subtitle }}
            </p>
        @endif
    </div>

    {{-- Breadcrumbs Section --}}
    @if(count($breadcrumbs) > 0)
        <div class="ps-0 ps-sm-3 ms-auto order-last order-sm-0">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0 bg-transparent">
                    @foreach($breadcrumbs as $label => $link)
                        @if($loop->last)
                            <li class="breadcrumb-item active text-primary" aria-current="page">{{ $label }}</li>
                        @else
                            <li class="breadcrumb-item">
                                <a href="{{ $link }}" class="text-secondary text-decoration-none">{{ $label }}</a>
                            </li>
                        @endif
                    @endforeach
                </ol>
            </nav>
        </div>
    @endif


    {{-- Action Slot --}}
    @if(isset($action))
        <div class="ms-sm-3 mt-3 mt-sm-0">
            {{ $action }}
        </div>
    @endif
</div>
