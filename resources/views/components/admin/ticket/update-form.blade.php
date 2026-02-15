@props([
    'ticket' => null,
])

@php
    $statusMap = [
        'open' => 'admin.status_open',
        'in_progress' => 'admin.status_in_progress',
        'pending' => 'admin.status_pending',
        'closed' => 'admin.status_closed',
    ];
    $priorityMap = [
        'Low' => 'admin.prio_low',
        'Medium' => 'admin.prio_medium',
        'High' => 'admin.prio_high',
        'Critical' => 'admin.prio_critical',
    ];
@endphp

<div class="admin-table-card shadow mb-4">
    <div class="card-header bg-white border-bottom px-4 py-3">
        <h6 class="fw-bold mb-0 text-dark">{{ __('admin.ticket_info') }}</h6>
    </div>
    <div class="card-body p-4">
         <form action="{{ route('admin.support.tickets.update', $ticket->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                 <label class="form-label text-secondary small fw-bold text-uppercase">{{ __('admin.current_status') }}</label>
                 <select name="status" class="form-select bg-light border-0">
                    <option value="open" {{ $ticket->status == 'open' ? 'selected' : '' }}>{{ __($statusMap['open']) }}</option>
                    <option value="in_progress" {{ $ticket->status == 'in_progress' ? 'selected' : '' }}>{{ __($statusMap['in_progress']) }}</option>
                    <option value="pending" {{ $ticket->status == 'pending' ? 'selected' : '' }}>{{ __($statusMap['pending']) }}</option>
                    <option value="closed" {{ $ticket->status == 'closed' ? 'selected' : '' }}>{{ __($statusMap['closed']) }}</option>
                </select>
            </div>

             <div class="mb-3">
                <label class="form-label text-secondary small fw-bold text-uppercase">{{ __('admin.priority_level') }}</label>
                 <select name="priority" class="form-select bg-light border-0">
                    <option value="Low" {{ $ticket->priority == 'Low' ? 'selected' : '' }}>{{ __($priorityMap['Low']) }}</option>
                    <option value="Medium" {{ $ticket->priority == 'Medium' ? 'selected' : '' }}>{{ __($priorityMap['Medium']) }}</option>
                    <option value="High" {{ $ticket->priority == 'High' ? 'selected' : '' }}>{{ __($priorityMap['High']) }}</option>
                    <option value="Critical" {{ $ticket->priority == 'Critical' ? 'selected' : '' }}>{{ __($priorityMap['Critical']) }}</option>
                </select>
            </div>

             <div class="mb-4">
                <label class="form-label text-secondary small fw-bold text-uppercase">{{ __('admin.department') }}</label>
                <input type="text" class="form-control bg-light border-0" value="{{ $ticket->department }}" readonly>
            </div>

            <button type="submit" class="btn btn-dark w-100 fw-bold rounded-3">
                <span class="material-icons-outlined me-2">save</span> {{ __('admin.update_properties') }}
            </button>
        </form>
    </div>
</div>
