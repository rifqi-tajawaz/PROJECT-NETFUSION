@props([
    'title' => '',
    'description' => '',
    'icon' => 'contact_phone',
    'channels' => [],
])

<div class="card rounded-3 border shadow bg-white overflow-hidden contact-card mt-4">
    <div class="card-body p-4">
        <div class="row align-items-center">
            <div class="col-lg-6 mb-3 mb-lg-0">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0 bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center"
                        style="width: 50px; height: 50px;">
                        <span class="material-icons-outlined text-primary fs-3">{{ $icon }}</span>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h5 class="fw-bold mb-1 text-dark">{{ $title }}</h5>
                        <p class="mb-0 text-secondary small">{{ $description }}</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="d-flex flex-column flex-md-row gap-3 justify-content-lg-end">
                    @foreach($channels as $channel)
                        <a href="{{ $channel['url'] }}" target="_blank"
                            class="d-flex align-items-center justify-content-center justify-content-md-start px-3 py-2 rounded-3 bg-light text-decoration-none transition-hover border hover:border-{{ $channel['color'] }}">
                            <span class="material-icons-outlined fs-4 text-{{ $channel['color'] }} me-2">{{ $channel['icon'] }}</span>
                            <div>
                                <span class="d-block small text-secondary fw-bold text-start">{{ $channel['label'] }}</span>
                                <span class="d-block small text-dark">{{ $channel['value'] }}</span>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
