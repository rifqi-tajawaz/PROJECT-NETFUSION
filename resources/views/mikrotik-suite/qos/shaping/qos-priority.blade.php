@extends('layouts.app')



@section('content')
    <x-page-header title="QoS Priority Config"
        subtitle="Set Priority (1-8) for Queues. REMEMBER: 1 is Highest, 8 is Lowest.">
        <x-slot name="action">
            <div class="d-none d-md-block text-secondary small">
                <i class="bi bi-sort-numeric-down me-1"></i> Traffic Rank
            </div>
        </x-slot>
    </x-page-header>

    <form id="prioForm" data-route="{{ route('mikrotik-suite.qos.queues.priority.generate') }}">
        <div class="row g-4 justify-content-center">
            <div class="col-lg-5">
                <x-card title="Priority Map" class="h-100">
                    <x-slot name="headerAction">
                        <i class="bi bi-list-stars text-info"></i>
                    </x-slot>

                    <div class="mb-3">
                        <label class="form-label small text-uppercase fw-bold text-secondary">Target Queue Name</label>
                        <input type="text" class="form-control" id="qname" name="qname" value="VoIP_Queue">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small text-uppercase fw-bold text-secondary">Priority</label>
                        <select class="form-select" id="prio" name="prio">
                            <option value="1">1 (Critical / VoIP / DNS)</option>
                            <option value="2">2 (Winbox / Management)</option>
                            <option value="3">3 (Gaming)</option>
                            <option value="4">4 (Browsing / HTTP)</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8 (Bulk / P2P / Default)</option>
                        </select>
                    </div>

                    <div class="d-grid mt-4">
                        <button type="submit" class="btn btn-info text-dark btn-lg shadow-info">Set Priority</button>
                    </div>
                </x-card>
            </div>

            <div class="col-lg-6">
                <x-card title="RouterOS Script" class="h-100 p-0" bodyClass="p-0">
                    <x-slot name="headerAction">
                        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="copyText('scriptOutput')">
                            <i class="bi bi-clipboard"></i> Copy
                        </button>
                    </x-slot>

                    <pre class="m-0 p-4 text-warning bg-dark bg-opacity-75 h-100 font-monospace" id="scriptOutput"
                        style="min-height:300px;">// Script...</pre>
                </x-card>
            </div>
        </div>
    </form>
@endsection

@push('scripts')
    @vite(['resources/js/pages/mikrotik-suite/qos/shaping/qos-priority.js'])
@endpush
