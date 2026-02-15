@extends('layouts.app')

@section('title', __('netfusion.active_sessions'))

@section('content')
    <div class="container-fluid px-4 py-4">
        <!-- Header & Controls -->
        <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center mb-4 gap-3">
            <div>
                <h4 class="fw-bold text-dark mb-1">{{ __('netfusion.active_sessions') }}</h4>
                <div class="d-flex align-items-center gap-2">
                    <span
                        class="badge bg-success bg-opacity-10 text-success border border-success px-3 rounded-pill d-flex align-items-center gap-2">
                        <span class="spinner-grow spinner-grow-sm text-success" style="width: 0.5rem; height: 0.5rem;"
                            role="status"></span>
                        {{ __('netfusion.live_monitor') }}
                    </span>
                    <span class="text-muted small">{{ __('netfusion.real_time_activity') }} <span
                            class="d-none d-md-inline">&bull; {{ __('netfusion.auto_refresh_enabled') }}</span></span>
                </div>
            </div>

            <div class="d-flex gap-2 w-100 w-lg-auto align-items-center">
                <!-- Search Input -->
                <div class="position-relative flex-grow-1">
                    <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3 text-secondary"></i>
                    <input type="text" id="searchInput"
                        class="form-control rounded-pill ps-5 border border-secondary border-opacity-25 shadow-sm"
                        placeholder="{{ __('topbar.search') }}..." style="min-width: 0;">
                </div>

                <!-- Actions -->
                <div class="d-flex gap-2 flex-shrink-0">
                    <form action="{{ route('mikrotik-suite.netfusion.active.disconnect-multiple') }}" method="POST"
                        id="batchDisconnectForm" class="d-inline">
                        @csrf
                        <button type="submit"
                            class="btn btn-danger fw-bold rounded-pill px-3 px-md-4 shadow-sm d-none transition-all"
                            id="batchDisconnectBtn">
                            <i class="bi bi-x-circle"></i> <span
                                class="d-none d-md-inline ms-2">{{ __('netfusion.disconnect') }}</span>
                        </button>
                    </form>
                    <a href="{{ route('mikrotik-suite.netfusion.active.index') }}"
                        class="btn btn-white text-primary border fw-bold rounded-pill px-3 px-md-4 shadow-sm hover-scale hover-refresh">
                        <i class="bi bi-arrow-clockwise"></i> <span
                            class="d-none d-md-inline ms-2">{{ __('netfusion.refresh') }}</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Main Glass Container -->
        <div class="glass-card border-0 shadow-lg rounded-4 overflow-hidden position-relative" style="min-height: 400px;">
            <!-- Decorative Background -->
            <div class="position-absolute top-0 start-0 w-100 h-100 overflow-hidden"
                style="z-index: 0; pointer-events: none;">
                <div class="position-absolute top-0 end-0 bg-primary opacity-10 rounded-circle blur-3xl"
                    style="width: 300px; height: 300px; margin-top: -100px; margin-right: -50px;"></div>
                <div class="position-absolute bottom-0 start-0 bg-success opacity-10 rounded-circle blur-3xl"
                    style="width: 250px; height: 250px; margin-bottom: -50px; margin-left: -50px;"></div>
            </div>

            <div class="card-header bg-transparent border-bottom p-4 d-flex justify-content-between align-items-center position-relative"
                style="z-index: 1;">
                <div class="d-flex align-items-center gap-3">
                    <div
                        class="rounded-circle bg-primary bg-opacity-10 text-primary p-3 d-flex align-items-center justify-content-center">
                        <i class="bi bi-broadcast fs-4"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold text-dark mb-0">{{ __('netfusion.connected_users') }}</h5>
                        <p class="text-muted small mb-0">{{ __('netfusion.total_active') }}: <span
                                class="fw-bold text-primary" id="activeCount">{{ count($activeUsers) }}</span></p>
                    </div>
                </div>
            </div>

            <!-- Desktop View (Table) -->
            <div class="table-responsive d-none d-lg-block position-relative" style="z-index: 1;">
                <table class="table table-hover align-middle mb-0" id="usersTable">
                    <thead class="bg-light bg-opacity-50 text-secondary text-uppercase small ls-1 fw-bold border-bottom">
                        <tr>
                            <th class="ps-4 py-3" style="width: 50px;">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="checkAllDesktop"
                                        style="cursor: pointer;">
                                </div>
                            </th>
                            <th class="py-3">{{ __('netfusion.user_info') }}</th>
                            <th class="py-3">{{ __('netfusion.ip_address') }}</th>
                            <th class="py-3">{{ __('netfusion.mac_address') }}</th>
                            <th class="py-3">{{ __('netfusion.session') }}</th>
                            <th class="py-3">{{ __('netfusion.traffic') }}</th>
                            <th class="text-end pe-4 py-3">{{ __('netfusion.action') }}</th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0" id="deskTableBody">
                        @forelse($activeUsers as $user)
                            <tr class="active-row user-item"
                                data-search="{{ strtolower($user['user'] . ' ' . ($user['address'] ?? '') . ' ' . ($user['mac-address'] ?? '')) }}">
                                <td class="ps-4">
                                    <div class="form-check">
                                        <input class="form-check-input user-check" type="checkbox" name="users[]"
                                            value="{{ $user['.id'] }}" form="batchDisconnectForm">
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="position-relative me-3">
                                            <div class="rounded-circle bg-white shadow-sm p-2 text-primary border d-flex align-items-center justify-content-center"
                                                style="width: 40px; height: 40px;">
                                                <i class="bi bi-person-fill"></i>
                                            </div>
                                            <span
                                                class="position-absolute bottom-0 start-100 translate-middle p-1 bg-success border border-white rounded-circle"></span>
                                        </div>
                                        <div class="fw-bold text-dark">{{ $user['user'] ?? 'Unknown' }}</div>
                                    </div>
                                </td>
                                <td class="font-monospace text-muted small"><i
                                        class="bi bi-globe me-1"></i>{{ $user['address'] ?? '-' }}</td>
                                <td class="font-monospace text-muted small">{{ $user['mac-address'] ?? '-' }}</td>
                                <td>
                                    <div class="d-flex flex-column small">
                                        <span class="text-dark fw-bold">{{ $user['uptime'] ?? '-' }}</span>
                                        @if(isset($user['session-time-left']))
                                            <span class="text-warning small text-nowrap"><i class="bi bi-hourglass-split"></i>
                                                {{ $user['session-time-left'] }}</span>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex flex-column small fw-bold">
                                        <span class="text-success"><i
                                                class="bi bi-arrow-down-short"></i>{{ isset($user['bytes-in']) ? \App\Helpers\Format::bytes($user['bytes-in']) : '0B' }}</span>
                                        <span class="text-danger"><i
                                                class="bi bi-arrow-up-short"></i>{{ isset($user['bytes-out']) ? \App\Helpers\Format::bytes($user['bytes-out']) : '0B' }}</span>
                                    </div>
                                </td>
                                <td class="text-end pe-4">
                                    <button type="button"
                                        class="btn btn-sm btn-light text-danger border hover-shadow rounded-circle p-2"
                                        onclick="confirmDisconnect('{{ $user['user'] }}', '{{ route('mikrotik-suite.netfusion.active.disconnect', $user['.id']) }}')">
                                        <i class="bi bi-power"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr class="no-data-row">
                                <td colspan="7" class="text-center py-5">
                                    <div class="d-flex flex-column align-items-center opacity-50">
                                        <i class="bi bi-broadcast fs-1 mb-2"></i>
                                        <h6>{{ __('netfusion.no_active_sessions_found') }}</h6>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Mobile View (Details Cards) -->
            <div class="d-block d-lg-none p-3 position-relative" style="z-index: 1;" id="mobileCardContainer">
                <div class="d-flex align-items-center justify-content-between mb-3 px-1">
                    <span class="small text-muted text-uppercase fw-bold ls-1">{{ __('netfusion.user_list') }}</span>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="checkAllMobile">
                        <label class="form-check-label small" for="checkAllMobile">{{ __('netfusion.select_all') }}</label>
                    </div>
                </div>

                @forelse($activeUsers as $user)
                    <div class="card border-0 shadow-sm mb-3 rounded-4 user-item hover-scale"
                        data-search="{{ strtolower($user['user'] . ' ' . ($user['address'] ?? '') . ' ' . ($user['mac-address'] ?? '')) }}">
                        <div class="card-body p-3">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="form-check pt-1">
                                        <input class="form-check-input user-check" type="checkbox" name="users[]"
                                            value="{{ $user['.id'] }}" form="batchDisconnectForm">
                                    </div>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="rounded-circle bg-primary bg-opacity-10 text-primary p-2 d-flex align-items-center justify-content-center"
                                            style="width: 40px; height: 40px;">
                                            <i class="bi bi-laptop fs-5"></i>
                                        </div>
                                        <div>
                                            <h6 class="fw-bold text-dark mb-0">{{ $user['user'] ?? 'Unknown' }}</h6>
                                            <span
                                                class="badge bg-success bg-opacity-10 text-success rounded-pill px-2 py-0 border border-success border-opacity-25"
                                                style="font-size: 10px;">{{ __('netfusion.online') }}</span>
                                        </div>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-light text-danger border rounded-circle p-2 shadow-sm"
                                    style="width: 36px; height: 36px;"
                                    onclick="confirmDisconnect('{{ $user['user'] }}', '{{ route('mikrotik-suite.netfusion.active.disconnect', $user['.id']) }}')">
                                    <i class="bi bi-power"></i>
                                </button>
                            </div>

                            <!-- Grid Details -->
                            <div class="row g-2 small mb-0">
                                <div class="col-6">
                                    <div class="bg-light rounded p-2 border border-light">
                                        <span class="d-block text-muted"
                                            style="font-size: 10px;">{{ strtoupper(__('netfusion.ip_address')) }}</span>
                                        <span class="font-monospace fw-bold">{{ $user['address'] ?? '-' }}</span>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="bg-light rounded p-2 border border-light">
                                        <span class="d-block text-muted"
                                            style="font-size: 10px;">{{ strtoupper(__('netfusion.mac_address')) }}</span>
                                        <span
                                            class="font-monospace fw-bold text-truncate d-block">{{ $user['mac-address'] ?? '-' }}</span>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="bg-light rounded p-2 border border-light">
                                        <span class="d-block text-muted"
                                            style="font-size: 10px;">{{ strtoupper(__('netfusion.session')) }}</span>
                                        <span class="fw-bold">{{ $user['uptime'] ?? '-' }}</span>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="bg-light rounded p-2 border border-light">
                                        <span class="d-block text-muted"
                                            style="font-size: 10px;">{{ strtoupper(__('netfusion.data_usage')) }}</span>
                                        <span class="text-success fw-bold"><i
                                                class="bi bi-arrow-down-short"></i>{{ isset($user['bytes-in']) ? \App\Helpers\Format::bytes($user['bytes-in']) : '0B' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-5 no-data-msg">
                        <img src="https://cdni.iconscout.com/illustration/premium/thumb/empty-state-2130362-1800926.png"
                            style="width: 120px; opacity: 0.5;" class="mb-3">
                        <h6 class="text-muted fw-bold">{{ __('netfusion.no_active_sessions_found') }}</h6>
                    </div>
                @endforelse

                <div id="noSearchResults" class="text-center py-5 d-none">
                    <i class="bi bi-search fs-1 text-muted opacity-50 mb-2"></i>
                    <h6 class="text-muted">{{ __('netfusion.no_users_found') }}</h6>
                </div>
            </div>

            <div
                class="card-footer bg-transparent border-top p-3 d-flex justify-content-between align-items-center small text-muted">
                <span id="showingCount">{{ __('netfusion.showing_users', ['count' => count($activeUsers)]) }}</span>
                <span>{{ __('netfusion.live_data') }}</span>
            </div>
        </div>
    </div>

    <!-- Hidden Disconnect Form -->
    <form id="disconnectSingleForm" action="" method="POST" style="display: none;">
        @csrf
    </form>

    @push('scripts')
        <script>
            // Search Functionality
            document.getElementById('searchInput').addEventListener('keyup', function () {
                let filter = this.value.toLowerCase();
                let items = document.querySelectorAll('.user-item');
                let visibleCount = 0;

                items.forEach(function (item) {
                    let text = item.getAttribute('data-search');
                    if (text.includes(filter)) {
                        item.classList.remove('d-none');
                        visibleCount++;
                    } else {
                        item.classList.add('d-none');
                    }
                });

                // Show/Hide No Results Message
                let noResults = document.getElementById('noSearchResults');
                if (visibleCount === 0 && items.length > 0) {
                    if (noResults) noResults.classList.remove('d-none');
                } else {
                    if (noResults) noResults.classList.add('d-none');
                }

                // Update Counter
                document.getElementById('showingCount').innerText = 'Showing ' + visibleCount + ' users';
            });

            // Validated Check All (Syncs both Desktop and Mobile checkboxes if needed, or simple separate logic)
            function toggleCheckboxes(source, targetClass) {
                document.querySelectorAll(targetClass).forEach(checkbox => {
                    checkbox.checked = source.checked;
                });
                toggleBatchBtn();
            }

            document.getElementById('checkAllDesktop').addEventListener('change', function () { toggleCheckboxes(this, '.user-check'); });
            document.getElementById('checkAllMobile').addEventListener('change', function () { toggleCheckboxes(this, '.user-check'); });

            document.querySelectorAll('.user-check').forEach(cb => {
                cb.addEventListener('change', toggleBatchBtn);
            });

            function toggleBatchBtn() {
                let count = document.querySelectorAll('.user-check:checked').length;
                let btn = document.getElementById('batchDisconnectBtn');
                if (count > 0) {
                    btn.classList.remove('d-none');
                    btn.innerHTML = '<i class="bi bi-power me-2"></i>Disconnect (' + count + ')';
                    btn.style.animation = 'fadeIn 0.3s ease';
                } else {
                    btn.classList.add('d-none');
                }
            }

            // Custom Confirmation
            window.confirmDisconnect = function (name, url) {
                if (confirm('Are you sure you want to disconnect ' + name + '?')) {
                    let form = document.getElementById('disconnectSingleForm');
                    form.action = url;
                    form.submit();
                }
            }
        </script>
    @endpush

    <style>
        .glass-card {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.5);
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.05);
        }

        .blur-3xl {
            filter: blur(60px);
        }

        .hover-scale:hover {
            transform: translateY(-2px);
            transition: transform 0.2s ease;
        }

        .hover-refresh:hover i {
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            100% {
                transform: rotate(360deg);
            }
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
@endsection
