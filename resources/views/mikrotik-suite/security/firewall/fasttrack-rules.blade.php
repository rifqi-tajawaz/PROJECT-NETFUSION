@extends('layouts.app')

@section('content')
    <div class="container-fluid px-4 py-4">
        <div class="mb-4 d-flex flex-column align-items-center justify-content-center text-center">
            <h2 class="display-6 fw-bold mb-2 text-white" style="text-shadow: 0 2px 10px rgba(0,0,0,0.3);">
                <i class="bi bi-lightning-fill me-2 text-warning"></i> FastTrack Rules
            </h2>
            <p class="lead opacity-75 mb-0" style="max-width: 700px;">
                Enable FastPath/FastTrack to bypass Linux kernel processing for established connections.
            </p>
        </div>

        <div class="glass-card p-5 text-center">
            <p class="text-white">This rule significantly reduces CPU usage but bypasses Queues and some Firewall rules for
                tracked packets.</p>
            <div class="mt-3 p-3 bg-dark bg-opacity-50 rounded text-start font-monospace text-warning">
                /ip firewall filter<br>
                add action=fasttrack-connection chain=forward connection-state=established,related comment="defconf:
                fasttrack"<br>
                add action=accept chain=forward connection-state=established,related,untracked comment="defconf: accept
                established,related,untracked"
            </div>
        </div>
    </div>
@endsection
