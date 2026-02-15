@extends('layouts.app')

@section('title', __('netfusion.quick_print'))

@section('content')
    <div class="container-fluid px-4">

        <!-- 1. Header Section -->
        <div class="row g-3 mb-4 align-items-end justify-content-between">
            <div class="col-12 col-md-8">
                <h4 class="fw-bold text-dark mb-1 d-flex align-items-center gap-3">
                    <div class="rounded-circle bg-white text-primary p-3 shadow-sm d-flex align-items-center justify-content-center"
                        style="width: 56px; height: 56px;">
                        <i class="bi bi-printer-fill fs-3"></i>
                    </div>
                    <div>
                        <span style="letter-spacing: -0.5px;">{{ __('netfusion.quick_print_vouchers') }}</span>
                        <div class="text-muted small fw-normal mt-1 text-uppercase ls-1" style="font-size: 0.75rem;">
                            {{ __('netfusion.batch_generation_printing') }}
                        </div>
                    </div>
                </h4>
            </div>
        </div>

        <!-- 2. Main Content Card -->
        <!-- Used a single large card with internal grid for better stability -->
        <div class="card border-0 shadow-lg rounded-5 overflow-hidden" style="min-height: 600px;">

            <!-- Decorative Background Layer -->
            <div class="position-absolute top-0 start-0 w-100 h-100 bg-light" style="z-index: 0;">
                <div class="position-absolute top-0 end-0 bg-primary opacity-10 rounded-circle blur-3xl"
                    style="width: 600px; height: 600px; transform: translate(30%, -30%);"></div>
                <div class="position-absolute bottom-0 start-0 bg-secondary opacity-10 rounded-circle blur-3xl"
                    style="width: 500px; height: 500px; transform: translate(-30%, 30%);"></div>
            </div>

            <div class="position-relative h-100" style="z-index: 1;">
                <div class="row g-0 h-100">

                    <!-- LEFT COLUMN: Configuration Form -->
                    <div class="col-12 col-lg-7 bg-white bg-opacity-75 h-100 border-end border-light">
                        <div class="p-4 p-lg-5">
                            <h5 class="fw-bold text-dark mb-4 d-flex align-items-center gap-2">
                                <i class="bi bi-sliders2 text-primary"></i>
                                {{ __('netfusion.configuration') }}
                            </h5>

                            <!-- A. Select Batch -->
                            <div class="mb-4">
                                <label class="form-label text-secondary fw-bold text-uppercase fs-7 ls-1 mb-2">
                                    {{ __('netfusion.select_batch_source') }}
                                </label>
                                <div class="input-group input-group-lg shadow-sm rounded-pill overflow-hidden border border-secondary border-opacity-25">
                                    <span class="input-group-text bg-white border-0 ps-4 text-primary">
                                        <i class="bi bi-search"></i>
                                    </span>
                                    <select class="form-select border-0 bg-white" id="batchSelect" style="cursor: pointer;">
                                        <option value="" selected disabled>Choose a batch...</option>
                                        @foreach($batches as $batch)
                                            <option value="{{ $batch['name'] }}" data-count="{{ $batch['count'] }}"
                                                data-profile="{{ $batch['profile'] }}">
                                                {{ $batch['name'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                </div>
                                <div class="form-text mt-2 ms-3 text-muted">
                                    <i class="bi bi-info-circle me-1"></i> {{ __('netfusion.batches_grouped_comment') }}
                                </div>
                            </div>

                            <!-- B. Select Layout -->
                            <div class="mb-4">
                                <label class="form-label text-secondary fw-bold text-uppercase fs-7 ls-1 mb-3">
                                    {{ __('netfusion.specific_paper_layout') }}
                                </label>

                                <div class="d-flex flex-column gap-3">
                                    <!-- Option 1: Standard -->
                                    <div class="position-relative">
                                        <input type="radio" class="btn-check" name="layout" id="layoutStandard"
                                            value="standard" checked>
                                        <label
                                            class="btn btn-outline-light text-dark w-100 p-4 rounded-4 border bg-white shadow-sm text-start position-relative hover-elevate transition-all"
                                            for="layoutStandard">
                                            <div class="d-flex align-items-center gap-4">
                                                <div
                                                    class="rounded-circle bg-primary bg-opacity-10 text-primary p-3 flex-shrink-0">
                                                    <i class="bi bi-grid-3x3-gap fs-3"></i>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="fw-bold mb-1">{{ __('netfusion.layout_standard') }}</h6>
                                                    <p class="mb-0 text-muted small">
                                                        {{ __('netfusion.layout_standard_desc') }}</p>
                                                </div>
                                                <div class="check-indicator text-primary fs-3">
                                                    <i class="bi bi-check-circle-fill"></i>
                                                </div>
                                            </div>
                                        </label>
                                    </div>

                                    <!-- Option 2: Thermal -->
                                    <div class="position-relative">
                                        <input type="radio" class="btn-check" name="layout" id="layoutThermal"
                                            value="thermal">
                                        <label
                                            class="btn btn-outline-light text-dark w-100 p-4 rounded-4 border bg-white shadow-sm text-start position-relative hover-elevate transition-all"
                                            for="layoutThermal">
                                            <div class="d-flex align-items-center gap-4">
                                                <div
                                                    class="rounded-circle bg-danger bg-opacity-10 text-danger p-3 flex-shrink-0">
                                                    <i class="bi bi-receipt fs-3"></i>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="fw-bold mb-1">{{ __('netfusion.layout_thermal') }}</h6>
                                                    <p class="mb-0 text-muted small">{{ __('netfusion.layout_thermal_desc') }}
                                                    </p>
                                                </div>
                                                <div class="check-indicator text-primary fs-3">
                                                    <i class="bi bi-check-circle-fill"></i>
                                                </div>
                                            </div>
                                        </label>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                    <!-- RIGHT COLUMN: Preview & Actions -->
                    <!-- Added min-height to ensure it never collapses -->
                    <div class="col-12 col-lg-5 bg-light bg-opacity-10 d-flex flex-column justify-content-center p-4 p-lg-5"
                        style="min-height: 400px;">

                        <div class="text-center mb-4 d-none d-lg-block">
                            <label
                                class="text-secondary fw-bold text-uppercase fs-7 ls-1">{{ __('netfusion.preview_summary') }}</label>
                        </div>

                        <!-- Info Card -->
                        <div class="card border-0 bg-white shadow-lg rounded-4 mb-4 position-relative overflow-hidden"
                            id="infoCard" style="transition: all 0.3s ease; opacity: 0.6; filter: grayscale(1);">
                            <div class="card-body p-5 text-center">

                                <div class="mb-4">
                                    <div
                                        class="d-inline-block rounded-pill bg-warning bg-opacity-10 text-warning px-4 py-3">
                                        <i class="bi bi-ticket-perforated-fill fs-1"></i>
                                    </div>
                                </div>

                                <h3 class="fw-bold mb-1" id="previewBatchName">Batch Name</h3>
                                <span class="badge bg-light text-secondary border rounded-pill px-3 py-2 mt-2"
                                    id="previewProfile">{{ __('netfusion.no_profile_selected') }}</span>

                                <hr class="my-4 border-light">

                                <div class="row g-2 justify-content-center">
                                    <div class="col-6">
                                        <div class="p-3 bg-light rounded-3 border border-light">
                                            <div class="text-muted small text-uppercase fw-bold ls-1 mb-1">
                                                {{ __('netfusion.total_users') }}</div>
                                            <div class="fs-4 fw-bold text-dark" id="previewCount">-</div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="p-3 bg-light rounded-3 border border-light">
                                            <div class="text-muted small text-uppercase fw-bold ls-1 mb-1">
                                                {{ __('netfusion.est_pages') }}</div>
                                            <div class="fs-4 fw-bold text-dark" id="previewPages">-</div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="card-footer bg-light p-3 border-0 text-center text-muted small">
                                {{ __('netfusion.ready_to_print') }}
                            </div>
                        </div>

                        <!-- Action Button -->
                        <button type="button"
                            class="btn btn-primary w-100 py-3 rounded-pill fw-bold shadow-lg fs-5 d-flex align-items-center justify-content-center gap-2 hover-elevate"
                            id="printBtn" disabled>
                            <i class="bi bi-printer-fill"></i>
                            <span>{{ __('netfusion.start_printing') }}</span>
                        </button>

                    </div>

                </div>
            </div>
        </div>
    </div>

    <style>
        .blur-3xl {
            filter: blur(80px);
        }

        .ls-1 {
            letter-spacing: 1px;
        }

        .fs-7 {
            font-size: 0.75rem;
        }

        .hover-elevate {
            transition: transform 0.2s cubic-bezier(0.175, 0.885, 0.32, 1.275), box-shadow 0.2s ease;
        }

        .hover-elevate:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1) !important;
        }

        /* Radio Button Logic */
        .btn-check:checked+label .check-indicator {
            opacity: 1;
            transform: scale(1);
        }

        .btn-check:not(:checked)+label .check-indicator {
            opacity: 0;
            transform: scale(0);
            transition: all 0.2s ease;
        }

        .btn-check:checked+label {
            border-color: var(--bs-primary) !important;
            background-color: #fff !important;
            box-shadow: 0 0 0 4px rgba(13, 110, 253, 0.1) !important;
        }

        /* Smooth Input */
        .form-select:focus {
            box-shadow: none;
        }

        .input-group:focus-within {
            box-shadow: 0 0 0 4px rgba(13, 110, 253, 0.1) !important;
            border: 1px solid var(--bs-primary);
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const batchSelect = document.getElementById('batchSelect');
            const printBtn = document.getElementById('printBtn');
            const infoCard = document.getElementById('infoCard');

            // Preview Nodes
            const domName = document.getElementById('previewBatchName');
            const domProfile = document.getElementById('previewProfile');
            const domCount = document.getElementById('previewCount');
            const domPages = document.getElementById('previewPages');

            batchSelect.addEventListener('change', function () {
                if (this.value) {
                    const opt = this.options[this.selectedIndex];
                    const count = parseInt(opt.getAttribute('data-count') || 0);

                    // Update DOM
                    domName.innerText = opt.value;
                    domProfile.innerText = opt.getAttribute('data-profile') || 'Unknown Profile';
                    domCount.innerText = count;
                    domPages.innerText = Math.ceil(count / 10); // Approx 10 per page

                    // Enable UI
                    printBtn.removeAttribute('disabled');
                    infoCard.style.opacity = '1';
                    infoCard.style.filter = 'none';
                }
            });

            // Auto-select if batch is provided from server
            @if(isset($selectedBatch) && $selectedBatch)
                batchSelect.value = "{{ $selectedBatch }}";
                // Trigger change event manually
                if (batchSelect.value) {
                    batchSelect.dispatchEvent(new Event('change'));
                }
            @endif

            printBtn.addEventListener('click', function () {
                const batch = batchSelect.value;
                const layout = document.querySelector('input[name="layout"]:checked').value;
                if (!batch) return;

                // Simulate Action
                const originalHTML = this.innerHTML;
                this.disabled = true;
                this.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> {{ __('netfusion.generating_pdf') }}';

                setTimeout(() => {
                    alert(`{{ __('netfusion.printing_batch_alert', ['batch' => '${batch}', 'layout' => '${layout}']) }}`);
                    this.innerHTML = originalHTML;
                    this.disabled = false;
                }, 1500);
            });
        });
    </script>
@endsection
