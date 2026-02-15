@extends('layouts.app')

@section('title', __('netfusion.dhcp_leases'))

@section('content')
    <div class="container-fluid px-4 py-4">

        <!-- Header -->
        <div class="row g-3 mb-4 align-items-center justify-content-between">
            <div class="col-12 col-md-8">
                <h4 class="fw-bold text-dark mb-1 d-flex align-items-center gap-2">
                    <div class="rounded-circle bg-white text-success p-2 shadow-sm d-flex align-items-center justify-content-center"
                        style="width: 48px; height: 48px;">
                        <i class="bi bi-hdd-network-fill fs-4"></i>
                    </div>
                    <div>
                        {{ __('netfusion.dhcp_leases') }}
                        <div class="text-muted small fw-normal mt-1" style="font-size: 0.85rem;">
                            {{ __('netfusion.monitor_manage_leases') }}
                        </div>
                    </div>
                </h4>
            </div>
            <div class="col-12 col-md-4 text-md-end">
                <span class="badge bg-white text-dark border px-3 py-2 rounded-pill shadow-sm">
                    <i class="bi bi-activity me-1 text-success"></i> {{ __('netfusion.realtime_status') }}
                </span>
            </div>
        </div>

        <!-- Glass Card -->
        <div class="card border-0 shadow-lg rounded-5 overflow-hidden position-relative" style="min-height: 500px;">

            <!-- Decorative BG -->
            <div class="position-absolute top-0 start-0 w-100 h-100 bg-light" style="z-index: 0;">
                <div class="position-absolute top-0 end-0 bg-success opacity-10 rounded-circle blur-3xl"
                    style="width: 500px; height: 500px; transform: translate(30%, -30%);"></div>
                <div class="position-absolute bottom-0 start-0 bg-info opacity-10 rounded-circle blur-3xl"
                    style="width: 400px; height: 400px; transform: translate(-30%, 30%);"></div>
            </div>

            <div class="position-relative p-0" style="z-index: 1;">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" style="background: rgba(255,255,255,0.7);">
                        <thead class="bg-light bg-opacity-75">
                            <tr>
                                <th class="px-4 py-3 text-uppercase text-secondary small fw-bold ls-1 border-0">{{ __('netfusion.ip_address') }}
                                </th>
                                <th class="px-4 py-3 text-uppercase text-secondary small fw-bold ls-1 border-0">{{ __('netfusion.mac_address') }}
                                </th>
                                <th class="px-4 py-3 text-uppercase text-secondary small fw-bold ls-1 border-0">{{ __('netfusion.hostname') }}
                                </th>
                                <th class="px-4 py-3 text-uppercase text-secondary small fw-bold ls-1 border-0">{{ __('netfusion.server') }}</th>
                                <th class="px-4 py-3 text-uppercase text-secondary small fw-bold ls-1 border-0">{{ __('netfusion.status') }}</th>
                                <th class="px-4 py-3 text-uppercase text-secondary small fw-bold ls-1 border-0 text-end">
                                    {{ __('netfusion.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody class="border-top-0">
                            @forelse($leases as $lease)
                                <tr class="transition-hover">
                                    <td class="px-4 py-3 border-light">
                                        <div class="d-flex align-items-center">
                                            <div class="rounded-circle bg-white shadow-sm p-1 text-success me-3"
                                                style="width: 32px; height: 32px; display:flex; align-items:center; justify-content:center;">
                                                <i class="bi bi-pc-display"></i>
                                            </div>
                                            <div class="fw-bold text-dark font-monospace">{{ $lease['address'] ?? '-' }}</div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 border-light">
                                        <span class="font-monospace small text-muted bg-white px-2 py-1 rounded border">
                                            {{ $lease['mac-address'] ?? '-' }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 border-light">
                                        @if(isset($lease['host-name']))
                                            <span class="fw-bold text-dark">{{ $lease['host-name'] }}</span>
                                        @else
                                            <span class="text-muted small fst-italic">{{ __('netfusion.unknown_device') }}</span>
                                        @endif

                                        @if(isset($lease['comment']))
                                            <div class="small text-muted mt-1"><i
                                                    class="bi bi-chat-quote me-1"></i>{{ $lease['comment'] }}</div>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 border-light text-muted small">
                                        {{ $lease['server'] ?? 'all' }}
                                    </td>
                                    <td class="px-4 py-3 border-light">
                                        <div class="d-flex align-items-center gap-2">
                                            @if(isset($lease['dynamic']) && $lease['dynamic'] == 'true')
                                                <span class="badge bg-light text-primary border rounded-pill px-3">
                                                    {{ __('netfusion.dynamic') }}
                                                </span>
                                                <small class="text-muted">{{ $lease['status'] ?? '' }}</small>
                                            @else
                                                <span
                                                    class="badge bg-success bg-opacity-10 text-success border border-success px-3 rounded-pill">
                                                    <i class="bi bi-pin-angle-fill me-1"></i> {{ __('netfusion.static') }}
                                                </span>
                                                <small class="text-muted">{{ $lease['status'] ?? '' }}</small>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 border-light text-end">
                                        <div class="d-flex justify-content-end gap-2">
                                            @if(isset($lease['dynamic']) && $lease['dynamic'] == 'true')
                                                <form action="{{ route('mikrotik-suite.netfusion.dhcp.leases.static', $lease['.id']) }}"
                                                    method="POST">
                                                    @csrf
                                                    <button type="submit"
                                                        class="btn btn-outline-primary btn-sm rounded-circle shadow-sm"
                                                        title="{{ __('netfusion.make_static') }}" data-bs-toggle="tooltip">
                                                        <i class="bi bi-pin-angle"></i>
                                                    </button>
                                                </form>
                                            @endif

                                            <form action="{{ route('mikrotik-suite.netfusion.dhcp.leases.destroy', $lease['.id']) }}"
                                                method="POST"
                                                onsubmit="return confirm('{{ __('netfusion.confirm_remove_lease', ['address' => $lease['address']]) }}');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="btn btn-outline-danger btn-sm rounded-circle shadow-sm hover-danger"
                                                    title="{{ __('netfusion.remove_lease') }}">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5 border-0">
                                        <div class="d-flex flex-column align-items-center justify-content-center opacity-50">
                                            <i class="bi bi-hdd-network fs-1 mb-3 text-secondary"></i>
                                            <h6 class="text-muted fw-bold">{{ __('netfusion.no_leases_found') }}</h6>
                                            <p class="text-muted small mb-0">{{ __('netfusion.no_leases_desc') }}</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <style>
        .blur-3xl {
            filter: blur(80px);
        }

        .ls-1 {
            letter-spacing: 0.5px;
        }

        .hover-danger:hover {
            background-color: #dc3545 !important;
            color: white !important;
            border-color: #dc3545 !important;
        }

        .transition-hover:hover {
            background-color: rgba(255, 255, 255, 0.5);
        }
    </style>
@endsection
