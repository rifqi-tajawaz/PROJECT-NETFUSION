@extends('layouts.app')

@push('css')
    @vite(['resources/sass/pages/admin-support-center.scss'])
@endpush

@section('title')
    {{ __('support.title') }}
@endsection

@section('content')
    <div class="container-fluid py-4">

        {{-- Hero Section --}}
        <div class="row mb-5">
            <div class="col-12">
                <div class="support-hero-card overflow-hidden position-relative">
                    <div class="card-body p-5 position-relative z-index-1">
                        <div class="row align-items-center">
                            <div class="col-lg-7">
                                <span
                                    class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-10 rounded-pill px-3 py-2 mb-3">
                                    <span class="material-icons-outlined me-1"
                                        style="font-size: 16px; vertical-align: text-bottom;">stars</span>
                                    {{ __('support.center_intro_badge') }}
                                </span>
                                <h1 class="display-5 fw-bold mb-3 text-dark">{{ __('support.center_title') }}</h1>
                                <p class="lead text-secondary opacity-75 mb-4" style="max-width: 600px;">
                                    {{ __('support.center_subtitle') }}
                                </p>
                                <div class="d-flex flex-wrap gap-3">
                                    <a href="{{ route('support.ticket') }}"
                                        class="btn btn-primary rounded-pill px-4 py-2 fw-bold shadow-sm d-flex align-items-center gap-2 transition-hover">
                                        <span class="material-icons-outlined">add</span>
                                        {{ __('support.create_ticket_btn') }}
                                    </a>
                                    <a href="{{ route('documentation') }}"
                                        class="btn btn-light border rounded-pill px-4 py-2 fw-bold d-flex align-items-center gap-2 transition-hover bg-white">
                                        <span class="material-icons-outlined text-secondary">menu_book</span>
                                        {{ __('support.browse_docs') }}
                                    </a>
                                </div>
                            </div>
                            <div class="col-lg-5 d-none d-lg-block position-relative">
                                {{-- Creative Abstract Illustration using Icons --}}
                                <div class="position-absolute top-50 start-50 translate-middle" style="z-index: -1;">
                                    <div class="bg-primary opacity-10 rounded-circle blur-xl"
                                        style="width: 300px; height: 300px;"></div>
                                </div>
                                <div class="d-flex justify-content-center gap-4">
                                    <div class="d-flex flex-column gap-4 mt-5">
                                        <div class="card p-3 shadow-sm border-0 rounded-4 animate-float"
                                            style="animation-duration: 3s;">
                                            <span class="material-icons-outlined text-primary fs-1">support_agent</span>
                                        </div>
                                        <div class="card p-3 shadow-sm border-0 rounded-4 animate-float"
                                            style="animation-duration: 4s; animation-delay: 0.5s;">
                                            <span class="material-icons-outlined text-success fs-1">check_circle</span>
                                        </div>
                                    </div>
                                    <div class="d-flex flex-column gap-4 justify-content-center">
                                        <div class="card p-3 shadow-sm border-0 rounded-4 animate-float"
                                            style="animation-duration: 3.5s; animation-delay: 1s;">
                                            <span class="material-icons-outlined text-warning fs-1">lightbulb</span>
                                        </div>
                                        <div class="card p-3 shadow-sm border-0 rounded-4 animate-float"
                                            style="animation-duration: 4.5s; animation-delay: 1.5s;">
                                            <span class="material-icons-outlined text-info fs-1">forum</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Background decoration --}}
                    <div class="position-absolute top-0 end-0 h-100 w-50 bg-gradient-primary opacity-5 d-none d-lg-block"
                        style="clip-path: polygon(20% 0, 100% 0, 100% 100%, 0% 100%);"></div>
                </div>
            </div>
        </div>

        {{-- Main Features Grid --}}
        <div class="row g-4 mb-5">
            {{-- Knowledge Base --}}
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm hover-translate-y transition-all rounded-4 overflow-hidden group">
                    <div class="card-body p-4 position-relative">
                        <div class="mb-4 d-flex justify-content-between align-items-start">
                            <div
                                class="p-3 bg-primary bg-opacity-10 rounded-3 text-primary group-hover-bg-primary group-hover-text-white transition-all">
                                <span class="material-icons-outlined fs-3">menu_book</span>
                            </div>
                            <span
                                class="material-icons-outlined text-gray-300 fs-1 opacity-25 position-absolute top-0 end-0 mt-3 me-3">article</span>
                        </div>
                        <h4 class="fw-bold mb-2">{{ __('support.knowledge_base') }}</h4>
                        <p class="text-secondary mb-4 opacity-75">
                            {{ __('support.knowledge_base_desc') }}
                        </p>
                        <a href="{{ route('documentation') }}"
                            class="stretched-link text-decoration-none fw-bold d-flex align-items-center gap-2 text-primary">
                            {{ __('support.browse_docs') }} <span class="material-icons-outlined fs-6">arrow_forward</span>
                        </a>
                    </div>
                </div>
            </div>

            {{-- FAQ --}}
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm hover-translate-y transition-all rounded-4 overflow-hidden group">
                    <div class="card-body p-4 position-relative">
                        <div class="mb-4 d-flex justify-content-between align-items-start">
                            <div
                                class="p-3 bg-success bg-opacity-10 rounded-3 text-success group-hover-bg-success group-hover-text-white transition-all">
                                <span class="material-icons-outlined fs-3">quiz</span>
                            </div>
                            <span
                                class="material-icons-outlined text-gray-300 fs-1 opacity-25 position-absolute top-0 end-0 mt-3 me-3">help</span>
                        </div>
                        <h4 class="fw-bold mb-2">{{ __('support.faq') }}</h4>
                        <p class="text-secondary mb-4 opacity-75">
                            {{ __('support.faq_desc') }}
                        </p>
                        <a href="{{ route('faq.index') }}"
                            class="stretched-link text-decoration-none fw-bold d-flex align-items-center gap-2 text-success">
                            {{ __('support.view_faqs') }} <span class="material-icons-outlined fs-6">arrow_forward</span>
                        </a>
                    </div>
                </div>
            </div>

            {{-- Ticket System --}}
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm hover-translate-y transition-all rounded-4 overflow-hidden group">
                    <div class="card-body p-4 position-relative">
                        <div class="mb-4 d-flex justify-content-between align-items-start">
                            <div
                                class="p-3 bg-warning bg-opacity-10 rounded-3 text-warning group-hover-bg-warning group-hover-text-white transition-all">
                                <span class="material-icons-outlined fs-3">confirmation_number</span>
                            </div>
                            <span
                                class="material-icons-outlined text-gray-300 fs-1 opacity-25 position-absolute top-0 end-0 mt-3 me-3">support_agent</span>
                        </div>
                        <h4 class="fw-bold mb-2">{{ __('support.customer_support') }}</h4>
                        <p class="text-secondary mb-4 opacity-75">
                            {{ __('support.support_desc') }}
                        </p>
                        <a href="{{ route('support.ticket') }}"
                            class="stretched-link text-decoration-none fw-bold d-flex align-items-center gap-2 text-warning">
                            {{ __('support.submit_ticket') }} <span
                                class="material-icons-outlined fs-6">arrow_forward</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Direct Channels Section --}}
        <div class="row mb-5">
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                    <div class="row g-0">
                        <div class="col-lg-4 bg-light p-5 d-flex flex-column justify-content-center">
                            <h3 class="fw-bold mb-3">{{ __('support.direct_channels') }}</h3>
                            <p class="text-secondary mb-0">
                                Need faster response? Reach out to us directly through our official channels.
                            </p>
                        </div>
                        <div class="col-lg-8 p-5">
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <a href="#"
                                        class="d-flex align-items-center gap-3 text-decoration-none group p-3 rounded-3 hover-bg-light transition-all">
                                        <div class="p-3 bg-white shadow-sm rounded-circle text-primary">
                                            <span class="material-icons-outlined">groups</span>
                                        </div>
                                        <div>
                                            <h6 class="fw-bold text-dark mb-1">{{ __('support.channel_community') }}</h6>
                                            <span class="text-secondary small">Join Discussions</span>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-md-6">
                                    <a href="mailto:netfusion@tajawaz.com"
                                        class="d-flex align-items-center gap-3 text-decoration-none group p-3 rounded-3 hover-bg-light transition-all">
                                        <div class="p-3 bg-white shadow-sm rounded-circle text-info">
                                            <span class="material-icons-outlined">email</span>
                                        </div>
                                        <div>
                                            <h6 class="fw-bold text-dark mb-1">{{ __('support.channel_email') }}</h6>
                                            <span class="text-secondary small">netfusion@tajawaz.com</span>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-md-6">
                                    <a href="#"
                                        class="d-flex align-items-center gap-3 text-decoration-none group p-3 rounded-3 hover-bg-light transition-all">
                                        <div class="p-3 bg-white shadow-sm rounded-circle text-success">
                                            <span class="material-icons-outlined">whatsapp</span>
                                        </div>
                                        <div>
                                            <h6 class="fw-bold text-dark mb-1">WhatsApp Support</h6>
                                            <span class="text-secondary small">Chat with us</span>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-md-6">
                                    <a href="#"
                                        class="d-flex align-items-center gap-3 text-decoration-none group p-3 rounded-3 hover-bg-light transition-all">
                                        <div class="p-3 bg-white shadow-sm rounded-circle text-danger">
                                            <span class="material-icons-outlined">phone</span>
                                        </div>
                                        <div>
                                            <h6 class="fw-bold text-dark mb-1">Emergency Call</h6>
                                            <span class="text-secondary small">24/7 Hotline</span>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Quick Tips Section --}}
        <div class="row">
            <div class="col-12">
                <div class="d-flex align-items-center mb-4">
                    <span class="material-icons-outlined text-warning me-2">lightbulb</span>
                    <h5 class="fw-bold mb-0">Quick Support Tips</h5>
                </div>
            </div>
            <div class="col-md-4">
                <div class="d-flex gap-3">
                    <div class="flex-shrink-0">
                        <span class="material-icons-outlined text-success fs-2">check_circle</span>
                    </div>
                    <div>
                        <h6 class="fw-bold">Check Documentation First</h6>
                        <p class="text-secondary small">Most issues have detailed solutions in our comprehensive knowledge
                            base.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="d-flex gap-3">
                    <div class="flex-shrink-0">
                        <span class="material-icons-outlined text-primary fs-2">edit_note</span>
                    </div>
                    <div>
                        <h6 class="fw-bold">Use Specific Subject Lines</h6>
                        <p class="text-secondary small">Help us assign your ticket to the right expert by using clear
                            titles.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="d-flex gap-3">
                    <div class="flex-shrink-0">
                        <span class="material-icons-outlined text-info fs-2">screenshot</span>
                    </div>
                    <div>
                        <h6 class="fw-bold">Provide Details</h6>
                        <p class="text-secondary small">Include screenshots and error messages to speed up resolution.</p>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection

@push('js')
    <script src="{{ URL::asset('build/plugins/perfect-scrollbar/js/perfect-scrollbar.js') }}"></script>
    <script src="{{ URL::asset('build/plugins/metismenu/metisMenu.min.js') }}"></script>
    <script src="{{ URL::asset('build/plugins/simplebar/js/simplebar.min.js') }}"></script>
    <script src="{{ URL::asset('build/js/main.js') }}"></script>
@endpush