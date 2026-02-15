@extends('layouts.app')

@section('content')
    <div class="container-fluid px-4 py-4">
        <div class="mb-4 d-flex flex-column align-items-center justify-content-center text-center">
            <h2 class="display-6 fw-bold mb-2 text-white" style="text-shadow: 0 2px 10px rgba(0,0,0,0.3);">
                <i class="bi bi-diagram-3-fill me-2 text-primary"></i> Bridge VLAN Filtering
            </h2>
            <p class="lead opacity-75 mb-0" style="max-width: 700px;">
                Configure Layer 2 VLAN filtering on a single Bridge interface (ROS v6.41+).
            </p>
        </div>

        <form id="vlanForm" data-route="{{ route('mikrotik-suite.network.switching.bridge-vlan.generate') }}">
            <div class="row g-4 justify-content-center">
                <div class="col-lg-5">
                    <div class="glass-card h-100">
                        <div class="card-header border-0 bg-transparent pt-4 px-4">
                            <h5 class="card-title fw-bold text-white mb-0">Bridge Setup</h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="mb-3">
                                <label class="form-label text-white small text-uppercase">Bridge Name</label>
                                <input type="text" class="form-control" name="bridge_name" id="bridge" value="bridge1">
                            </div>

                            <hr class="border-secondary opacity-25">

                            <div class="mb-3">
                                <label class="form-label text-white small text-uppercase">Tagged Ports (Trunk)</label>
                                <input type="text" class="form-control" name="tagged_ports" id="tagged" value="ether1">
                                <div class="form-text text-white-50">Ports carrying multiple VLANs. Comma separated.</div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-white small text-uppercase">Untagged Ports (Access)</label>
                                <input type="text" class="form-control" name="untagged_ports" id="untagged"
                                    value="ether2,ether3">
                                <div class="form-text text-white-50">Ports for end devices.</div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-white small text-uppercase">VLAN ID</label>
                                <input type="number" class="form-control" name="vlan_id" id="vlanId" value="10">
                            </div>

                            <div class="d-grid mt-4">
                                <button type="submit" class="btn btn-primary btn-lg shadow-lg">
                                    <span class="btn-label">Generate VLAN Entry</span>
                                    <span class="btn-loader d-none"><span
                                            class="spinner-border spinner-border-sm me-2"></span>Generating...</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="glass-card h-100">
                        <div class="card-header border-0 bg-transparent pt-4 px-4 d-flex justify-content-between">
                            <h5 class="card-title fw-bold text-white mb-0">RouterOS Script</h5>
                            <button type="button" class="btn btn-sm btn-outline-light"
                                onclick="copyText('scriptOutput')">Copy</button>
                        </div>
                        <div class="card-body p-0">
                            <div class="h-100 d-flex flex-column">
                                <div
                                    class="alert alert-warning bg-warning bg-opacity-10 border-0 text-warning m-3 small border-start border-4 border-warning">
                                    <i class="bi bi-exclamation-triangle me-1"></i> Apply config carefully. Enabling
                                    filtering might cut access if config is wrong.
                                </div>
                                <pre class="m-0 p-4 text-warning bg-dark bg-opacity-50 flex-grow-1 font-monospace"
                                    id="scriptOutput" style="min-height:300px;">// Script...</pre>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    @vite(['resources/js/pages/mikrotik-suite/network/vlan/bridge-vlan-filtering.js'])
@endpush
