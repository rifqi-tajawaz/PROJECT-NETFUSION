@extends('layouts.app')



@section('content')
    <x-page-header title="Website Routing"
        subtitle="Route specific domains through VPN or specific gateways using Address Lists.">
        <x-slot name="action">
            <div class="d-none d-md-block text-secondary small">
                <i class="bi bi-globe me-1"></i> Domain Routing
            </div>
        </x-slot>
    </x-page-header>

    <x-card class="p-5 text-center">
        <div class="py-4">
            <i class="bi bi-signpost-split display-1 text-primary opacity-50 mb-3"></i>
            <h4 class="fw-bold text-dark">Policy Routing</h4>
            <p class="text-secondary opacity-75 mb-4">Please use the general Global policy routing or Domain-based routing
                logic.</p>
            <a href="{{ route('mikrotik-suite.network.config.policy-routing') }}" class="btn btn-primary btn-lg shadow-primary">
                <i class="bi bi-arrow-right-circle me-1"></i> Go to Policy Routing
            </a>
        </div>
    </x-card>
@endsection
