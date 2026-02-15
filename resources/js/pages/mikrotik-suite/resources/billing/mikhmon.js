document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('mikhmonForm');
    if (form) {
        form.addEventListener('submit', async function (e) {
            e.preventDefault();

            const submitBtn = form.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            const output = document.getElementById('scriptOutput');

            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Generating...';
            if (output) output.textContent = '// Generating...';

            const data = {
                user: document.getElementById('user').value,
                pass: document.getElementById('pass').value,
                group: document.getElementById('group').value,
                apiService: document.getElementById('apiService').checked ? 1 : 0
            };

            try {
                const response = await window.axios.post(form.getAttribute('data-route'), data);

                if (response.data && response.data.script) {
                    if (output) output.innerHTML = response.data.script;
                    if (typeof showToast === 'function') {
                        showToast('Success', 'Mikhmon config generated successfully!', 'success');
                    }
                }
            } catch (error) {
                console.error('Error:', error);
                const msg = error.response?.data?.message || 'An error occurred';
                if (output) output.textContent = '// Error: ' + msg;
                alert(msg);
            } finally {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            }
        });
    }
});
