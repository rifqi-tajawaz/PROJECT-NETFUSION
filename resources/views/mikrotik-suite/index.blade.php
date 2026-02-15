@extends('layouts.app')

@section('title', 'Service Center & Dashboard')

@section('content')
    <!-- 3D Coverflow Hero Section (Transparent / Blended) -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="perspective-container position-relative py-5 bg-transparent overflow-hidden">
                <!-- Floating Elements (Stars/Coins simulation) -->
                <div class="position-absolute top-0 start-0 m-5 animate-float-slow opacity-50">
                    <span class="material-icons-outlined text-primary fs-1">stars</span>
                </div>
                <div class="position-absolute top-0 end-0 m-5 mt-4 me-5 animate-float opacity-50">
                    <span class="material-icons-outlined text-warning fs-3">monetization_on</span>
                </div>

                <!-- Content Header -->
                <div class="position-relative z-index-1 text-center mb-4 mt-4">
                    <span
                        class="d-inline-block py-1 px-3 rounded-pill bg-white border shadow-sm text-secondary fw-bold small mb-3 animate-fade-down"
                        style="letter-spacing: 1px; font-size: 0.7rem;">
                        <span class="text-brand me-1">&bullet;</span> {{ __('dashboard.hero.tag') }}
                    </span>
                    <h1 class="display-3 fw-bolder mb-3 tracking-tight text-gradient-dark animate-fade-down"
                        style="animation-delay: 0.1s;">
                        {{ __('dashboard.hero.title') }}
                    </h1>
                    <p class="text-secondary fs-6 mb-5 mx-auto animate-fade-down" <p
                        class="text-secondary fs-6 mb-5 mx-auto animate-fade-down"
                        style="max-width: 600px; line-height: 1.8; animation-delay: 0.2s;">
                        {{ __('dashboard.hero.subtitle') }}
                    </p>

                    <div class="d-flex justify-content-center gap-3 animate-fade-up" style="animation-delay: 0.3s;">
                        <a href="{{ route('mikrotik-suite.monitoring.traffic-monitor') }}"
                            class="btn btn-brand rounded-pill px-5 py-3 d-flex align-items-center gap-2 transition-hover shadow-brand glow-effect">
                            {{ __('dashboard.hero.btn_monitor') }} <span class="material-icons-outlined fs-6">speed</span>
                        </a>
                        <a href="#services"
                            class="btn btn-white rounded-pill px-5 py-3 fw-semibold d-flex align-items-center gap-2 transition-hover shadow-sm text-dark border-white-glass">
                            {{ __('dashboard.hero.btn_tools') }} <span class="material-icons-outlined fs-6">widgets</span>
                        </a>
                    </div>
                </div>

                <div class="coverflow-slider position-relative mt-4" id="coverflowSlider">
                    <div class="coverflow-track">
                        <!-- Card 1: Traffic Monitor (Brand Theme) -->
                        <div class="coverflow-card active" data-theme="blue">
                            <x-service-card :wrapCol="false" theme="primary" icon="analytics"
                                :title="__('dashboard.cards.traffic_monitor.title')"
                                :description="__('dashboard.cards.traffic_monitor.desc')" badge="v2.4" :features="[
            ['icon' => 'check_circle', 'label' => __('dashboard.cards.traffic_monitor.list.live_graph')],
            ['icon' => 'check_circle', 'label' => __('dashboard.cards.traffic_monitor.list.top_talkers')],
            ['icon' => 'check_circle', 'label' => __('dashboard.cards.traffic_monitor.list.export')]
        ]"
                                :btnLink="route('mikrotik-suite.monitoring.traffic-monitor')"
                                :btnText="__('dashboard.cards.traffic_monitor.btn')" btnClass="btn-primary text-white">
                                <x-slot name="footer">
                                    <div class="d-flex justify-content-between small">
                                        <span
                                            class="text-secondary">{{ __('dashboard.cards.traffic_monitor.status') }}</span>
                                        <span class="text-success fw-bold"><i class="bi bi-circle-fill text-success me-1"
                                                style="font-size: 6px; vertical-align: middle;"></i>
                                            {{ __('dashboard.cards.traffic_monitor.active') }}</span>
                                    </div>
                                </x-slot>
                            </x-service-card>
                        </div>

                        <!-- Card 2: Security (Green Theme) -->
                        <div class="coverflow-card" data-theme="green">
                            <x-service-card :wrapCol="false" theme="success" icon="shield"
                                :title="__('dashboard.cards.security.title')"
                                :description="__('dashboard.cards.security.desc')"
                                :badge="__('dashboard.cards.security.badge')" :features="[
            ['icon' => 'verified_user', 'label' => __('dashboard.cards.security.list.ddos')],
            ['icon' => 'verified_user', 'label' => __('dashboard.cards.security.list.port_knocking')],
            ['icon' => 'verified_user', 'label' => __('dashboard.cards.security.list.blacklist')]
        ]">
                                <x-slot name="btnSlot">
                                    <a href="https://wa.me/6281234567890?text=I%20want%20Security%20Hardening"
                                        target="_blank"
                                        class="btn btn-outline-success rounded-pill btn-sm w-100 fw-bold bg-white bg-opacity-50">
                                        {{ __('dashboard.cards.security.btn') }}
                                    </a>
                                </x-slot>
                                <x-slot name="footer">
                                    <div class="d-flex justify-content-between small">
                                        <span
                                            class="text-secondary">{{ __('dashboard.cards.security.label_protection') }}</span>
                                        <span
                                            class="text-dark fw-bold">{{ __('dashboard.cards.security.val_enterprise') }}</span>
                                    </div>
                                </x-slot>
                            </x-service-card>
                        </div>

                        <!-- Card 3: VPN Access (Yellow Theme) -->
                        <div class="coverflow-card" data-theme="yellow">
                            <x-service-card :wrapCol="false" theme="warning" icon="vpn_lock"
                                :title="__('dashboard.cards.vpn.title')" :description="__('dashboard.cards.vpn.desc')"
                                :badge="__('dashboard.cards.vpn.badge')" :features="[
            ['icon' => 'key', 'label' => __('dashboard.cards.vpn.list.encryption')],
            ['icon' => 'key', 'label' => __('dashboard.cards.vpn.list.multi_user')],
            ['icon' => 'key', 'label' => __('dashboard.cards.vpn.list.latency')]
        ]">
                                <x-slot name="btnSlot">
                                    <a href="https://wa.me/6281234567890?text=I%20want%20VPN%20Setup" target="_blank"
                                        class="btn btn-outline-warning text-dark rounded-pill btn-sm w-100 fw-bold bg-white bg-opacity-50">
                                        Setup VPN
                                    </a>
                                </x-slot>
                                <x-slot name="footer">
                                    <div class="d-flex justify-content-between small">
                                        <span class="text-secondary">{{ __('dashboard.cards.vpn.label_latency') }}</span>
                                        <span class="text-dark fw-bold">{{ __('dashboard.cards.vpn.val_low') }}</span>
                                    </div>
                                </x-slot>
                            </x-service-card>
                        </div>

                        <!-- Card 4: Hotspot (Red Theme) -->
                        <div class="coverflow-card" data-theme="red">
                            <x-service-card :wrapCol="false" theme="danger" icon="wifi_tethering"
                                :title="__('dashboard.cards.hotspot.title')"
                                :description="__('dashboard.cards.hotspot.desc')"
                                :badge="__('dashboard.cards.hotspot.badge')" :features="[
            ['icon' => 'local_printshop', 'label' => __('dashboard.cards.hotspot.list.print')],
            ['icon' => 'qr_code', 'label' => __('dashboard.cards.hotspot.list.qr')],
            ['icon' => 'bolt', 'label' => __('dashboard.cards.hotspot.list.users')]
        ]"
                                :btnLink="route('mikrotik-suite.connectivity.hotspot.user-generator')"
                                :btnText="__('dashboard.cards.hotspot.btn')"
                                btnClass="btn-outline-danger text-dark bg-white bg-opacity-50">
                                <x-slot name="footer">
                                    <div class="d-flex justify-content-between small">
                                        <span
                                            class="text-secondary">{{ __('dashboard.cards.hotspot.label_template') }}</span>
                                        <span
                                            class="text-dark fw-bold">{{ __('dashboard.cards.hotspot.val_custom') }}</span>
                                    </div>
                                </x-slot>
                            </x-service-card>
                        </div>

                        <!-- Card 5: IP Calc (Cyan Theme) -->
                        <div class="coverflow-card" data-theme="cyan">
                            <x-service-card :wrapCol="false" theme="info" icon="calculate"
                                :title="__('dashboard.cards.ip_calc.title')"
                                :description="__('dashboard.cards.ip_calc.desc')"
                                :badge="__('dashboard.cards.ip_calc.badge')" :features="[
            ['icon' => 'grid_on', 'label' => __('dashboard.cards.ip_calc.list.subnet')],
            ['icon' => 'dns', 'label' => __('dashboard.cards.ip_calc.list.vlsm')],
            ['icon' => 'public', 'label' => __('dashboard.cards.ip_calc.list.support')]
        ]"
                                :btnLink="route('mikrotik-suite.utilities.calculators.ip')"
                                :btnText="__('dashboard.cards.ip_calc.btn')"
                                btnClass="btn-outline-info text-dark bg-white bg-opacity-50">
                                <x-slot name="footer">
                                    <div class="d-flex justify-content-between small">
                                        <span class="text-secondary">{{ __('dashboard.cards.ip_calc.label_type') }}</span>
                                        <span class="text-dark fw-bold">{{ __('dashboard.cards.ip_calc.val_ipv46') }}</span>
                                    </div>
                                </x-slot>
                            </x-service-card>
                        </div>
                    </div>

                    <!-- Navigation Controls -->
                    <button class="nav-btn prev position-absolute top-50 start-0 ms-4 z-index-1">
                        <span class="material-icons-outlined text-dark fs-5">chevron_left</span>
                    </button>
                    <button class="nav-btn next position-absolute top-50 end-0 me-4 z-index-1">
                        <span class="material-icons-outlined text-dark fs-5">chevron_right</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Service Catalog (Layanan Jasa) -->
    <div
        class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-end mb-4 px-2 gap-3">
        <div>
            <h5 class="mb-0 fw-bold text-theme-main tracking-tight d-flex align-items-center gap-2">
                <span class="material-icons-outlined text-brand fs-4">storage</span>
                {{ __('dashboard.services.title') }}
            </h5>
            <p class="text-theme-secondary small mb-0 mt-1 ms-1">{{ __('dashboard.services.subtitle') }}</p>
        </div>
        <div>
            <a href="javascript:;"
                class="btn btn-sm rounded-pill px-4 fw-bold border-0 bg-brand-soft text-brand transition-hover shadow-sm">
                {{ __('dashboard.services.view_all') }} <span
                    class="material-icons-outlined align-middle fs-6 ms-1">arrow_forward</span>
            </a>
        </div>
    </div>

    <div class="row g-3">
        <!-- Service Card 1: Network Setup (Blue) -->
        <div class="col-12 col-md-6 col-xl-3">
            <div class="card h-100 border-0 shadow-sm transition-hover overflow-hidden">
                <div class="bg-primary bg-opacity-10 p-3 text-center position-relative overflow-hidden"
                    style="height: 120px;">
                    <!-- Decorative Icon Faded -->
                    <div class="position-absolute top-50 start-50 translate-middle opacity-25">
                        <span class="material-icons-outlined text-primary" style="font-size: 6rem;">hub</span>
                    </div>
                    <!-- Central Content -->
                    <div
                        class="position-relative z-index-1 h-100 d-flex flex-column justify-content-center align-items-center">
                        <span class="material-icons-outlined text-primary fs-2 mb-1">hub</span>
                        <div class="fw-bold text-primary small tracking-wide" style="font-size: 0.7rem;">FULL NETWORK SETUP
                        </div>
                    </div>
                </div>
                <div class="card-body p-3 d-flex flex-column">
                    <h5 class="fw-bold mb-2 text-theme-main">{{ __('dashboard.services.full_setup.title') }}</h5>
                    <p class="text-theme-secondary small mb-4 line-clamp-2">{{ __('dashboard.services.full_setup.desc') }}
                    </p>

                    <div class="mt-auto d-flex align-items-center justify-content-between">
                        <div>
                            <small class="text-theme-secondary d-block text-uppercase fw-bold opacity-75"
                                style="font-size: 10px; letter-spacing: 0.5px;">{{ __('dashboard.services.full_setup.starting_from') }}</small>
                            <span class="fw-bold text-theme-main">{{ __('dashboard.services.full_setup.price') }}</span>
                        </div>
                        <a href="https://wa.me/6281234567890?text=I%20want%20Mikrotik%20Full%20Setup" target="_blank"
                            class="btn btn-primary rounded-pill btn-sm px-4 fw-bold shadow-sm">
                            {{ __('dashboard.services.full_setup.btn') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Service Card 2: Security (Green) -->
        <div class="col-12 col-md-6 col-xl-3">
            <div class="card h-100 border-0 shadow-sm transition-hover overflow-hidden">
                <div class="bg-primary bg-opacity-10 p-3 text-center position-relative overflow-hidden"
                    style="height: 120px;">
                    <div class="position-absolute top-50 start-50 translate-middle opacity-25">
                        <span class="material-icons-outlined text-primary" style="font-size: 6rem;">security</span>
                    </div>
                    <div
                        class="position-relative z-index-1 h-100 d-flex flex-column justify-content-center align-items-center">
                        <span class="material-icons-outlined text-primary fs-2 mb-1">shield</span>
                        <div class="fw-bold text-primary small tracking-wide" style="font-size: 0.7rem;">SECURITY HARDENING
                        </div>
                    </div>
                </div>
                <div class="card-body p-3 d-flex flex-column">
                    <h5 class="fw-bold mb-2 text-theme-main">{{ __('dashboard.services.security_setup.title') }}</h5>
                    <p class="text-theme-secondary small mb-4 line-clamp-2">
                        {{ __('dashboard.services.security_setup.desc') }}
                    </p>

                    <div class="mt-auto d-flex align-items-center justify-content-between">
                        <div>
                            <small class="text-theme-secondary d-block text-uppercase fw-bold opacity-75"
                                style="font-size: 10px; letter-spacing: 0.5px;">{{ __('dashboard.services.full_setup.starting_from') }}</small>
                            <span class="fw-bold text-theme-main">{{ __('dashboard.services.security_setup.price') }}</span>
                        </div>
                        <a href="https://wa.me/6281234567890?text=I%20want%20Security%20Hardening" target="_blank"
                            class="btn btn-primary rounded-pill btn-sm px-4 fw-bold shadow-sm">
                            {{ __('dashboard.services.full_setup.btn') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Service Card 3: VPN (Yellow) -->
        <div class="col-12 col-md-6 col-xl-3">
            <div class="card h-100 border-0 shadow-sm transition-hover overflow-hidden">
                <div class="bg-primary bg-opacity-10 p-3 text-center position-relative overflow-hidden"
                    style="height: 120px;">
                    <div class="position-absolute top-50 start-50 translate-middle opacity-25">
                        <span class="material-icons-outlined text-primary" style="font-size: 6rem;">vpn_key</span>
                    </div>
                    <div
                        class="position-relative z-index-1 h-100 d-flex flex-column justify-content-center align-items-center">
                        <span class="material-icons-outlined text-primary fs-2 mb-1">vpn_lock</span>
                        <div class="fw-bold text-primary small tracking-wide" style="font-size: 0.7rem;">REMOTE ACCESS</div>
                    </div>
                </div>
                <div class="card-body p-3 d-flex flex-column">
                    <h5 class="fw-bold mb-2 text-theme-main">{{ __('dashboard.services.vpn_setup.title') }}</h5>
                    <p class="text-theme-secondary small mb-4 line-clamp-2">{{ __('dashboard.services.vpn_setup.desc') }}
                    </p>

                    <div class="mt-auto d-flex align-items-center justify-content-between">
                        <div>
                            <small class="text-theme-secondary d-block text-uppercase fw-bold opacity-75"
                                style="font-size: 10px; letter-spacing: 0.5px;">{{ __('dashboard.services.full_setup.starting_from') }}</small>
                            <span class="fw-bold text-theme-main">{{ __('dashboard.services.vpn_setup.price') }}</span>
                        </div>
                        <a href="https://wa.me/6281234567890?text=I%20want%20VPN%20Setup" target="_blank"
                            class="btn btn-primary rounded-pill btn-sm px-4 fw-bold shadow-sm">
                            {{ __('dashboard.services.full_setup.btn') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Service Card 4 (Custom - Dark) -->
        <!-- Service Card 4 (Custom - Brand) -->
        <div class="col-12 col-md-6 col-xl-3">
            <div class="card h-100 border-0 shadow-sm transition-hover overflow-hidden">
                <div class="bg-brand-soft p-3 text-center position-relative overflow-hidden" style="height: 120px;">
                    <div class="position-absolute top-50 start-50 translate-middle opacity-25">
                        <span class="material-icons-outlined text-brand" style="font-size: 6rem;">support_agent</span>
                    </div>
                    <div
                        class="position-relative z-index-1 h-100 d-flex flex-column justify-content-center align-items-center">
                        <span class="material-icons-outlined text-brand fs-2 mb-1">support_agent</span>
                        <div class="fw-bold text-brand small tracking-wide" style="font-size: 0.7rem;">CUSTOM REQUEST
                        </div>
                    </div>
                </div>
                <div class="card-body p-3 d-flex flex-column">
                    <h5 class="fw-bold mb-2 text-theme-main">{{ __('dashboard.services.custom.title') }}</h5>
                    <p class="text-theme-secondary small mb-4 line-clamp-2">
                        {{ __('dashboard.services.custom.desc') }}
                    </p>

                    <div class="mt-auto w-100">
                        <a href="https://wa.me/6281234567890" target="_blank"
                            class="btn btn-brand w-100 rounded-pill btn-sm px-4 fw-bold shadow-brand text-white">
                            {{ __('dashboard.services.custom.btn') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('css')
        @vite('resources/css/dashboard.css')
    @endpush

    @push('scripts')
        @vite('resources/js/dashboard.js')
    @endpush

@endsection