@extends('layouts.app')

@section('title', __('netfusion.selling_reports'))

@section('content')
    <div class="container-fluid px-4">

        <!-- Header -->
        <div
            class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-3">
            <div class="d-flex align-items-center gap-3">
                <div class="bg-white p-3 rounded-circle shadow-sm d-flex align-items-center justify-content-center"
                    style="width: 50px; height: 50px;">
                    <i class="bi bi-graph-up-arrow text-primary fs-4"></i>
                </div>
                <div>
                    <h4 class="fw-bold text-dark mb-1">{{ __('netfusion.selling_reports') }}</h4>
                    <p class="text-muted small mb-0">{{ __('netfusion.track_sales_income') }}</p>
                </div>
            </div>
            <div class="d-flex flex-wrap gap-2">
                <button
                    class="btn btn-primary rounded-pill px-4 py-2 shadow-sm fw-bold d-flex align-items-center gap-2 hover-scale"
                    data-bs-toggle="modal" data-bs-target="#addReportModal">
                    <i class="bi bi-plus-lg"></i>
                    <span>{{ __('netfusion.add_entry') }}</span>
                </button>
                <a href="{{ route('mikrotik-suite.netfusion.reports.export-csv', request()->all()) }}"
                    class="btn btn-white border shadow-sm rounded-pill px-4 py-2 hover-scale fw-bold d-flex align-items-center gap-2 text-success">
                    <i class="bi bi-file-earmark-spreadsheet"></i>
                    <span>{{ __('netfusion.export_csv') }}</span>
                </a>
                <a href="{{ route('mikrotik-suite.netfusion.reports.print-view', request()->all()) }}" target="_blank"
                    class="btn btn-white border shadow-sm rounded-pill px-4 py-2 hover-scale fw-bold d-flex align-items-center gap-2 text-dark">
                    <i class="bi bi-printer"></i>
                    <span>{{ __('netfusion.print') }}</span>
                </a>
            </div>
        </div>

        <!-- Monthly Summary Cards -->
        @if(!empty($monthlyReport))
            <div class="row g-4 mb-4">
                @foreach($monthlyReport as $month)
                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="card border-0 shadow-sm rounded-4 h-100 bg-white">
                            <div class="card-body p-4">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <div>
                                        <span class="text-muted small text-uppercase fw-bold ls-1 d-block mb-1"
                                            style="font-size: 0.7rem;">{{ $month['month'] }}</span>
                                        <h4 class="fw-bold text-dark mb-0">Rp {{ number_format($month['total'], 0, '', '.') }}</h4>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-center rounded-circle bg-primary bg-opacity-10 text-primary flex-shrink-0"
                                        style="width: 40px; height: 40px;">
                                        <span class="fw-bold small">{{ $month['count'] }}</span>
                                    </div>
                                </div>
                                <?php
                    $maxTotal = collect($monthlyReport)->max('total');
                    $percentage = $maxTotal > 0 ? ($month['total'] / $maxTotal) * 100 : 0;
                                                                                ?>
                                <div class="progress rounded-pill bg-light" style="height: 6px;">
                                    <div class="progress-bar bg-primary rounded-pill" role="progressbar"
                                        style="width: {{ $percentage }}%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        <!-- Filters & Main Content -->
        <div class="row g-4">
            <!-- Filter Sidebar (Mobile friendly: stacks on top) -->
            <div class="col-lg-3">
                <div class="card border-0 shadow-sm rounded-4 bg-white h-100">
                    <div class="card-body p-4">
                        <h6 class="fw-bold text-dark mb-4 d-flex align-items-center gap-2">
                            <i class="bi bi-funnel text-primary"></i> {{ __('netfusion.filter_records') }}
                        </h6>
                        <form method="GET" action="{{ route('mikrotik-suite.netfusion.reports.index') }}">
                            <div class="mb-3">
                                <label
                                    class="form-label small fw-bold text-muted text-uppercase ls-1">{{ __('netfusion.month') }}</label>
                                <select name="month"
                                    class="form-select border-secondary border-opacity-25 bg-light rounded-3 shadow-sm py-2">
                                    <option value="">{{ __('netfusion.all_months') }}</option>
                                    @foreach($months as $key => $label)
                                        <option value="{{ $key }}" {{ $selectedMonth == $key ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-4">
                                <label
                                    class="form-label small fw-bold text-muted text-uppercase ls-1">{{ __('netfusion.specific_date') }}</label>
                                <input type="date" name="day"
                                    class="form-control border-secondary border-opacity-25 bg-light rounded-3 shadow-sm py-2"
                                    value="{{ $selectedDay ?? '' }}">
                            </div>
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary rounded-pill fw-bold shadow-sm py-2">
                                    {{ __('netfusion.apply_filter') }}
                                </button>
                                @if($selectedMonth || $selectedDay)
                                    <a href="{{ route('mikrotik-suite.netfusion.reports.index') }}"
                                        class="btn btn-light text-muted border rounded-pill fw-bold py-2">
                                        {{ __('netfusion.clear_filter') }}
                                    </a>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Report Table -->
            <div class="col-lg-9">
                <div class="card border-0 shadow-sm rounded-4 h-100 bg-white overflow-hidden">
                    <div
                        class="card-header bg-transparent border-0 pt-4 px-4 pb-0 d-flex justify-content-between align-items-center">
                        <h6 class="fw-bold mb-0 text-dark d-flex align-items-center gap-2">
                            <i class="bi bi-list-columns-reverse text-primary"></i>
                            {{ __('netfusion.records_list') }}
                            @if($selectedMonth || $selectedDay)
                                <span
                                    class="badge bg-info bg-opacity-10 text-info rounded-pill fw-normal px-3">{{ __('netfusion.filtered') }}</span>
                            @endif
                        </h6>
                        <div class="d-flex align-items-center gap-2">
                            <span class="text-muted small text-uppercase fw-bold ls-1">{{ __('netfusion.total') }}</span>
                            <span class="fw-bold text-success fs-5">Rp {{ number_format($totalIncome, 0, '', '.') }}</span>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <div class="table-responsive">
                            <table class="table align-middle mb-0">
                                <thead class="text-muted small text-uppercase">
                                    <tr class="border-bottom border-light">
                                        <th class="ps-4 py-3 fw-semibold border-0">{{ __('netfusion.date_and_time') }}</th>
                                        <th class="py-3 fw-semibold border-0">{{ __('netfusion.username') }}</th>
                                        <th class="py-3 fw-semibold border-0">{{ __('netfusion.profile') }}</th>
                                        <th class="py-3 fw-semibold border-0">{{ __('netfusion.price') }}</th>
                                        <th class="py-3 fw-semibold border-0">{{ __('netfusion.note') }}</th>
                                        <th class="text-end pe-4 py-3 fw-semibold border-0">{{ __('netfusion.action') }}
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($reports as $report)
                                        <tr class="border-light border-bottom border-opacity-50">
                                            <td class="ps-4 py-3">
                                                <div class="fw-bold text-dark">{{ $report['date'] }}</div>
                                                <div class="small text-muted">{{ $report['time'] }}</div>
                                            </td>
                                            <td class="py-3">
                                                <span class="fw-bold text-dark">{{ $report['username'] }}</span>
                                            </td>
                                            <td class="py-3">
                                                <span
                                                    class="badge bg-light text-dark border fw-normal">{{ $report['profile'] ?? '-' }}</span>
                                            </td>
                                            <td class="py-3">
                                                <span class="fw-bold text-success">Rp
                                                    {{ number_format($report['price'], 0, '', '.') }}</span>
                                            </td>
                                            <td class="py-3">
                                                <small class="text-muted d-inline-block text-truncate"
                                                    style="max-width: 150px;">
                                                    {{ $report['comment'] ?? '-' }}
                                                </small>
                                            </td>
                                            <td class="text-end pe-4 py-3">
                                                <form
                                                    action="{{ route('mikrotik-suite.netfusion.reports.destroy', $report['id']) }}"
                                                    method="POST" onsubmit="return confirm('Delete this report entry?');"
                                                    class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="btn btn-sm btn-white text-danger border shadow-sm rounded-circle d-flex align-items-center justify-content-center ms-auto hover-scale"
                                                        style="width: 32px; height: 32px;" title="{{ __('netfusion.delete') }}">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center py-5">
                                                <div class="text-muted opacity-50">
                                                    <i class="bi bi-inbox fs-1 d-block mb-3"></i>
                                                    <h6>{{ __('netfusion.no_reports_found') }}</h6>
                                                    <p class="small mb-0">{{ __('netfusion.adjust_filters_hint') }}</p>
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
        </div>
    </div>

    <!-- Add Report Modal -->
    <div class="modal fade" id="addReportModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <form method="POST" action="{{ route('mikrotik-suite.netfusion.reports.store') }}">
                @csrf
                <div class="modal-content border-0 shadow rounded-4">
                    <div class="modal-header border-bottom-0 px-4 pt-4 pb-0">
                        <h5 class="fw-bold mb-0 text-dark">{{ __('netfusion.add_report_entry') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label for="username"
                                class="form-label small fw-bold text-muted text-uppercase ls-1">{{ __('netfusion.username') }}
                                <span class="text-danger">*</span></label>
                            <input type="text"
                                class="form-control bg-light border-secondary border-opacity-25 rounded-3 py-2"
                                id="username" name="username" required placeholder="{{ __('netfusion.user_identifier') }}">
                        </div>
                        <div class="mb-3">
                            <label for="price"
                                class="form-label small fw-bold text-muted text-uppercase ls-1">{{ __('netfusion.price') }}
                                (Rp)
                                <span class="text-danger">*</span></label>
                            <input type="number"
                                class="form-control bg-light border-secondary border-opacity-25 rounded-3 py-2" id="price"
                                name="price" required min="0" placeholder="0">
                        </div>
                        <div class="mb-3">
                            <label for="profile"
                                class="form-label small fw-bold text-muted text-uppercase ls-1">{{ __('netfusion.profile') }}</label>
                            <input type="text"
                                class="form-control bg-light border-secondary border-opacity-25 rounded-3 py-2" id="profile"
                                name="profile" placeholder="{{ __('netfusion.optional') }}">
                        </div>
                        <div class="mb-3">
                            <label for="comment"
                                class="form-label small fw-bold text-muted text-uppercase ls-1">{{ __('netfusion.comment') }}</label>
                            <textarea class="form-control bg-light border-secondary border-opacity-25 rounded-3 py-2"
                                id="comment" name="comment" rows="2"
                                placeholder="{{ __('netfusion.optional_notes') }}"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer border-top-0 px-4 pb-4 pt-0">
                        <button type="button" class="btn btn-light rounded-pill px-4 fw-bold"
                            data-bs-dismiss="modal">{{ __('netfusion.cancel') }}</button>
                        <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm">
                            {{ __('netfusion.save_entry') }}
                        </button>
                    </div>
                </div>
            </form>
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