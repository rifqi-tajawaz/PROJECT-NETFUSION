document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('wifiForm');
    if (form) {
        form.addEventListener('submit', async function (e) {
            e.preventDefault();

            const submitBtn = form.querySelector('button[type="submit"]');
            // Check button structure, assume standard labeled button or simple
            const loader = submitBtn.querySelector('.btn-loader');
            const label = submitBtn.querySelector('.btn-label');
            const output = document.getElementById('scriptOutput');

            submitBtn.disabled = true;
            if (loader && label) {
                label.classList.add('d-none');
                loader.classList.remove('d-none');
            } else {
                submitBtn.innerText = 'Generating...';
            }

            if (output) output.textContent = '// Generating...';

            const data = {
                uid: document.getElementById('uid').value,
                pwd: document.getElementById('pwd').value,
                iface: document.getElementById('iface').value
            };

            try {
                const response = await window.axios.post(form.getAttribute('data-route'), data);

                if (response.data && response.data.script) {
                    if (output) output.innerHTML = response.data.script;
                    if (typeof showToast === 'function') {
                        showToast('Success', 'Auto-Login script generated successfully!', 'success');
                    }
                }
            } catch (error) {
                console.error('Error:', error);
                const msg = error.response?.data?.message || 'An error occurred';
                if (output) output.textContent = '// Error: ' + msg;
                alert(msg);
            } finally {
                submitBtn.disabled = false;
                if (loader && label) {
                    label.classList.remove('d-none');
                    loader.classList.add('d-none');
                } else {
                    submitBtn.innerHTML = '<span class="btn-label">Generate Auto-Login Script</span> <span class="btn-loader d-none"><span class="spinner-border spinner-border-sm me-2"></span>Generating...</span>'; // Reset to original structure if needed or just text
                    // Actually let's just reset classes if they existed
                }
            }
        });
    }
});
