@extends('layouts.app')

@section('title', __('netfusion.ip_bindings'))

@section('content')
    <div class="container-fluid px-4 py-4">
        <!-- Header & Controls -->
        <div class="row g-3 mb-4 align-items-center justify-content-between">
            <div class="col-12 col-lg-auto">
                <h4 class="fw-bold text-dark mb-1 d-flex align-items-center gap-2">
                    {{ __('netfusion.ip_bindings') }}
                    <span
                        class="badge bg-warning bg-opacity-10 text-warning border border-warning fw-normal px-2 py-1 rounded-pill ms-2"
                        style="font-size: 0.75rem;">
                        {{ count($bindings) }}
                    </span>
                </h4>
                <div class="text-muted small d-flex align-items-center gap-2">
                    <i class="bi bi-link"></i> {{ __('netfusion.bypassed_regular') }}
                </div>
            </div>

            <div class="col-12 col-lg-auto d-flex gap-2 align-items-center">
                <!-- Add Button -->
                <button class="btn btn-primary fw-bold rounded-pill px-3 shadow-sm hover-scale flex-shrink-0"
                    data-bs-toggle="modal" data-bs-target="#addBindingModal">
                    <i class="bi bi-plus-lg"></i> <span
                        class="d-none d-md-inline ms-2">{{ __('netfusion.add_binding') }}</span>
                </button>

                <!-- Search -->
                <div class="position-relative w-100" style="max-width: 300px;">
                    <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3 text-secondary"></i>
                    <input type="text" id="searchInput"
                        class="form-control rounded-pill ps-5 border border-secondary border-opacity-25 shadow-sm"
                        placeholder="{{ __('netfusion.search_binding') }}" style="width: 100%;">
                </div>
            </div>
        </div>

        <!-- Main Glass Container -->
        <div class="glass-card border-0 shadow-lg rounded-4 overflow-hidden position-relative" style="min-height: 400px;">
            <!-- Decorative Background -->
            <div class="position-absolute top-0 start-0 w-100 h-100 overflow-hidden"
                style="z-index: 0; pointer-events: none;">
                <div class="position-absolute top-0 end-0 bg-warning opacity-10 rounded-circle blur-3xl"
                    style="width: 300px; height: 300px; margin-top: -100px; margin-right: -50px;"></div>
                <div class="position-absolute bottom-0 start-0 bg-info opacity-10 rounded-circle blur-3xl"
                    style="width: 250px; height: 250px; margin-bottom: -50px; margin-left: -50px;"></div>
            </div>

            <!-- Desktop View (Table) -->
            <div class="table-responsive d-none d-lg-block position-relative" style="z-index: 1;">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light bg-opacity-50 text-secondary text-uppercase small ls-1 fw-bold border-bottom">
                        <tr>
                            <th class="ps-4 py-3">{{ __('netfusion.mac_address') }}</th>
                            <th class="py-3">{{ __('netfusion.address') }}</th>
                            <th class="py-3">{{ __('netfusion.to_address') }}</th>
                            <th class="py-3">{{ __('netfusion.type') }}</th>
                            <th class="py-3">{{ __('netfusion.server') }}</th>
                            <th class="py-3">{{ __('netfusion.status') }}</th>
                            <th class="text-end pe-4 py-3">{{ __('netfusion.action') }}</th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0">
                        @forelse($bindings as $binding)
                            <tr class="user-item"
                                data-search="{{ strtolower(($binding['mac-address'] ?? '') . ' ' . ($binding['address'] ?? '') . ' ' . ($binding['comment'] ?? '')) }}">
                                <td class="ps-4">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="rounded-circle bg-warning bg-opacity-10 text-warning p-2 d-flex align-items-center justify-content-center"
                                            style="width: 36px; height: 36px;">
                                            <i class="bi bi-network-wire"></i>
                                        </div>
                                        <div class="d-flex flex-column">
                                            <span
                                                class="fw-bold text-dark font-monospace">{{ $binding['mac-address'] ?? '-' }}</span>
                                            <small class="text-muted"
                                                style="font-size: 11px;">{{ $binding['comment'] ?? '' }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td class="font-monospace text-muted">{{ $binding['address'] ?? '-' }}</td>
                                <td class="font-monospace text-muted">{{ $binding['to-address'] ?? '-' }}</td>
                                <td>
                                    @php
                                        $typeClass = match ($binding['type'] ?? 'regular') {
                                            'bypassed' => 'bg-success bg-opacity-10 text-success border-success',
                                            'blocked' => 'bg-danger bg-opacity-10 text-danger border-danger',
                                            default => 'bg-info bg-opacity-10 text-info border-info',
                                        };
                                    @endphp
                                    <span class="badge border {{ $typeClass }} text-uppercase"
                                        style="font-size: 10px;">{{ $binding['type'] ?? 'regular' }}</span>
                                </td>
                                <td><span class="badge bg-light text-dark border">{{ $binding['server'] ?? 'All' }}</span></td>
                                <td>
                                    @if(($binding['disabled'] ?? 'false') === 'true')
                                        <span class="badge bg-secondary">{{ __('netfusion.disabled') }}</span>
                                    @else
                                        <span class="badge bg-success">{{ __('netfusion.active') }}</span>
                                    @endif
                                </td>
                                <td class="text-end pe-4">
                                    <div class="d-flex justify-content-end gap-1">
                                        <!-- Edit -->
                                        <button class="btn btn-sm btn-light text-warning border hover-shadow rounded-circle p-2"
                                            data-bs-toggle="modal"
                                            data-bs-target="#editBindingModal{{ str_replace('*', '', $binding['.id']) }}">
                                            <i class="bi bi-pencil"></i>
                                        </button>

                                        <!-- Toggle -->
                                        @if(($binding['disabled'] ?? 'false') === 'true')
                                            <form action="{{ route('mikrotik-suite.netfusion.ip-binding.enable', $binding['.id']) }}"
                                                method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit"
                                                    class="btn btn-sm btn-light text-success border hover-shadow rounded-circle p-2"
                                                    title="{{ __('netfusion.enable') }}">
                                                    <i class="bi bi-check-lg"></i>
                                                </button>
                                            </form>
                                        @else
                                            <form action="{{ route('mikrotik-suite.netfusion.ip-binding.disable', $binding['.id']) }}"
                                                method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit"
                                                    class="btn btn-sm btn-light text-secondary border hover-shadow rounded-circle p-2"
                                                    title="{{ __('netfusion.disable') }}">
                                                    <i class="bi bi-x-lg"></i>
                                                </button>
                                            </form>
                                        @endif

                                        <!-- Delete -->
                                        <form action="{{ route('mikrotik-suite.netfusion.ip-binding.destroy', $binding['.id']) }}"
                                            method="POST" class="d-inline"
                                            onsubmit="return confirm('{{ __('netfusion.remove_binding_confirm') }}');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="btn btn-sm btn-light text-danger border hover-shadow rounded-circle p-2"
                                                title="{{ __('netfusion.remove') }}">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>

                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <div class="d-flex flex-column align-items-center opacity-50">
                                        <i class="bi bi-link fs-1 mb-2"></i>
                                        <h6>{{ __('netfusion.no_bindings_found') }}</h6>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Mobile View (Details Cards) -->
            <div class="d-block d-lg-none p-3 position-relative" style="z-index: 1;">
                @forelse($bindings as $binding)
                    <div class="card border-0 shadow-sm mb-3 rounded-4 user-item hover-scale"
                        data-search="{{ strtolower(($binding['mac-address'] ?? '') . ' ' . ($binding['address'] ?? '') . ' ' . ($binding['comment'] ?? '')) }}">
                        <div class="card-body p-3">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="rounded-circle bg-warning bg-opacity-10 text-warning p-2 d-flex align-items-center justify-content-center"
                                        style="width: 40px; height: 40px;">
                                        <i class="bi bi-network-wire fs-5"></i>
                                    </div>
                                    <div>
                                        <h6 class="fw-bold text-dark mb-0 font-monospace">{{ $binding['mac-address'] ?? '-' }}
                                        </h6>
                                        <span
                                            class="badge bg-light text-secondary border">{{ $binding['type'] ?? 'regular' }}</span>
                                    </div>
                                </div>

                                <div class="dropdown">
                                    <button class="btn btn-light border rounded-circle p-2 shadow-sm" type="button"
                                        data-bs-toggle="dropdown" style="width: 36px; height: 36px;">
                                        <i class="bi bi-three-dots-vertical"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end shadow border-0 rounded-3">
                                        <li>
                                            <button class="dropdown-item" data-bs-toggle="modal"
                                                data-bs-target="#editBindingModal{{ str_replace('*', '', $binding['.id']) }}">
                                                <i class="bi bi-pencil me-2 text-warning"></i> {{ __('netfusion.edit') }}
                                            </button>
                                        </li>
                                        @if(($binding['disabled'] ?? 'false') === 'true')
                                            <li>
                                                <form action="{{ route('mikrotik-suite.netfusion.ip-binding.enable', $binding['.id']) }}"
                                                    method="POST">
                                                    @csrf <button class="dropdown-item"><i
                                                            class="bi bi-check-lg me-2 text-success"></i>
                                                        {{ __('netfusion.enable') }}</button>
                                                </form>
                                            </li>
                                        @else
                                            <li>
                                                <form action="{{ route('mikrotik-suite.netfusion.ip-binding.disable', $binding['.id']) }}"
                                                    method="POST">
                                                    @csrf <button class="dropdown-item"><i class="bi bi-x-lg me-2 text-danger"></i>
                                                        {{ __('netfusion.disable') }}</button>
                                                </form>
                                            </li>
                                        @endif
                                        <li>
                                            <hr class="dropdown-divider">
                                        </li>
                                        <li>
                                            <form action="{{ route('mikrotik-suite.netfusion.ip-binding.destroy', $binding['.id']) }}"
                                                method="POST"
                                                onsubmit="return confirm('{{ __('netfusion.remove_question') }}');">
                                                @csrf @method('DELETE')
                                                <button class="dropdown-item text-danger"><i class="bi bi-trash me-2"></i>
                                                    {{ __('netfusion.remove') }}</button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="row g-2 small mb-0">
                                <div class="col-6">
                                    <div class="bg-light rounded p-2 border border-light">
                                        <span class="d-block text-muted"
                                            style="font-size: 10px;">{{ __('netfusion.address_upper') }}</span>
                                        <span class="font-monospace fw-bold">{{ $binding['address'] ?? '-' }}</span>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="bg-light rounded p-2 border border-light">
                                        <span class="d-block text-muted"
                                            style="font-size: 10px;">{{ __('netfusion.to_address_upper') }}</span>
                                        <span class="font-monospace fw-bold">{{ $binding['to-address'] ?? '-' }}</span>
                                    </div>
                                </div>
                                @if(isset($binding['comment']))
                                    <div class="col-12 mt-1">
                                        <span class="text-muted"><i class="bi bi-chat-text me-1"></i>
                                            {{ $binding['comment'] }}</span>
                                    </div>
                                @endif
                                <div class="col-12 mt-1">
                                    <span
                                        class="badge {{ ($binding['disabled'] ?? 'false') === 'true' ? 'bg-secondary' : 'bg-success' }}">
                                        {{ ($binding['disabled'] ?? 'false') === 'true' ? __('netfusion.disabled_upper') : __('netfusion.active_upper') }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-5">
                        <div class="d-flex flex-column align-items-center opacity-50">
                            <i class="bi bi-link fs-1 mb-2"></i>
                            <h6>{{ __('netfusion.no_bindings_found') }}</h6>
                        </div>
                    </div>
                @endforelse

                <div id="noSearchResults" class="text-center py-5 d-none">
                    <i class="bi bi-search fs-1 text-muted opacity-50 mb-2"></i>
                    <h6 class="text-muted">{{ __('netfusion.no_bindings_found_query') }}</h6>
                </div>
            </div>

            <div class="card-footer bg-transparent border-top p-3 d-flex justify-content-between align-items-center small text-muted position-relative"
                style="z-index: 1;">
                <span id="showingCount">{{ __('netfusion.showing_bindings', ['count' => count($bindings)]) }}</span>
            </div>
        </div>
    </div>

    <!-- Edit Modals Loop (Moved Outside Glass Card to prevent Z-Index/Overflow Locking) -->
    @foreach($bindings as $binding)
        <div class="modal fade" id="editBindingModal{{ str_replace('*', '', $binding['.id']) }}" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content bg-white shadow-lg rounded-4 border-0">
                    <div class="modal-header border-bottom">
                        <h5 class="modal-title fw-bold text-dark">{{ __('netfusion.edit_binding') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form action="{{ route('mikrotik-suite.netfusion.ip-binding.update', $binding['.id']) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label text-secondary small fw-bold">{{ __('netfusion.mac_address') }}</label>
                                <input type="text" name="mac_address"
                                    class="form-control bg-light border border-secondary border-opacity-25 text-dark"
                                    value="{{ $binding['mac-address'] ?? '' }}" required>
                            </div>
                            <div class="row g-3 mb-3">
                                <div class="col-6">
                                    <label class="form-label text-secondary small fw-bold">{{ __('netfusion.address') }}</label>
                                    <input type="text" name="address"
                                        class="form-control bg-light border border-secondary border-opacity-25 text-dark"
                                        value="{{ $binding['address'] ?? '' }}">
                                </div>
                                <div class="col-6">
                                    <label
                                        class="form-label text-secondary small fw-bold">{{ __('netfusion.to_address') }}</label>
                                    <input type="text" name="to_address"
                                        class="form-control bg-light border border-secondary border-opacity-25 text-dark"
                                        value="{{ $binding['to-address'] ?? '' }}">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-secondary small fw-bold">{{ __('netfusion.type') }}</label>
                                <select name="type"
                                    class="form-select bg-light border border-secondary border-opacity-25 text-dark">
                                    <option value="regular" {{ ($binding['type'] ?? '') == 'regular' ? 'selected' : '' }}>
                                        {{ __('netfusion.regular') }}
                                    </option>
                                    <option value="bypassed" {{ ($binding['type'] ?? '') == 'bypassed' ? 'selected' : '' }}>
                                        {{ __('netfusion.bypassed') }}
                                    </option>
                                    <option value="blocked" {{ ($binding['type'] ?? '') == 'blocked' ? 'selected' : '' }}>
                                        {{ __('netfusion.blocked') }}
                                    </option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-secondary small fw-bold">Comment</label>
                                <input type="text" name="comment"
                                    class="form-control bg-light border border-secondary border-opacity-25 text-dark"
                                    value="{{ $binding['comment'] ?? '' }}">
                            </div>
                        </div>
                        <div class="modal-footer border-top bg-light">
                            <button type="button" class="btn btn-white border"
                                data-bs-dismiss="modal">{{ __('netfusion.cancel') }}</button>
                            <button type="submit"
                                class="btn btn-primary px-4 rounded-pill">{{ __('netfusion.save_changes') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach

    <!-- Add Modal (Global) - Light Theme -->
    <div class="modal fade" id="addBindingModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <!-- FIX: Solved Contrast Issue using bg-white -->
            <div class="modal-content bg-white shadow-lg rounded-4 border-0">
                <div class="modal-header border-bottom">
                    <h5 class="modal-title fw-bold text-dark">{{ __('netfusion.add_ip_binding') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('mikrotik-suite.netfusion.ip-binding.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label text-secondary small fw-bold">{{ __('netfusion.mac_address') }}</label>
                            <input type="text" name="mac_address"
                                class="form-control bg-light border border-secondary border-opacity-25 text-dark"
                                placeholder="XX:XX:XX:XX:XX:XX" required>
                        </div>
                        <div class="row g-3 mb-3">
                            <div class="col-6">
                                <label class="form-label text-secondary small fw-bold">{{ __('netfusion.address') }}</label>
                                <input type="text" name="address" class="form-control bg-light border-light text-dark"
                                    placeholder="{{ __('netfusion.ip_address') }}">
                            </div>
                            <div class="col-6">
                                <label
                                    class="form-label text-secondary small fw-bold">{{ __('netfusion.to_address') }}</label>
                                <input type="text" name="to_address" class="form-control bg-light border-light text-dark"
                                    placeholder="{{ __('netfusion.translated_ip') }}">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-secondary small fw-bold">{{ __('netfusion.type') }}</label>
                            <select name="type" class="form-select bg-light border-light text-dark">
                                <option value="regular">{{ __('netfusion.regular') }}</option>
                                <option value="bypassed">{{ __('netfusion.bypassed') }}</option>
                                <option value="blocked">{{ __('netfusion.blocked') }}</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-secondary small fw-bold">{{ __('netfusion.server') }}</label>
                            <select name="server"
                                class="form-select bg-light border border-secondary border-opacity-25 text-dark">
                                <option value="all">All</option>
                                <!-- Ideal: Loop servers here -->
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-secondary small fw-bold">{{ __('netfusion.comment') }}</label>
                            <input type="text" name="comment" class="form-control bg-light border-light text-dark"
                                placeholder="{{ __('netfusion.optional_notes') }}">
                        </div>
                    </div>
                    <div class="modal-footer border-top bg-light">
                        <button type="button" class="btn btn-white border"
                            data-bs-dismiss="modal">{{ __('netfusion.cancel') }}</button>
                        <button type="submit"
                            class="btn btn-primary px-4 rounded-pill">{{ __('netfusion.add_binding') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

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

            let noResults = document.getElementById('noSearchResults');
            if (visibleCount === 0 && items.length > 0) {
                if (noResults) noResults.classList.remove('d-none');
            } else {
                if (noResults) noResults.classList.add('d-none');
            }

            // Update Counter
            let showingEl = document.getElementById('showingCount');
            if (showingEl) showingEl.innerText = '{{ __('netfusion.showing_bindings', ['count' => '']) }}' + visibleCount + ' {{ strtolower(__('netfusion.ip_bindings')) }}'; // Simplified for JS
        });
    </script>

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

        .hover-shadow:hover {
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
    </style>
@endsection
