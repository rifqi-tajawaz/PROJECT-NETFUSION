<h5 class="mb-4 fw-bold text-dark">Personal Information</h5>
<form class="row g-4" action="{{ route('user.profile.update') }}" method="POST">
    @csrf
    <div class="col-md-6">
        <label class="form-label fw-bold">Full Name</label>
        <input type="text" class="form-control rounded-3" name="name" value="{{ old('name', $user->name) }}" required>
    </div>
    <div class="col-md-6">
        <label class="form-label fw-bold">Email Address</label>
        <input type="email" class="form-control rounded-3" name="email" value="{{ old('email', $user->email) }}"
            required>
    </div>
    <div class="col-md-6">
        <label class="form-label fw-bold">Phone Number</label>
        <input type="text" class="form-control rounded-3" name="phone" value="{{ old('phone', $user->phone) }}">
    </div>
    <div class="col-md-6">
        <label class="form-label fw-bold">Location</label>
        <input type="text" class="form-control rounded-3" name="address" value="{{ old('address', $user->address) }}">
    </div>
    <div class="col-12 text-end mt-4">
        <button type="submit" class="btn btn-dark px-5 rounded-pill">Save Changes</button>
    </div>
</form>
