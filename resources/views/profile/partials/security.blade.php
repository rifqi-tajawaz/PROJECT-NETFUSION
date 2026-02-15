{{-- Change Password Section --}}
<div class="mb-5">
    <h5 class="mb-4 fw-bold text-dark">Change Password</h5>
    <form id="update-password-form" action="{{ route('user.profile.password') }}" method="POST">
        @csrf
        <div class="row g-3">
            <div class="col-md-12">
                <div class="mb-2">
                    <label for="current_password" class="form-label fw-bold">Current
                        Password</label>
                    <input type="password" class="form-control rounded-3" id="current_password" name="current_password"
                        required>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-2">
                    <label for="password" class="form-label fw-bold">New Password</label>
                    <input type="password" class="form-control rounded-3" id="password" name="password" required>
                    {{-- Segmented Strength Meter (Matches Register Page) --}}
                    <div class="mt-2" id="password-strength-meter" style="display: none;">
                        <div class="d-flex gap-1" style="height: 4px;">
                            <div class="flex-grow-1 rounded bg-secondary opacity-25 strength-segment" id="seg-1">
                            </div>
                            <div class="flex-grow-1 rounded bg-secondary opacity-25 strength-segment" id="seg-2">
                            </div>
                            <div class="flex-grow-1 rounded bg-secondary opacity-25 strength-segment" id="seg-3">
                            </div>
                            <div class="flex-grow-1 rounded bg-secondary opacity-25 strength-segment" id="seg-4">
                            </div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mt-2">
                            <span class="small fw-bold text-muted" id="strength-text">Lemah</span>
                            <span class="small text-muted" id="strength-score">0%</span>
                        </div>
                        <ul class="list-unstyled mt-2 small text-muted text-start" id="password-requirements">
                            <li id="req-length"><i class="bi bi-circle me-2"></i>Minimal 8
                                karakter</li>
                            <li id="req-uppercase"><i class="bi bi-circle me-2"></i>Minimal 1
                                huruf besar (A-Z)</li>
                            <li id="req-lowercase"><i class="bi bi-circle me-2"></i>Minimal 1
                                huruf kecil (a-z)</li>
                            <li id="req-number"><i class="bi bi-circle me-2"></i>Minimal 1 angka
                                (0-9)</li>
                            <li id="req-special"><i class="bi bi-circle me-2"></i>Minimal 1
                                karakter spesial (!@#$%^&*)</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-2">
                    <label for="password_confirmation" class="form-label fw-bold">Confirm
                        Password</label>
                    <input type="password" class="form-control rounded-3" id="password_confirmation"
                        name="password_confirmation" required>
                    <div id="password-match-feedback" class="small mt-1 text-danger" style="display: none;">
                        <i class="bi bi-exclamation-circle me-1"></i> Password tidak cocok
                    </div>
                </div>
            </div>
            <div class="col-12 text-end">
                <button type="submit" class="btn btn-dark rounded-pill">Update Password</button>
            </div>
        </div>
    </form>
</div>

{{-- 2FA Section --}}
<div class="mb-3">
    <h5 class="mb-4 fw-bold text-dark">Two-Factor Authentication</h5>
    <div class="bg-light rounded-4 p-4 border-0 shadow">
        <div class="d-flex flex-column flex-md-row align-items-center justify-content-between gap-3">
            <div class="d-flex align-items-center gap-3">
                <div class="d-flex align-items-center justify-content-center bg-white text-primary rounded-circle shadow-sm"
                    style="width: 50px; height: 50px;">
                    <span class="material-icons-outlined fs-4">verified_user</span>
                </div>
                <div>
                    <div class="d-flex align-items-center gap-2">
                        <h6 class="mb-0 fw-bold text-dark">Authenticator App</h6>
                        @if ($user->two_factor_confirmed_at)
                            <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3"><i
                                    class="bi bi-shield-check me-1"></i>Aktif</span>
                        @endif
                    </div>
                    <p class="mb-0 text-secondary small">Secure your account with Google
                        Authenticator.</p>
                </div>
            </div>
            <div class="d-flex flex-wrap align-items-center gap-2 mt-3 mt-md-0 justify-content-end">
                @if ($user->two_factor_confirmed_at)
                    <button class="btn btn-light rounded-pill px-3 fw-bold text-dark border" data-bs-toggle="modal"
                        data-bs-target="#showRecoveryCodesModal">Lihat
                        Kode</button>
                    <button
                        class="btn btn-outline-danger border-0 bg-danger bg-opacity-10 text-danger rounded-pill px-3 fw-bold"
                        data-bs-toggle="modal" data-bs-target="#disable2FAModal">Hapus</button>
                @else
                    <button class="btn btn-primary btn-sm rounded-pill px-4" type="button" data-bs-toggle="collapse"
                        data-bs-target="#setup2FA">Setup</button>
                @endif
            </div>
        </div>

        {{-- 2FA Setup Area --}}
        @if (!$user->two_factor_confirmed_at)
            <div class="collapse mt-4" id="setup2FA">
                <div class="card card-body border-0 shadow-sm rounded-4">
                    @if ($twoFactorData)
                        <div class="row align-items-center">
                            <div class="col-md-5 text-center">
                                <div class="p-3 bg-white rounded-3 shadow-sm d-inline-block mb-3 mw-100">
                                    <div class="d-flex justify-content-center align-items-center svg-container"
                                        style="max-width: 150px; min-width: 150px; min-height: 150px;">
                                        {!! $twoFactorData['qr_code'] !!}
                                    </div>
                                </div>
                                <p class="small text-muted mb-0">Scan this QR Code</p>
                            </div>

                            <style>
                                /* Fix SVG Scaling for QR Code */
                                .svg-container svg {
                                    width: 100% !important;
                                    height: auto !important;
                                    max-width: 100%;
                                }
                            </style>
                            <div class="col-md-7 ps-md-4 pt-3 pt-md-0">
                                <h6 class="fw-bold mb-3">Verify Configuration</h6>
                                <p class="small text-muted mb-3">Enter the 6-digit code from your
                                    authenticator app to enable 2FA.</p>

                                <form id="enable-2fa-form" method="POST" action="{{ route('two-factor.confirm') }}">
                                    @csrf
                                    <div class="mb-3">
                                        {{-- Input Group for Desktop, Stacked for Mobile --}}
                                        <div class="d-flex flex-column flex-sm-row gap-2">
                                            <input type="text"
                                                class="form-control form-control-lg bg-light border-0 shadow text-center fw-bold letter-spacing-2 w-100"
                                                name="code" placeholder="000 000" maxlength="6" required>
                                            <button class="btn btn-primary px-4 fw-bold w-sm-auto w-100"
                                                type="submit">Activate</button>
                                        </div>
                                    </div>
                                    <div
                                        class="d-flex align-items-center justify-content-center justify-content-md-start gap-2 bg-light p-2 rounded-3 border-0 shadow text-break">
                                        <span class="small text-nuted flex-shrink-0">Key:</span>
                                        <code
                                            class="text-primary fw-bold user-select-all small">{{ $twoFactorData['secret'] }}</code>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        @endif
    </div>
</div>