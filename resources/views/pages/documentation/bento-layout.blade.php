@extends('layouts.app')

@push('css')
    @vite(['resources/sass/pages/documentation.scss'])
@endpush

@section('title')
    {{ $pageTitle ?? 'Documentation' }}
@endsection

@section('content')

    {{-- Hero Section --}}
    <div class="card rounded-4 border shadow mb-4 bg-white position-relative overflow-hidden">
        <div class="card-body p-4 p-lg-5 text-center position-relative z-index-1">

            <div class="position-absolute top-0 start-0 m-4 animate-float-slow d-none d-md-block opacity-50">
                <i class="bx bx-book-content text-primary fs-3"></i>
            </div>
            <div class="position-absolute top-0 end-0 m-5 animate-float opacity-50">
                <i class="bx bx-code-alt text-warning fs-1"></i>
            </div>
            <div class="position-absolute bottom-0 start-0 m-5 mb-4 animate-float d-none d-md-block opacity-50">
                <i class="bx bx-search text-success fs-3"></i>
            </div>
            <div class="position-absolute bottom-0 end-0 m-4 animate-float-slow opacity-50">
                <i class="bx bx-file text-info fs-1"></i>
            </div>

            <h2 class="fw-bold mb-3 display-6 text-primary">Documentation Hub</h2>
            <p class="lead mb-0 text-secondary">Comprehensive guides and references for your tools.</p>
        </div>
    </div>

    <div class="row g-4">
        {{-- LEFT COLUMN: Navigation Sidebar --}}
        <div class="col-12 col-lg-3 col-xl-3">
            <div class="sticky-top" style="top: 6rem; z-index: 10;">
                <div class="card rounded-4 border shadow-sm bg-white custom-scrollbar"
                    style="max-height: calc(100vh - 120px); overflow-y: auto;">
                    <div class="card-body p-0">
                        <div class="p-3 border-bottom bg-white sticky-top" style="z-index: 10 !important;">
                            <div class="position-relative">
                                <input type="text" id="sidebar-search"
                                    class="form-control ps-5 rounded-pill border bg-white" placeholder="Search..."
                                    style="font-size: 0.9rem;">
                                <span
                                    class="material-icons-outlined position-absolute top-50 start-0 translate-middle-y ms-3 text-secondary"
                                    style="font-size: 1.2rem;">search</span>
                            </div>
                        </div>

                        <div class="list-group list-group-flush rounded-4 overflow-hidden py-2 px-2">
                            <a href="{{ route('mikrotik-suite.dashboard') }}"
                                class="list-group-item list-group-item-action border-0 px-3 py-2 d-flex align-items-center text-primary fw-bold bg-primary bg-opacity-10 mb-2 rounded-3">
                                <i class="bi bi-grid-fill me-3 fs-6"></i>
                                <span>Dashboard</span>
                            </a>

                            <div class="border-bottom mx-2 mb-2 opacity-25"></div>

                            <div class="accordion accordion-flush" id="docsSidebarAccordion">
                                @foreach($menu as $catKey => $category)
                                    @php
                                        $catName = $category['title'];
                                        $safeCatName = $catKey;

                                        $hasActiveItem = false;
                                        try {
                                            if (isset($category['items']) && is_array($category['items'])) {
                                                $iterator = new RecursiveIteratorIterator(
                                                    new RecursiveArrayIterator($category['items']),
                                                    RecursiveIteratorIterator::SELF_FIRST
                                                );
                                                foreach ($iterator as $key => $value) {
                                                    if ($key === $currentSlug) {
                                                        $hasActiveItem = true;
                                                        break;
                                                    }
                                                }
                                            }
                                        } catch (\Exception $e) {
                                        }

                                        if ($currentSlug === 'index' && $catKey === 'getting_started') {
                                            $hasActiveItem = true;
                                        }
                                    @endphp
                                    <div class="accordion-item border-0 bg-transparent">
                                        <h2 class="accordion-header" id="heading-{{ $safeCatName }}">
                                            <button
                                                class="accordion-button shadow-none bg-transparent px-3 py-2 {{ $hasActiveItem ? 'text-primary fw-bold' : 'collapsed text-secondary' }}"
                                                type="button" data-bs-toggle="collapse"
                                                data-bs-target="#collapse-{{ $safeCatName }}"
                                                aria-expanded="{{ $hasActiveItem ? 'true' : 'false' }}"
                                                aria-controls="collapse-{{ $safeCatName }}">
                                                @if(isset($category['icon']))
                                                    <span
                                                        class="material-icons-outlined fs-6 me-3 opacity-75">{{ $category['icon'] }}</span>
                                                @endif
                                                <span class="text-uppercase tracking-wide text-nowrap"
                                                    style="font-size: 0.75rem; font-weight: 600;">{{ $catName }}</span>

                                                <i class="bi bi-chevron-left ms-auto transition-transform {{ $hasActiveItem ? 'rotate-down' : '' }}"
                                                    style="font-size: 0.8rem;"></i>
                                            </button>
                                        </h2>
                                        <div id="collapse-{{ $safeCatName }}"
                                            class="accordion-collapse collapse {{ $hasActiveItem ? 'show' : '' }}"
                                            aria-labelledby="heading-{{ $safeCatName }}" data-bs-parent="#docsSidebarAccordion">
                                            <div class="accordion-body p-0 pb-2">
                                                <div class="list-group list-group-flush rounded-0 border-0 ps-2">
                                                    @include('pages.documentation.partials.sidebar_item', ['items' => $category['items'], 'currentSlug' => $currentSlug])
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- CENTER COLUMN: Bento Grid Content (Full width, no inner card wrapper) --}}
        <div class="col-12 col-lg-9 col-xl-9">
            <div class="bento-container">
                {{-- Direct Bento Grid container without nested Bootstrap card --}}
                @if(!empty($contentView))
                    @include($contentView)
                @else
                    @yield('dynamic_content')
                @endif
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script src="{{ URL::asset('build/plugins/perfect-scrollbar/js/perfect-scrollbar.js') }}"></script>
    <script src="{{ URL::asset('build/plugins/metismenu/metisMenu.min.js') }}"></script>
    <script src="{{ URL::asset('build/plugins/simplebar/js/simplebar.min.js') }}"></script>
    @vite(['resources/js/pages/documentation.js'])
@endpush
