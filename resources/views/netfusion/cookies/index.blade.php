@extends('layouts.app')

@section('title', __('netfusion.hotspot_cookies'))

@section('content')
    <div class="container-fluid px-4 py-4">
        <!-- Header & Controls -->
        <div class="row g-3 mb-4 align-items-center justify-content-between">
            <div class="col-12 col-lg-auto">
                <h4 class="fw-bold text-dark mb-1 d-flex align-items-center gap-2">
                    {{ __('netfusion.hotspot_cookies') }}
                    <span
                        class="badge bg-danger bg-opacity-10 text-danger border border-danger fw-normal px-2 py-1 rounded-pill ms-2"
                        style="font-size: 0.75rem;">
                        {{ count($cookies) }}
                    </span>
                </h4>
                <div class="text-muted small d-flex align-items-center gap-2">
                    <i class="bi bi-cookie"></i> {{ __('netfusion.active_user_sessions') }}
                </div>
            </div>

            <div class="col-12 col-lg-auto d-flex gap-2 align-items-center">
                <!-- Search -->
                <div class="position-relative w-100" style="max-width: 300px;">
                    <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3 text-secondary"></i>
                    <input type="text" id="searchInput"
                        class="form-control rounded-pill ps-5 border border-secondary border-opacity-25 shadow-sm"
                        placeholder="{{ __('netfusion.search_cookie') }}" style="width: 100%;">
                </div>
            </div>
        </div>

        <!-- Main Glass Container -->
        <div class="glass-card border-0 shadow-lg rounded-4 overflow-hidden position-relative" style="min-height: 400px;">
            <!-- Decorative Background -->
            <div class="position-absolute top-0 start-0 w-100 h-100 overflow-hidden"
                style="z-index: 0; pointer-events: none;">
                <div class="position-absolute top-0 end-0 bg-danger opacity-10 rounded-circle blur-3xl"
                    style="width: 300px; height: 300px; margin-top: -100px; margin-right: -50px;"></div>
                <div class="position-absolute bottom-0 start-0 bg-warning opacity-10 rounded-circle blur-3xl"
                    style="width: 250px; height: 250px; margin-bottom: -50px; margin-left: -50px;"></div>
            </div>

            <!-- Desktop View (Table) -->
            <div class="table-responsive d-none d-lg-block position-relative" style="z-index: 1;">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light bg-opacity-50 text-secondary text-uppercase small ls-1 fw-bold border-bottom">
                        <tr>
                            <th class="ps-4 py-3">{{ __('netfusion.mac_address') }}</th>
                            <th class="py-3">{{ __('netfusion.user') }}</th>
                            <th class="py-3">{{ __('netfusion.domain') }}</th>
                            <th class="py-3">{{ __('netfusion.expires_in') }}</th>
                            <th class="text-end pe-4 py-3">{{ __('netfusion.action') }}</th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0">
                        @forelse($cookies as $cookie)
                            <tr class="user-item"
                                data-search="{{ strtolower(($cookie['mac-address'] ?? '') . ' ' . ($cookie['user'] ?? '')) }}">
                                <td class="ps-4">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="rounded-circle bg-danger bg-opacity-10 text-danger p-2 d-flex align-items-center justify-content-center"
                                            style="width: 36px; height: 36px;">
                                            <i class="bi bi-laptop"></i>
                                        </div>
                                        <span
                                            class="fw-bold text-dark font-monospace">{{ $cookie['mac-address'] ?? '-' }}</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="bi bi-person text-secondary"></i>
                                        <span class="fw-bold">{{ $cookie['user'] ?? '-' }}</span>
                                    </div>
                                </td>
                                <td><span class="badge bg-light text-dark border">{{ $cookie['domain'] ?? '-' }}</span></td>
                                <td class="text-secondary font-monospace small">
                                    <i class="bi bi-hourglass-split me-1"></i> {{ $cookie['expires-in'] ?? '-' }}
                                </td>
                                <td class="text-end pe-4">
                                    <form action="{{ route('mikrotik-suite.netfusion.cookies.destroy', $cookie['.id']) }}"
                                        method="POST" class="d-inline"
                                        onsubmit="return confirm('{{ __('netfusion.remove_cookie_confirm') }}');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="btn btn-sm btn-light text-danger border hover-shadow rounded-circle p-2"
                                            title="{{ __('netfusion.remove') }}">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5">
                                    <div class="d-flex flex-column align-items-center opacity-50">
                                        <i class="bi bi-cookie fs-1 mb-2"></i>
                                        <h6>{{ __('netfusion.no_cookies_found') }}</h6>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Mobile View (Details Cards) -->
            <div class="d-block d-lg-none p-3 position-relative" style="z-index: 1;">
                @forelse($cookies as $cookie)
                    <div class="card border-0 shadow-sm mb-3 rounded-4 user-item hover-scale"
                        data-search="{{ strtolower(($cookie['mac-address'] ?? '') . ' ' . ($cookie['user'] ?? '')) }}">
                        <div class="card-body p-3">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="rounded-circle bg-danger bg-opacity-10 text-danger p-2 d-flex align-items-center justify-content-center"
                                        style="width: 40px; height: 40px;">
                                        <i class="bi bi-laptop fs-5"></i>
                                    </div>
                                    <div>
                                        <h6 class="fw-bold text-dark mb-0 font-monospace">{{ $cookie['mac-address'] ?? '-' }}
                                        </h6>
                                        <span class="badge bg-light text-dark border">{{ $cookie['domain'] ?? '-' }}</span>
                                    </div>
                                </div>
                                <form action="{{ route('mikrotik-suite.netfusion.cookies.destroy', $cookie['.id']) }}" method="POST"
                                    onsubmit="return confirm('{{ __('netfusion.remove_question') }}');">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-light border rounded-circle p-2 shadow-sm text-danger"
                                        style="width: 36px; height: 36px;">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                            <div class="row g-2 small mb-0">
                                <div class="col-6">
                                    <div class="bg-light rounded p-2 border border-light">
                                        <span class="d-block text-muted"
                                            style="font-size: 10px;">{{ __('netfusion.user') }}</span>
                                        <span class="fw-bold">{{ $cookie['user'] ?? '-' }}</span>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="bg-light rounded p-2 border border-light">
                                        <span class="d-block text-muted"
                                            style="font-size: 10px;">{{ __('netfusion.expires_in') }}</span>
                                        <span class="font-monospace text-secondary">{{ $cookie['expires-in'] ?? '-' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-5">
                        <div class="d-flex flex-column align-items-center opacity-50">
                            <i class="bi bi-cookie fs-1 mb-2"></i>
                            <h6>{{ __('netfusion.no_cookies_found') }}</h6>
                        </div>
                    </div>
                @endforelse

                <div id="noSearchResults" class="text-center py-5 d-none">
                    <i class="bi bi-search fs-1 text-muted opacity-50 mb-2"></i>
                    <h6 class="text-muted">{{ __('netfusion.no_cookies_found_query') }}</h6>
                </div>
            </div>

            <div class="card-footer bg-transparent border-top p-3 d-flex justify-content-between align-items-center small text-muted position-relative"
                style="z-index: 1;">
                <span id="showingCount">{{ __('netfusion.showing_cookies', ['count' => count($cookies)]) }}</span>
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
            if (showingEl) showingEl.innerText = '{{ __('netfusion.showing_cookies', ['count' => '']) }}' + visibleCount + ' {{ strtolower(__('netfusion.hotspot_cookies')) }}'; // Simplified for JS
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
