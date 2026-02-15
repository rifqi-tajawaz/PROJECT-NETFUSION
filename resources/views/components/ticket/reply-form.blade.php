@props([
    'action' => '',
    'ticketId' => null,
    'closed' => false,
])

@if(!$closed)
    <div class="mt-5 pt-4 border-top">
        <h6 class="fw-bold mb-3">Send a Reply</h6>
        <form action="{{ $action }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <textarea name="message" class="form-control" rows="4"
                    placeholder="Type your reply here..." required></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label small text-secondary">Attachments (Optional)</label>
                <input type="file" name="attachments[]" class="form-control" multiple>
            </div>
            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('ticket.index') }}" class="btn btn-light border">Back</a>
                <button type="submit" class="btn btn-brand rounded-pill px-4 fw-bold shadow-sm">
                    <span class="material-icons-outlined me-2 fs-6">send</span> Send Reply
                </button>
            </div>
        </form>
    </div>
@else
    <div class="alert alert-secondary mt-5 text-center rounded-3">
        <span class="material-icons-outlined me-2">lock</span> This ticket is closed. If you need further assistance,
        please open a new ticket.
    </div>
@endif
