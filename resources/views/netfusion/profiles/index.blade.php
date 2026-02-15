@extends('layouts.app')

@section('title', __('netfusion.user_profiles'))

@section('content')
    <div class="container-fluid px-4 py-4" style="min-height: 100vh;">
        <!-- Header -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-4 gap-3">
            <div class="text-center text-md-start">
                <h4
                    class="fw-bold text-dark mb-1 d-flex align-items-center gap-2 justify-content-center justify-content-md-start">
                    <i class="bi bi-speedometer2 text-primary"></i> {{ __('netfusion.user_profiles') }}
                </h4>
                <p class="text-muted small mb-0">{{ __('netfusion.manage_user_packages') }}</p>
            </div>
            <button type="button" data-bs-toggle="modal" data-bs-target="#addProfileModal"
                class="btn btn-primary fw-bold rounded-pill px-4 shadow-sm hover-scale w-100 w-md-auto">
                <i class="bi bi-plus-lg me-2"></i>{{ __('netfusion.new_profile') }}
            </button>
        </div>

        <!-- Success Alert -->
        @if(session('success'))
            <div class="alert alert-success border-0 shadow-sm rounded-4 mb-4 bg-success bg-opacity-10 d-flex align-items-center gap-3"
                role="alert">
                <div class="rounded-circle bg-success text-white p-2">
                    <i class="bi bi-check-lg fs-5"></i>
                </div>
                <div>
                    <h6 class="fw-bold text-success mb-0">Success</h6>
                    <small class="text-success opacity-75">{{ session('success') }}</small>
                </div>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger border-0 shadow-sm rounded-4 mb-4 bg-danger bg-opacity-10 d-flex align-items-center gap-3"
                role="alert">
                <div class="rounded-circle bg-danger text-white p-2">
                    <i class="bi bi-exclamation-triangle fs-5"></i>
                </div>
                <div>
                    <h6 class="fw-bold text-danger mb-0">Error</h6>
                    <small class="text-danger opacity-75">{{ session('error') }}</small>
                </div>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row g-4">
            @forelse($profiles as $profile)
                <div class="col-12 col-md-6 col-xl-4">
                    <div class="glass-card border-0 h-100 position-relative overflow-hidden transition-all">
                        <div class="card-body p-4 position-relative z-1">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="rounded-circle bg-primary bg-opacity-10 p-3 text-primary shadow-sm">
                                        <i class="bi bi-hdd-network fs-4"></i>
                                    </div>
                                    <div>
                                        <h5 class="fw-bold text-dark mb-0 text-truncate" style="max-width: 150px;"
                                            title="{{ $profile['name'] }}">{{ $profile['name'] }}</h5>
                                        <span class="badge bg-light text-secondary border mt-1 rounded-pill">
                                            <i class="bi bi-people me-1"></i>{{ $profile['shared-users'] ?? '1' }}
                                            {{ __('netfusion.shared') }}
                                        </span>
                                    </div>
                                </div>
                                @if($profile['name'] !== 'default')
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-light rounded-circle shadow-sm" type="button"
                                            data-bs-toggle="dropdown">
                                            <i class="bi bi-three-dots-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg rounded-4 p-2">
                                            <li>
                                                <a href="{{ route('mikrotik-suite.netfusion.profiles.edit', $profile['.id']) }}"
                                                    class="dropdown-item rounded-3 py-2">
                                                    <i class="bi bi-pencil me-2 text-info"></i>{{ __('netfusion.edit_profile') }}
                                                </a>
                                            </li>
                                            <li>
                                                <hr class="dropdown-divider">
                                            </li>
                                            <li>
                                                <form action="{{ route('mikrotik-suite.netfusion.profiles.destroy', $profile['.id']) }}"
                                                    method="POST"
                                                    onsubmit="return confirm('{{ __('netfusion.confirm_delete_profile', ['name' => $profile['name']]) }}');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item rounded-3 py-2 text-danger">
                                                        <i class="bi bi-trash me-2"></i>{{ __('netfusion.delete_profile') }}
                                                    </button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                @endif
                            </div>

                            <div class="row g-3">
                                <div class="col-6">
                                    <div class="p-3 bg-light rounded-4 bg-opacity-50 h-100">
                                        <small class="text-uppercase text-muted fw-bold lh-1 d-block mb-1"
                                            style="font-size: 0.65rem;">{{ __('netfusion.rate_limit') }}</small>
                                        <div class="fw-bold text-dark text-truncate fs-5">
                                            @if(isset($profile['rate-limit']))
                                                {{ $profile['rate-limit'] }}
                                            @else
                                                <span class="text-success small"><i
                                                        class="bi bi-infinity me-1"></i>{{ __('netfusion.unlimited') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="p-3 bg-light rounded-4 bg-opacity-50 h-100">
                                        <small class="text-uppercase text-muted fw-bold lh-1 d-block mb-1"
                                            style="font-size: 0.65rem;">{{ __('netfusion.session_timeout') }}</small>
                                        <div class="fw-bold text-dark fs-5 text-truncate">
                                            {{ $profile['session-timeout'] ?? __('netfusion.none') }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="d-flex align-items-center gap-2 p-2 rounded-3 text-muted small">
                                        <i class="bi bi-arrow-repeat"></i>
                                        <span>{{ __('netfusion.refresh_base') }}:
                                            <strong>{{ $profile['status-autorefresh'] ?? 'Default' }}</strong></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="glass-card border-0 text-center py-5">
                        <div class="py-4">
                            <div class="rounded-circle bg-light p-4 d-inline-block mb-3">
                                <i class="bi bi-speedometer2 text-muted fs-1"></i>
                            </div>
                            <h5 class="text-muted fw-bold">{{ __('netfusion.no_profiles_found') }}</h5>
                            <p class="text-muted small mb-4">{{ __('netfusion.no_profiles_msg') }}</p>
                            <button type="button" data-bs-toggle="modal" data-bs-target="#addProfileModal"
                                class="btn btn-primary rounded-pill px-4">{{ __('netfusion.create_first_profile') }}</button>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Add Profile Modal -->
    <div class="modal fade" id="addProfileModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="glass-card border-0 shadow-lg rounded-4 w-100 modal-content"
                style="background: rgba(255, 255, 255, 0.98);">
                <div class="modal-header border-bottom-0 ps-4 pt-4 px-4">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-circle bg-primary bg-opacity-10 text-primary p-2 d-flex align-items-center justify-content-center"
                            style="width: 50px; height: 50px;">
                            <i class="bi bi-plus-lg fs-4"></i>
                        </div>
                        <div>
                            <h5 class="modal-title fw-bold text-dark">{{ __('netfusion.create_new_profile') }}</h5>
                            <p class="mb-0 text-muted small" style="line-height: normal;">
                                {{ __('netfusion.setup_bandwidth_limitations') }}</p>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('mikrotik-suite.netfusion.profiles.store') }}" method="POST">
                    @csrf
                    <div class="modal-body px-4 pt-2 pb-4">

                        <div class="form-floating mb-3">
                            <input type="text" name="name" class="form-control bg-white border fw-bold text-dark"
                                id="modalName" placeholder="" required>
                            <label for="modalName">{{ __('netfusion.profile_name_placeholder') }}</label>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-6">
                                <div class="form-floating">
                                    <input type="number" name="shared_users"
                                        class="form-control bg-white border fw-bold text-dark" id="modalShared" value="1"
                                        min="1" placeholder="">
                                    <label for="modalShared">{{ __('netfusion.shared_users') }}</label>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-floating">
                                    <input type="text" name="rate_limit"
                                        class="form-control bg-white border fw-bold text-dark" id="modalLimit"
                                        placeholder="">
                                    <label for="modalLimit">{{ __('netfusion.rate_limit_help_short') }}</label>
                                </div>
                            </div>
                        </div>
                        <div class="bg-primary bg-opacity-10 p-3 rounded-3 d-flex gap-3 align-items-start small text-dark">
                            <i class="bi bi-info-circle-fill text-primary mt-1"></i>
                            <div>
                                <strong>{{ __('netfusion.tip') }}:</strong> {!! __('netfusion.rate_limit_tip') !!}
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-top-0 px-4 pb-4 pt-0">
                        <button type="button" class="btn btn-light rounded-pill px-4 fw-bold"
                            data-bs-dismiss="modal">{{ __('netfusion.cancel') }}</button>
                        <button type="submit"
                            class="btn btn-primary rounded-pill px-4 shadow-sm fw-bold">{{ __('netfusion.save_profile') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
        .glass-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.5);
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.05);
            border-radius: 24px;
        }

        .hover-scale:hover {
            transform: scale(1.05);
            transition: transform 0.2s;
        }

        .transition-all {
            transition: all 0.2s ease;
        }

        .glass-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 40px 0 rgba(31, 38, 135, 0.1) !important;
        }

        .form-control:focus {
            box-shadow: none;
            background-color: #fff !important;
        }

        .modal-content {
            background: transparent;
            border: none;
        }

        @media (min-width: 768px) {
            .w-md-auto {
                width: auto !important;
            }
        }
    </style>

    <script>
        // Auto-dismiss alerts
        const successAlert = document.querySelectorAll('.alert-success');
        successAlert.forEach(alert => {
            setTimeout(function () {
                alert.style.transition = 'opacity 0.5s ease';
                alert.style.opacity = '0';
                setTimeout(function () {
                    alert.remove();
                }, 500);
            }, 3000);
        });
    </script>
@endsection
