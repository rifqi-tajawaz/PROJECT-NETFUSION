@extends('layouts.app')



@section('content')
    <x-page-header title="Queue Tree Generator" subtitle="Configure hierarchical HTB queues for global traffic shaping.">
        <x-slot name="action">
            <div class="d-none d-md-block text-secondary small">
                <i class="bi bi-diagram-3 me-1"></i> Global Shaping
            </div>
        </x-slot>
    </x-page-header>

    <form id="treeForm" data-route="{{ route('mikrotik-suite.qos.queues.tree.generate') }}">
        <div class="row g-4 justify-content-center">
            <div class="col-lg-5">
                <x-card title="Parent Queue" class="h-100">
                    <x-slot name="headerAction">
                        <i class="bi bi-diagram-2 text-warning"></i>
                    </x-slot>

                    <div class="mb-3">
                        <label class="form-label small text-uppercase fw-bold text-secondary">Parent Name</label>
                        <input type="text" class="form-control" id="parentName" name="parentName" value="Global_Download">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small text-uppercase fw-bold text-secondary">Parent Interface (or
                            Global)</label>
                        <input type="text" class="form-control" id="parentIface" name="parentIface" value="global">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small text-uppercase fw-bold text-secondary">Total Bandwidth</label>
                        <input type="text" class="form-control" id="totalBw" name="totalBw" placeholder="100M">
                    </div>
                    <hr class="border-secondary opacity-25">
                    <div class="mb-3">
                        <label class="form-label small text-uppercase fw-bold text-secondary">Create Child Queue?</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="childName" name="childName" placeholder="Client_1">
                            <input type="text" class="form-control" id="childLimit" name="childLimit" placeholder="10M">
                            <input type="text" class="form-control" id="packetMark" name="packetMark"
                                placeholder="mark_client1">
                        </div>
                    </div>

                    <div class="d-grid mt-4">
                        <button type="submit" class="btn btn-warning text-dark btn-lg shadow-warning">Generate Tree</button>
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
    @vite(['resources/js/pages/mikrotik-suite/qos/queues/queue-tree.js'])
@endpush
