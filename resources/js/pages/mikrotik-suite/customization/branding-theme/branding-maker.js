document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('brandingForm');
    if (form) {
        form.addEventListener('submit', async function (e) {
            e.preventDefault();

            const submitBtn = form.querySelector('button[type="submit"]');
            const originalText = submitBtn.querySelector('.btn-label').innerHTML; // specific structure in this blade
            const loader = submitBtn.querySelector('.btn-loader');
            const label = submitBtn.querySelector('.btn-label');
            const output = document.getElementById('scriptOutput');

            submitBtn.disabled = true;
            label.classList.add('d-none');
            loader.classList.remove('d-none');
            if (output) output.textContent = '// Generating...';

            const data = {
                identity: document.getElementById('identity').value,
                note: document.getElementById('note').value,
                ascii: document.getElementById('ascii').value
            };

            try {
                const response = await window.axios.post(form.getAttribute('data-route'), data);

                if (response.data && response.data.script) {
                    if (output) output.innerHTML = response.data.script;
                    if (typeof showToast === 'function') {
                        showToast('Success', 'Branding script generated successfully!', 'success');
                    }
                }
            } catch (error) {
                console.error('Error:', error);
                const msg = error.response?.data?.message || 'An error occurred';
                if (output) output.textContent = '// Error: ' + msg;
                alert(msg);
            } finally {
                submitBtn.disabled = false;
                label.classList.remove('d-none');
                loader.classList.add('d-none');
            }
        });
    }
});
