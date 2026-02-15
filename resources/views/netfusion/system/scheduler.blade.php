@extends('layouts.app')

@section('title', 'Scheduler')

@section('content')
    <div class="container-fluid px-4">

        <!-- Header -->
        <div
            class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-5 gap-3">
            <div class="d-flex align-items-center gap-3">
                <div class="bg-white p-3 rounded-circle shadow-sm d-flex align-items-center justify-content-center"
                    style="width: 50px; height: 50px;">
                    <i class="bi bi-clock-history text-primary fs-4"></i>
                </div>
                <div>
                    <h4 class="fw-bold text-dark mb-1">{{ __('netfusion.scheduler') }}</h4>
                    <p class="text-muted small mb-0">{{ __('netfusion.scheduler_description') }}</p>
                </div>
            </div>
            <div>
                <button
                    class="btn btn-primary rounded-pill px-4 py-2 shadow-sm hover-scale fw-bold d-flex align-items-center gap-2"
                    data-bs-toggle="modal" data-bs-target="#addSchedulerModal">
                    <i class="bi bi-plus-lg"></i>
                    <span>{{ __('netfusion.add_schedule') }}</span>
                </button>
            </div>
        </div>

        <!-- Main Card -->
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table align-middle mb-0 table-hover">
                        <thead class="bg-light">
                            <tr>
                                <th class="px-4 py-3 text-muted small text-uppercase border-0 fw-semibold">{{ __('netfusion.name') }}</th>
                                <th class="px-4 py-3 text-muted small text-uppercase border-0 fw-semibold">{{ __('netfusion.interval') }}</th>
                                <th class="px-4 py-3 text-muted small text-uppercase border-0 fw-semibold">{{ __('netfusion.next_run') }}</th>
                                <th class="px-4 py-3 text-muted small text-uppercase border-0 fw-semibold">{{ __('netfusion.on_event') }}</th>
                                <th class="px-4 py-3 text-muted small text-uppercase border-0 fw-semibold text-center">{{ __('netfusion.runs_count') }}
                                </th>
                                <th class="px-4 py-3 text-muted small text-uppercase border-0 fw-semibold text-end">{{ __('netfusion.actions') }}
                                </th>
                            </tr>
                        </thead>
                        <tbody class="border-top-0">
                            @forelse($schedulers as $scheduler)
                                <tr class="border-light border-bottom border-opacity-50">
                                    <td class="px-4 py-3">
                                        <span class="fw-bold text-dark d-block">{{ $scheduler['name'] }}</span>
                                        @if(isset($scheduler['comment']))
                                            <small class="text-muted">{{ $scheduler['comment'] }}</small>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-secondary font-monospace small bg-light rounded">
                                        {{ $scheduler['interval'] ?? '00:00:00' }}</td>
                                    <td class="px-4 py-3">
                                        <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3 fw-normal">
                                            {{ $scheduler['next-run'] ?? '-' }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="text-muted small text-truncate"
                                            style="max-width: 200px; font-family: monospace;">
                                            {{ $scheduler['on-event'] ?? '-' }}
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 text-center fw-bold text-secondary">
                                        {{ $scheduler['run-count'] ?? '0' }}</td>
                                    <td class="px-4 py-3 text-end">
                                        <div class="d-flex justify-content-end gap-2">
                                            <form
                                                action="{{ route('mikrotik-suite.netfusion.system.scheduler.toggle', $scheduler['.id']) }}"
                                                method="POST">
                                                @csrf
                                                @if(($scheduler['disabled'] ?? 'false') == 'true')
                                                    <input type="hidden" name="disable" value="false">
                                                    <button type="submit"
                                                        class="btn btn-sm btn-light text-secondary rounded-circle shadow-sm"
                                                        title="{{ __('netfusion.enable') }}" style="width: 32px; height: 32px;">
                                                        <i class="bi bi-toggle-off fs-6"></i>
                                                    </button>
                                                @else
                                                    <input type="hidden" name="disable" value="true">
                                                    <button type="submit"
                                                        class="btn btn-sm btn-light text-success rounded-circle shadow-sm"
                                                        title="{{ __('netfusion.disable') }}" style="width: 32px; height: 32px;">
                                                        <i class="bi bi-toggle-on fs-6"></i>
                                                    </button>
                                                @endif
                                            </form>

                                            <form
                                                action="{{ route('mikrotik-suite.netfusion.system.scheduler.destroy', $scheduler['.id']) }}"
                                                method="POST"
                                                onsubmit="return confirm('{{ __('netfusion.confirm_delete_scheduler', ['name' => $scheduler['name']]) }}');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="btn btn-sm btn-white text-danger border-0 rounded-circle shadow-sm hover-scale"
                                                    title="{{ __('netfusion.delete') }}" style="width: 32px; height: 32px;">
                                                    <i class="bi bi-trash fs-6"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5 text-muted">
                                        <div class="d-flex flex-column align-items-center">
                                            <i class="bi bi-calendar-x fs-1 opacity-25 mb-3"></i>
                                            <p class="mb-0">{{ __('netfusion.no_schedulers_found') }}</p>
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

    <!-- Add Modal (Clean Style) -->
    <div class="modal fade" id="addSchedulerModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="modal-header border-0 bg-light p-4">
                    <h5 class="modal-title fw-bold text-dark"><i class="bi bi-plus-circle me-2 text-primary"></i>{{ __('netfusion.add_new_scheduler') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <form action="{{ route('mikrotik-suite.netfusion.system.scheduler.store') }}" method="POST">
                        @csrf

                        <div class="form-floating mb-3">
                            <input type="text" name="name" class="form-control border border-secondary border-opacity-25 bg-light rounded-3" id="name"
                                required placeholder="{{ __('netfusion.name') }}">
                            <label for="name" class="text-muted">{{ __('netfusion.name') }}</label>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-6">
                                <div class="form-floating">
                                    <input type="text" name="start_date" class="form-control border border-secondary border-opacity-25 bg-light rounded-3"
                                        id="start_date" placeholder="{{ __('netfusion.start_date') }}" value="Jan/01/2024">
                                    <label for="start_date" class="text-muted">{{ __('netfusion.start_date') }}</label>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-floating">
                                    <input type="text" name="start_time" class="form-control border border-secondary border-opacity-25 bg-light rounded-3"
                                        id="start_time" placeholder="{{ __('netfusion.start_time') }}" value="00:00:00">
                                    <label for="start_time" class="text-muted">{{ __('netfusion.start_time') }}</label>
                                </div>
                            </div>
                        </div>

                        <div class="form-floating mb-3">
                            <input type="text" name="interval" class="form-control border border-secondary border-opacity-25 bg-light rounded-3"
                                id="interval" required placeholder="1d" value="1d 00:00:00">
                            <label for="interval" class="text-muted">{{ __('netfusion.interval') }} (e.g., 1d 00:00:00)</label>
                        </div>

                        <div class="form-floating mb-3">
                            <textarea name="on_event" class="form-control border border-secondary border-opacity-25 bg-light rounded-3" id="on_event"
                                style="height: 100px" required placeholder="{{ __('netfusion.on_event_script') }}"></textarea>
                            <label for="on_event" class="text-muted">{{ __('netfusion.on_event_script') }}</label>
                        </div>

                        <div class="form-floating mb-4">
                            <input type="text" name="comment" class="form-control border border-secondary border-opacity-25 bg-light rounded-3" id="comment"
                                placeholder="{{ __('netfusion.comment') }}">
                            <label for="comment" class="text-muted">{{ __('netfusion.comment') }} (Optional)</label>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary fw-bold rounded-pill py-3 shadow-sm hover-scale">
                                {{ __('netfusion.create_schedule') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <style>
        .hover-scale {
            transition: transform 0.2s ease;
        }

        .hover-scale:hover {
            transform: scale(1.05);
        }
    </style>
@endsection
