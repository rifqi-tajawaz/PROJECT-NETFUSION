document.addEventListener('DOMContentLoaded', function () {
    const userDropdown = document.getElementById('user-dropdown-item');
    if (userDropdown) {
        userDropdown.addEventListener('show.bs.dropdown', function () {
            const icon = this.querySelector('.user-setting-icon');
            if (icon) icon.classList.add('spin-animation');
        });
        userDropdown.addEventListener('hide.bs.dropdown', function () {
            const icon = this.querySelector('.user-setting-icon');
            if (icon) icon.classList.remove('spin-animation');
        });
    }
});
