@extends('layouts.app')
@section('title')
    Pricing Plans
@endsection
@section('content')

    <style>
        .pricing-toggle-wrapper {
            position: relative;
            display: inline-flex;
            background-color: #e9ecef;
            border-radius: 50rem;
            padding: 4px;
            user-select: none;
            border: 1px solid #dee2e6;
        }

        .toggle-option {
            position: relative;
            z-index: 2;
            padding: 8px 24px;
            font-weight: 600;
            color: #6c757d;
            cursor: pointer;
            transition: color 0.3s ease;
            min-width: 100px;
            text-align: center;
        }

        .toggle-option.active {
            color: #000;
        }

        .toggle-glider {
            position: absolute;
            top: 4px;
            left: 4px;
            height: calc(100% - 8px);
            width: calc(50% - 4px);
            background-color: #fff;
            border-radius: 50rem;
            transition: transform 0.3s cubic-bezier(0.4, 0.0, 0.2, 1);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            z-index: 1;
        }

        /* State when Yearly is active - move glider to right */
        .pricing-toggle-wrapper.yearly-active .toggle-glider {
            transform: translateX(100%);
        }

        .discount-badge {
            position: absolute;
            top: -15px;
            right: -20px;
            background: #198754;
            color: white;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.7rem;
            font-weight: bold;
            transform: rotate(6deg);
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.15);
            z-index: 3;
            border: 2px solid #fff;
        }

        /* Custom animation for prices */
        .price-text {
            transition: opacity 0.2s ease-in-out;
        }
    </style>

    <div class="row justify-content-center">
        <div class="col-12 col-md-10 col-xl-12">
            <div class="text-center mb-5">
                <h2 class="fw-bold display-5 display-md-4 mb-3" style="letter-spacing: -1px;">
                    Simple, <br class="d-md-none">
                    <span class="text-primary text-gradient">Transparent Pricing</span>
                </h2>
                <p class="text-secondary fs-5 mb-4 px-3">Choose the plan that best fits your needs.</p>

                <style>
                    .text-gradient {
                        background: linear-gradient(45deg, #0d6efd, #6610f2);
                        background-clip: text;
                        -webkit-background-clip: text;
                        -webkit-text-fill-color: transparent;
                    }
                    /* Responsive Adjustments */
                    @media (max-width: 576px) {
                        .pricing-toggle-wrapper {
                            transform: scale(0.9);
                        }
                        .discount-badge {
                            right: -10px;
                            font-size: 0.65rem;
                        }
                    }
                </style>

                <div class="d-flex justify-content-center align-items-center mt-4">
                    <div class="position-relative">
                        <div class="pricing-toggle-wrapper" id="pricing-wrapper" style="cursor: pointer;">
                            <div class="toggle-glider"></div>
                            <div class="toggle-option active" id="opt-monthly">Monthly</div>
                            <div class="toggle-option" id="opt-yearly">Yearly</div>
                        </div>
                        <div class="discount-badge">Save 20%</div>
                    </div>
                </div>
            </div>

            <div class="row row-cols-1 row-cols-lg-3 g-4">

                {{-- Trial Plan --}}
                <div class="col">
                    <div class="card h-100 rounded-4 border-top border-4 border-secondary shadow-sm">
                        <div class="card-body p-4">
                            <div class="text-center mb-4">
                                <span
                                    class="badge bg-secondary bg-opacity-10 text-secondary rounded-pill px-3 py-2 text-uppercase mb-3">Trial</span>
                                <h3 class="fw-bold mb-1">Premium Trial</h3>
                                <h1 class="display-4 fw-bold mb-0">Rp
                                    <span class="price-value price-text" data-monthly="0" data-yearly="0">0</span>
                                    <span class="fs-5 fw-normal text-muted billing-period">/3 days</span>
                                </h1>
                            </div>
                            <ul class="list-unstyled d-flex flex-column gap-3 mb-4">
                                <li class="d-flex align-items-center gap-2">
                                    <span class="material-icons-outlined text-success">check</span>
                                    <span>Access to All Premium Tools</span>
                                </li>
                                <li class="d-flex align-items-center gap-2">
                                    <span class="material-icons-outlined text-success">check</span>
                                    <span>Community Support</span>
                                </li>
                                <li class="d-flex align-items-center gap-2 text-muted">
                                    <span class="material-icons-outlined">close</span>
                                    <span>No Priority Support</span>
                                </li>
                                <li class="d-flex align-items-center gap-2 text-muted">
                                    <span class="material-icons-outlined">close</span>
                                    <span>Limited to 3 Days</span>
                                </li>
                            </ul>
                            <div class="d-grid mt-auto">
                                @if (Auth::user() && Auth::user()->trial_used_at)
                                    <button class="btn btn-secondary rounded-5 py-2" disabled>Trial Used</button>
                                @else
                                    <form method="POST" action="{{ route('payment.trial') }}">
                                        @csrf
                                        <button type="submit" class="btn btn-outline-secondary rounded-5 py-2 w-100">Start 3-Day Free Trial</button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Basic Plan --}}
                <div class="col">
                    <div class="card h-100 rounded-4 border-top border-4 border-primary shadow">
                        <div class="card-body p-4">
                            <div class="text-center mb-4">
                                <span
                                    class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3 py-2 text-uppercase mb-3">Popular</span>
                                <h3 class="fw-bold mb-1">Basic</h3>
                                <h1 class="display-4 fw-bold mb-0">
                                    <span class="price-value price-text" data-monthly="50k" data-yearly="500k">50k</span>
                                    <span class="fs-5 fw-normal text-muted billing-period">/mo</span>
                                </h1>
                                <div class="mt-2 text-success small fw-medium yearly-savings d-none">Save Rp 100k/year</div>
                            </div>
                            <ul class="list-unstyled d-flex flex-column gap-3 mb-4">
                                <li class="d-flex align-items-center gap-2">
                                    <span class="material-icons-outlined text-success">check</span>
                                    <span>All Free Features</span>
                                </li>
                                <li class="d-flex align-items-center gap-2">
                                    <span class="material-icons-outlined text-success">check</span>
                                    <span>Access to 10+ Pro Tools</span>
                                </li>
                                <li class="d-flex align-items-center gap-2">
                                    <span class="material-icons-outlined text-success">check</span>
                                    <span>Email Support</span>
                                </li>
                                <li class="d-flex align-items-center gap-2 text-muted">
                                    <span class="material-icons-outlined">close</span>
                                    <span>No Priority Support</span>
                                </li>
                            </ul>
                            <div class="d-grid mt-auto">
                                <form method="POST" action="{{ route('payment.checkout') }}">
                                    @csrf
                                    <input type="hidden" name="plan" class="plan-input" value="basic_monthly">
                                    <button type="submit" class="btn btn-primary rounded-5 py-2 w-100">Choose Basic</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Premium Plan --}}
                <div class="col">
                    <div class="card h-100 rounded-4 border-top border-4 border-dark shadow-sm">
                        <div class="card-body p-4">
                            <div class="text-center mb-4">
                                <span
                                    class="badge bg-dark bg-opacity-10 text-dark rounded-pill px-3 py-2 text-uppercase mb-3">Enterprise</span>
                                <h3 class="fw-bold mb-1">Premium</h3>
                                <h1 class="display-4 fw-bold mb-0">
                                    <span class="price-value price-text" data-monthly="150k" data-yearly="1.5jt">150k</span>
                                    <span class="fs-5 fw-normal text-muted billing-period">/mo</span>
                                </h1>
                                <div class="mt-2 text-success small fw-medium yearly-savings d-none">Save Rp 300k/year</div>
                            </div>
                            <ul class="list-unstyled d-flex flex-column gap-3 mb-4">
                                <li class="d-flex align-items-center gap-2">
                                    <span class="material-icons-outlined text-success">check</span>
                                    <span>All Basic Features</span>
                                </li>
                                <li class="d-flex align-items-center gap-2">
                                    <span class="material-icons-outlined text-success">check</span>
                                    <span>Unlimited Tool Access</span>
                                </li>
                                <li class="d-flex align-items-center gap-2">
                                    <span class="material-icons-outlined text-success">check</span>
                                    <span>Priority 24/7 Support</span>
                                </li>
                                <li class="d-flex align-items-center gap-2">
                                    <span class="material-icons-outlined text-success">check</span>
                                    <span>Early Access to New Features</span>
                                </li>
                            </ul>
                            <div class="d-grid mt-auto">
                                <form method="POST" action="{{ route('payment.checkout') }}">
                                    @csrf
                                    <input type="hidden" name="plan" class="plan-input" value="premium_monthly">
                                    <button type="submit" class="btn btn-outline-dark rounded-5 py-2 w-100">Go Premium</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

