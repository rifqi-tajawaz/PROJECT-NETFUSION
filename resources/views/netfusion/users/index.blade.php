@extends('layouts.app')

@section('title', __('netfusion.hotspot_users'))

@section('content')
    <div class="container-fluid px-4 py-4" style="min-height: 100vh;">

        <!-- Header & Main Actions -->
        <div
            class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-3">
            <div>
                <h4 class="fw-bold text-dark mb-1 d-flex align-items-center gap-2">
                    <i class="bi bi-people-fill text-primary"></i> {{ __('netfusion.hotspot_users') }}
                </h4>
                <p class="text-muted small mb-0">{{ __('netfusion.manage_wifi_users') }}</p>
            </div>
            <div class="d-flex gap-2 w-100 w-md-auto">
                <a href="{{ route('mikrotik-suite.netfusion.users.create') }}"
                    class="btn btn-light text-primary fw-bold rounded-pill px-4 shadow-sm border hover-scale flex-fill flex-md-grow-0 text-center">
                    <i class="bi bi-plus-lg me-2"></i>{{ __('netfusion.add_user') }}
                </a>
                <a href="{{ route('mikrotik-suite.netfusion.users.generate') }}"
                    class="btn btn-primary fw-bold rounded-pill px-4 shadow-sm hover-scale flex-fill flex-md-grow-0 text-center">
                    <i class="bi bi-qr-code me-2"></i>{{ __('netfusion.generate_batch') }}
                </a>
            </div>
        </div>

        <!-- Success/Batch Notification -->
        @if(session('batch_comment'))
            <div class="alert alert-success border-0 shadow-sm rounded-4 mb-4 bg-success bg-opacity-10" role="alert">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-circle bg-success text-white p-2">
                            <i class="bi bi-check-lg fs-5"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold text-success mb-0">{{ __('netfusion.batch_generated_success') }}</h6>
                            <p class="mb-0 small text-success opacity-75">{{ __('netfusion.vouchers_ready') }}</p>
                        </div>
                    </div>
                    <a href="{{ route('mikrotik-suite.netfusion.printing.print', ['comment' => session('batch_comment')]) }}"
                        target="_blank" class="btn btn-success rounded-pill px-4 shadow-sm fw-bold">
                        <i class="bi bi-printer-fill me-2"></i>{{ __('netfusion.print_now') }}
                    </a>
                </div>
            </div>
        @endif

        @if(session('success'))
            <div class="alert alert-success border-0 shadow-sm rounded-4 mb-4 bg-success bg-opacity-10 d-flex align-items-center gap-3"
                role="alert">
                <div class="rounded-circle bg-success text-white p-2">
                    <i class="bi bi-check-lg fs-5"></i>
                </div>
                <div>
                    <h6 class="fw-bold text-success mb-0">{{ __('netfusion.success') }}</h6>
                    <small class="text-success opacity-75">{{ session('success') }}</small>
                </div>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Stats Grid -->
        <div class="row g-3 mb-4">
            <div class="col-6 col-md-3">
                <div class="glass-card-small border-0 h-100 position-relative overflow-hidden">
                    <div class="card-body p-3 d-flex align-items-center gap-3 position-relative z-1">
                        <div class="rounded-circle bg-primary bg-opacity-10 p-3 text-primary">
                            <i class="bi bi-people fs-4"></i>
                        </div>
                        <div>
                            <span
                                class="text-muted small text-uppercase fw-bold ls-1 d-block">{{ __('netfusion.total') }}</span>
                            <h5 class="fw-bold text-dark mb-0 main-digit">{{ $totalCount }}</h5>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="glass-card-small border-0 h-100 position-relative overflow-hidden">
                    <div class="card-body p-3 d-flex align-items-center gap-3 position-relative z-1">
                        <div class="rounded-circle bg-success bg-opacity-10 p-3 text-success">
                            <i class="bi bi-wifi fs-4"></i>
                        </div>
                        <div>
                            <span
                                class="text-muted small text-uppercase fw-bold ls-1 d-block">{{ __('netfusion.active') }}</span>
                            <h5 class="fw-bold text-dark mb-0 main-digit">{{ $onlineCount }}</h5>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="glass-card-small border-0 h-100 position-relative overflow-hidden">
                    <div class="card-body p-3 d-flex align-items-center gap-3 position-relative z-1">
                        <div class="rounded-circle bg-danger bg-opacity-10 p-3 text-danger">
                            <i class="bi bi-slash-circle fs-4"></i>
                        </div>
                        <div>
                            <span
                                class="text-muted small text-uppercase fw-bold ls-1 d-block">{{ __('netfusion.expired') }}</span>
                            <h5 class="fw-bold text-dark mb-0 main-digit">{{ $expiredCount }}</h5>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="glass-card-small border-0 h-100 position-relative overflow-hidden">
                    <div class="card-body p-3 d-flex align-items-center gap-3 position-relative z-1">
                        <div class="rounded-circle bg-secondary bg-opacity-10 p-3 text-secondary">
                            <i class="bi bi-pause-circle fs-4"></i>
                        </div>
                        <div>
                            <span
                                class="text-muted small text-uppercase fw-bold ls-1 d-block">{{ __('netfusion.disabled') }}</span>
                            <h5 class="fw-bold text-dark mb-0 main-digit">{{ $disabledCount }}</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content Card -->
        <div class="glass-card border-0 overflow-hidden">
            <!-- Toolbar -->
            <div class="card-header bg-transparent border-bottom px-4 py-3">
                <div class="row g-3 align-items-center">
                    <!-- Search -->
                    <div class="col-12 col-md-3">
                        <div class="position-relative">
                            <input type="text" id="userSearch"
                                class="form-control bg-light border border-secondary border-opacity-25 ps-5 rounded-pill py-2"
                                placeholder="{{ __('netfusion.search_users') }}">
                            <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
                        </div>
                    </div>

                    <!-- Filters & Actions -->
                    <div class="col-12 col-md-9">
                        <div class="d-flex flex-wrap gap-2 justify-content-md-end align-items-center">

                            <!-- Profile Filter -->
                            <div class="dropdown">
                                <button
                                    class="btn btn-light border fw-bold rounded-pill py-2 px-3 dropdown-toggle text-start d-flex align-items-center justify-content-between"
                                    type="button" data-bs-toggle="dropdown" aria-expanded="false" style="min-width: 140px;">
                                    <span
                                        class="text-truncate">{{ $selectedProfile ?: __('netfusion.all_profiles') }}</span>
                                </button>
                                <ul class="dropdown-menu border-0 shadow-lg rounded-4 p-2"
                                    style="max-height: 300px; overflow-y: auto;">
                                    <li><a class="dropdown-item rounded-3 {{ !$selectedProfile ? 'active' : '' }}"
                                            href="{{ request()->fullUrlWithQuery(['profile' => null]) }}">{{ __('netfusion.all_profiles') }}</a>
                                    </li>
                                    @foreach($profiles as $profile)
                                        <li><a class="dropdown-item rounded-3 {{ $selectedProfile == $profile['name'] ? 'active' : '' }}"
                                                href="{{ request()->fullUrlWithQuery(['profile' => $profile['name']]) }}">{{ $profile['name'] }}</a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>

                            <!-- Comment Filter -->
                            <div class="dropdown">
                                <button
                                    class="btn btn-light border fw-bold rounded-pill py-2 px-3 dropdown-toggle text-start d-flex align-items-center justify-content-between"
                                    type="button" data-bs-toggle="dropdown" aria-expanded="false" style="min-width: 140px;">
                                    <span
                                        class="text-truncate">{{ $selectedComment ? Str::limit($selectedComment, 15) : __('netfusion.all_comments') }}</span>
                                </button>
                                <ul class="dropdown-menu border-0 shadow-lg rounded-4 p-2"
                                    style="max-height: 300px; overflow-y: auto;">
                                    <li><a class="dropdown-item rounded-3 {{ !$selectedComment ? 'active' : '' }}"
                                            href="{{ request()->fullUrlWithQuery(['comment' => null]) }}">{{ __('netfusion.all_comments') }}</a>
                                    </li>
                                    @foreach($uniqueComments as $comment)
                                        <li><a class="dropdown-item rounded-3 {{ $selectedComment == $comment ? 'active' : '' }}"
                                                href="{{ request()->fullUrlWithQuery(['comment' => $comment]) }}">{{ Str::limit($comment, 25) }}</a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>

                            <div class="vr mx-1"></div>

                            <!-- Mikhmon Parity Actions -->
                            @if(request('comment'))
                                <div class="btn-group shadow-sm rounded-pill" role="group">
                                    <a href="{{ route('mikrotik-suite.netfusion.printing.print', ['comment' => request('comment'), 'mode' => 'default']) }}"
                                        target="_blank" class="btn btn-success text-white fw-bold px-3 py-2"
                                        title="{{ __('netfusion.print_default') }}">
                                        <i class="bi bi-printer-fill"></i> <span
                                            class="d-none d-lg-inline ms-1">{{ __('netfusion.print_default') }}</span>
                                    </a>
                                    <a href="{{ route('mikrotik-suite.netfusion.printing.print', ['comment' => request('comment'), 'mode' => 'qr']) }}"
                                        target="_blank" class="btn btn-dark text-white fw-bold px-3 py-2"
                                        title="{{ __('netfusion.print_qr') }}">
                                        <i class="bi bi-qr-code"></i> <span
                                            class="d-none d-lg-inline ms-1">{{ __('netfusion.print_qr') }}</span>
                                    </a>
                                    <a href="{{ route('mikrotik-suite.netfusion.printing.print', ['comment' => request('comment'), 'mode' => 'small']) }}"
                                        target="_blank" class="btn btn-info text-white fw-bold px-3 py-2"
                                        title="{{ __('netfusion.print_small') }}">
                                        <i class="bi bi-receipt"></i> <span
                                            class="d-none d-lg-inline ms-1">{{ __('netfusion.print_small') }}</span>
                                    </a>
                                </div>

                                <form action="{{ route('mikrotik-suite.netfusion.users.destroy-by-comment') }}" method="POST"
                                    class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <input type="hidden" name="comment" value="{{ request('comment') }}">
                                    @php
                                        $batchDeleteMsg = '⚠️ ' . __('netfusion.confirm_delete') . ' ' . request('comment') . '?';
                                    @endphp
                                    <button class="btn btn-danger rounded-pill px-3 py-2 shadow-sm fw-bold hover-scale"
                                        onclick="return confirm('{{ $batchDeleteMsg }}');">
                                        <i class="bi bi-trash-fill me-1"></i> {{ __('netfusion.delete_batch') }}
                                    </button>
                                </form>
                            @endif

                            @if(request('expired'))

                            @endif

                            <div class="vr mx-1"></div>

                            <!-- Tools -->
                            <div class="btn-group">
                                <a href="{{ route('mikrotik-suite.netfusion.users.export-csv', request()->all()) }}"
                                    class="btn btn-light border text-success rounded-start-pill hover-shadow"
                                    title="{{ __('netfusion.export_csv') }}">
                                    <i class="bi bi-filetype-csv"></i>
                                </a>
                                <a href="{{ route('mikrotik-suite.netfusion.users.export-script', request()->all()) }}"
                                    class="btn btn-light border text-primary rounded-end-pill hover-shadow"
                                    title="{{ __('netfusion.export_script') }}">
                                    <i class="bi bi-code-slash"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Active Filter Tags -->
                @if($selectedProfile || $selectedComment || $showExpired)
                    <div class="d-flex align-items-center gap-2 mt-3 flex-wrap">
                        <span class="text-muted small fw-bold text-uppercase ls-1">{{ __('netfusion.filters') }}:</span>
                        @if($selectedProfile)
                            <a href="{{ request()->fullUrlWithQuery(['profile' => null]) }}"
                                class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-25 rounded-pill text-decoration-none px-3 py-2">
                                Profile: {{ $selectedProfile }} <i class="bi bi-x ms-1"></i>
                            </a>
                        @endif
                        @if($selectedComment)
                            <a href="{{ request()->fullUrlWithQuery(['comment' => null]) }}"
                                class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 rounded-pill text-decoration-none px-3 py-2">
                                Comment: {{ Str::limit($selectedComment, 15) }} <i class="bi bi-x ms-1"></i>
                            </a>
                        @endif
                        @if($showExpired)
                            <a href="{{ request()->fullUrlWithQuery(['expired' => null]) }}"
                                class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 rounded-pill text-decoration-none px-3 py-2">
                                {{ __('netfusion.expired') }} <i class="bi bi-x ms-1"></i>
                            </a>
                        @endif
                        <a href="{{ route('mikrotik-suite.netfusion.users.index') }}"
                            class="btn btn-link btn-sm text-muted text-decoration-none">{{ __('netfusion.clear_all') }}</a>
                    </div>
                @else
                    <div class="mt-3">
                        <a href="{{ request()->fullUrlWithQuery(['expired' => '1']) }}"
                            class="btn btn-sm btn-light text-danger border rounded-pill px-3">
                            <i class="bi bi-exclamation-circle me-1"></i> {{ __('netfusion.show_expired_users') }}
                        </a>
                    </div>
                @endif
            </div>

            <!-- Bulk Action Bar -->
            <div id="bulkActionBar"
                class="bg-dark text-white p-3 rounded-3 mb-3 d-none align-items-center justify-content-between shadow-lg mx-3 mt-3">
                <div class="d-flex align-items-center gap-3">
                    <span class="fw-bold"><span id="selectedCount">0</span> {{ __('netfusion.selected_users') }}</span>
                    <div class="vr bg-white opacity-25"></div>
                    <form id="bulkForm" action="{{ route('mikrotik-suite.netfusion.users.bulk-action') }}" method="POST"
                        class="d-flex gap-2">
                        @csrf
                        <div id="bulkIdsContainer"></div>
                        <button type="submit" name="action" value="delete"
                            class="btn btn-danger btn-sm rounded-pill fw-bold shadow-sm hover-scale"
                            onclick="return confirm('{{ __('netfusion.confirm_delete') }}');">
                            <i class="bi bi-trash-fill me-1"></i> {{ __('netfusion.delete') }}
                        </button>
                        <button type="submit" name="action" value="disable"
                            class="btn btn-secondary btn-sm rounded-pill fw-bold">
                            <i class="bi bi-lock-fill me-1"></i> {{ __('netfusion.disable') }}
                        </button>
                        <button type="submit" name="action" value="enable"
                            class="btn btn-success btn-sm rounded-pill fw-bold">
                            <i class="bi bi-unlock-fill me-1"></i> {{ __('netfusion.enable') }}
                        </button>
                        <button type="submit" name="action" value="reset"
                            class="btn btn-primary btn-sm rounded-pill fw-bold"
                            onclick="return confirm('{{ $bulkResetMsg }}');">
                            <i class="bi bi-arrow-counterclockwise me-1"></i> {{ __('netfusion.reset') }}
                        </button>
                    </form>
                </div>
                <button type="button" class="btn-close btn-close-white" onclick="deselectAll()"></button>
            </div>

            <!-- Table -->
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="usersTable">
                    <thead class="bg-light text-secondary text-uppercase small ls-1 fw-bold">
                        <tr>
                            <th class="ps-4 py-3" style="width: 40px;">
                                <input class="form-check-input" type="checkbox" id="selectAll" onchange="toggleSelectAll()">
                            </th>
                            <th class="py-3">{{ __('netfusion.server') }}</th>
                            <th class="py-3">{{ __('netfusion.user_details') }}</th>
                            <th class="py-3">{{ __('netfusion.profile') }}</th>
                            <th class="py-3">{{ __('netfusion.mac_address') }}</th>
                            <th class="py-3">{{ __('netfusion.uptime_limit') }}</th>
                            <th class="py-3">{{ __('netfusion.bytes_limit') }}</th>
                            <th class="py-3">{{ __('netfusion.comment') }}</th>
                            <th class="text-end pe-4 py-3">{{ __('netfusion.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0">
                        @forelse($users as $user)
                            @php
                                $statusRowClass = '';
                                if ($user->isExpired())
                                    $statusRowClass = 'table-danger';
                                elseif ($user->isDisabled())
                                    $statusRowClass = 'table-secondary';
                            @endphp
                            <tr class="user-row position-relative {{ $statusRowClass }}">
                                <td class="ps-4">
                                    <input class="form-check-input user-checkbox" type="checkbox" value="{{ $user->id }}"
                                        onchange="updateBulkState()">
                                </td>
                                <!-- Server -->
                                <td class="text-muted small">
                                    {{ $user->server }}
                                </td>

                                <!-- User Info -->
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="position-relative me-3">
                                            @php
                                                $statusClass = 'success';
                                                $statusIcon = 'wifi';
                                                if ($user->isDisabled()) {
                                                    $statusClass = 'secondary';
                                                    $statusIcon = 'pause-fill';
                                                } elseif ($user->isExpired()) {
                                                    $statusClass = 'danger';
                                                    $statusIcon = 'exclamation-circle-fill';
                                                }
                                            @endphp
                                            <div class="rounded-circle bg-light p-2 text-{{ $statusClass }} d-flex align-items-center justify-content-center border border-{{ $statusClass }} border-opacity-25"
                                                style="width: 40px; height: 40px;">
                                                <i class="bi bi-{{ $statusIcon }} fs-5"></i>
                                            </div>
                                        </div>
                                        <div>
                                            <div class="fw-bold text-dark username mb-0 text-truncate"
                                                style="max-width: 150px;">{{ $user->name }}</div>
                                            <div
                                                class="small text-muted font-monospace bg-light rounded px-2 py-0 d-inline-block mt-1 user-select-all">
                                                {{ $user->password }}
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <!-- Profile -->
                                <td>
                                    <a href="{{ request()->fullUrlWithQuery(['profile' => $user->profile]) }}"
                                        class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 rounded-pill profile px-3 py-2 text-decoration-none">
                                        {{ $user->profile }}
                                    </a>
                                </td>

                                <!-- Mac Address -->
                                <td class="font-monospace small text-muted">
                                    {{ $user->macAddress }}
                                </td>

                                <!-- Uptime -->
                                <td>
                                    <div class="d-flex align-items-center gap-1 small text-muted">
                                        <span
                                            class="{{ $user->uptime == $user->limitUptime ? 'text-danger fw-bold' : '' }}">
                                            {{ $user->uptime }}
                                        </span>
                                        @if($user->limitUptime)
                                            <span class="text-xs text-muted opacity-75">/ {{ $user->limitUptime }}</span>
                                        @endif
                                    </div>
                                </td>

                                <!-- Bytes -->
                                <td>
                                    @php
                                        $totalBytes = $user->getTotalBytes();
                                        $limitBytes = $user->limitBytesTotal ?? 0;
                                        $usagePercent = $user->getUsagePercent();
                                        $usageColor = $usagePercent > 90 ? 'danger' : ($usagePercent > 70 ? 'warning' : 'success');
                                    @endphp
                                    <div class="d-flex flex-column" style="width: 120px;">
                                        <div class="d-flex justify-content-between small mb-1">
                                            <span>{{ \App\Helpers\Format::bytes($totalBytes) }}</span>
                                            @if($limitBytes > 0)
                                                <span
                                                    class="text-xs text-muted">{{ \App\Helpers\Format::bytes($limitBytes) }}</span>
                                            @endif
                                        </div>
                                        @if($limitBytes > 0)
                                            <div class="progress rounded-pill bg-secondary bg-opacity-10" style="height: 4px;">
                                                <div class="progress-bar bg-{{ $usageColor }}" role="progressbar"
                                                    style="{{ 'width: ' . $usagePercent . '%;' }}" aria-valuenow="{{ $usagePercent }}"
                                                    aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                        @endif
                                    </div>
                                </td>

                                <!-- Comment -->
                                <td>
                                    @if($user->comment)
                                        <a href="{{ request()->fullUrlWithQuery(['comment' => $user->comment]) }}"
                                            class="small text-decoration-none text-muted fst-italic comment"
                                            title="Filter by this comment">
                                            {{ Str::limit($user->comment, 20) }}
                                        </a>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>

                                <!-- Actions -->
                                <td class="text-end pe-4">
                                    <div class="dropdown">
                                        <button
                                            class="btn btn-light btn-sm border rounded-pill shadow-sm py-1 px-3 d-flex align-items-center gap-2 ms-auto"
                                            type="button" data-bs-toggle="dropdown">
                                            <i class="bi bi-three-dots-vertical small"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg rounded-4 p-2">

                                            <!-- Mikhmon Quick Actions -->
                                            <li>
                                                @if($user->isDisabled())
                                                    <form action="{{ route('mikrotik-suite.netfusion.users.enable', $user->id) }}"
                                                        method="POST">
                                                        @csrf
                                                        <button type="submit" class="dropdown-item rounded-3 py-2 text-success">
                                                            <i class="bi bi-unlock-fill me-2"></i>{{ __('netfusion.enable') }}
                                                        </button>
                                                    </form>
                                                @else
                                                    <form action="{{ route('mikrotik-suite.netfusion.users.disable', $user->id) }}"
                                                        method="POST">
                                                        @csrf
                                                        <button type="submit" class="dropdown-item rounded-3 py-2 text-warning">
                                                            <i class="bi bi-lock-fill me-2"></i>{{ __('netfusion.disable') }}
                                                        </button>
                                                    </form>
                                                @endif
                                            </li>

                                            <li>
                                                <a class="dropdown-item rounded-3 py-2"
                                                    href="{{ route('mikrotik-suite.netfusion.users.edit', $user->id) }}">
                                                    <i class="bi bi-pencil me-2 text-info"></i>{{ __('netfusion.edit') }}
                                                </a>
                                            </li>

                                            <li>
                                                <hr class="dropdown-divider">
                                            </li>

                                            <!-- Print -->
                                            @if(Session::get('NetFusion_quick_print') == 'enable')
                                                <li>
                                                    <a class="dropdown-item rounded-3 py-2"
                                                        href="{{ route('mikrotik-suite.netfusion.printing.print', ['id' => $user->id, 'mode' => 'single']) }}"
                                                        target="_blank">
                                                        <i class="bi bi-printer me-2 text-dark"></i>{{ __('netfusion.print') }}
                                                    </a>
                                                </li>
                                            @endif

                                            <!-- Reset -->
                                            <li>
                                                <form action="{{ route('mikrotik-suite.netfusion.users.reset', $user->id) }}"
                                                    method="POST">
                                                    @csrf
                                                    @php
                                                        $resetMsg = __('netfusion.confirm_reset_user', ['name' => $user->name]);
                                                    @endphp
                                                    <button type="submit" class="dropdown-item rounded-3 py-2"
                                                        onclick="return confirm('{{ $resetMsg }}');">
                                                        <i
                                                            class="bi bi-arrow-counterclockwise me-2 text-primary"></i>{{ __('netfusion.reset_stats') }}
                                                    </button>
                                                </form>
                                            </li>

                                            <li>
                                                <hr class="dropdown-divider">
                                            </li>

                                            <!-- Delete -->
                                            <li>
                                                <form action="{{ route('mikrotik-suite.netfusion.users.destroy', $user->id) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    @php
                                                        $deleteMsg = __('netfusion.confirm_delete_user', ['name' => $user->name]);
                                                    @endphp
                                                    <button type="submit" class="dropdown-item rounded-3 py-2 text-danger"
                                                        onclick="return confirm('{{ $deleteMsg }}');">
                                                        <i class="bi bi-x-circle me-2"></i>{{ __('netfusion.delete') }}
                                                    </button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        @empty
                                        <tr class="no-data">
                                            <td colspan="9" class="text-center py-5">
                                                <div class="py-5">
                                                    <div class="mb-3">
                                                        <div class="rounded-circle bg-light p-4 d-inline-block">
                                                            <i class="bi bi-people text-muted fs-1"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                                <h5 class="text-muted fw-bold">{{ __('netfusion.no_users_found') }}</h5>
                                                <p class="text-muted small mb-4">{{ __('netfusion.no_users_msg') }}</p>
                                                <div class="d-flex gap-2 justify-content-center">
                                                    <a href="{{ route('mikrotik-suite.netfusion.users.create') }}"
                                                        class="btn btn-outline-primary rounded-pill px-4">{{ __('netfusion.add_manually') }}</a>
                                                    <a href="{{ route('mikrotik-suite.netfusion.users.generate') }}"
                                                        class="btn btn-primary rounded-pill px-4">{{ __('netfusion.generate_batch') }}</a>
                                                </div>
                            </div>
                            </td>
                            </tr>
                        @endforelse
            </tbody>
            </table>
        </div>

        <!-- Footer Hints -->
        <div class="card-footer bg-transparent border-top p-3 border-secondary border-opacity-10">
            <div class="d-flex gap-4 justify-content-center justify-content-md-end text-muted small">
                <span class="d-flex align-items-center gap-2"><i class="bi bi-wifi text-success"></i>
                    {{ __('netfusion.active') }}</span>
                <span class="d-flex align-items-center gap-2"><i class="bi bi-lock-fill text-secondary"></i>
                    {{ __('netfusion.disabled') }}</span>
                <span class="d-flex align-items-center gap-2"><i class="bi bi-exclamation-circle-fill text-danger"></i>
                    {{ __('netfusion.expired') }}</span>
            </div>
        </div>
    </div>
    </div>

    <script>
        function toggleSelectAll() {
            let checkboxes = document.querySelectorAll('.user-checkbox');
            let selectAll = document.getElementById('selectAll');
            checkboxes.forEach(cb => cb.checked = selectAll.checked);
            updateBulkState();
        }

        function deselectAll() {
            document.getElementById('selectAll').checked = false;
            let checkboxes = document.querySelectorAll('.user-checkbox');
            checkboxes.forEach(cb => cb.checked = false);
            updateBulkState();
        }

        function updateBulkState() {
            let checkboxes = document.querySelectorAll('.user-checkbox:checked');
            let count = checkboxes.length;
            let bar = document.getElementById('bulkActionBar');
            let container = document.getElementById('bulkIdsContainer');

            document.getElementById('selectedCount').innerText = count;

            // Clear existing hidden inputs
            container.innerHTML = '';

            if (count > 0) {
                bar.classList.remove('d-none');
                bar.classList.add('d-flex');

                // Add hidden inputs for each selected ID
                checkboxes.forEach(cb => {
                    let input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'ids[]';
                    input.value = cb.value;
                    container.appendChild(input);
                });
            } else {
                bar.classList.add('d-none');
                bar.classList.remove('d-flex');
            }
        }

        document.getElementById('userSearch').addEventListener('keyup', function () {
            let filter = this.value.toLowerCase();
            let rows = document.querySelectorAll('#usersTable tbody tr:not(.no-data)');

            rows.forEach(row => {
                let username = row.querySelector('.username')?.innerText.toLowerCase() || '';
                let profile = row.querySelector('.profile')?.innerText.toLowerCase() || '';
                let comment = row.querySelector('.comment')?.innerText.toLowerCase() || '';

                if (username.includes(filter) || profile.includes(filter) || comment.includes(filter)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });

        // Auto-dismiss alerts
        const successAlert = document.querySelectorAll('.alert-success');
        successAlert.forEach(alert => {
            setTimeout(function () {
                alert.style.transition = 'opacity 0.5s ease';
                alert.style.opacity = '0';
                setTimeout(function () {
                    alert.remove();
                }, 500);
            }, 4000);
        });
    </script>

    <style>
        .glass-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.5);
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.05);
            border-radius: 24px;
        }

        .glass-card-small {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.5);
            box-shadow: 0 4px 16px 0 rgba(31, 38, 135, 0.05);
            border-radius: 20px;
            transition: transform 0.2s;
        }

        .glass-card-small:hover {
            transform: translateY(-5px);
        }

        .form-control,
        .form-select,
        .input-group-text {
            border-color: #dee2e6;
            transition: all 0.2s;
        }

        .form-control:focus,
        .form-select:focus {
            box-shadow: none;
            border-color: #0d6efd;
            background-color: #fff !important;
        }

        .hover-scale:hover {
            transform: scale(1.02);
            transition: transform 0.2s;
        }
    </style>
@endsection
