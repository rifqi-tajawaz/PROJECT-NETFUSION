{{-- Disable 2FA Modal --}}
<div class="modal fade" id="disable2FAModal" tabindex="-1" aria-labelledby="disable2FAModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow-lg">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold text-dark" id="disable2FAModalLabel">Hapus Autentikasi 2 Faktor
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="text-center mb-4">
                    <div class="bg-danger bg-opacity-10 text-danger rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                        style="width: 60px; height: 60px;">
                        <span class="material-icons-outlined fs-2">gpp_bad</span>
                    </div>
                    <h6 class="fw-bold mb-2">Apakah Anda yakin?</h6>
                    <p class="text-muted small mb-0">Tindakan ini akan menghapus lapisan keamanan tambahan dari akun
                        Anda. Akun Anda akan menjadi lebih rentan.</p>
                </div>

                <form id="disable-2fa-form-modal" action="{{ route('two-factor.disable') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="password_2fa" class="form-label fw-bold">Konfirmasi Password</label>
                        <input type="password" class="form-control rounded-3 border border-secondary border-opacity-25"
                            name="password" id="password_2fa" placeholder="Masukkan password Anda" required>
                    </div>

                    <div class="d-grid mt-4">
                        <button type="submit" class="btn btn-danger rounded-pill fw-bold py-2">Ya, Hapus
                            2FA</button>
                        <button type="button" class="btn btn-light rounded-pill fw-bold py-2 mt-2 text-muted"
                            data-bs-dismiss="modal">Batal</button>
                    </div>
                </form>
            </div>


        </div>
    </div>
</div>