@endsection
@push('scripts')
    {{-- Scripts handled by common-scripts, only page specific logic here --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const pricingWrapper = document.getElementById('pricing-wrapper');
            const optMonthly = document.getElementById('opt-monthly');
            const optYearly = document.getElementById('opt-yearly');

            const priceValues = document.querySelectorAll('.price-value');
            const periods = document.querySelectorAll('.billing-period');
            const savingsBadges = document.querySelectorAll('.yearly-savings');
            const planInputs = document.querySelectorAll('.plan-input');

            function togglePricing(isYearly) {
                // Toggle Glider State via CSS Class
                if (isYearly) {
                    pricingWrapper.classList.add('yearly-active');
                    optMonthly.classList.remove('active');
                    optYearly.classList.add('active');

                    periods.forEach(el => el.textContent = '/yr');
                    savingsBadges.forEach(el => el.classList.remove('d-none'));
                    
                    // Update Form Inputs
                    planInputs.forEach(input => {
                        input.value = input.value.replace('_monthly', '_yearly');
                    });
                } else {
                    pricingWrapper.classList.remove('yearly-active');
                    optYearly.classList.remove('active');
                    optMonthly.classList.add('active');

                    periods.forEach(el => el.textContent = '/mo');
                    savingsBadges.forEach(el => el.classList.add('d-none'));

                    // Update Form Inputs
                    planInputs.forEach(input => {
                        input.value = input.value.replace('_yearly', '_monthly');
                    });
                }

                // Update Prices with Animation
                priceValues.forEach(el => {
                    const monthlyPrice = el.dataset.monthly;
                    const yearlyPrice = el.dataset.yearly;
                    el.style.opacity = '0';
                    setTimeout(() => {
                        el.textContent = isYearly ? yearlyPrice : monthlyPrice;
                        el.style.opacity = '1';
                    }, 200);
                });
            }

            optMonthly.addEventListener('click', () => togglePricing(false));
            optYearly.addEventListener('click', () => togglePricing(true));
        });
    </script>
@endpush
