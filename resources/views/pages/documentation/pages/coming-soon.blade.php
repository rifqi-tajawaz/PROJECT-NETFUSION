{{-- Breadcrumb --}}
<nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('documentation.show', 'index') }}"
                class="text-primary text-decoration-none">Docs</a></li>
        <li class="breadcrumb-item active" aria-current="page">{{ $pageTitle }}</li>
    </ol>
</nav>

{{-- Coming Soon Content --}}
<div class="text-center py-5 animated fadeIn">
    <div class="mb-4">
        <div class="d-inline-flex align-items-center justify-content-center bg-light rounded-circle p-5 mb-3"
            style="width: 150px; height: 150px;">
            <span class="material-icons-outlined text-secondary opacity-50" style="font-size: 4rem;">construction</span>
        </div>
        <h2 class="fw-bold text-dark">{{ $pageTitle }}</h2>
        <p class="text-muted lead mb-4">{{ __('documentation.coming_soon_desc') }}</p>
    </div>

    <div class="alert alert-info border-0 bg-info bg-opacity-10 text-info-emphasis rounded-4 p-4 d-inline-block text-start"
        style="max-width: 600px;">
        <h6 class="fw-bold mb-2"><i class="bi bi-info-circle-fill me-2"></i>{{ __('documentation.need_help_title') }}
        </h6>
        <p class="mb-0">
            {!! __('documentation.need_help_desc', ['url' => route('support')]) !!}
        </p>
    </div>
</div>