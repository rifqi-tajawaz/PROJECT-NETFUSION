@extends('layouts.app')



@section('content')
    <x-page-header title="Gaming Optimization" subtitle="Prioritize gaming traffic using Mangle rules and Packet Marking.">
        <x-slot name="action">
            <div class="d-none d-md-block text-secondary small">
                <i class="bi bi-controller me-1"></i> Low Latency
            </div>
        </x-slot>
    </x-page-header>

    <form id="gameForm" data-route="{{ route('mikrotik-suite.qos.application.gaming.generate') }}">
        <div class="row g-4 justify-content-center">
            <div class="col-lg-5">
                <x-card title="Game Select" class="h-100">
                    <x-slot name="headerAction">
                        <i class="bi bi-joystick text-primary"></i>
                    </x-slot>

                    <div class="mb-3">
                        <label class="form-label small text-uppercase fw-bold text-secondary">Game Ports (Presets)</label>
                        <select class="form-select" id="gamePreset" name="game_preset">
                            <option value="custom">Custom</option>
                            <option value="mobile_legends">Mobile Legends (TCP: 30000-30010, UDP: 30000-30010)</option>
                            <option value="pubg_mobile">PUBG Mobile (TCP: 17500, UDP: 10000-20000)</option>
                            <option value="free_fire">Free Fire (TCP: 39698-39700)</option>
                            <option value="valorant">Valorant (UDP: 7000-7500)</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small text-uppercase fw-bold text-secondary">Custom Ports</label>
                        <input type="text" class="form-control" id="ports" name="custom_ports"
                            placeholder="udp:1234,tcp:5678">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small text-uppercase fw-bold text-secondary">Subnet to Prioritize</label>
                        <input type="text" class="form-control" id="src" name="src_address" value="192.168.88.0/24">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small text-uppercase fw-bold text-secondary">Priority Level</label>
                        <select class="form-select" id="prio" name="priority">
                            <option value="1">1 (Highest)</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                        </select>
                    </div>

                    <div class="d-grid mt-4">
                        <button type="submit" class="btn btn-primary btn-lg shadow-primary">Generate Mangle Rules</button>
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
    @vite(['resources/js/pages/mikrotik-suite/qos/application-routing/gaming-routes.js'])
@endpush
