@extends('layouts.app')

@section('title', __('netfusion.ppp_active'))

@section('content')
    <div class="container-fluid px-4 py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-bold text-dark mb-1">{{ __('netfusion.ppp_active') }}</h4>
                <p class="text-muted small mb-0">{{ __('netfusion.monitor_live_connections') }}</p>
            </div>
        </div>

        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="px-4 py-3">{{ __('netfusion.secret_name') }}</th>
                                <th class="px-4 py-3">{{ __('netfusion.secret_service') }}</th>
                                <th class="px-4 py-3">{{ __('netfusion.caller_id') }}</th>
                                <th class="px-4 py-3">{{ __('netfusion.address_ip') }}</th>
                                <th class="px-4 py-3">{{ __('netfusion.uptime') }}</th>
                                <th class="px-4 py-3 text-end">{{ __('netfusion.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($active as $conn)
                                <tr>
                                    <td class="px-4 fw-bold text-primary">{{ $conn['name'] }}</td>
                                    <td class="px-4">{{ $conn['service'] }}</td>
                                    <td class="px-4 font-monospace">{{ $conn['caller-id'] ?? '-' }}</td>
                                    <td class="px-4">{{ $conn['address'] }}</td>
                                    <td class="px-4 text-muted">{{ $conn['uptime'] ?? '-' }}</td>
                                    <td class="px-4 text-end">
                                        <form action="{{ route('mikrotik-suite.netfusion.ppp.active.disconnect', $conn['.id']) }}"
                                            method="POST"
                                            onsubmit="return confirm('{{ __('netfusion.confirm_disconnect', ['name' => $conn['name']]) }}');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-light text-danger rounded-circle p-2"
                                                title="{{ __('netfusion.disconnect') }}">
                                                <i class="bi bi-x-lg"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5">{{ __('netfusion.no_active_connections') }}</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
