{{-- Breadcrumb --}}
<nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('documentation.show', 'index') }}"
                class="text-primary text-decoration-none">Docs</a></li>
        <li class="breadcrumb-item"><a href="javascript:;" class="text-muted text-decoration-none">Network</a></li>
        <li class="breadcrumb-item"><a href="javascript:;" class="text-muted text-decoration-none">Load Balancing</a>
        </li>
        <li class="breadcrumb-item active" aria-current="page">ECMP Method</li>
    </ol>
</nav>

<div id="doc-ecmp-load-balancing" class="animated fadeIn">
    <h1 class="fw-bold text-dark mb-2 fs-3">{{ __('documentation.ecmp_title') }}</h1>
    <p class="text-muted mb-4">{{ __('documentation.ecmp_subtitle') }}</p>

    <hr class="mb-5 opacity-10">

    {{-- Introduction --}}
    <h5 class="fw-bold text-dark mb-3">{{ __('documentation.ecmp_intro_title') }}</h5>
    <p class="mb-3">
        {!! __('documentation.ecmp_intro_desc') !!}
    </p>
    <p class="mb-5">
        {!! __('documentation.ecmp_intro_desc_2') !!}
    </p>

    {{-- Requirements --}}
    <h5 class="fw-bold text-dark mb-3">{{ __('documentation.ecmp_req_title') }}</h5>
    <ul class="mb-5">
        <li class="mb-2">{!! __('documentation.ecmp_req_1') !!}</li>
        <li class="mb-2">{!! __('documentation.ecmp_req_2') !!}</li>
    </ul>

    {{-- Guide --}}
    <h5 class="fw-bold text-dark mb-3">{{ __('documentation.ecmp_config_title') }}</h5>
    <div class="mb-5">
        <h6 class="fw-bold text-dark mt-4">{{ __('documentation.ecmp_step_1_title') }}</h6>
        <p class="text-muted mb-2">
            {!! __('documentation.ecmp_step_1_desc') !!}
        </p>
        <div class="bg-light border p-3 rounded-3 font-monospace small mb-3 text-secondary">
            /ip route<br>
            add dst-address=0.0.0.0/0 gateway=192.168.1.1,192.168.2.1 check-gateway=ping
        </div>
        <p class="text-muted mb-3">
            {!! __('documentation.ecmp_step_1_note') !!}
        </p>

        <h6 class="fw-bold text-dark mt-4">{{ __('documentation.ecmp_step_2_title') }}</h6>
        <p class="text-muted mb-2">
            {!! __('documentation.ecmp_step_2_desc') !!}
        </p>
        <div class="bg-light border p-3 rounded-3 font-monospace small mb-3 text-secondary">
            gateway=192.168.1.1,192.168.1.1,192.168.2.1
        </div>
        <p class="text-muted mb-3">
            {!! __('documentation.ecmp_step_2_note') !!}
        </p>

        <h6 class="fw-bold text-dark mt-4">{{ __('documentation.ecmp_step_3_title') }}</h6>
        <p class="text-muted mb-0">
            {!! __('documentation.ecmp_step_3_desc') !!}
        </p>
    </div>

    {{-- Pros Cons --}}
    <h5 class="fw-bold text-dark mb-3">{{ __('documentation.ecmp_pros_cons_title') }}</h5>
    <div class="row">
        <div class="col-md-6">
            <h5 class="fw-bold text-success mb-2">{{ __('documentation.ecmp_pros') }}</h5>
            <ul class="mb-4">
                <li>{{ __('documentation.ecmp_pros_1') }}</li>
                <li>{{ __('documentation.ecmp_pros_2') }}</li>
                <li>{{ __('documentation.ecmp_pros_3') }}</li>
            </ul>
        </div>
        <div class="col-md-6">
            <h5 class="fw-bold text-danger mb-2">{{ __('documentation.ecmp_cons') }}</h5>
            <ul class="mb-4">
                <li>{{ __('documentation.ecmp_cons_1') }}</li>
                <li>{{ __('documentation.ecmp_cons_2') }}</li>
                <li>{{ __('documentation.ecmp_cons_3') }}</li>
            </ul>
        </div>
    </div>
</div>