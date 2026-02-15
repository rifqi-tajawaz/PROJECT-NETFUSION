@props([
    'sections' => [],
])

@if(!empty($sections))
    <div class="col-md-4 col-lg-3 d-none d-md-block">
        <div class="sticky-top top-6" style="z-index: 10;">
            <div class="card border rounded-3 shadow-sm">
                <div class="card-body p-2">
                    <div id="faq-nav" class="nav flex-column faq-nav w-100">
                        @foreach ($sections as $section)
                            <a class="nav-link rounded-3 mb-1 d-flex justify-content-between align-items-center px-3 py-2"
                                href="#{{ $section->id }}">
                                <div class="d-flex align-items-center">
                                    <span class="fw-medium">{{ $section->title }}</span>
                                </div>
                                <span class="material-icons-outlined text-muted opacity-75">{{ $section->icon }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
