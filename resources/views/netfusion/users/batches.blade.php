@extends('layouts.app')

@section('title', __('netfusion.batch_history'))

@section('content')
    <div class="container-fluid px-4 py-4">

        <!-- Header -->
        <div class="row g-3 mb-4 align-items-center justify-content-between">
            <div class="col-12 col-md-8">
                <h4 class="fw-bold text-dark mb-1 d-flex align-items-center gap-2">
                    <div class="rounded-circle bg-white text-info p-2 shadow-sm d-flex align-items-center justify-content-center"
                        style="width: 48px; height: 48px;">
                        <i class="bi bi-clock-history fs-4"></i>
                    </div>
                    <div>
                        {{ __('netfusion.batch_history') }}
                        <div class="text-muted small fw-normal mt-1" style="font-size: 0.85rem;">
                            {{ __('netfusion.manage_batches') }}
                        </div>
                    </div>
                </h4>
            </div>
            <div class="col-12 col-md-4 text-md-end">
                <a href="{{ route('mikrotik-suite.netfusion.users.generate') }}"
                    class="btn btn-primary rounded-pill px-4 py-2 shadow-sm hover-scale fw-bold">
                    <i class="bi bi-plus-lg me-2"></i>{{ __('netfusion.generate_new') }}
                </a>
            </div>
        </div>

        <!-- Glass Card -->
        <div class="card border-0 shadow-lg rounded-5 overflow-hidden position-relative" style="min-height: 500px;">

            <!-- Decorative BG -->
            <div class="position-absolute top-0 start-0 w-100 h-100 bg-light" style="z-index: 0;">
                <div class="position-absolute top-0 end-0 bg-info opacity-10 rounded-circle blur-3xl"
                    style="width: 500px; height: 500px; transform: translate(30%, -30%);"></div>
                <div class="position-absolute bottom-0 start-0 bg-primary opacity-10 rounded-circle blur-3xl"
                    style="width: 400px; height: 400px; transform: translate(-30%, 30%);"></div>
            </div>

            <div class="position-relative p-0" style="z-index: 1;">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" style="background: rgba(255,255,255,0.7);">
                        <thead class="bg-light bg-opacity-75">
                            <tr>
                                <th class="px-4 py-3 text-uppercase text-secondary small fw-bold ls-1 border-0">{{ __('netfusion.batch_name_comment') }}</th>
                                <th class="px-4 py-3 text-uppercase text-secondary small fw-bold ls-1 border-0">{{ __('netfusion.profile') }}</th>
                                <th class="px-4 py-3 text-uppercase text-secondary small fw-bold ls-1 border-0 text-center">
                                    {{ __('netfusion.vouchers') }}</th>
                                <th class="px-4 py-3 text-uppercase text-secondary small fw-bold ls-1 border-0">{{ __('netfusion.created_at') }}
                                </th>
                                <th class="px-4 py-3 text-uppercase text-secondary small fw-bold ls-1 border-0 text-end">
                                    {{ __('netfusion.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody class="border-top-0">
                            @forelse($batches as $batch)
                                <tr class="transition-hover">
                                    <td class="px-4 py-3 border-light">
                                        <div class="d-flex align-items-center">
                                            <div class="rounded-circle bg-white shadow-sm p-2 text-primary me-3 d-flex align-items-center justify-content-center"
                                                style="width: 40px; height: 40px;">
                                                <i class="bi bi-collection-fill"></i>
                                            </div>
                                            <div class="fw-bold text-dark">{{ $batch['comment'] }}</div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 border-light">
                                        <span class="badge bg-white text-dark border px-3 py-2 rounded-pill shadow-sm">
                                            <i class="bi bi-hdd-network me-1 text-secondary"></i> {{ $batch['profile'] }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 border-light text-center">
                                        <span class="fw-bold text-dark fs-5">{{ $batch['count'] }}</span>
                                    </td>
                                    <td class="px-4 py-3 border-light text-muted small">
                                        <i class="bi bi-calendar3 me-1"></i> {{ $batch['created_at'] }}
                                    </td>
                                    <td class="px-4 py-3 border-light text-end">
                                        <div class="d-flex justify-content-end gap-2">
                                            <!-- Quick Print Action (Goes to new module) -->
                                            <a href="{{ route('mikrotik-suite.netfusion.printing.index', ['batch' => $batch['comment']]) }}"
                                                class="btn btn-outline-primary btn-sm rounded-pill px-3 shadow-sm hover-scale d-flex align-items-center gap-2"
                                                title="{{ __('netfusion.print_batch') }}">
                                                <i class="bi bi-printer-fill"></i> {{ __('netfusion.print') }}
                                            </a>

                                            <!-- Delete Action -->
                                            <div class="dropdown">
                                                <button class="btn btn-light btn-sm rounded-circle shadow-sm" type="button"
                                                    data-bs-toggle="dropdown">
                                                    <i class="bi bi-three-dots-vertical"></i>
                                                </button>
                                                <ul
                                                    class="dropdown-menu dropdown-menu-end border-0 shadow-lg rounded-4 overflow-hidden p-0">
                                                    <li>
                                                        <form action="{{ route('mikrotik-suite.netfusion.users.destroy-batch') }}"
                                                            method="POST"
                                                            onsubmit="return confirm('{{ __('netfusion.confirm_delete_batch', ['count' => $batch['count'], 'batch' => $batch['comment']]) }}');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <input type="hidden" name="comment" value="{{ $batch['comment'] }}">
                                                            <button type="submit"
                                                                class="dropdown-item text-danger py-3 px-4 d-flex align-items-center gap-2 hover-danger">
                                                                <i class="bi bi-trash-fill"></i> {{ __('netfusion.delete_batch') }}
                                                            </button>
                                                        </form>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5 border-0">
                                        <div class="d-flex flex-column align-items-center justify-content-center opacity-50">
                                            <i class="bi bi-inbox-fill fs-1 mb-3 text-secondary"></i>
                                            <h6 class="text-muted fw-bold">{{ __('netfusion.no_batch_history') }}</h6>
                                            <p class="text-muted small mb-0">{{ __('netfusion.no_batch_msg') }}</p>
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

        .hover-scale {
            transition: transform 0.2s ease;
        }

        .hover-scale:hover {
            transform: translateY(-2px);
        }

        .transition-hover:hover {
            background-color: rgba(255, 255, 255, 0.5);
        }

        .hover-danger:hover {
            background-color: #dc3545 !important;
            color: white !important;
        }
    </style>
@endsection
