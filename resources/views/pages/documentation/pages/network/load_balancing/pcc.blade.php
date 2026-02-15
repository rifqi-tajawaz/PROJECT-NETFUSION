{{-- Breadcrumb --}}
<nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('documentation.show', 'index') }}"
                class="text-primary text-decoration-none">Docs</a></li>
        <li class="breadcrumb-item"><a href="javascript:;" class="text-muted text-decoration-none">Network</a></li>
        <li class="breadcrumb-item"><a href="javascript:;" class="text-muted text-decoration-none">Load Balancing</a>
        </li>
        <li class="breadcrumb-item active" aria-current="page">PCC Method</li>
    </ol>
</nav>

<div id="doc-pcc-load-balancing" class="animated fadeIn">
    <h1 class="fw-bold text-dark mb-2 fs-3">{{ __('documentation.pcc_title') }}</h1>
    <p class="text-muted mb-4">{{ __('documentation.pcc_subtitle') }}</p>

    <hr class="mb-5 opacity-10">

    {{-- Introduction --}}
    <h5 class="fw-bold text-dark mb-3">{{ __('documentation.pcc_intro_title') }}</h5>
    <p class="mb-3">
        {!! __('documentation.pcc_intro_desc') !!}
    </p>
    <p class="mb-5">
        {!! __('documentation.pcc_intro_desc_2') !!}
    </p>

    {{-- Prerequisites --}}
    <h5 class="fw-bold text-dark mb-3">{{ __('documentation.pcc_prereq_title') }}</h5>
    <ul class="mb-5">
        <li class="mb-2">{!! __('documentation.pcc_prereq_1') !!}</li>
        <li class="mb-2">{!! __('documentation.pcc_prereq_2') !!}</li>
        <li>{!! __('documentation.pcc_prereq_3') !!}</li>
    </ul>

    {{-- Guide --}}
    <h5 class="fw-bold text-dark mb-3">{{ __('documentation.pcc_config_title') }}</h5>
    <div class="mb-5">
        <h6 class="fw-bold text-dark mt-4">{{ __('documentation.pcc_step_1_title') }}</h6>
        <p class="text-muted mb-2">
            {!! __('documentation.pcc_step_1_desc') !!}
        </p>
        <ul class="mb-3">
            <li>{!! __('documentation.pcc_step_1_list_1') !!}</li>
            <li>{!! __('documentation.pcc_step_1_list_2') !!}</li>
            <li>{!! __('documentation.pcc_step_1_list_3') !!}</li>
        </ul>

        <h6 class="fw-bold text-dark mt-4">{{ __('documentation.pcc_step_2_title') }}</h6>
        <p class="text-muted mb-3">
            {!! __('documentation.pcc_step_2_desc') !!}
        </p>

        <h6 class="fw-bold text-dark mt-4">{{ __('documentation.pcc_step_3_title') }}</h6>
        <p class="text-muted mb-2">{!! __('documentation.pcc_step_3_desc') !!}</p>
        <ul class="mb-3">
            <li class="mb-2">{!! __('documentation.pcc_step_3_list_1') !!}</li>
            <li>{!! __('documentation.pcc_step_3_list_2') !!}</li>
        </ul>

        <h6 class="fw-bold text-dark mt-4">{{ __('documentation.pcc_step_4_title') }}</h6>
        <p class="text-muted mb-0">
            {!! __('documentation.pcc_step_4_desc') !!}
        </p>
    </div>

    {{-- Verification --}}
    <h5 class="fw-bold text-dark mb-3">{{ __('documentation.pcc_verify_title') }}</h5>
    <p class="text-muted mb-3">{!! __('documentation.pcc_verify_desc') !!}</p>
    <ul class="mb-5">
        <li class="mb-2">{!! __('documentation.pcc_verify_list_1') !!}</li>
        <li>{!! __('documentation.pcc_verify_list_2') !!}</li>
    </ul>

    {{-- Warnings --}}
    <div class="alert alert-warning border-0 bg-warning bg-opacity-10 text-warning-emphasis rounded-3 p-3">
        <h6 class="fw-bold mb-2"><i
                class="bi bi-exclamation-triangle-fill me-2"></i>{{ __('documentation.pcc_notes_title') }}
        </h6>
        <ul class="mb-0 small">
            <li class="mb-1">{!! __('documentation.pcc_notes_1') !!}</li>
            <li class="mb-1">{!! __('documentation.pcc_notes_2') !!}</li>
            <li>{!! __('documentation.pcc_notes_3') !!}</li>
        </ul>
    </div>
</div>