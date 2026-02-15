@extends('layouts.app')
@section('title')
    User Profile
@endsection
@section('content')



    {{-- Success Message --}}
    @if (session('status') === 'profile-updated')
        <div class="alert alert-success border-0 bg-success alert-dismissible fade show">
            <div class="text-white">Profile successfully updated.</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if (session('status') === 'password-updated')
        <div class="alert alert-success border-0 bg-success alert-dismissible fade show">
            <div class="text-white">Password successfully updated.</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Validation Errors --}}
    @if ($errors->any())
        <div class="alert alert-danger border-0 bg-danger alert-dismissible fade show">
            <div class="text-white">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <style>
        .profile-banner-margin {
            margin-bottom: 5rem;
        }

        .profile-banner-img {
            width: 100%;
            height: auto;
            min-height: 140px;
            aspect-ratio: 3.84 / 1;
            /* Matches 1920x500 */
            object-fit: fill;
        }

        .profile-avatar-img {
            width: 100px;
            height: 100px;
            border: 4px solid #fff;
        }

        @media (min-width: 768px) {
            .profile-banner-margin {
                margin-bottom: 7rem;
            }

            /* Height is auto determined by aspect ratio now, so no need to force 320px unless max constraint needed */
            .profile-avatar-img {
                width: 160px;
                height: 160px;
                border-width: 5px;
            }
        }
    </style>
    <div id="alert-container" class="mb-3"></div>
    <div class="card rounded-4 mb-4">
        <div class="card-body p-4">
            <div class="position-relative profile-banner-margin">
                <img src="https://placehold.co/1920x500/png" class="profile-banner-img rounded-4 shadow"
                    alt="Profile Banner">
                <div class="profile-avatar position-absolute top-100 start-50 translate-middle">
                    <img src="https://placehold.co/110x110/png" class="rounded-circle bg-dark shadow profile-avatar-img"
                        alt="Avatar">
                </div>
            </div>

        </div>
    </div>

    <div class="row">
        <div class="col-12 col-lg-8 col-xl-8">
            <div class="card rounded-4 border-0 shadow-sm">
                <div class="card-body p-4">
                    {{-- Navigation Tabs --}}
                    {{-- Navigation Tabs (Horizontal Scroll / Slide) --}}
                    <ul class="nav nav-pills nav-fill mb-4 bg-light rounded-4 p-2 flex-nowrap overflow-auto gap-2"
                        id="profileTab" role="tablist" style="scrollbar-width: none; -ms-overflow-style: none;">
                        <li class="nav-item flex-shrink-0" role="presentation">
                            <button class="nav-link active rounded-pill fw-bold px-4" id="profile-tab" data-bs-toggle="pill"
                                data-bs-target="#profile" type="button" role="tab" aria-selected="true">
                                <i class="material-icons-outlined align-middle me-2">person</i>Profile Details
                            </button>
                        </li>
                        <li class="nav-item flex-shrink-0" role="presentation">
                            <button class="nav-link rounded-pill fw-bold px-4" id="security-tab" data-bs-toggle="pill"
                                data-bs-target="#security" type="button" role="tab" aria-selected="false">
                                <i class="material-icons-outlined align-middle me-2">verified_user</i>Security
                            </button>
                        </li>
                        <li class="nav-item flex-shrink-0" role="presentation">
                            <button class="nav-link rounded-pill fw-bold px-4" id="settings-tab" data-bs-toggle="pill"
                                data-bs-target="#settings" type="button" role="tab" aria-selected="false">
                                <i class="material-icons-outlined align-middle me-2">settings</i>Settings
                            </button>
                        </li>
                    </ul>

                    <div class="tab-content" id="profileTabContent">
                        {{-- Tab 1: Edit Profile --}}
                        <div class="tab-pane fade show active" id="profile" role="tabpanel">
                            @include('profile.partials.overview')
                        </div>

                        {{-- Tab 2: Security --}}
                        <div class="tab-pane fade" id="security" role="tabpanel">

                            @include('profile.partials.security')
                        </div>

                        {{-- Tab 3: Settings --}}
                        <div class="tab-pane fade" id="settings" role="tabpanel">
                            @include('profile.partials.settings')
                            </div>

                        </div>
                    </div>
                </div>


            </div>

            {{-- Delete Account Modal --}}
            @include('profile.partials.delete-user-modal')

            @if($errors->userDeletion->any())
                <script>
                    document.addEventListener('DOMContentLoaded', function () {
                        new bootstrap.Modal(document.getElementById('deleteAccountModal')).show();
                    });
                </script>
            @endif

            {{-- Disable 2FA Modal --}}
            @include('profile.partials.disable-2fa-modal')
            <div class="col-12 col-lg-4 col-xl-4">

                {{-- Membership Card --}}
                <div class="card rounded-4 border-top border-4 border-{{ $user->membership_status_color }}">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <h5 class="mb-0 fw-bold">Membership</h5>
                            <span
                                class="badge bg-{{ $user->membership_status_color }} rounded-pill">{{ ucfirst($user->membership_status) }}</span>
                        </div>
                        <div class="text-center mb-4">
                            <h2 class="mb-0 fw-bold display-5">{{ $user->membership_package }}</h2>
                            <p class="mb-0 text-muted">Current Plan</p>
                        </div>
                        <div class="d-flex flex-column gap-3">
                            <div class="d-flex align-items-center justify-content-between border-bottom pb-2">
                                <span class="text-muted">Payment Date</span>
                                <span
                                    class="fw-bold">{{ $user->membership_pay_date ? $user->membership_pay_date->format('d M Y') : '-' }}</span>
                            </div>
                            <div class="d-flex align-items-center justify-content-between">
                                <span class="text-muted">Expires On</span>
                                <span
                                    class="fw-bold">{{ $user->membership_expire ? $user->membership_expire->format('d M Y') : '-' }}</span>
                            </div>
                        </div>
                        <div class="d-grid mt-4">
                            <a href="{{ route('pricing') }}" class="btn btn-outline-dark rounded-5">Upgrade Plan</a>
                        </div>
                    </div>
                </div>



                {{-- Quote/Info Card --}}
                <div class="card rounded-4">
                    <div class="card-body">
                        <p class="mb-0 text-muted fst-italic">
                            "Security is a process, not a product."
                        </p>
                    </div>
                </div>
            </div>
            {{-- Show Recovery Codes Modal (Re-inserted at root level) --}}
            @include('profile.partials.recovery-codes-modal')
        </div><!--end row-->

        <script>
            document.addEventListener('DOMContentLoaded', function () {

                // Helper to show alert (Refactored to Global Notification)
                function showAlert(message, type = 'success') {
                    // Use the global window.showNotification function if available
                    if (window.showNotification) {
                        // Map local types to global types
                        const typeMap = {
                            'success': 'success',
                            'danger': 'error',
                            'warning': 'warning',
                            'info': 'info'
                        };
                        window.showNotification(typeMap[type] || 'info', message);
                    } else {
                        // Fallback if global function is missing
                        alert(message);
                    }
                }

                // Handle Enable 2FA
                const enableForm = document.getElementById('enable-2fa-form');
                if (enableForm) {
                    enableForm.addEventListener('submit', async function (e) {
                        e.preventDefault();

                        const btn = enableForm.querySelector('button[type="submit"]');
                        const originalText = btn.innerHTML;
                        btn.disabled = true;
                        btn.innerHTML = 'Activating...';

                        try {
                            const formData = new FormData(enableForm);
                            const response = await fetch(enableForm.action, {
                                method: 'POST',
                                headers: {
                                    'X-Requested-With': 'XMLHttpRequest',
                                    'Accept': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                                },
                                body: formData
                            });

                            const data = await response.json();

                            if (response.ok) {
                                showAlert(data.message, 'success');
                                setTimeout(() => location.reload(), 3000);
                            } else {
                                showAlert(data.message || data.errors?.code?.[0] || 'Verification failed', 'danger');
                            }
                        } catch (error) {
                            showAlert('An error occurred. Please try again.', 'danger');
                        } finally {
                            btn.disabled = false;
                            btn.innerHTML = originalText;
                        }
                    });
                }

                // Handle Show Recovery Codes
                const showRecoveryModalEl = document.getElementById('showRecoveryCodesModal');
                if (showRecoveryModalEl) {
                    const showRecoveryForm = document.getElementById('show-recovery-codes-form');
                    const authSection = document.getElementById('recovery-auth-section');
                    const displaySection = document.getElementById('recovery-codes-display');
                    const listContainer = document.getElementById('recovery-codes-list');

                    showRecoveryForm.addEventListener('submit', async function (e) {
                        e.preventDefault();
                        const btn = showRecoveryForm.querySelector('button[type="submit"]');
                        const originalText = btn.innerHTML;
                        btn.disabled = true;
                        btn.innerHTML = 'Memuat...';

                        try {
                            const formData = new FormData(showRecoveryForm);
                            const response = await fetch(showRecoveryForm.action, {
                                method: 'POST',
                                headers: {
                                    'X-Requested-With': 'XMLHttpRequest',
                                    'Accept': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                                },
                                body: formData
                            });

                            const data = await response.json();

                            if (response.ok) {
                                // Populate codes
                                listContainer.innerHTML = '';
                                if (data.codes.length === 0) {
                                    listContainer.innerHTML = '<div class="col-12 text-muted">Tidak ada kode tersisa.</div>';
                                } else {
                                    data.codes.forEach(code => {
                                        listContainer.innerHTML += `<div class="col-6 mb-1">${code}</div>`;
                                    });
                                }
                                // Switch view
                                authSection.style.display = 'none';
                                displaySection.style.display = 'block';
                                showRecoveryForm.reset();
                            } else {
                                showAlert(data.message || 'Password salah', 'danger');
                            }
                        } catch (error) {
                            showAlert('Terjadi kesalahan.', 'danger');
                        } finally {
                            btn.disabled = false;
                            btn.innerHTML = originalText;
                        }
                    });

                    // Reset view on close
                    showRecoveryModalEl.addEventListener('hidden.bs.modal', function () {
                        authSection.style.display = 'block';
                        displaySection.style.display = 'none';
                        showRecoveryForm.reset();
                    });
                }

                // Handle Password Update
                const passwordForm = document.getElementById('update-password-form');
                if (passwordForm) {
                    passwordForm.addEventListener('submit', async function (e) {
                        e.preventDefault();

                        const btn = passwordForm.querySelector('button[type="submit"]');
                        const originalText = btn.innerHTML;
                        btn.disabled = true;
                        btn.innerHTML = 'Updating...';

                        // Clear previous errors if any (optional, can be expanded)

                        try {
                            const formData = new FormData(passwordForm);
                            const response = await fetch(passwordForm.action, {
                                method: 'POST',
                                headers: {
                                    'X-Requested-With': 'XMLHttpRequest',
                                    'Accept': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                                },
                                body: formData
                            });

                            const data = await response.json();

                            if (response.ok) {
                                showAlert(data.message, 'success');
                                passwordForm.reset();
                            } else {
                                // Handle validation errors or general error message
                                let errorMessage = data.message || 'Gagal memperbarui password.';
                                if (data.errors) {
                                    // Extract first error for simplicity or join unique ones
                                    errorMessage = Object.values(data.errors).flat()[0];
                                }
                                showAlert(errorMessage, 'danger');
                            }
                        } catch (error) {
                            showAlert('Terjadi kesalahan koneksi.', 'danger');
                        } finally {
                            btn.disabled = false;
                            btn.innerHTML = originalText;
                        }
                    });
                }

                // Handle Disable 2FA
                const disableModalEl = document.getElementById('disable2FAModal');
                if (disableModalEl) {
                    const disableForm = document.getElementById('disable-2fa-form-modal');
                    disableForm.addEventListener('submit', async function (e) {
                        e.preventDefault();

                        const btn = disableForm.querySelector('button[type="submit"]');
                        const originalText = btn.innerHTML;
                        btn.disabled = true;
                        btn.innerHTML = 'Menghapus...';

                        try {
                            const formData = new FormData(disableForm);
                            const response = await fetch(disableForm.action, {
                                method: 'POST',
                                headers: {
                                    'X-Requested-With': 'XMLHttpRequest',
                                    'Accept': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                                },
                                body: formData
                            });

                            const data = await response.json();

                            if (response.ok) {
                                showAlert(data.message || '2FA Berhasil Dihapus', 'success');
                                bootstrap.Modal.getInstance(disableModalEl).hide();
                                setTimeout(() => location.reload(), 3000);
                            } else {
                                showAlert(data.message || 'Password salah', 'danger');
                            }

                        } catch (error) {
                            showAlert('An error occurred.', 'danger');
                        } finally {
                            btn.disabled = false;
                            btn.innerHTML = originalText;
                        }
                    });
                }

                // Handle Password Strength Meter (Matches Register Page Logic)
                const passwordInput = document.getElementById('password');
                const confirmInput = document.getElementById('password_confirmation');
                const meter = document.getElementById('password-strength-meter');
                const matchFeedback = document.getElementById('password-match-feedback');

                // Requirements Elements
                const reqLength = document.getElementById('req-length');
                const reqUppercase = document.getElementById('req-uppercase');
                const reqLowercase = document.getElementById('req-lowercase');
                const reqNumber = document.getElementById('req-number');
                const reqSpecial = document.getElementById('req-special');

                function updateRequirement(element, valid) {
                    const icon = element.querySelector('i');
                    if (valid) {
                        element.classList.remove('text-muted');
                        element.classList.add('text-success');
                        icon.classList.remove('bi-circle');
                        icon.classList.add('bi-check-circle-fill');
                    } else {
                        element.classList.remove('text-success');
                        element.classList.add('text-muted');
                        icon.classList.remove('bi-check-circle-fill');
                        icon.classList.add('bi-circle');
                    }
                }

                if (passwordInput && meter) {
                    passwordInput.addEventListener('input', function () {
                        const password = passwordInput.value;

                        if (password.length > 0) {
                            meter.style.display = 'block';
                        } else {
                            meter.style.display = 'none';
                        }

                        // Requirements Logic
                        const requirements = {
                            length: password.length >= 8,
                            uppercase: /[A-Z]/.test(password),
                            lowercase: /[a-z]/.test(password),
                            number: /[0-9]/.test(password),
                            special: /[^a-zA-Z0-9]/.test(password)
                        };

                        updateRequirement(reqLength, requirements.length);
                        updateRequirement(reqUppercase, requirements.uppercase);
                        updateRequirement(reqLowercase, requirements.lowercase);
                        updateRequirement(reqNumber, requirements.number);
                        updateRequirement(reqSpecial, requirements.special);

                        // Calculate score
                        let strength = 0;
                        if (requirements.length) strength += 20;
                        if (requirements.uppercase) strength += 20;
                        if (requirements.lowercase) strength += 20;
                        if (requirements.number) strength += 20;
                        if (requirements.special) strength += 20;

                        // Determine Level
                        let activeSegments = 0;
                        let strengthColor = 'bg-danger';
                        let strengthTextColor = 'text-danger';
                        let strengthText = 'Lemah';

                        if (strength <= 20) {
                            activeSegments = 1;
                            strengthColor = 'bg-danger';
                            strengthTextColor = 'text-danger';
                            strengthText = 'Sangat Lemah';
                        } else if (strength <= 40) {
                            activeSegments = 2;
                            strengthColor = 'bg-warning';
                            strengthTextColor = 'text-warning';
                            strengthText = 'Lemah';
                        } else if (strength <= 80) {
                            activeSegments = 3;
                            strengthColor = 'bg-info';
                            strengthTextColor = 'text-info';
                            strengthText = 'Lumayan';
                        } else {
                            activeSegments = 4;
                            strengthColor = 'bg-success';
                            strengthTextColor = 'text-success';
                            strengthText = 'Kuat';
                        }

                        if (password.length === 0) {
                            activeSegments = 0;
                            strengthText = '';
                        }

                        // Update UI segments
                        for (let i = 1; i <= 4; i++) {
                            const seg = document.getElementById('seg-' + i);
                            seg.className = 'flex-grow-1 rounded opacity-25 strength-segment bg-secondary'; // resetting
                            if (i <= activeSegments) {
                                seg.classList.remove('bg-secondary', 'opacity-25');
                                seg.classList.add(strengthColor, 'opacity-100');
                            }
                        }

                        const textEl = document.getElementById('strength-text');
                        const scoreEl = document.getElementById('strength-score');

                        textEl.textContent = strengthText;
                        textEl.className = 'small fw-bold ' + strengthTextColor;
                        scoreEl.textContent = strength + '%';

                        checkMatch();
                    });
                }

                if (confirmInput) {
                    confirmInput.addEventListener('input', checkMatch);
                }

                function checkMatch() {
                    if (!confirmInput.value) {
                        matchFeedback.style.display = 'none';
                        return;
                    }

                    if (passwordInput.value !== confirmInput.value) {
                        matchFeedback.style.display = 'block';
                        matchFeedback.innerHTML = '<i class="bi bi-exclamation-circle me-1"></i> Password tidak cocok';
                        matchFeedback.className = 'small mt-1 text-danger';
                    } else {
                        matchFeedback.style.display = 'block';
                        matchFeedback.innerHTML = '<i class="bi bi-check-circle me-1"></i> Password cocok';
                        matchFeedback.className = 'small mt-1 text-success';
                    }
                }
            });
        </script>
@endsection

@push('script')
    <!--plugins-->
    <script src="{{ URL::asset('build/plugins/perfect-scrollbar/js/perfect-scrollbar.js') }}"></script>
    <script src="{{ URL::asset('build/plugins/metismenu/metisMenu.min.js') }}"></script>
    <script src="{{ URL::asset('build/plugins/simplebar/js/simplebar.min.js') }}"></script>
@endpush
