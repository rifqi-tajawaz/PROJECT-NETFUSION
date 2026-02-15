@extends('layouts.app')

@section('title', __('netfusion.session_manager'))

@section('content')
    <div class="container-fluid px-4">
        <!-- Header (Aligned with Dashboard) -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-5 gap-3">
            <div class="text-center text-md-start">
                <h3 class="fw-bold text-dark mb-1">{{ __('netfusion.session_manager') }}</h3>
                <span class="text-muted">{{ __('netfusion.manage_routeros_connections') }}</span>
            </div>
            <div class="d-flex align-items-center gap-2">
                <!-- Tools Dropdown -->
                <div class="dropdown">
                    <button
                        class="btn btn-white border shadow-sm rounded-pill px-4 py-2 fw-bold dropdown-toggle d-flex align-items-center gap-2"
                        type="button" data-bs-toggle="dropdown">
                        <i class="bi bi-gear-fill text-muted"></i>
                        <span>{{ __('netfusion.tools') }}</span>
                    </button>
                    <ul class="dropdown-menu border-0 shadow-lg rounded-4 p-2 mt-2" style="min-width: 200px;">
                        <li>
                            <h6 class="dropdown-header text-uppercase small fw-bold text-muted ls-1">
                                {{ __('netfusion.data_management') }}
                            </h6>
                        </li>
                        <li>
                            <a href="{{ route('mikrotik-suite.netfusion.settings.export') }}"
                                class="dropdown-item rounded-3 py-2 px-3 d-flex align-items-center gap-2">
                                <i class="bi bi-cloud-download text-primary"></i>
                                <span>{{ __('netfusion.export_backup') }}</span>
                            </a>
                        </li>
                        <li>
                            <button class="dropdown-item rounded-3 py-2 px-3 d-flex align-items-center gap-2"
                                data-bs-toggle="modal" data-bs-target="#importModal">
                                <i class="bi bi-cloud-upload text-success"></i>
                                <span>{{ __('netfusion.import_backup') }}</span>
                            </button>
                        </li>
                    </ul>
                </div>

                <!-- Add Session Button -->
                <button type="button"
                    class="btn btn-primary fw-bold rounded-pill px-4 py-2 shadow-lg hover-scale d-flex align-items-center gap-2"
                    data-bs-toggle="modal" data-bs-target="#sessionModal" onclick="clearSessionForm()">
                    <i class="bi bi-plus-lg"></i>
                    <span class="d-none d-sm-inline">{{ __('netfusion.new_session') }}</span>
                    <span class="d-inline d-sm-none">{{ __('netfusion.add') }}</span>
                </button>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success border-0 shadow-sm rounded-4 mb-4 d-flex align-items-center gap-3 p-3">
                <div class="bg-success bg-opacity-10 text-success rounded-circle p-2 d-flex align-items-center justify-content-center"
                    style="width: 40px; height: 40px;">
                    <i class="bi bi-check-lg fs-5"></i>
                </div>
                <div>
                    <h6 class="fw-bold mb-0 text-success">{{ __('netfusion.success') }}</h6>
                    <small class="text-muted">{{ session('success') }}</small>
                </div>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger border-0 shadow-sm rounded-4 mb-4 d-flex align-items-center gap-3 p-3">
                <div class="bg-danger bg-opacity-10 text-danger rounded-circle p-2 d-flex align-items-center justify-content-center"
                    style="width: 40px; height: 40px;">
                    <i class="bi bi-exclamation-triangle fs-5"></i>
                </div>
                <div>
                    <h6 class="fw-bold mb-0 text-danger">{{ __('netfusion.error') }}</h6>
                    <small class="text-muted">{{ session('error') }}</small>
                </div>
            </div>
        @endif

        <!-- Session Grid -->
        <div class="row g-4">
            @forelse($sessions as $id => $s)
                @php
                    $isActive = isset($currentSession['id']) && $currentSession['id'] == $id;

                    // Active State Styling
                    $cardBg = $isActive
                        ? 'linear-gradient(135deg, #aeafff 0%, #3b3f5c 100%)' // Fallback / Example
                        : '#ffffff';

                    // We use specific classes instead of inline styles for cleaner code where possible,
                    // but for dynamic active state gradients, inline is easier.
                    // Let's match the user's "Active" preference (blue/primary).
                @endphp

                <div class="col-12 col-md-6 col-lg-4 col-xl-4">
                    <div class="card h-100 rounded-5 shadow-sm overflow-hidden border transition-all position-relative file-card {{ $isActive ? 'ring-active' : '' }}"
                        style="background: {{ $isActive ? 'linear-gradient(135deg, #0d6efd 0%, #0043a8 100%)' : '#ffffff' }};">

                        <!-- Top Status Badge -->
                        <div class="position-absolute top-0 end-0 p-4">
                            @if($isActive)
                                <span
                                    class="badge bg-white text-primary rounded-pill px-3 py-2 shadow-sm fw-bold d-flex align-items-center gap-2">
                                    <span class="spinner-grow spinner-grow-sm text-primary" role="status" aria-hidden="true"></span>
                                    {{ __('netfusion.active') }}
                                </span>
                            @else
                                <div class="badge bg-light text-secondary border rounded-pill px-3 py-2 fw-medium">
                                    {{ __('netfusion.offline') }}
                                </div>
                            @endif
                        </div>

                        <div class="card-body p-4 d-flex flex-column">
                            <!-- Router Icon & Title -->
                            <div class="mb-4 pt-2">
                                <div class="d-inline-flex align-items-center justify-content-center rounded-4 mb-3 shadow-sm bg-white text-primary"
                                    style="width: 64px; height: 64px;">
                                    <i class="bi bi-router fs-2"></i>
                                </div>
                                <h4 class="fw-bold mb-1 {{ $isActive ? 'text-white' : 'text-dark' }} text-truncate"
                                    title="{{ $s['hotspot_name'] ?? 'Hotspot System' }}">
                                    {{ $s['hotspot_name'] ?? 'Hotspot System' }}
                                </h4>
                                <p class="mb-0 text-truncate small {{ $isActive ? 'text-white-50' : 'text-muted' }}"
                                    title="{{ $s['name'] }}">
                                    {{ __('netfusion.server') }}: {{ $s['name'] ?? __('netfusion.unknown') }}
                                </p>
                            </div>

                            <!-- Technical Details Box -->
                            <div class="mt-auto">
                                <div
                                    class="bg-body-secondary bg-opacity-10 rounded-4 p-3 mb-4 border border-white border-opacity-10">
                                    <div class="row g-2">
                                        <div class="col-12">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <span
                                                    class="small {{ $isActive ? 'text-white-50' : 'text-muted' }}">{{ __('netfusion.ip_address') }}</span>
                                                <span
                                                    class="fw-medium font-monospace small text-truncate d-inline-block text-end {{ $isActive ? 'text-white' : 'text-dark' }}"
                                                    style="max-width: 150px;" title="{{ $s['ip'] }}">
                                                    {{ $s['ip'] }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <span
                                                    class="small {{ $isActive ? 'text-white-50' : 'text-muted' }}">{{ __('netfusion.user') }}</span>
                                                <span
                                                    class="fw-medium small text-truncate {{ $isActive ? 'text-white' : 'text-dark' }}">
                                                    {{ $s['user'] }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <span
                                                    class="small {{ $isActive ? 'text-white-50' : 'text-muted' }}">{{ __('netfusion.interface') }}</span>
                                                <span
                                                    class="fw-medium small text-truncate {{ $isActive ? 'text-white' : 'text-dark' }}">
                                                    {{ $s['traffic_interface'] ?? 'ether1' }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Action Buttons -->
                                <div class="d-flex gap-2">
                                    @if($isActive)
                                        <form action="{{ route('mikrotik-suite.netfusion.settings.disconnect') }}" method="POST"
                                            class="w-100">
                                            @csrf
                                            <button type="submit"
                                                class="btn btn-light w-100 rounded-pill fw-bold text-danger shadow-sm py-2">
                                                {{ __('netfusion.disconnect') }}
                                            </button>
                                        </form>
                                    @else
                                        <form action="{{ route('mikrotik-suite.netfusion.settings.connect', $id) }}" method="POST"
                                            class="flex-grow-1">
                                            @csrf
                                            <button type="submit" class="btn btn-primary w-100 rounded-pill fw-bold shadow-sm py-2">
                                                {{ __('netfusion.connect') }}
                                            </button>
                                        </form>
                                    @endif

                                    <div class="dropdown">
                                        <button
                                            class="btn btn-light border rounded-circle bg-white shadow-sm d-flex align-items-center justify-content-center"
                                            style="width: 42px; height: 42px;" type="button" data-bs-toggle="dropdown">
                                            <i class="bi bi-three-dots-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg rounded-4 p-2">
                                            <li>
                                                <form action="{{ route('mikrotik-suite.netfusion.settings.ping', $id) }}"
                                                    method="POST">
                                                    @csrf
                                                    <button type="submit"
                                                        class="dropdown-item rounded-3 d-flex align-items-center gap-2 py-2">
                                                        <i class="bi bi-activity text-info"></i>
                                                        {{ __('netfusion.ping_check') }}
                                                    </button>
                                                </form>
                                            </li>
                                            <li>
                                                <button class="dropdown-item rounded-3 d-flex align-items-center gap-2 py-2"
                                                    onclick='editSession(@json($s))' data-bs-toggle="modal"
                                                    data-bs-target="#sessionModal">
                                                    <i class="bi bi-pencil-square text-warning"></i>
                                                    {{ __('netfusion.edit_details') }}
                                                </button>
                                            </li>
                                            <li>
                                                <hr class="dropdown-divider">
                                            </li>
                                            <li>
                                                <form action="{{ route('mikrotik-suite.netfusion.settings.destroy', $id) }}"
                                                    method="POST"
                                                    onsubmit="return confirm('{{ __('netfusion.confirm_delete_session', ['name' => $s['name']]) }}');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="dropdown-item rounded-3 d-flex align-items-center gap-2 py-2 text-danger">
                                                        <i class="bi bi-trash"></i> {{ __('netfusion.delete_session') }}
                                                    </button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="text-center py-5 rounded-5"
                        style="border: 2px dashed #dee2e6; background: rgba(255,255,255,0.5);">
                        <div class="mb-4">
                            <div class="bg-primary bg-opacity-10 p-4 rounded-circle d-inline-block">
                                <i class="bi bi-router fs-1 text-primary"></i>
                            </div>
                        </div>
                        <h4 class="fw-bold text-dark">{{ __('netfusion.no_sessions_found') }}</h4>
                        <p class="text-muted mb-4">{{ __('netfusion.no_sessions_desc') }}</p>
                        <button type="button" class="btn btn-primary fw-bold rounded-pill px-5 py-3 shadow-lg hover-scale"
                            data-bs-toggle="modal" data-bs-target="#sessionModal" onclick="clearSessionForm()">
                            <i class="bi bi-plus-lg me-2"></i>{{ __('netfusion.create_new_session') }}
                        </button>
                    </div>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Session Modal -->
    <div class="modal fade" id="sessionModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow-lg rounded-5 overflow-hidden">
                <div class="modal-header border-bottom-0 ps-4 pt-4 bg-light bg-opacity-50">
                    <h5 class="modal-title fw-bold" id="modalTitle">{{ __('netfusion.session_configuration') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="progress" style="height: 2px;">
                    <div class="progress-bar bg-primary" role="progressbar" style="width: 100%"></div>
                </div>
                <form action="{{ route('mikrotik-suite.netfusion.settings.save') }}" method="POST" id="sessionForm"
                    class="no-loader">
                    @csrf
                    <input type="hidden" name="id" id="sessionId">
                    <div class="modal-body px-4 pb-4 bg-light bg-opacity-50">
                        <!-- Error Alert Container -->
                        <div id="modalErrorAlert" class="alert alert-danger d-none rounded-3 shadow-sm" role="alert">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i> <span id="modalErrorMessage"></span>
                        </div>

                        <div class="row g-4">
                            <!-- Left: Credentials -->
                            <div class="col-md-6 border-end">
                                <h6 class="text-uppercase text-muted small fw-bold ls-1 mb-3">
                                    {{ __('netfusion.connection_details') }}
                                </h6>
                                <div class="mb-3">
                                    <label
                                        class="form-label small fw-bold text-secondary">{{ __('netfusion.session_name') }}</label>
                                    <div class="input-group has-validation">
                                        <span class="input-group-text bg-white border-end-0"><i
                                                class="bi bi-tag text-muted"></i></span>
                                        <input type="text" name="session_name" id="sessionName"
                                            class="form-control border-start-0 ps-0"
                                            placeholder="{{ __('netfusion.session_name_placeholder') }}" required>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                                <div class="row g-2 mb-3">
                                    <div class="col-8">
                                        <label
                                            class="form-label small fw-bold text-secondary">{{ __('netfusion.ip_hostname') }}</label>
                                        <div class="input-group has-validation">
                                            <span class="input-group-text bg-white border-end-0"><i
                                                    class="bi bi-globe text-muted"></i></span>
                                            <input type="text" name="ip" id="sessionIp"
                                                class="form-control border-start-0 ps-0"
                                                placeholder="{{ __('netfusion.ip_placeholder') }}" required>
                                            <div class="invalid-feedback"></div>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <label
                                            class="form-label small fw-bold text-secondary">{{ __('netfusion.port') }}</label>
                                        <input type="number" name="port" id="sessionPort" class="form-control text-center"
                                            value="8728" required>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label
                                        class="form-label small fw-bold text-secondary">{{ __('netfusion.username') }}</label>
                                    <div class="input-group has-validation">
                                        <span class="input-group-text bg-white border-end-0"><i
                                                class="bi bi-person text-muted"></i></span>
                                        <input type="text" name="user" id="sessionUser"
                                            class="form-control border-start-0 ps-0"
                                            placeholder="{{ __('netfusion.username_placeholder') }}" required>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label
                                        class="form-label small fw-bold text-secondary">{{ __('netfusion.password') }}</label>
                                    <div class="input-group has-validation">
                                        <span class="input-group-text bg-white border-end-0"><i
                                                class="bi bi-key text-muted"></i></span>
                                        <input type="password" name="password" id="sessionPass"
                                            class="form-control border-start-0 ps-0"
                                            placeholder="{{ __('netfusion.router_password_placeholder') }}">
                                        <button class="btn btn-outline-secondary bg-white border-start-0" type="button"
                                            onclick="togglePass('sessionPass')">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="form-text small">{{ __('netfusion.leave_empty_password') }}</div>
                                </div>
                            </div>
                            <!-- Right: Config -->
                            <div class="col-md-6">
                                <h6 class="text-uppercase text-muted small fw-bold ls-1 mb-3">
                                    {{ __('netfusion.hotspot_defaults') }}
                                </h6>
                                <div class="p-3 bg-white rounded-4 shadow-sm">
                                    <div class="mb-3">
                                        <label
                                            class="form-label small fw-bold text-secondary">{{ __('netfusion.hotspot_name') }}</label>
                                        <input type="text" name="hotspot_name" id="hotspotName"
                                            class="form-control bg-light border border-secondary border-opacity-25"
                                            placeholder="{{ __('netfusion.hotspot_name_placeholder') }}" required>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="mb-3">
                                        <label
                                            class="form-label small fw-bold text-secondary">{{ __('netfusion.dns_name') }}</label>
                                        <input type="text" name="dns_name" id="dnsName"
                                            class="form-control bg-light border border-secondary border-opacity-25"
                                            placeholder="{{ __('netfusion.dns_name_placeholder') }}" required>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="row g-2">
                                        <div class="col-12">
                                            <label
                                                class="form-label small fw-bold text-secondary">{{ __('netfusion.currency') }}</label>
                                            <input type="text" name="currency" id="currency"
                                                class="form-control bg-light border border-secondary border-opacity-25"
                                                value="Rp" required>
                                            <div class="invalid-feedback"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-top-0 px-4 pb-4 bg-light bg-opacity-50">
                        <button type="button" class="btn btn-link text-muted text-decoration-none fw-bold me-auto"
                            data-bs-dismiss="modal">{{ __('netfusion.cancel') }}</button>
                        <button type="submit" id="saveButton" class="btn btn-primary rounded-pill px-5 shadow-sm fw-bold">
                            <span class="spinner-border spinner-border-sm d-none me-2" role="status"
                                aria-hidden="true"></span>
                            <span class="btn-text">{{ __('netfusion.save_configuration') }}</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Import Modal -->
    <div class="modal fade" id="importModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-5">
                <div class="modal-header border-bottom-0 pb-0">
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('mikrotik-suite.netfusion.settings.import') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body text-center pt-0 pb-4 px-4">
                        <div class="mb-3">
                            <i class="bi bi-file-earmark-arrow-up text-primary" style="font-size: 4rem;"></i>
                        </div>
                        <h5 class="fw-bold mb-2">{{ __('netfusion.import_sessions') }}</h5>
                        <p class="text-muted small mb-4">{!! __('netfusion.import_desc') !!}</p>

                        <div class="input-group mb-3">
                            <input type="file" name="file" class="form-control form-control-lg rounded-pill" accept=".json"
                                required>
                        </div>

                        <button type="submit"
                            class="btn btn-primary w-100 rounded-pill py-2 fw-bold shadow-sm">{{ __('netfusion.upload_restore') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function togglePass(id) {
            var x = document.getElementById(id);
            if (x.type === "password") {
                x.type = "text";
            } else {
                x.type = "password";
            }
        }

        function editSession(data) {
            document.getElementById('modalTitle').innerText = '{{ __('netfusion.edit_session') }}';
            document.getElementById('sessionId').value = data.id;
            document.getElementById('sessionName').value = data.name;
            document.getElementById('sessionIp').value = data.ip;
            document.getElementById('sessionPort').value = data.port;
            document.getElementById('sessionUser').value = data.user;
            document.getElementById('sessionPass').value = '';
            document.getElementById('hotspotName').value = data.hotspot_name;
            document.getElementById('dnsName').value = data.dns_name;
            document.getElementById('currency').value = data.currency;
        }

        function clearSessionForm() {
            document.getElementById('modalTitle').innerText = '{{ __('netfusion.new_session') }}';
            document.getElementById('sessionForm').reset();
            document.getElementById('sessionId').value = '';

            // Clear errors
            document.getElementById('modalErrorAlert').classList.add('d-none');
            document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
        }

        document.getElementById('sessionForm').addEventListener('submit', function (e) {
            e.preventDefault();

            const form = this;
            const btn = document.getElementById('saveButton');
            const spinner = btn.querySelector('.spinner-border');
            const btnText = btn.querySelector('.btn-text');
            const alertBox = document.getElementById('modalErrorAlert');
            const alertMsg = document.getElementById('modalErrorMessage');

            // Reset UI
            alertBox.classList.add('d-none');
            document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));

            // Loading State
            btn.disabled = true;
            spinner.classList.remove('d-none');
            btnText.textContent = '{{ __('netfusion.saving') }}';

            const formData = new FormData(form);

            fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: formData
            })
                .then(response => response.json().then(data => ({ status: response.status, body: data })))
                .then(({ status, body }) => {
                    if (status === 200) {
                        location.reload();
                    } else if (status === 422) {
                        if (body.errors) {
                            Object.keys(body.errors).forEach(key => {
                                const input = form.querySelector(`[name="${key}"]`);
                                if (input) {
                                    input.classList.add('is-invalid');
                                    const feedback = input.parentElement.querySelector('.invalid-feedback');
                                    if (feedback) {
                                        feedback.textContent = body.errors[key][0];
                                    }
                                }
                            });
                            alertMsg.textContent = '{{ __('netfusion.correct_errors') }}';
                            alertBox.classList.remove('d-none');
                        }
                    } else {
                        throw new Error(body.message || 'Unknown error occurred');
                    }
                })
                .catch(error => {
                    console.error(error);
                    alertMsg.textContent = error.message || '{{ __('netfusion.unexpected_error') }}';
                    alertBox.classList.remove('d-none');
                })
                .finally(() => {
                    btn.disabled = false;
                    spinner.classList.add('d-none');
                    btnText.textContent = '{{ __('netfusion.save_configuration') }}';
                });
        });
    </script>

    <style>
        .file-card {
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .file-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.05) !important;
        }

        .ring-active {
            box-shadow: 0 10px 30px rgba(13, 110, 253, 0.3) !important;
            border: 2px solid #0d6efd !important;
        }

        .hover-scale:hover {
            transform: scale(1.02);
            transition: transform 0.2s;
        }
    </style>
@endsection