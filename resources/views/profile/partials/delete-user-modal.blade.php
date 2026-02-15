{{-- Delete Account Modal --}}
<div class="modal fade" id="deleteAccountModal" tabindex="-1" aria-labelledby="deleteAccountModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow-lg">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold text-dark" id="deleteAccountModalLabel">Delete Account</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="text-center mb-4">
                    <div class="bg-danger bg-opacity-10 text-danger rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                        style="width: 60px; height: 60px;">
                        <span class="material-icons-outlined fs-2">delete_forever</span>
                    </div>
                    <h6 class="fw-bold mb-2">Are you sure?</h6>
                    <p class="text-muted small mb-0">This action is permanent and cannot be undone. All your data
                        will
                        be wiped.</p>
                </div>

                <form action="{{ route('user.profile.destroy') }}" method="POST">
                    @csrf
                    @method('DELETE')

                    <div class="mb-3">
                        <label for="password_delete" class="form-label fw-bold">Password</label>
                        <input type="password"
                            class="form-control rounded-3 border border-secondary border-opacity-25 @error('password', 'userDeletion') is-invalid @enderror"
                            name="password" id="password_delete" placeholder="Enter your password to confirm">
                        @error('password', 'userDeletion')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-grid mt-4">
                        <button type="submit" class="btn btn-danger rounded-pill fw-bold py-2">Yes, Delete
                            Account</button>
                        <button type="button" class="btn btn-light rounded-pill fw-bold py-2 mt-2 text-muted"
                            data-bs-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
