@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <h2 class="mb-4">Security Dashboard</h2>

                <!-- User Profile Section -->
                <div class="card mb-4">
                    <div class="card-header">{{ __('Profile') }}</div>
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="me-4 text-center">
                                @if($user->avatar)
                                    <img src="{{ asset('storage/' . $user->avatar) }}" class="rounded-circle mb-2" width="100"
                                        height="100" alt="Avatar">
                                @else
                                    <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center mb-2"
                                        style="width: 100px; height: 100px; color: white; font-size: 2rem;">
                                        {{ substr($user->name, 0, 1) }}
                                    </div>
                                @endif

                                <form action="{{ route('security.avatar.update') }}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <label class="btn btn-sm btn-outline-primary" for="avatar-upload">
                                        Change Photo <input type="file" id="avatar-upload" name="avatar" class="d-none"
                                            onchange="this.form.submit()">
                                    </label>
                                </form>
                            </div>
                            <div>
                                <h4>{{ $user->name }}</h4>
                                <p class="text-muted">{{ $user->email }}</p>
                                <span class="badge bg-success">Account Verified</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <!-- Recent Activity -->
                    <div class="col-md-6">
                        <div class="card h-100">
                            <div class="card-header">{{ __('Recent Activity') }}</div>
                            <ul class="list-group list-group-flush">
                                @forelse($logs as $log)
                                    <li class="list-group-item d-flex justify-content-between align-items-start">
                                        <div class="ms-2 me-auto">
                                            <div class="fw-bold">{{ $log->action }}</div>
                                            <small class="text-muted">{{ $log->description }}</small>
                                        </div>
                                        <span class="badge bg-light text-dark">{{ $log->created_at->diffForHumans() }}</span>
                                    </li>
                                @empty
                                    <li class="list-group-item text-center text-muted">No recent activity found.</li>
                                @endforelse
                            </ul>
                            <div class="card-footer text-center">
                                <a href="{{ route('activity-logs.index') }}" class="btn btn-link btn-sm">View all
                                    activity</a>
                            </div>
                        </div>
                    </div>

                    <!-- Your Devices -->
                    <div class="col-md-6">
                        <div class="card h-100">
                            <div class="card-header">{{ __('Your Devices') }}</div>
                            <div class="card-body">
                                @if(config('session.driver') !== 'database')
                                    <div class="alert alert-warning">
                                        Session driver must be set to 'database' to view active devices.
                                    </div>
                                @else
                                    <ul class="list-group list-group-flush">
                                        @forelse($sessions as $session)
                                            <li class="list-group-item">
                                                <div class="d-flex w-100 justify-content-between">
                                                    <h5 class="mb-1">
                                                        @if($session->is_current_device)
                                                            <span class="text-success fw-bold">Current Device</span>
                                                        @else
                                                            {{ $session->platform }} - {{ $session->browser }}
                                                        @endif
                                                    </h5>
                                                    @if(!$session->is_current_device)
                                                        <form action="{{ route('security.device.logout', $session->id) }}"
                                                            method="POST">
                                                            @csrf
                                                            <button type="submit" class="btn btn-danger btn-sm">Sign out</button>
                                                        </form>
                                                    @endif
                                                </div>
                                                <p class="mb-1">{{ $session->ip_address }}</p>
                                                <small class="text-muted">Last active: {{ $session->last_active }}</small>
                                            </li>
                                        @empty
                                            <li class="list-group-item">No active sessions found.</li>
                                        @endforelse
                                    </ul>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
