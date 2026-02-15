@extends('layouts.app')

@push('css')
    @vite(['resources/sass/pages/admin-support-center.scss'])
@endpush

@section('title', __('faq.title'))

@section('content')
    <div class="container-fluid py-4">

        {{-- Hero Section --}}
        <div class="row mb-4">
            <div class="col-12">
                <div class="support-hero-card">
                    <div class="card-body p-5">
                        <div class="row align-items-center">
                            <div class="col-lg-8">
                                <div class="mb-1">
                                    <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-2 mb-3">
                                        <span class="material-icons-outlined me-1"
                                            style="font-size: 16px; vertical-align: text-bottom;">quiz</span>
                                        {{ __('faq.title') }}
                                    </span>
                                </div>
                                <h2 class="fw-bold mb-3">{{ __('faq.title') }}</h2>
                                <p class="lead text-secondary mb-4">{{ __('faq.intro') }}</p>

                                {{-- Search Box --}}
                                <div class="faq-search-wrapper">
                                    <form action="{{ route('faq.index') }}" method="GET">
                                        <div class="position-relative">
                                            <input type="text" name="search" class="form-control form-control-lg"
                                                placeholder="{{ __('faq.search_placeholder') }}"
                                                value="{{ request('search') }}">
                                            <span class="search-icon">
                                                <span class="material-icons-outlined">search</span>
                                            </span>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="col-lg-4 d-none d-lg-block text-end">
                                <span class="material-icons-outlined floating-icon icon-lg animate-float text-success"
                                    style="font-size: 120px; opacity: 0.15;">
                                    quiz
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Stats Row --}}
        <div class="row g-4 mb-4">
            <div class="col-6 col-md-3">
                <div class="card rounded-3 border shadow-sm text-center p-4">
                    <div class="mb-2">
                        <span class="material-icons-outlined text-success" style="font-size: 48px;">help_outline</span>
                    </div>
                    <h3 class="fw-bold mb-1">{{ count($sections) ?? 0 }}</h3>
                    <p class="text-secondary small mb-0">Total FAQs</p>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card rounded-3 border shadow-sm text-center p-4">
                    <div class="mb-2">
                        <span class="material-icons-outlined text-primary" style="font-size: 48px;">category</span>
                    </div>
                    <h3 class="fw-bold mb-1">4</h3>
                    <p class="text-secondary small mb-0">Categories</p>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card rounded-3 border shadow-sm text-center p-4">
                    <div class="mb-2">
                        <span class="material-icons-outlined text-warning" style="font-size: 48px;">trending_up</span>
                    </div>
                    <h3 class="fw-bold mb-1">128</h3>
                    <p class="text-secondary small mb-0">Total Views</p>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card rounded-3 border shadow-sm text-center p-4">
                    <div class="mb-2">
                        <span class="material-icons-outlined text-info" style="font-size: 48px;">update</span>
                    </div>
                    <h3 class="fw-bold mb-1">Daily</h3>
                    <p class="text-secondary small mb-0">Updated</p>
                </div>
            </div>
        </div>

        {{-- Categories Quick Nav --}}
        @if(!empty($sections))
            <div class="row mb-5">
                <div class="col-12">
                    <h5 class="fw-bold mb-3">Browse by Category</h5>
                    <div class="row g-3">
                        @php
                            $categories = [
                                ['title' => __('support.faq_billing'), 'icon' => 'payments', 'count' => 24, 'color' => 'primary'],
                                ['title' => __('support.faq_errors'), 'icon' => 'bug_report', 'count' => 18, 'color' => 'danger'],
                                ['title' => __('support.faq_connectivity'), 'icon' => 'wifi', 'count' => 15, 'color' => 'info'],
                                ['title' => 'Account & Login', 'icon' => 'person', 'count' => 12, 'color' => 'success'],
                            ];
                        @endphp
                        @foreach($categories as $cat)
                            <div class="col-6 col-md-3">
                                <div class="card rounded-3 border shadow-sm hover-border-{{ $cat['color'] }} contact-card h-100">
                                    <div class="card-body p-4">
                                        <div class="d-flex align-items-center mb-3">
                                            <div
                                                class="icon-box bg-{{ $cat['color'] }} bg-opacity-10 text-{{ $cat['color'] }} me-3">
                                                <span class="material-icons-outlined">{{ $cat['icon'] }}</span>
                                            </div>
                                            <span
                                                class="badge bg-{{ $cat['color'] }} bg-opacity-10 text-{{ $cat['color'] }} rounded-pill">
                                                {{ $cat['count'] }}
                                            </span>
                                        </div>
                                        <h6 class="fw-bold mb-0">{{ $cat['title'] }}</h6>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        {{-- FAQ Sections --}}
        @if(!empty($sections))
            @foreach($sections as $index => $section)
                <div id="{{ $section->id }}" class="mb-5">
                    <div class="row">
                        <div class="col-12">
                            <div class="d-flex align-items-center mb-4">
                                <div class="icon-box bg-success bg-opacity-10 text-success me-3">
                                    <span class="material-icons-outlined">{{ $section->icon ?? 'help_outline' }}</span>
                                </div>
                                <div>
                                    <h4 class="fw-bold mb-1">{{ $section->title }}</h4>
                                    <p class="text-secondary small mb-0">{{ $section->items->count() }} questions in this section
                                    </p>
                                </div>
                            </div>

                            <div class="accordion accordion-custom" id="faq-{{ $section->id }}">
                                @foreach($section->items as $faqIndex => $faq)
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="heading-{{ $section->id }}-{{ $faqIndex }}">
                                            <button class="accordion-button {{ $faqIndex > 0 ? 'collapsed' : '' }}" type="button"
                                                data-bs-toggle="collapse" data-bs-target="#collapse-{{ $section->id }}-{{ $faqIndex }}"
                                                aria-expanded="{{ $faqIndex === 0 ? 'true' : 'false' }}"
                                                aria-controls="collapse-{{ $section->id }}-{{ $faqIndex }}">
                                                {{ $faq->question }}
                                            </button>
                                        </h2>
                                        <div id="collapse-{{ $section->id }}-{{ $faqIndex }}"
                                            class="accordion-collapse collapse {{ $faqIndex === 0 ? 'show' : '' }}"
                                            aria-labelledby="heading-{{ $section->id }}-{{ $faqIndex }}"
                                            data-bs-parent="#faq-{{ $section->id }}">
                                            <div class="accordion-body">
                                                <div class="text-secondary">
                                                    {!! nl2br(e($faq->answer)) !!}
                                                </div>
                                                @if($faq->helpful_count ?? false)
                                                    <div class="mt-3 pt-3 border-top">
                                                        <div class="d-flex align-items-center gap-2">
                                                            <span class="small text-secondary">Was this helpful?</span>
                                                            <button class="btn btn-sm btn-light border">
                                                                <span class="material-icons-outlined"
                                                                    style="font-size: 16px;">thumb_up</span>
                                                            </button>
                                                            <button class="btn btn-sm btn-light border">
                                                                <span class="material-icons-outlined"
                                                                    style="font-size: 16px;">thumb_down</span>
                                                            </button>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            {{-- Empty State --}}
            <div class="row">
                <div class="col-12">
                    <div class="card rounded-3 border shadow-sm">
                        <div class="card-body p-5 text-center">
                            <div class="mb-4">
                                <span class="material-icons-outlined text-secondary"
                                    style="font-size: 96px; opacity: 0.3;">help_outline</span>
                            </div>
                            <h3 class="fw-bold mb-3">No FAQs Yet</h3>
                            <p class="text-secondary mb-4">We are currently curating a list of common questions.</p>
                            <a href="{{ route('support.ticket') }}" class="btn btn-success rounded-pill px-4">
                                <span class="material-icons-outlined me-2">support_agent</span>
                                Contact Support
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        {{-- Still Have Questions --}}
        <div class="row mt-5">
            <div class="col-12">
                <div class="card rounded-3 border-0 shadow-sm bg-primary text-white">
                    <div class="card-body p-5 text-center">
                        <h4 class="fw-bold text-white mb-3">Still Have Questions?</h4>
                        <p class="mb-4 opacity-75">Can't find what you're looking for? Our support team is here to help!</p>
                        <a href="{{ route('support.ticket') }}" class="btn btn-light btn-lg rounded-pill px-5">
                            <span class="material-icons-outlined me-2">support_agent</span>
                            Submit a Ticket
                        </a>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection

@push('scripts')
    @vite(['resources/js/pages/faq.js'])
@endpush