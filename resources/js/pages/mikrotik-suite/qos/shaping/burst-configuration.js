document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('burstForm');
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
                limit: document.getElementById('limit').value,
                burstLimit: document.getElementById('burstLimit').value,
                burstThreshold: document.getElementById('burstThreshold').value,
                burstTime: document.getElementById('burstTime').value
            };

            try {
                const response = await window.axios.post(form.getAttribute('data-route'), data);

                if (response.data && response.data.script) {
                    if (output) output.innerHTML = response.data.script;

                    // Simple analysis
                    if (document.getElementById('analysis')) {
                        document.getElementById('analysis').innerText = `Burst allows ${data.burstLimit} for ${data.burstTime}s if avg < ${data.burstThreshold}.`;
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
