@extends('layouts.app')

@section('title', 'System Tools')

@section('content')
    <div class="container-fluid px-4">

        <!-- Header -->
        <div
            class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-5 gap-3">
            <div class="d-flex align-items-center gap-3">
                <div class="bg-white p-3 rounded-circle shadow-sm d-flex align-items-center justify-content-center"
                    style="width: 50px; height: 50px;">
                    <i class="bi bi-hdd-rack text-primary fs-4"></i>
                </div>
                <div>
                    <h4 class="fw-bold text-dark mb-1">{{ __('netfusion.system_tools') }}</h4>
                    <p class="text-muted small mb-0">{{ __('netfusion.system_description') }}</p>
                </div>
            </div>
            <div>
                <a href="{{ route('mikrotik-suite.netfusion.system.scheduler') }}"
                    class="btn btn-white border shadow-sm rounded-pill px-4 py-2 hover-scale fw-bold d-flex align-items-center gap-2 text-primary">
                    <i class="bi bi-clock-history"></i>
                    <span>{{ __('netfusion.scheduler') }}</span>
                </a>
            </div>
        </div>

        <!-- Quick Stats Cards (Clean) -->
        <div class="row g-4 mb-5">
            <!-- Identity -->
            <div class="col-12 col-md-6 col-lg-3">
                <div class="card border-0 shadow-sm rounded-4 h-100 bg-white">
                    <div class="card-body p-4 d-flex align-items-center gap-3">
                        <div class="d-flex align-items-center justify-content-center rounded-circle bg-primary bg-opacity-10 text-primary flex-shrink-0"
                            style="width: 50px; height: 50px;">
                            <i class="bi bi-router fs-4"></i>
                        </div>
                        <div>
                            <span class="text-muted small text-uppercase fw-bold ls-1 d-block mb-0"
                                style="font-size: 0.7rem;">{{ __('netfusion.identity') }}</span>
                            <h6 class="fw-bold text-dark mb-0 text-truncate" style="max-width: 150px;">{{ $identity }}</h6>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Uptime -->
            <div class="col-12 col-md-6 col-lg-3">
                <div class="card border-0 shadow-sm rounded-4 h-100 bg-white">
                    <div class="card-body p-4 d-flex align-items-center gap-3">
                        <div class="d-flex align-items-center justify-content-center rounded-circle bg-success bg-opacity-10 text-success flex-shrink-0"
                            style="width: 50px; height: 50px;">
                            <i class="bi bi-activity fs-4"></i>
                        </div>
                        <div>
                            <span class="text-muted small text-uppercase fw-bold ls-1 d-block mb-0"
                                style="font-size: 0.7rem;">{{ __('netfusion.uptime') }}</span>
                            <h6 class="fw-bold text-dark mb-0">{{ $uptime }}</h6>
                        </div>
                    </div>
                </div>
            </div>

            <!-- CPU Load -->
            <div class="col-12 col-md-6 col-lg-3">
                <div class="card border-0 shadow-sm rounded-4 h-100 bg-white">
                    <div class="card-body p-4 d-flex align-items-center gap-3">
                        <div class="d-flex align-items-center justify-content-center rounded-circle bg-info bg-opacity-10 text-info flex-shrink-0"
                            style="width: 50px; height: 50px;">
                            <i class="bi bi-cpu fs-4"></i>
                        </div>
                        <div class="w-100">
                            <span class="text-muted small text-uppercase fw-bold ls-1 d-block mb-1"
                                style="font-size: 0.7rem;">{{ __('netfusion.cpu_load') }}</span>
                            <div class="d-flex align-items-center gap-2">
                                <h6 class="fw-bold text-dark mb-0">{{ $resource['cpu-load'] ?? 0 }}%</h6>
                                <div class="progress flex-grow-1 rounded-pill bg-light" style="height: 6px;">
                                    <div class="progress-bar bg-info" role="progressbar"
                                        style="width: {{ $resource['cpu-load'] ?? 0 }}%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Version -->
            <div class="col-12 col-md-6 col-lg-3">
                <div class="card border-0 shadow-sm rounded-4 h-100 bg-white">
                    <div class="card-body p-4 d-flex align-items-center gap-3">
                        <div class="d-flex align-items-center justify-content-center rounded-circle bg-warning bg-opacity-10 text-warning flex-shrink-0"
                            style="width: 50px; height: 50px;">
                            <i class="bi bi-heptagon-half fs-4"></i>
                        </div>
                        <div>
                            <span class="text-muted small text-uppercase fw-bold ls-1 d-block mb-0"
                                style="font-size: 0.7rem;">{{ __('netfusion.version') }}</span>
                            <h6 class="fw-bold text-dark mb-0">{{ $resource['version'] ?? '-' }}</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <!-- Detailed Resources -->
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm rounded-4 h-100 bg-white overflow-hidden">
                    <div class="card-header bg-transparent border-0 pt-4 px-4 pb-0">
                        <h6 class="fw-bold mb-0 text-dark d-flex align-items-center gap-2">
                            <i class="bi bi-list-columns-reverse text-primary"></i> {{ __('netfusion.resource_details') }}
                        </h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="table-responsive">
                            <table class="table align-middle mb-0">
                                <thead class="text-muted small text-uppercase">
                                    <tr class="border-bottom border-light">
                                        <th class="ps-4 py-3 fw-semibold border-0">{{ __('netfusion.metric') }}</th>
                                        <th class="text-end pe-4 py-3 fw-semibold border-0">{{ __('netfusion.value') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="border-light border-bottom border-opacity-50">
                                        <td class="ps-4 py-3 fw-medium text-secondary">{{ __('netfusion.platform') }}</td>
                                        <td class="text-end pe-4 py-3 font-monospace text-dark">
                                            {{ $resource['platform'] ?? '-' }}
                                        </td>
                                    </tr>
                                    <tr class="border-light border-bottom border-opacity-50">
                                        <td class="ps-4 py-3 fw-medium text-secondary">{{ __('netfusion.board_name') }}</td>
                                        <td class="text-end pe-4 py-3 font-monospace text-dark">
                                            {{ $resource['board-name'] ?? '-' }}
                                        </td>
                                    </tr>
                                    <tr class="border-light border-bottom border-opacity-50">
                                        <td class="ps-4 py-3 fw-medium text-secondary">{{ __('netfusion.architecture') }}
                                        </td>
                                        <td class="text-end pe-4 py-3 font-monospace text-dark">
                                            {{ $resource['architecture-name'] ?? '-' }}
                                        </td>
                                    </tr>
                                    <tr class="border-light border-bottom border-opacity-50">
                                        <td class="ps-4 py-3 fw-medium text-secondary">{{ __('netfusion.cpu_frequency') }}
                                        </td>
                                        <td class="text-end pe-4 py-3 font-monospace text-dark">
                                            {{ $resource['cpu'] ?? '-' }} @ {{ $resource['cpu-frequency'] ?? '-' }}MHz
                                        </td>
                                    </tr>
                                    <!-- Memory -->
                                    <tr class="border-light border-bottom border-opacity-50">
                                        <td class="ps-4 py-3 fw-medium text-secondary">{{ __('netfusion.memory_status') }}
                                        </td>
                                        <td class="text-end pe-4 py-3">
                                            @php
                                                $freeMem = isset($resource['free-memory']) ? $resource['free-memory'] : 0;
                                                $totalMem = isset($resource['total-memory']) ? $resource['total-memory'] : 1;
                                                $usedMem = $totalMem - $freeMem;
                                                $memPercent = ($usedMem / $totalMem) * 100;
                                                $memColor = $memPercent > 90 ? 'danger' : ($memPercent > 70 ? 'warning' : 'success');
                                            @endphp
                                            <div class="d-flex flex-column align-items-end">
                                                <span class="small fw-bold mb-1 text-dark">
                                                    {{ round($freeMem / 1024 / 1024, 1) }}MB Free <span
                                                        class="text-muted fw-normal">{{ __('netfusion.free_of') }}
                                                        {{ round($totalMem / 1024 / 1024, 1) }}MB</span>
                                                </span>
                                                <div class="progress rounded-pill w-100 bg-secondary bg-opacity-10"
                                                    style="height: 6px; width: 150px !important;">
                                                    <div class="progress-bar bg-{{ $memColor }}" role="progressbar"
                                                        style="width: {{ $memPercent }}%"></div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <!-- HDD -->
                                    <tr>
                                        <td class="ps-4 py-3 fw-medium text-secondary">{{ __('netfusion.hdd_storage') }}
                                        </td>
                                        <td class="text-end pe-4 py-3">
                                            @php
                                                $freeHdd = isset($resource['free-hdd-space']) ? $resource['free-hdd-space'] : 0;
                                                $totalHdd = isset($resource['total-hdd-space']) ? $resource['total-hdd-space'] : 1;
                                                $usedHdd = $totalHdd - $freeHdd;
                                                $hddPercent = ($usedHdd / $totalHdd) * 100;
                                            @endphp
                                            <div class="d-flex flex-column align-items-end">
                                                <span class="small fw-bold mb-1 text-dark">
                                                    {{ round($freeHdd / 1024 / 1024, 1) }}MB Free <span
                                                        class="text-muted fw-normal">{{ __('netfusion.free_of') }}
                                                        {{ round($totalHdd / 1024 / 1024, 1) }}MB</span>
                                                </span>
                                                <div class="progress rounded-pill w-100 bg-secondary bg-opacity-10"
                                                    style="height: 6px; width: 150px !important;">
                                                    <div class="progress-bar bg-primary" role="progressbar"
                                                        style="width: {{ $hddPercent }}%"></div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Power Controls -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm rounded-4 h-100 bg-white overflow-hidden">
                    <div class="card-body p-4 d-flex flex-column align-items-center text-center">

                        <div class="mt-auto mb-5">
                            <div class="bg-danger bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center mx-auto text-danger"
                                style="width: 100px; height: 100px;">
                                <i class="bi bi-power fs-1"></i>
                            </div>
                            <h5 class="fw-bold mt-4 mb-2 text-dark">{{ __('netfusion.power_controls') }}</h5>
                            <p class="text-muted small mb-0 px-2"
                                style="max-width: 280px; margin: 0 auto; line-height: 1.6;">
                                {{ __('netfusion.power_controls_description') }}
                            </p>
                        </div>

                        <div class="w-100 px-3 d-flex flex-column gap-3 mb-auto">
                            <form action="{{ route('mikrotik-suite.netfusion.system.reboot') }}" method="POST"
                                onsubmit="return confirm('{{ __('netfusion.confirm_reboot') }}');">
                                @csrf
                                <button
                                    class="btn btn-warning w-100 rounded-pill py-3 fw-bold shadow-sm hover-scale text-white d-flex align-items-center justify-content-center gap-2">
                                    <i class="bi bi-arrow-clockwise fs-5"></i>
                                    <span>{{ __('netfusion.reboot_system') }}</span>
                                </button>
                            </form>

                            <form action="{{ route('mikrotik-suite.netfusion.system.shutdown') }}" method="POST"
                                onsubmit="return confirm('{{ __('netfusion.confirm_shutdown') }}');">
                                @csrf
                                <button
                                    class="btn btn-danger w-100 rounded-pill py-3 fw-bold shadow-sm hover-scale text-white d-flex align-items-center justify-content-center gap-2">
                                    <i class="bi bi-power fs-5"></i>
                                    <span>{{ __('netfusion.shutdown_system') }}</span>
                                </button>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .ls-1 {
            letter-spacing: 1px;
        }

        .hover-scale {
            transition: transform 0.2s ease;
        }

        .hover-scale:hover {
            transform: scale(1.05);
        }
    </style>
@endsection