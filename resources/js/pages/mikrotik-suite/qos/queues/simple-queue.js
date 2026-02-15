document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('queueForm');
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
                name: document.getElementById('name').value,
                target: document.getElementById('target').value,
                maxUp: document.getElementById('maxUp').value,
                maxDown: document.getElementById('maxDown').value
            };

            try {
                const response = await window.axios.post(form.getAttribute('data-route'), data);

                if (response.data && response.data.script) {
                    if (output) output.innerHTML = response.data.script;
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
