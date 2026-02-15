@extends('layouts.guest')

@section('title')
    Pendaftaran Berhasil
@endsection

@section('content')
    <div class="container-fluid my-5">
        <div class="row">
            <div class="col-12 col-md-8 col-lg-6 col-xl-5 col-xxl-4 mx-auto">
                <div class="card rounded-4 mb-0 border-top border-4 border-danger border-gradient-2">
                    <div class="card-body p-4 p-md-5">
                        <div class="text-center mb-4">
                            <img src="{{ URL::asset('build/images/logo1.png') }}" class="mb-4" width="145" alt="Logo">

                            <h4 class="fw-bold">Pendaftaran Sukses!</h4>
                            <p class="text-muted">Akun Anda berhasil dibuat.</p>
                        </div>

                        <div class="alert border-0 border-start border-4 border-info shadow-sm bg-white mb-4">
                            <div class="d-flex align-items-center">
                                <div class="fs-3 text-info"><i class="bi bi-envelope-exclamation-fill"></i></div>
                                <div class="ms-3">
                                    <h6 class="mb-0 text-info fw-bold">Verifikasi Email</h6>
                                    <div class="text-secondary small">Kami telah mengirimkan link verifikasi ke email Anda.
                                        Silakan cek inbox/spam.</div>
                                </div>
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <a href="{{ route('login') }}" class="btn btn-primary btn-lg btn-gradient-1">
                                <i class="bi bi-box-arrow-in-right me-2"></i>Masuk Sekarang
                            </a>
                            <button type="button" class="btn btn-link text-decoration-none text-secondary w-100"
                                data-bs-toggle="modal" data-bs-target="#resendEmailModal">
                                Belum terima email? <span class="text-primary fw-bold">Kirim Ulang</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Resend Email Modal -->
    <div class="modal fade" id="resendEmailModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4 border-0">
                <div class="modal-header border-bottom-0">
                    <h5 class="modal-title fw-bold">Kirim Ulang Verifikasi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body pb-4">
                    <p class="text-muted mb-4">Masukkan alamat email yang Anda gunakan saat mendaftar untuk menerima link
                        verifikasi baru.</p>
                    <form id="resendForm" action="{{ route('verification.resend.guest') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="email" class="form-label">Alamat Email</label>
                            <div class="position-relative input-icon">
                                <input type="email" class="form-control ps-5" id="email" name="email"
                                    value="{{ session('registration_email', old('email')) }}" required
                                    placeholder="Contoh: nama@domain.com">
                                <span class="position-absolute top-50 translate-middle-y"><i
                                        class="bi bi-envelope fs-5 ps-2"></i></span>
                            </div>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-gradient-1">
                                Kirim Email Verifikasi
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        // Handle form submission via AJAX
        document.getElementById('resendForm').addEventListener('submit', function (e) {
            e.preventDefault();

            const form = this;
            const submitBtn = form.querySelector('button[type="submit"]');
            const originalText = '<i class="bi bi-send me-2"></i>Kirim Email Verifikasi'; // Store original HTML
            const emailInput = form.querySelector('input[name="email"]');
            const csrfToken = form.querySelector('input[name="_token"]').value;
            const msgContainer = document.createElement('div');

            // Clear previous messages
            form.querySelectorAll('.alert').forEach(el => el.remove());

            // Disable UI
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Mengirim...';
            submitBtn.disabled = true;
            emailInput.disabled = true;

            fetch('{{ route("verification.resend.guest") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ email: emailInput.value })
            })
                .then(async response => {
                    const data = await response.json().catch(() => ({}));

                    if (!response.ok) {
                        // Handle Throttle (429)
                        if (response.status === 429) {
                            throw new Error('Terlalu banyak permintaan. Silakan tunggu beberapa saat.');
                        }
                        // Handle Validation (422)
                        if (response.status === 422) {
                            const errorMsg = data.errors ? Object.values(data.errors)[0][0] : data.message;
                            throw new Error(errorMsg || 'Format email tidak valid.');
                        }
                        // Generic Error
                        throw new Error(data.message || 'Terjadi kesalahan pada server.');
                    }
                    return data;
                })
                .then(data => {
                    // Show Success Message
                    msgContainer.className = 'alert alert-success mt-3 small';
                    msgContainer.innerHTML = '<i class="bi bi-check-circle me-2"></i>' + data.message;
                    form.appendChild(msgContainer);

                    // Start Countdown Timer (60 seconds)
                    let timeLeft = 60;
                    const timerInterval = setInterval(() => {
                        submitBtn.innerHTML = `<i class="bi bi-clock me-2"></i>Tunggu ${timeLeft} detik`;
                        timeLeft--;

                        if (timeLeft < 0) {
                            clearInterval(timerInterval);
                            submitBtn.innerHTML = originalText;
                            submitBtn.disabled = false;
                            emailInput.disabled = false;
                        }
                    }, 1000);

                })
                .catch(error => {
                    // Show Error Message
                    msgContainer.className = 'alert alert-danger mt-3 small';
                    msgContainer.innerHTML = '<i class="bi bi-exclamation-circle me-2"></i>' + error.message;
                    form.appendChild(msgContainer);

                    // Reset Button immediately on error (unless it's a success-but-throttle case, but simple reset is safer)
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                    emailInput.disabled = false;
                });
        });
    </script>
@endpush
