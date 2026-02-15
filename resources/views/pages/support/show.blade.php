@extends('layouts.app')

@push('css')
    @vite(['resources/sass/pages/admin-support-center.scss'])
@endpush

@section('title', 'Ticket Details')

@section('content')
    <div class="container-fluid py-4">
        
        {{-- Breadcrumb Navigation --}}
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('support') }}" class="text-decoration-none">
                        <span class="material-icons-outlined" style="font-size: 18px; vertical-align: text-bottom;">support</span>
                        Support
                    </a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('ticket.index') }}" class="text-decoration-none">My Tickets</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">Ticket #{{ $ticket->id }}</li>
            </ol>
        </nav>

        <div class="row justify-content-center">
            <div class="col-12 col-lg-10 col-xl-9">
                
                {{-- Ticket Card --}}
                <div class="card rounded-3 border shadow-sm">
                    
                    {{-- Card Header --}}
                    <div class="card-header bg-white border-bottom p-4">
                        <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
                            <div class="d-flex align-items-start gap-3 flex-grow-1">
                                <a href="{{ route('ticket.index') }}"
                                    class="btn btn-light btn-sm rounded-circle border d-flex align-items-center justify-content-center"
                                    style="width: 40px; height: 40px;"
                                    data-bs-toggle="tooltip" title="Back to My Tickets">
                                    <span class="material-icons-outlined text-secondary">arrow_back</span>
                                </a>
                                <div>
                                    <h5 class="fw-bold mb-1 d-flex align-items-center">
                                        <span class="material-icons-outlined text-primary me-2">confirmation_number</span>
                                        Ticket #{{ $ticket->id }}
                                    </h5>
                                    <p class="text-secondary small mb-0">
                                        Created {{ $ticket->created_at->format('M d, Y \a\t H:i') }}
                                    </p>
                                </div>
                            </div>
                            @php
                                $statusColors = [
                                    'open' => 'success',
                                    'closed' => 'secondary',
                                    'pending' => 'warning',
                                    'in_progress' => 'info',
                                ];
                                $color = $statusColors[$ticket->status] ?? 'secondary';
                            @endphp
                            <span class="badge bg-{{ $color }} bg-opacity-10 text-{{ $color }} rounded-pill px-3 py-2">
                                <span class="material-icons-outlined me-1" style="font-size: 16px; vertical-align: text-bottom;">
                                    {{ $ticket->status === 'closed' ? 'check_circle' : 'pending' }}
                                </span>
                                {{ ucfirst(str_replace('_', ' ', $ticket->status)) }}
                            </span>
                        </div>
                    </div>
                    
                    {{-- Card Body --}}
                    <div class="card-body p-4">
                        
                        {{-- Ticket Subject & Meta --}}
                        <div class="mb-5 pb-4 border-bottom">
                            <h4 class="fw-bold mb-3">{{ $ticket->subject }}</h4>
                            <div class="d-flex flex-wrap gap-3 text-secondary small">
                                <div class="d-flex align-items-center">
                                    <span class="material-icons-outlined me-1" style="font-size: 18px;">business</span>
                                    <span>{{ $ticket->department }}</span>
                                </div>
                                <div class="d-flex align-items-center">
                                    <span class="material-icons-outlined me-1" style="font-size: 18px;">speed</span>
                                    <span>Priority: <strong class="text-dark">{{ $ticket->priority }}</strong></span>
                                </div>
                                <div class="d-flex align-items-center">
                                    <span class="material-icons-outlined me-1" style="font-size: 18px;">schedule</span>
                                    <span>Last updated {{ $ticket->updated_at->diffForHumans() }}</span>
                                </div>
                            </div>
                        </div>

                        {{-- Original Message --}}
                        <h6 class="fw-bold text-dark text-uppercase small mb-4 d-flex align-items-center">
                            <span class="material-icons-outlined me-2 text-primary" style="font-size: 20px;">chat</span>
                            Conversation
                        </h6>

                        <x-message-bubble 
                            :message="$ticket->message"
                            sender="user"
                            :name="auth()->user()->name"
                            :time="$ticket->created_at->format('M d, Y H:i')"
                            :attachments="$ticket->attachments ?? []" 
                        />

                        {{-- Conversation History --}}
                        @if($ticket->replies->count() > 0)
                            @foreach($ticket->replies as $reply)
                                <x-message-bubble 
                                    :message="$reply->message"
                                    :sender="$reply->user_id === auth()->id() ? 'user' : 'support'"
                                    :name="$reply->user_id === auth()->id() ? 'You' : 'Support Team'"
                                    :time="$reply->created_at->format('M d, Y H:i')"
                                    :attachments="$reply->attachments ?? []" 
                                />
                            @endforeach
                        @endif

                        {{-- Reply Form --}}
                        <div class="mt-5 pt-4 border-top">
                            <x-ticket-reply-form 
                                :action="route('ticket.reply', $ticket->id)"
                                :closed="$ticket->status === 'closed'" 
                            />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Initialize tooltips
        document.addEventListener('DOMContentLoaded', function() {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });
    </script>
@endpush
