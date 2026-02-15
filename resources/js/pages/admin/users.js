document.addEventListener('DOMContentLoaded', function () {
    // Edit User Modal Handling
    // Attached to window to be accessible from inline onclick if needed, 
    // but ideally we should attach event listeners to buttons instead.
    // For now, keeping it global to match the existing onclick pattern 
    // but moving the logic out of the blade file.
    window.editUser = function(id, name, email, role, isActive, updateUrl) {
        const form = document.getElementById('editUserForm');
        if (!form) return;

        form.action = updateUrl;

        const nameInput = document.getElementById('editName');
        const emailInput = document.getElementById('editEmail');
        const roleInput = document.getElementById('editRole');
        const statusInput = document.getElementById('editStatus');

        if (nameInput) nameInput.value = name;
        if (emailInput) emailInput.value = email;
        if (roleInput) roleInput.value = role;
        if (statusInput) statusInput.value = isActive ? 'active' : 'inactive';

        const modalEl = document.getElementById('editUserModal');
        if (modalEl) {
            new bootstrap.Modal(modalEl).show();
        }
    };

    // Delete User Confirmation
    // We attach this to forms with class 'delete-user-form'
    const deleteForms = document.querySelectorAll('.delete-user-form');
    deleteForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            if (!confirm('Are you sure you want to delete this user? This action cannot be undone.')) {
                e.preventDefault();
            }
        });
    });
});
