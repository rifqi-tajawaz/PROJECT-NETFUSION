@extends('layouts.app')

@section('title', __('netfusion.ppp_profiles'))

@section('content')
    <div class="container-fluid px-4 py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-bold text-dark mb-1">{{ __('netfusion.ppp_profiles') }}</h4>
                <p class="text-muted small mb-0">{{ __('netfusion.manage_connection_profiles') }}</p>
            </div>
            <button class="btn btn-primary fw-bold rounded-pill px-4 shadow-sm hover-scale" data-bs-toggle="modal"
                data-bs-target="#addProfileModal">
                <i class="bi bi-plus-lg me-2"></i>{{ __('netfusion.add_profile') }}
            </button>
        </div>

        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="px-4 py-3">{{ __('netfusion.secret_name') }}</th>
                                <th class="px-4 py-3">{{ __('netfusion.local_address') }}</th>
                                <th class="px-4 py-3">{{ __('netfusion.remote_address') }}</th>
                                <th class="px-4 py-3">{{ __('netfusion.rate_limit') }}</th>
                                <th class="px-4 py-3">{{ __('netfusion.dns_server') }}</th>
                                <th class="px-4 py-3 text-end">{{ __('netfusion.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($profiles as $profile)
                                <tr>
                                    <td class="px-4 fw-bold text-dark">{{ $profile['name'] }}</td>
                                    <td class="px-4">{{ $profile['local-address'] ?? '-' }}</td>
                                    <td class="px-4">{{ $profile['remote-address'] ?? '-' }}</td>
                                    <td class="px-4">{{ $profile['rate-limit'] ?? '-' }}</td>
                                    <td class="px-4 small font-monospace">{{ $profile['dns-server'] ?? '-' }}</td>
                                    <td class="px-4 text-end">
                                        <form action="{{ route('mikrotik-suite.netfusion.ppp.profiles.destroy', $profile['.id']) }}"
                                            method="POST"
                                            onsubmit="return confirm('{{ __('netfusion.confirm_delete', ['name' => $profile['name']]) }}');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-light text-danger rounded-circle p-2">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5">{{ __('netfusion.no_profiles_found') }}</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Modal -->
    <div class="modal fade" id="addProfileModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold">{{ __('netfusion.add_ppp_profile_modal') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('mikrotik-suite.netfusion.ppp.profiles.store') }}" method="POST">
                        @csrf
                        <div class="form-floating mb-3">
                            <input type="text" name="name" class="form-control" id="name" required placeholder="Name">
                            <label for="name">{{ __('netfusion.profile_name') }}</label>
                        </div>
                        <div class="row g-2 mb-3">
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" name="local_address" class="form-control" id="local"
                                        placeholder="IP">
                                    <label for="local">{{ __('netfusion.local_address') }}</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" name="remote_address" class="form-control" id="remote"
                                        placeholder="Pool/IP">
                                    <label for="remote">{{ __('netfusion.remote_address') }}</label>
                                </div>
                            </div>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="text" name="rate_limit" class="form-control" id="rate" placeholder="Rx/Tx">
                            <label for="rate">{{ __('netfusion.rate_limit_example') }}</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="text" name="dns_server" class="form-control" id="dns" placeholder="DNS">
                            <label for="dns">{{ __('netfusion.dns_server') }}</label>
                        </div>
                        <div class="d-grid">
                            <button type="submit"
                                class="btn btn-primary fw-bold rounded-pill py-2">{{ __('netfusion.create_profile') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
