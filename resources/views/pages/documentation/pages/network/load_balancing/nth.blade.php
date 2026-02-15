{{-- Breadcrumb --}}
<nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('documentation.show', 'index') }}"
                class="text-primary text-decoration-none">Docs</a></li>
        <li class="breadcrumb-item"><a href="javascript:;" class="text-muted text-decoration-none">Network</a></li>
        <li class="breadcrumb-item"><a href="javascript:;" class="text-muted text-decoration-none">Load Balancing</a>
        </li>
        <li class="breadcrumb-item active" aria-current="page">NTH Method</li>
    </ol>
</nav>

<div id="doc-nth-load-balancing" class="animated fadeIn">
    <h1 class="fw-bold text-dark mb-2 fs-3">{{ __('documentation.nth_title') }}</h1>
    <p class="text-muted mb-4">{{ __('documentation.nth_subtitle') }}</p>

    <hr class="mb-5 opacity-10">

    {{-- Introduction --}}
    <h5 class="fw-bold text-dark mb-3">{{ __('documentation.nth_intro_title') }}</h5>
    <p class="mb-3">
        {!! __('documentation.nth_intro_desc') !!}
    </p>
    <p class="mb-5">
        {!! __('documentation.nth_intro_desc_2') !!}
    </p>

    {{-- Warning: Read First --}}
    <div class="alert alert-danger border-0 bg-danger bg-opacity-10 text-danger-emphasis rounded-3 p-3 mb-5">
        <h6 class="fw-bold mb-2"><i
                class="bi bi-exclamation-octagon-fill me-2"></i>{{ __('documentation.nth_warning_title') }}
        </h6>
        <p class="mb-0">
            {!! __('documentation.nth_warning_desc') !!}
            <br><br>
            {!! __('documentation.nth_warning_use_case') !!}
        </p>
    </div>

    {{-- Guide --}}
    <h5 class="fw-bold text-dark mb-3">{{ __('documentation.nth_config_title') }}</h5>
    <div class="mb-5">
        <h6 class="fw-bold text-dark mt-4">{{ __('documentation.nth_step_1_title') }}</h6>
        <p class="text-muted mb-3">
            {!! __('documentation.nth_step_1_desc') !!}
        </p>

        <h6 class="fw-bold text-dark mt-4">{{ __('documentation.nth_step_2_title') }}</h6>
        <p class="text-muted mb-2">
            {!! __('documentation.nth_step_2_desc') !!}
        </p>
        <ul class="mb-3">
            <li>{!! __('documentation.nth_step_2_list_1') !!}</li>
            <li>{!! __('documentation.nth_step_2_list_2') !!}</li>
        </ul>
        <p class="text-muted mb-0">{!! __('documentation.nth_step_2_note') !!}</p>

        <h6 class="fw-bold text-dark mt-4">{{ __('documentation.nth_step_3_title') }}</h6>
        <p class="text-muted mb-0">
            {!! __('documentation.nth_step_3_desc') !!}
        </p>
    </div>

    {{-- Comparison --}}
    <h5 class="fw-bold text-dark mb-3">{{ __('documentation.nth_vs_pcc_title') }}</h5>
    <table class="table table-hover border mb-0 align-middle">
        <thead class="bg-light">
            <tr>
                <th class="ps-3 py-2">{{ __('documentation.nth_vs_pcc_feature') }}</th>
                <th class="py-2">NTH</th>
                <th class="py-2">PCC</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="ps-3 fw-bold small text-secondary">{{ __('documentation.nth_vs_pcc_logic') }}</td>
                <td class="text-muted small">{{ __('documentation.nth_vs_pcc_logic_nth') }}</td>
                <td class="text-muted small">{{ __('documentation.nth_vs_pcc_logic_pcc') }}</td>
            </tr>
            <tr>
                <td class="ps-3 fw-bold small text-secondary">{{ __('documentation.nth_vs_pcc_dist') }}</td>
                <td class="text-muted small">{{ __('documentation.nth_vs_pcc_dist_nth') }}</td>
                <td class="text-muted small">{{ __('documentation.nth_vs_pcc_dist_pcc') }}</td>
            </tr>
            <tr>
                <td class="ps-3 fw-bold small text-secondary">{{ __('documentation.nth_vs_pcc_safety') }}</td>
                <td class="text-danger fw-bold small">{{ __('documentation.nth_vs_pcc_safety_nth') }}</td>
                <td class="text-success fw-bold small">{{ __('documentation.nth_vs_pcc_safety_pcc') }}</td>
            </tr>
        </tbody>
    </table>
</div>