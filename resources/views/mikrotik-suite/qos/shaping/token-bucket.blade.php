@extends('layouts.app')



@section('content')
    <x-page-header title="Token Bucket Info"
        subtitle="Information about the Hierarchical Token Bucket (HTB) algorithm used in RouterOS.">
        <x-slot name="action">
            <div class="d-none d-md-block text-secondary small">
                <i class="bi bi-bucket me-1"></i> Algorithm Guide
            </div>
        </x-slot>
    </x-page-header>

    <x-card class="p-5 text-center">
        <div class="py-4">
            <h4 class="fw-bold text-dark mb-3">HTB Strategy</h4>
            <p class="text-secondary opacity-75 mb-4">RouterOS uses HTB for Queue Trees and Simple Queues (parent/child).
            </p>

            <div class="d-inline-block text-start bg-light bg-opacity-50 p-4 rounded border mb-4">
                <ul class="list-unstyled mb-0 text-secondary">
                    <li class="mb-2"><strong class="text-dark">CIR (Committed Information Rate):</strong> Guaranteed
                        bandwidth (`limit-at`).</li>
                    <li class="mb-2"><strong class="text-dark">MIR (Max Information Rate):</strong> Maximum bandwidth
                        (`max-limit`).</li>
                    <li class="mb-2"><strong class="text-dark">Priority:</strong> Determines who gets borrowed tokens first
                        (1 = High, 8 = Low).</li>
                    <li><strong class="text-dark">Parent Queue:</strong> Distributes tokens to children.</li>
                </ul>
            </div>

            <div>
                <a href="https://wiki.mikrotik.com/wiki/Manual:Queue" target="_blank" class="btn btn-outline-secondary">
                    <i class="bi bi-book me-1"></i> Read MikroTik Wiki
                </a>
            </div>
        </div>
    </x-card>
@endsection
