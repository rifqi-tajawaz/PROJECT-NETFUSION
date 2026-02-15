@extends('layouts.app')

@push('css')
    @vite(['resources/sass/pages/admin-support-center.scss'])
@endpush

@section('title')
    {{ __('support.support_ticket') }}
@endsection

@section('content')
    <div class="container-fluid py-4">

        {{-- Hero Section --}}
        <div class="row mb-4">
            <div class="col-12">
                <x-support.hero :title="__('support.support_ticket')" :subtitle="__('support.support_desc')"
                    :badge="__('support.customer_support')" badgeIcon="support_agent" actionLink="{{ route('support') }}"
                    :actionText="__('support.browse_docs')" actionIcon="arrow_back" />
            </div>
        </div>

        <div class="row g-4" {{-- Main Ticket Form --}} <div class="col-lg-8 animate-fade-up"
            style="animation-delay: 0.1s;">
            <div class="card rounded-3 border shadow-sm h-100">
                <div class="card-header bg-white border-bottom p-4">
                    <h5 class="fw-bold mb-0 d-flex align-items-center">
                        <span class="material-icons-outlined text-primary me-2">edit_note</span>
                        {{ __('support.new_ticket_title') }}
                    </h5>
                </div>
                <div class="card-body p-4">
                    <x-ticket.form :action="route('ticket.store')">
                        <div class="row g-3 mb-4">
                            <x-ticket.input name="name" type="text" :label="__('support.your_name')" icon="person"
                                :value="old('name', optional(auth()->user())->name)"
                                :placeholder="__('support.enter_name')" />
                            <x-ticket.input name="email" type="email" :label="__('support.email_address')" icon="email"
                                :value="old('email', optional(auth()->user())->email)"
                                :placeholder="__('support.enter_email')" />
                        </div>

                        <hr class="text-muted opacity-25 mb-4">

                        <x-ticket.input name="subject" type="text" :label="__('support.subject') . ' *'"
                            :value="old('subject')" :placeholder="__('support.subject_placeholder')" :required="true" />

                        <div class="row mb-3">
                            <x-ticket.select name="department" :label="__('support.department')" icon="business" :options="[
            'Technical Support' => __('support.dept_technical'),
            'Billing & Account' => __('support.dept_billing'),
            'Sales Inquiry' => __('support.dept_sales')
        ]"
                                :selected="old('department')" />
                            <x-ticket.select name="priority" :label="__('support.priority')" icon="speed" :options="[
            'Low' => __('support.priority_low'),
            'Medium' => __('support.priority_medium'),
            'High' => __('support.priority_high')
        ]" :selected="old('priority') ?? 'Medium'" />
                        </div>

                        <x-ticket.input name="message" type="textarea" :label="__('support.message_details') . ' *'"
                            :placeholder="__('support.message_placeholder')" :value="old('message')" :required="true" />

                        <x-ticket.file-upload-zone name="attachments" :label="__('support.attachments')"
                            :hint="__('support.upload_hint')" types=".svg,.png,.jpg,.jpeg,.pdf" />

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ url()->previous() }}"
                                class="btn btn-light border px-4">{{ __('support.cancel') }}</a>
                            <button type="submit"
                                class="btn btn-brand rounded-pill px-5 fw-bold shadow-sm transition-hover glow-effect">
                                <span class="material-icons-outlined me-2">send</span> {{ __('support.submit_ticket') }}
                            </button>
                        </div>
                    </x-ticket.form>
                </div>
            </div>
        </div>

        {{-- Sidebar Info --}}
        <div class="col-lg-4 animate-fade-up" style="animation-delay: 0.2s;">

            {{-- Tips Card --}}
            <div class="card rounded-3 border shadow-sm mb-4">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-3 d-flex align-items-center">
                        <i class="bx bx-bulb text-warning fs-4 me-2"></i> {{ __('support.before_submit') }}
                    </h6>
                    <ul class="list-unstyled mb-0 d-flex flex-column gap-3">
                        <li class="d-flex align-items-start small text-secondary">
                            <i class="bx bx-check-circle text-success me-2 mt-1"></i>
                            <div>{!! __('support.check_faq', ['url' => route('faq')]) !!}</div>
                        </li>
                        <li class="d-flex align-items-start small text-secondary">
                            <i class="bx bx-check-circle text-success me-2 mt-1"></i>
                            <div>{{ __('support.prepare_screenshots') }}</div>
                        </li>
                        <li class="d-flex align-items-start small text-secondary">
                            <i class="bx bx-check-circle text-success me-2 mt-1"></i>
                            <div>{{ __('support.provide_version') }}</div>
                        </li>
                    </ul>
                </div>
            </div>

            {{-- Support Info Card --}}
            <div class="card rounded-3 border-0 shadow-sm bg-primary text-white overflow-hidden position-relative">
                {{-- Background Pattern --}}
                <div class="position-absolute top-0 end-0 p-4 opacity-10">
                    <i class="bx bx-headphone" style="font-size: 80px;"></i>
                </div>

                <div class="card-body p-4 position-relative" style="z-index: 1;">
                    <h5 class="fw-bold mb-3 text-white d-flex align-items-center">
                        <span class="material-icons-outlined me-2">access_time</span>
                        {{ __('support.operating_hours') }}
                    </h5>
                    <p class="small text-white opacity-75 mb-4">
                        {{ __('support.hours_desc') }}
                    </p>

                    <div class="d-flex flex-column gap-2 mb-4">
                        <div class="d-flex justify-content-between border-bottom border-white border-opacity-25 pb-2">
                            <span class="small fw-semibold">{{ __('support.mon_fri') }}</span>
                            <span class="small">08:00 - 17:00</span>
                        </div>
                        <div class="d-flex justify-content-between border-bottom border-white border-opacity-25 pb-2">
                            <span class="small fw-semibold">{{ __('support.saturday') }}</span>
                            <span class="small">09:00 - 14:00</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="small fw-semibold">{{ __('support.sunday') }}</span>
                            <span class="small badge bg-warning text-dark">{{ __('support.closed') }}</span>
                        </div>
                    </div>

                    <div class="p-3 bg-white bg-opacity-15 rounded-3 d-flex align-items-center gap-3">
                        <div class="p-2 bg-white bg-opacity-20 rounded-circle">
                            <i class="bx bx-time-five fs-4 text-white"></i>
                        </div>
                        <div>
                            <div class="small fw-bold text-white">{{ __('support.avg_response') }}</div>
                            <div class="small text-white opacity-75">{{ __('support.response_time') }}</div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    </div>
@endsection

@push('scripts')
    <!--plugins-->
    <script src="{{ URL::asset('build/plugins/metismenu/metisMenu.min.js') }}"></script>
    <script src="{{ URL::asset('build/plugins/simplebar/js/simplebar.min.js') }}"></script>
    @vite(['resources/js/pages/support/ticket.js'])
@endpush