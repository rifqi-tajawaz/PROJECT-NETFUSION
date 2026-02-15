document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('autoUpgradeForm');
    if (!form) return;

    form.addEventListener('submit', async function (e) {
        e.preventDefault();

        const submitBtn = form.querySelector('button[type="submit"]');
        const originalBtnText = submitBtn.innerHTML;

        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Generating...';

        const output = document.getElementById('scriptOutput');
        if (output) output.textContent = '// Generating...';

        try {
            const formData = new FormData(form);
            const data = Object.fromEntries(formData.entries());
            const route = form.getAttribute('data-route');

            const response = await window.axios.post(route, data);

            if (response.data && response.data.script) {
                if (output) output.textContent = response.data.script;
                // Success feedback
            } else {
                throw new Error('Invalid response format');
            }
        } catch (error) {
            console.error('Error:', error);
            const msg = error.response?.data?.message || error.message || 'An error occurred';
            if (output) output.textContent = '// Error: ' + msg;
            alert(msg);
        } finally {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalBtnText;
        }
    });
});
