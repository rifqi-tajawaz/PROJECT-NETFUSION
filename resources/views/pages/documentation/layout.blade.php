@extends('layouts.app')

@push('css')
    @vite(['resources/sass/pages/documentation.scss'])
@endpush

@section('title')
    {{ $pageTitle ?? 'Documentation' }}
@endsection

@section('content')

    {{-- Hero Section --}}
    {{-- Hero Section --}}
    <div class="card rounded-4 border shadow mb-4 bg-white position-relative overflow-hidden">
        <div class="card-body p-4 p-lg-5 text-center position-relative z-index-1">

            {{-- Decorative Floating Icons --}}
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
            {{-- Sticky Wrapper (Matched to FAQ Page) --}}
            <div class="sticky-top" style="top: 6rem; z-index: 10;">
                <div class="card rounded-4 border shadow-sm bg-white custom-scrollbar"
                    style="max-height: calc(100vh - 120px); overflow-y: auto;">
                    <div class="card-body p-0">
                        {{-- Search --}}
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
                            {{-- Back to Dashboard --}}
                            <a href="{{ route('mikrotik-suite.dashboard') }}"
                                class="list-group-item list-group-item-action border-0 px-3 py-2 d-flex align-items-center text-primary fw-bold bg-primary bg-opacity-10 mb-2 rounded-3">
                                <i class="bi bi-grid-fill me-3 fs-6"></i>
                                <span>Dashboard</span>
                            </a>

                            <div class="border-bottom mx-2 mb-2 opacity-25"></div>

                            {{-- Accordion Menu --}}
                            <div class="accordion accordion-flush" id="docsSidebarAccordion">
                                @foreach($menu as $catKey => $category)
                                    @php
                                        $catName = $category['title']; // Translated Title
                                        $safeCatName = $catKey; // Stable ID (getting_started)

                                        // Check for active item recursively (Infinite Depth)
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
                                            // Fallback if structure is unexpected
                                        }

                                        // Also expand "Getting Started" by default if on home page
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

                                                {{-- Custom Arrow: Left (Closed) -> Down (Open) --}}
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

        {{-- CENTER COLUMN: Main Content --}}
        <div class="col-12 col-lg-7 col-xl-7">
            <div class="card rounded-4 border shadow-sm min-vh-50 bg-white">
                <div class="card-body p-4 doc-content-checker">
                    {{-- Dynamic Content Partial --}}
                    {{-- Dynamic Content Partial --}}
                    @if(!empty($contentView))
                        @include($contentView)
                    @else
                        <!-- Branch: Dynamic Content Yield -->
                        @yield('dynamic_content')
                    @endif

                    {{-- Bottom Pagination --}}
                    <div class="d-flex justify-content-between pt-5 mt-5 border-top">
                        @if(isset($prevPage))
                            <a href="{{ route('documentation.show', $prevPage['slug']) }}"
                                class="text-decoration-none text-secondary">
                                <div class="small fw-bold text-uppercase text-muted opacity-75 mb-1">Previous</div>
                                <div class="d-flex align-items-center text-dark fw-bold">
                                    <i class="bi bi-arrow-left me-2"></i> {{ $prevPage['title'] }}
                                </div>
                            </a>
                        @else
                            <div></div> {{-- Spacer --}}
                        @endif

                        @if(isset($nextPage))
                            <a href="{{ route('documentation.show', $nextPage['slug']) }}"
                                class="text-decoration-none text-secondary text-end">
                                <div class="small fw-bold text-uppercase text-muted opacity-75 mb-1">Next</div>
                                <div class="d-flex align-items-center text-dark fw-bold">
                                    {{ $nextPage['title'] }} <i class="bi bi-arrow-right ms-2"></i>
                                </div>
                            </a>
                        @else
                            <div></div> {{-- Spacer --}}
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- RIGHT COLUMN: Table of Contents (On this page) --}}
        <div class="col-12 col-lg-2 col-xl-2 d-none d-lg-block">
            <div class="sticky-top" style="top: 80px;">
                <h6 class="fw-bold text-dark text-uppercase small mb-3 ps-3 border-start border-3 border-primary">On this
                    page</h6>
                <nav id="toc" class="nav flex-column ps-0 small">
                    {{-- JS injected items --}}
                    <span class="text-muted ps-3 fst-italic">Loading...</span>
                </nav>
            </div>
        </div>
    </div>



@endsection

@push('scripts')
    <!--plugins-->
    <script src="{{ URL::asset('build/plugins/perfect-scrollbar/js/perfect-scrollbar.js') }}"></script>
    <script src="{{ URL::asset('build/plugins/metismenu/metisMenu.min.js') }}"></script>
    <script src="{{ URL::asset('build/plugins/simplebar/js/simplebar.min.js') }}"></script>


    {{-- Documentation Logic --}}
    @vite(['resources/js/pages/documentation.js'])
@endpush