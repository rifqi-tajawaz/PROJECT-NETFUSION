@extends('layouts.app')

@section('title', __('netfusion.sales_reports'))

@section('content')
    <div class="container-fluid px-4 py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-bold text-dark mb-1">{{ __('netfusion.sales_overview') }}</h4>
                <p class="text-muted small mb-0">{{ __('netfusion.financial_performance') }}</p>
            </div>
            <div class="d-flex gap-2">
                <span class="badge bg-light text-secondary border px-3 py-2 rounded-pill d-flex align-items-center">
                    <i class="bi bi-calendar-event me-2"></i> {{ date('F Y') }}
                </span>
            </div>
        </div>

        <div class="row g-4 mb-4">
            <!-- Income Logic -->
            <div class="col-12 col-md-6 col-lg-4">
                <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden text-white"
                    style="background: linear-gradient(135deg, #0d6efd, #0a58ca);">
                    <div class="card-body p-4 position-relative">
                        <div class="position-absolute top-0 end-0 p-3 opacity-10">
                            <i class="bi bi-wallet2" style="font-size: 5rem;"></i>
                        </div>
                        <div class="position-relative z-1">
                            <h6 class="text-uppercase text-white-50 small fw-bold ls-1 mb-1">
                                {{ __('netfusion.total_estimated_income') }}</h6>
                            <h2 class="display-5 fw-bold mb-0">Rp {{ number_format($totalIncome, 0, ',', '.') }}</h2>
                            <p
                                class="mb-0 mt-3 small text-white-50 bg-white bg-opacity-10 d-inline-block px-2 py-1 rounded-2">
                                <i class="bi bi-graph-up-arrow me-1"></i> {{ __('netfusion.based_on_generated_vouchers') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Users Logic -->
            <div class="col-12 col-md-6 col-lg-4">
                <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden text-white"
                    style="background: linear-gradient(135deg, #198754, #157347);">
                    <div class="card-body p-4 position-relative">
                        <div class="position-absolute top-0 end-0 p-3 opacity-10">
                            <i class="bi bi-people-fill" style="font-size: 5rem;"></i>
                        </div>
                        <div class="position-relative z-1">
                            <h6 class="text-uppercase text-white-50 small fw-bold ls-1 mb-1">
                                {{ __('netfusion.total_users_tracked') }}</h6>
                            <h2 class="display-5 fw-bold mb-0">{{ number_format($usersCount) }}</h2>
                            <p
                                class="mb-0 mt-3 small text-white-50 bg-white bg-opacity-10 d-inline-block px-2 py-1 rounded-2">
                                <i class="bi bi-check-circle-fill me-1"></i> {{ __('netfusion.active_sold_vouchers') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Info Logic -->
            <div class="col-12 col-lg-4">
                <div class="card border-0 shadow-sm rounded-4 h-100 bg-white">
                    <div class="card-body p-4 d-flex flex-column justify-content-center">
                        <h6 class="text-uppercase text-muted small fw-bold ls-1 mb-2">{{ __('netfusion.notice') }}</h6>
                        <p class="text-muted small mb-0">
                            {{ __('netfusion.income_calculation_notice') }}
                            <span class="font-monospace bg-light rounded px-1">User-Date - Price</span>
                            (e.g., <span class="fst-italic text-dark">User-2023-10-01 - 5000</span>).
                        </p>
                        <div class="mt-3">
                            <a href="{{ route('mikrotik-suite.netfusion.users.generate') }}"
                                class="btn btn-sm btn-outline-primary rounded-pill">
                                <i class="bi bi-magic me-1"></i> {{ __('netfusion.generate_format') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Report Table -->
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="card-header bg-transparent border-bottom p-4">
                <h5 class="fw-bold text-dark mb-0">{{ __('netfusion.monthly_breakdown') }}</h5>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light text-secondary text-uppercase small ls-1 fw-bold">
                        <tr>
                            <th class="ps-4 py-3">{{ __('netfusion.period') }}</th>
                            <th class="py-3">{{ __('netfusion.sales_volume') }}</th>
                            <th class="py-3">{{ __('netfusion.total_income') }}</th>
                            <th class="text-end pe-4 py-3">{{ __('netfusion.trend') }}</th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0">
                        @forelse($monthlyReport as $month => $data)
                            <tr>
                                <td class="ps-4 fw-bold text-primary">{{ $month }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <span class="badge bg-light text-dark border me-2">{{ $data['count'] }}</span>
                                        <small class="text-muted">{{ __('netfusion.vouchers_sold') }}</small>
                                    </div>
                                </td>
                                <td>
                                    <h6 class="fw-bold text-success mb-0">Rp {{ number_format($data['total'], 0, ',', '.') }}
                                    </h6>
                                </td>
                                <td class="text-end pe-4">
                                    @if($data['total'] > 0)
                                        <i class="bi bi-graph-up-arrow text-success"></i>
                                    @else
                                        <i class="bi bi-dash-lg text-muted"></i>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-5">
                                    <img src="https://cdni.iconscout.com/illustration/premium/thumb/data-analysis-4560738-3786278.png"
                                        style="width: 150px; opacity: 0.5;" class="mb-3">
                                    <h6 class="text-muted fw-bold">{{ __('netfusion.no_sales_data') }}</h6>
                                    <p class="text-muted small">{{ __('netfusion.start_tracking_msg') }}</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
