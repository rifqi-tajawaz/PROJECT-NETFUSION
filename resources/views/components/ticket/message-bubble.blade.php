@props([
    'message' => '',
    'sender' => 'user',
    'name' => '',
    'time' => '',
    'attachments' => [],
])

<div class="d-flex gap-3 mb-4 {{ $sender === 'user' ? 'flex-row-reverse' : '' }}">
    <div class="avatar {{ $sender === 'user' ? 'bg-light text-primary border' : 'bg-primary text-white' }}">
        @if($sender === 'user')
            <span class="fw-bold fs-5">{{ substr($name, 0, 1) }}</span>
        @else
            <span class="material-icons-outlined fs-4">support_agent</span>
        @endif
    </div>
    <div class="flex-grow-1 message-bubble-content">
        <div
            class="message-bubble {{ $sender === 'user' ? 'user-message' : 'support-message' }} rounded-3 p-4 shadow-sm">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h6 class="fw-bold mb-0 {{ $sender === 'user' ? 'text-dark' : 'text-white' }}">
                    {{ $name }}
                </h6>
                <small class="{{ $sender === 'user' ? 'text-secondary' : 'text-white-50' }}">{{ $time }}</small>
            </div>
            <p class="mb-0" style="white-space: pre-line;">{{ $message }}</p>

            @if(!empty($attachments))
                <div class="mt-3 pt-3 border-top {{ $sender === 'user' ? 'border-secondary border-opacity-10' : 'border-white border-opacity-25' }}">
                    <h6 class="fw-bold small mb-2">Attachments</h6>
                    <div class="row g-2">
                        @foreach($attachments as $file)
                            <div class="col-auto">
                                <x-ticket-attachment 
                                    :file="$file" 
                                    :bgColor="$sender === 'user' ? 'bg-white' : 'bg-white bg-opacity-10 text-white border-0'"
                                    :textColor="$sender === 'user' ? 'text-dark' : 'text-white'" 
                                />
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
