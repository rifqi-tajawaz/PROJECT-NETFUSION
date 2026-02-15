@extends('layouts.app')

@push('css')
    @vite(['resources/sass/pages/admin-support-center.scss'])
@endpush

@section('title', 'Ticket Details #' . $ticket->id)

@section('content')
    <div class="container-fluid py-4">
    
    {{-- Hero Section --}}
    <div class="card admin-header-card shadow-sm mb-4">
        <div class="card-body p-4">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-1 opacity-75 small">
                            <li class="breadcrumb-item"><a href="{{ route('mikrotik-suite.dashboard') }}"
                                    class="text-secondary text-decoration-none">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.support.tickets.index') }}"
                                    class="text-secondary text-decoration-none">Tickets</a></li>
                            <li class="breadcrumb-item active text-secondary" aria-current="page">View Ticket</li>
                        </ol>
                    </nav>
                    <h4 class="mb-1 text-dark">Ticket #{{ $ticket->id }}</h4>
                    <p class="mb-0 text-secondary opacity-75">View conversation and manage ticket status.</p>
                </div>
                <div class="d-none d-md-block">
                    <span class="material-icons-outlined text-primary opacity-25" style="font-size: 3.5rem;">confirmation_number</span>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
            <!-- Ticket Conversation -->
            <div class="col-lg-8 animate-fade-up">
                <div class="admin-table-card shadow h-100">
                    <div class="card-header d-flex justify-content-between align-items-center sticky-top"
                        style="z-index: 10; top: 0px;">
                        <div class="d-flex align-items-center gap-3">
                            <a href="{{ route('admin.support.tickets.index') }}"
                                class="btn btn-icon border d-flex align-items-center justify-content-center"
                                data-bs-toggle="tooltip" title="{{ __('admin.back_to_list') }}">
                                <span class="material-icons-outlined text-secondary fs-5">arrow_back</span>
                            </a>
                            <div>
                                <h5 class="fw-bold mb-0 text-dark">
                                    {{ $ticket->subject }}
                                </h5>
                                <div class="small text-secondary mt-1">
                                    Created {{ $ticket->created_at->format('M d, Y H:i') }}
                                </div>
                            </div>
                        </div>
                        <div>
                            @if($ticket->status === 'open')
                                <span
                                    class="badge badge-soft-success rounded-pill px-3 py-2">{{ __('admin.status_open') }}</span>
                            @elseif($ticket->status === 'closed')
                                <span
                                    class="badge badge-soft-secondary rounded-pill px-3 py-2">{{ __('admin.status_closed') }}</span>
                            @elseif($ticket->status === 'pending')
                                <span
                                    class="badge badge-soft-warning rounded-pill px-3 py-2">{{ __('admin.status_pending') }}</span>
                            @elseif($ticket->status === 'in_progress')
                                <span
                                    class="badge badge-soft-info rounded-pill px-3 py-2">{{ __('admin.status_in_progress') }}</span>
                            @endif
                        </div>
                    </div>

                <div class="card-body p-3 bg-light bg-opacity-50" style="min-height: 500px; max-height: 800px; overflow-y: auto;">
                    
                    <!-- Original Message (User - Left) -->
                    <div class="d-flex flex-row gap-2 mb-2">
                        <!-- Avatar -->
                        <div class="avatar bg-white border text-primary rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 shadow-sm align-self-end mb-1"
                            style="width: 35px; height: 35px;">
                                <span class="fw-bold fs-7">{{ substr($ticket->name, 0, 1) }}</span>
                        </div>
                        
                        <!-- Bubble -->
                        <div class="d-flex flex-column align-items-start" style="max-width: 70%;">
                            <div class="small text-secondary fw-bold ms-1 mb-1" style="font-size: 0.75rem;">{{ $ticket->name }} <span class="fw-normal text-muted fst-italic ms-1">{{ __('admin.customer') }}</span></div>
                            <div class="bg-white border py-2 px-3 shadow-sm position-relative" style="border-radius: 1rem 1rem 1rem 0; width: fit-content;">
                                <div class="text-dark mb-1" style="white-space: pre-line; font-size: 0.9rem;">{{ $ticket->message }}</div>
                                
                                {{-- Attachments --}}
                                @if($ticket->attachments && count($ticket->attachments) > 0)
                                    <div class="mt-2 pt-2 border-top">
                                        <div class="d-flex flex-wrap gap-2">
                                            @foreach($ticket->attachments as $file)
                                                <a href="{{ asset('storage/' . $file) }}" target="_blank" class="text-decoration-none">
                                                    <div class="badge bg-light text-dark border d-flex align-items-center gap-1 p-1">
                                                        <i class="bx bx-file"></i> {{ basename($file) }}
                                                    </div>
                                                </a>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                                <div class="text-end mt-1" style="line-height: 1;">
                                     <small class="text-muted" style="font-size: 0.7rem;">{{ $ticket->created_at->format('H:i') }}</small>
                                </div>
                            </div>
                        </div>
                    </div>

                     <!-- Conversation Thread -->
                     @foreach($ticket->replies as $reply)
                        @php
                            $isAdmin = $reply->user && $reply->user->isAdmin();
                            $borderRadius = $isAdmin ? '1rem 1rem 0 1rem' : '1rem 1rem 1rem 0';
                        @endphp
                        
                        <div class="d-flex {{ $isAdmin ? 'flex-row-reverse' : 'flex-row' }} gap-2 mb-2">
                            <!-- Avatar -->
                            <div class="avatar {{ $isAdmin ? 'bg-primary text-white' : 'bg-white border text-primary' }} rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 shadow-sm align-self-end mb-1"
                                style="width: 35px; height: 35px;">
                                @if($isAdmin)
                                    <i class="bx bx-support fs-6"></i>
                                @else
                                    <span class="fw-bold fs-7">{{ substr($reply->user ? $reply->user->name : 'U', 0, 1) }}</span>
                                @endif
                            </div>

                            <!-- Bubble -->
                            <div class="d-flex flex-column {{ $isAdmin ? 'align-items-end' : 'align-items-start' }}" style="max-width: 70%;">
                                <div class="small text-secondary fw-bold {{ $isAdmin ? 'me-1' : 'ms-1' }} mb-1" style="font-size: 0.75rem;">
                                    {{ $reply->user ? $reply->user->name : 'Unknown' }}
                                    @if($isAdmin) <span class="badge bg-primary rounded-pill ms-1" style="font-size: 0.6rem;">{{ __('admin.admin') }}</span> @endif
                                </div>
                                
                                <div class="{{ $isAdmin ? 'bg-primary bg-opacity-10 text-dark border border-primary border-opacity-10' : 'bg-white border shadow-sm' }} py-2 px-3 position-relative" 
                                     @style(['border-radius' => $borderRadius, 'width' => 'fit-content'])>
                                    
                                    <div class="mb-1" style="white-space: pre-line; font-size: 0.9rem;">{{ $reply->message }}</div>

                                     @if($reply->attachments && count($reply->attachments) > 0)
                                        <div class="mt-2 pt-2 border-top {{ $isAdmin ? 'border-primary border-opacity-25' : 'border-light' }}">
                                             <div class="d-flex flex-wrap gap-2">
                                                @foreach($reply->attachments as $file)
                                                    <a href="{{ asset('storage/' . $file) }}" target="_blank" class="text-decoration-none">
                                                        <div class="badge {{ $isAdmin ? 'bg-white bg-opacity-50 text-dark' : 'bg-light text-dark border' }} d-flex align-items-center gap-1 p-1">
                                                            <i class="bx bx-file"></i> {{ basename($file) }}
                                                        </div>
                                                    </a>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif

                                    <div class="text-end mt-1" style="line-height: 1;">
                                        <small class="{{ $isAdmin ? 'text-secondary' : 'text-muted' }}" style="font-size: 0.7rem;">
                                            {{ $reply->created_at->format('H:i') }}
                                            @if($isAdmin) <i class="bx bx-check-double text-primary ms-1"></i> @endif
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                     @endforeach
                </div>
                
                <!-- Reply Box -->
                <div class="card-footer bg-white px-4 py-3 border-top">
                    <h6 class="fw-bold mb-3 d-flex align-items-center"><i class="bx bx-reply text-primary me-2"></i> {{ __('admin.post_reply') }}</h6>
                     <form action="{{ route('admin.support.tickets.reply', $ticket->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <textarea name="message" class="form-control bg-light" rows="4" placeholder="{{ __('admin.write_reply') }}" required></textarea>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center gap-2">
                                <label class="btn btn-light border btn-sm" for="attach-file" data-bs-toggle="tooltip" title="Attach Files">
                                    <i class="bx bx-paperclip me-1"></i> {{ __('admin.attach_files') }}
                                </label>
                                <input type="file" name="attachments[]" id="attach-file" class="d-none" multiple>
                                <span class="small text-secondary" id="file-count"></span>
                            </div>
                            <button type="submit" class="btn btn-primary px-4 fw-bold shadow-sm rounded-3">
                                <i class="bx bx-send me-2"></i> {{ __('admin.send_reply') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Sidebar Info -->
        <div class="col-lg-4 animate-fade-up" style="animation-delay: 0.1s;">
            <x-admin.ticket.info-card :ticket="$ticket" :assigned="true" />
            <x-admin.ticket.update-form :ticket="$ticket" />
            
            <div class="card rounded-4 border-0 shadow-sm border-danger border-opacity-25">
                <div class="card-body p-4">
                    <h6 class="fw-bold text-danger mb-3">{{ __('admin.danger_zone') }}</h6>
                    <p class="small text-secondary mb-3">{{ __('admin.delete_ticket_warning') }}</p>
                     <form action="{{ route('admin.support.tickets.destroy', $ticket->id) }}" method="POST" id="delete-ticket-form">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger w-100 border-dashed rounded-3">
                            <i class="bx bx-trash me-2"></i> {{ __('admin.delete_ticket_permanently') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection

@push('scripts')
    @vite(['resources/js/pages/admin/support-tickets.js'])
@endpush