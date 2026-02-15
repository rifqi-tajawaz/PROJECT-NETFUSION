@extends('layouts.app')



@section('content')
    <x-page-header title="Mangle Obfuscator"
        subtitle="Obfuscate your Mangle rule comments to make them harder to understand for competitors.">
        <x-slot name="action">
            <div class="d-none d-md-block text-secondary small">
                <i class="bi bi-code-slash me-1"></i> Obfuscation
            </div>
        </x-slot>
    </x-page-header>

    <x-card class="p-4 text-center">
        <div class="py-5">
            <i class="bi bi-cone-striped display-1 text-secondary opacity-50 mb-3"></i>
            <h4 class="fw-bold text-secondary">Coming Soon</h4>
            <p class="text-secondary opacity-75 mb-4">This tool is currently under development.</p>
            <button class="btn btn-secondary disabled rounded-3">Coming Later</button>
        </div>
    </x-card>
@endsection
