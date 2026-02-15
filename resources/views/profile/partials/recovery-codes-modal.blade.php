{{-- Show Recovery Codes Modal --}}
<div class="modal fade" id="showRecoveryCodesModal" tabindex="-1" aria-labelledby="showRecoveryCodesModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow-lg">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold text-dark" id="showRecoveryCodesModalLabel">Kode Pemulihan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div id="recovery-auth-section">
                    <p class="text-muted mb-4">Masukkan password Anda untuk melihat kode pemulihan.</p>
                    <form id="show-recovery-codes-form" action="{{ route('two-factor.recovery-codes') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="password_recovery" class="form-label fw-bold">Password</label>
                            <input type="password"
                                class="form-control rounded-3 border border-secondary border-opacity-25" name="password"
                                id="password_recovery" placeholder="Masukkan password Anda" required>
                        </div>
                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <button type="button" class="btn btn-light rounded-pill px-4"
                                data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-dark rounded-pill px-4">Lihat Kode</button>
                        </div>
                    </form>
                </div>
                <div id="recovery-codes-display" style="display: none;">
                    <p class="text-muted mb-3 small">Simpan kode-kode ini di tempat yang aman. Anda dapat
                        menggunakannya jika kehilangan akses ke aplikasi authenticator.</p>
                    <div class="bg-light p-3 rounded-3 mb-3 border font-monospace text-center">
                        <div id="recovery-codes-list" class="row g-2">
                            {{-- Codes injected here --}}
                        </div>
                    </div>
                    <div class="d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-light rounded-pill px-4"
                            onclick="document.getElementById('recovery-auth-section').style.display='block'; document.getElementById('recovery-codes-display').style.display='none';">Kembali</button>
                        <button type="button" class="btn btn-primary rounded-pill px-4"
                            data-bs-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
