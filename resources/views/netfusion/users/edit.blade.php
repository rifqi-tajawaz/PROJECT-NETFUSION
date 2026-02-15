@extends('layouts.app')

@section('title', __('netfusion.edit_user') . ' - ' . ($user['name'] ?? 'Unknown'))

@section('content')
    <div class="container-fluid px-4 py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-bold text-dark mb-1">{{ __('netfusion.edit_user') }}</h4>
                <p class="text-muted small mb-0">{{ __('netfusion.modify_user_settings') }}</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('mikrotik-suite.netfusion.users.show', $user['.id']) }}"
                    class="btn btn-light text-secondary border fw-bold rounded-pill px-3 shadow-sm">
                    <i class="bi bi-x-lg me-2"></i>{{ __('netfusion.cancel') }}
                </a>
                <a href="{{ route('mikrotik-suite.netfusion.users.index') }}"
                    class="btn btn-light text-secondary border fw-bold rounded-pill px-3 shadow-sm">
                    <i class="bi bi-arrow-left me-2"></i>{{ __('netfusion.back_to_users') }}
                </a>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                    <div class="card-body p-5">
                        <form method="POST" action="{{ route('mikrotik-suite.netfusion.users.update', $user['.id']) }}">
                            @csrf
                            @method('PUT')

                            <!-- Username (Read-only) -->
                            <div class="row mb-4">
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">{{ __('netfusion.username') }}</label>
                                    <input type="text" class="form-control bg-light" value="{{ $user['name'] }}" disabled>
                                    <small class="text-muted">{{ __('netfusion.username_read_only') }}</small>
                                </div>
                            </div>

                            <!-- Password -->
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <label for="password" class="form-label fw-bold">{{ __('netfusion.password') }}</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0"><i class="bi bi-key"></i></span>
                                        <input type="text" name="password" id="password" class="form-control border-start-0"
                                            value="{{ old('password') ?? $user['password'] ?? '' }}"
                                            placeholder="{{ __('netfusion.password_leave_empty') }}">
                                    </div>
                                    <small class="text-muted">{{ __('netfusion.password_leave_empty_help') }}</small>
                                </div>
                            </div>

                            <!-- Profile & Server -->
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <label for="profile" class="form-label fw-bold">{{ __('netfusion.profile') }} <span class="text-danger">*</span></label>
                                    <select name="profile" id="profile" class="form-select" required>
                                        @foreach($profiles as $profile)
                                            <option value="{{ $profile['name'] }}"
                                                {{ ($user['profile'] ?? '') == $profile['name'] ? 'selected' : '' }}>
                                                {{ $profile['name'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="server" class="form-label fw-bold">{{ __('netfusion.server') }}</label>
                                    <select name="server" id="server" class="form-select">
                                        <option value="all" {{ ($user['server'] ?? 'all') == 'all' ? 'selected' : '' }}>{{ __('netfusion.server_all') }}</option>
                                        @foreach($servers as $server)
                                            <option value="{{ $server['name'] }}"
                                                {{ ($user['server'] ?? '') == $server['name'] ? 'selected' : '' }}>
                                                {{ $server['name'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <!-- Time Limit -->
                            <div class="row mb-4">
                                <div class="col-md-12">
                                    <label for="limit_uptime" class="form-label fw-bold">{{ __('netfusion.time_limit_uptime') }}</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0"><i class="bi bi-clock"></i></span>
                                        <input type="text" name="limit_uptime" id="limit_uptime" class="form-control border-start-0"
                                            value="{{ old('limit_uptime') ?? ($user['limit-uptime'] ?? '') }}"
                                            placeholder="{{ __('netfusion.time_limit_help_format') }}">
                                    </div>
                                    <small class="text-muted">{{ __('netfusion.time_limit_help_format') }}</small>
                                </div>
                            </div>

                            <!-- Data Limit -->
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <label for="limit_bytes_total" class="form-label fw-bold">{{ __('netfusion.data_limit') }}</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0"><i class="bi bi-hdd-network"></i></span>
                                        <input type="number" name="limit_bytes_total" id="limit_bytes_total" class="form-control border-start-0 border-end-0"
                                            value="{{ old('limit_bytes_total') ?? (isset($user['limit-bytes-total']) && $user['limit-bytes-total'] != '' ? $user['limit-bytes-total'] : '') }}"
                                            placeholder="{{ __('netfusion.data_limit_placeholder') }}" min="0">
                                        <select name="limit_bytes_unit" id="limit_bytes_unit" class="form-select border-start-0" style="max-width: 100px;">
                                            <option value="MB">MB</option>
                                            <option value="KB">KB</option>
                                            <option value="GB">GB</option>
                                            <option value="TB">TB</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6 d-flex align-items-end">
                                    <small class="text-muted">
                                        @if(isset($user['limit-bytes-total']) && $user['limit-bytes-total'] != '')
                                            {{ __('netfusion.current') }}: {{ \App\Helpers\Format::bytes($user['limit-bytes-total']) }}
                                        @else
                                            {{ __('netfusion.currently_unlimited') }}
                                        @endif
                                    </small>
                                </div>
                            </div>

                            <!-- Comment -->
                            <div class="row mb-4">
                                <div class="col-md-12">
                                    <label for="comment" class="form-label fw-bold">{{ __('netfusion.comment') }}</label>
                                    <textarea name="comment" id="comment" class="form-control" rows="3"
                                        placeholder="{{ __('netfusion.comment_placeholder') }}">{{ old('comment') ?? ($user['comment'] ?? '') }}</textarea>
                                    <small class="text-muted">{{ __('netfusion.comment_help') }}</small>
                                </div>
                            </div>

                            <!-- Current Usage Info -->
                            <div class="row mb-4">
                                <div class="col-md-12">
                                    <div class="alert alert-info border-0 shadow-sm rounded-3">
                                        <h6 class="fw-bold mb-2"><i class="bi bi-info-circle me-2"></i>{{ __('netfusion.current_usage') }}</h6>
                                        <div class="row text-center">
                                            <div class="col-4">
                                                <div class="text-muted small">{{ __('netfusion.uptime') }}</div>
                                                <div class="fw-bold">{{ $user['uptime'] ?? '0s' }}</div>
                                            </div>
                                            <div class="col-4">
                                                <div class="text-muted small">{{ __('netfusion.download') }}</div>
                                                <div class="fw-bold text-info">{{ isset($user['bytes-in']) ? \App\Helpers\Format::bytes($user['bytes-in']) : '0B' }}</div>
                                            </div>
                                            <div class="col-4">
                                                <div class="text-muted small">{{ __('netfusion.upload') }}</div>
                                                <div class="fw-bold text-primary">{{ isset($user['bytes-out']) ? \App\Helpers\Format::bytes($user['bytes-out']) : '0B' }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Buttons -->
                            <div class="row">
                                <div class="col-12">
                                    <div class="d-flex justify-content-between">
                                        <a href="{{ route('mikrotik-suite.netfusion.users.show', $user['.id']) }}"
                                            class="btn btn-light text-secondary fw-bold rounded-pill px-4">
                                            <i class="bi bi-x-lg me-2"></i>{{ __('netfusion.cancel') }}
                                        </a>
                                        <button type="submit" class="btn btn-primary fw-bold rounded-pill px-5">
                                            <i class="bi bi-check-lg me-2"></i>{{ __('netfusion.save_changes') }}
                                        </button>
                                    </div>
                                </div>
                            </div>

                            @error('password')
                                <div class="alert alert-danger mt-3">{{ $message }}</div>
                            @enderror
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('styles')
<style>
    .form-control,
    .form-select,
    .input-group-text {
        border-color: #ced4da;
    }
    .form-control:focus,
    .form-select:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
    }
</style>
@endsection
