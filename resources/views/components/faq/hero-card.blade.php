@props([
    'title' => '',
    'intro' => '',
    'searchPlaceholder' => '',
])

<div class="card rounded-3 border shadow mb-4 bg-white position-relative overflow-hidden faq-hero-card">
    <div class="card-body p-4 p-lg-5 text-center position-relative z-index-1">

        {{-- Decorative Floating Icons --}}
        <div class="position-absolute top-0 start-0 m-4 animate-float-slow d-none d-md-block opacity-50">
            <span class="material-icons-outlined text-primary fs-3">help_outline</span>
        </div>
        <div class="position-absolute top-0 end-0 m-5 animate-float opacity-50">
            <span class="material-icons-outlined text-warning fs-1">lightbulb</span>
        </div>
        <div class="position-absolute bottom-0 start-0 m-5 mb-4 animate-float d-none d-md-block opacity-50">
            <span class="material-icons-outlined text-success fs-3">support_agent</span>
        </div>
        <div class="position-absolute bottom-0 end-0 m-4 animate-float-slow opacity-50">
            <span class="material-icons-outlined text-info fs-1">search</span>
        </div>

        <h2 class="display-3 fw-bolder mb-3 tracking-tight text-gradient-dark">{{ $title }}</h2>
        <p class="lead mb-4 text-secondary">{{ $intro }}</p>

        {{-- Search Bar --}}
        <div class="faq-search-wrapper position-relative z-1 mb-2 mx-auto">
            <span class="material-icons-outlined fs-4 search-icon position-absolute top-50 start-0 translate-middle-y ms-3 text-secondary">search</span>
            <input type="text" id="faqSearch"
                class="form-control form-control-lg rounded-pill shadow-sm ps-5 bg-light border-0"
                placeholder="{{ $searchPlaceholder }}">
        </div>
    </div>
</div>
