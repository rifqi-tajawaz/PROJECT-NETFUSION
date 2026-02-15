import axios from 'axios';

document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('burstForm');
    const resDuration = document.getElementById('resDuration');
    const cmdQueue = document.getElementById('cmdQueue');

    form.addEventListener('submit', function (e) {
        e.preventDefault();

        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }

        const submitBtn = form.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Calculating...';

        const formData = new FormData(form);

        axios.post(form.dataset.route, formData)
            .then(response => {
                const data = response.data;
                // Update Results
                resDuration.textContent = data.actual_time;
                cmdQueue.textContent = data.script;

                // Highlight Command
                cmdQueue.classList.remove('text-light');
                cmdQueue.classList.add('text-success', 'fw-bold');
            })
            .catch(error => {
                console.error('Calculation Error:', error);
                if (error.response && error.response.data && error.response.data.errors) {
                    let msg = "Validation Error:\n";
                    for (const key in error.response.data.errors) {
                        msg += `- ${error.response.data.errors[key][0]}\n`;
                    }
                    alert(msg);
                } else if (error.response && error.response.data && error.response.data.error) {
                    alert(error.response.data.error);
                } else {
                    alert('An error occurred during calculation.');
                }
            })
            .finally(() => {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            });
    });

    window.copyQueue = function () {
        const text = cmdQueue.textContent.trim();
        if (text === 'Click Calculate...') return;

        navigator.clipboard.writeText(text).then(() => {
            const btn = document.querySelector('button[onclick="copyQueue()"]');
            const originalIcon = btn.innerHTML;
            btn.innerHTML = '<i class="bi bi-check-lg text-success"></i>';
            setTimeout(() => {
                btn.innerHTML = originalIcon;
            }, 2000);
        });
    }
});
