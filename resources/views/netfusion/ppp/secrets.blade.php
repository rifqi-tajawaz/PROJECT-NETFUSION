@extends('layouts.app')

@section('title', __('netfusion.ppp_secrets'))

@section('content')
    <div class="container-fluid px-4 py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-bold text-dark mb-1">{{ __('netfusion.ppp_secrets') }}</h4>
                <p class="text-muted small mb-0">{{ __('netfusion.manage_ppp_users') }}</p>
            </div>
            <button class="btn btn-primary fw-bold rounded-pill px-4 shadow-sm hover-scale" data-bs-toggle="modal"
                data-bs-target="#addSecretModal">
                <i class="bi bi-plus-lg me-2"></i>{{ __('netfusion.add_secret') }}
            </button>
        </div>

        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="px-4 py-3 text-uppercase text-muted small fw-bold">
                                    {{ __('netfusion.secret_name') }}</th>
                                <th class="px-4 py-3 text-uppercase text-muted small fw-bold">
                                    {{ __('netfusion.secret_password') }}</th>
                                <th class="px-4 py-3 text-uppercase text-muted small fw-bold">
                                    {{ __('netfusion.secret_profile') }}</th>
                                <th class="px-4 py-3 text-uppercase text-muted small fw-bold">
                                    {{ __('netfusion.secret_service') }}</th>
                                <th class="px-4 py-3 text-uppercase text-muted small fw-bold">
                                    {{ __('netfusion.secret_comment') }}</th>
                                <th class="px-4 py-3 text-uppercase text-muted small fw-bold text-end">
                                    {{ __('netfusion.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($secrets as $secret)
                                <tr>
                                    <td class="px-4 fw-bold text-dark">{{ $secret['name'] }}</td>
                                    <td class="px-4 font-monospace small"><i
                                            class="bi bi-key me-1 text-muted"></i>{{ $secret['password'] ?? '****' }}</td>
                                    <td class="px-4"><span
                                            class="badge bg-light text-secondary border">{{ $secret['profile'] ?? 'default' }}</span>
                                    </td>
                                    <td class="px-4">{{ $secret['service'] ?? 'any' }}</td>
                                    <td class="px-4 text-muted small">{{ $secret['comment'] ?? '-' }}</td>
                                    <td class="px-4 text-end">
                                        <form action="{{ route('mikrotik-suite.netfusion.ppp.secrets.destroy', $secret['.id']) }}"
                                            method="POST"
                                            onsubmit="return confirm('{{ __('netfusion.confirm_delete_secret', ['name' => $secret['name']]) }}');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-light text-danger rounded-circle p-2"
                                                title="{{ __('netfusion.delete_secret') }}">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5">
                                        <h6 class="text-muted fw-bold">{{ __('netfusion.no_secrets_found') }}</h6>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Modal -->
    <div class="modal fade" id="addSecretModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold">{{ __('netfusion.add_ppp_secret_modal') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('mikrotik-suite.netfusion.ppp.secrets.store') }}" method="POST">
                        @csrf
                        <div class="form-floating mb-3">
                            <input type="text" name="name" class="form-control" id="name" required placeholder="User">
                            <label for="name">{{ __('netfusion.username') }}</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="password" name="password" class="form-control" id="password" required
                                placeholder="Pass">
                            <label for="password">{{ __('netfusion.secret_password') }}</label>
                        </div>
                        <div class="row g-2 mb-3">
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <select name="service" class="form-select" id="service">
                                        <option value="any">any</option>
                                        <option value="pppoe">pppoe</option>
                                        <option value="l2tp">l2tp</option>
                                        <option value="ovpn">ovpn</option>
                                        <option value="sstp">sstp</option>
                                        <option value="pptp">pptp</option>
                                    </select>
                                    <label for="service">{{ __('netfusion.secret_service') }}</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <select name="profile" class="form-select" id="profile" required>
                                        @foreach($profiles as $prof)
                                            <option value="{{ $prof['name'] }}">{{ $prof['name'] }}</option>
                                        @endforeach
                                    </select>
                                    <label for="profile">{{ __('netfusion.secret_profile') }}</label>
                                </div>
                            </div>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="text" name="local_address" class="form-control" id="local" placeholder="IP">
                            <label for="local">{{ __('netfusion.local_address_optional') }}</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="text" name="remote_address" class="form-control" id="remote" placeholder="IP">
                            <label for="remote">{{ __('netfusion.remote_address_optional') }}</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="text" name="comment" class="form-control" id="comment" placeholder="Comment">
                            <label for="comment">{{ __('netfusion.secret_comment') }}</label>
                        </div>
                        <div class="d-grid">
                            <button type="submit"
                                class="btn btn-primary fw-bold rounded-pill py-2">{{ __('netfusion.create_secret') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
