<h5 class="mb-4 fw-bold text-dark">Preferences</h5>
<form action="{{ route('user.profile.preferences') }}" method="POST">
    @csrf
    <div class="list-group list-group-flush border-0 shadow rounded-4 overflow-hidden mb-4">
        <div class="list-group-item p-4 d-flex justify-content-between align-items-center">
            <div>
                <h6 class="mb-1 fw-bold">Email Notifications</h6>
                <p class="mb-0 text-muted small">Receive monthly newsletters and feature
                    updates.
                </p>
            </div>
            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" role="switch" name="email_notifications" value="1"
                    {{ $user->email_notifications ? 'checked' : '' }}>
            </div>
        </div>
        <div class="list-group-item p-4 d-flex justify-content-between align-items-center">
            <div>
                <h6 class="mb-1 fw-bold">Login Alerts</h6>
                <p class="mb-0 text-muted small">Get notified when someone logs in from a new
                    device.</p>
            </div>
            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" role="switch" name="login_alerts" value="1"
                    {{ $user->login_alerts ? 'checked' : '' }}>
            </div>
        </div>
    </div>
    <div class="text-end">
        <button type="submit" class="btn btn-dark px-4 rounded-pill">Save Preferences</button>
    </div>
</form>

<div class="mt-5">
    <h5 class="mb-4 fw-bold text-dark text-danger">Danger Zone</h5>
    <div class="p-4 rounded-4 border-0 shadow bg-danger bg-opacity-10">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h6 class="fw-bold text-danger mb-1">Delete Account</h6>
                <p class="mb-0 text-danger text-opacity-75 small">Permanently remove your
                    account and all data.</p>
            </div>
            <button class="btn btn-outline-danger rounded-pill btn-sm px-4" data-bs-toggle="modal"
                data-bs-target="#deleteAccountModal">Delete</button>
        </div>
    </div>
</div>
