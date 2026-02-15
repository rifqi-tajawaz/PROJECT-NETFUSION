

document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('proxyForm');
    const resCacheSize = document.getElementById('resCacheSize');
    const resObjects = document.getElementById('resObjects');
    const cmdScript = document.getElementById('cmdScript');

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
                resCacheSize.textContent = data.cache_size_display;
                resObjects.textContent = data.est_objects;
                cmdScript.textContent = data.script;

                // Highlight Command
                cmdScript.classList.remove('text-light');
                cmdScript.classList.add('text-success', 'fw-bold');
            })
            .catch(error => {
                console.error('Calculation Error:', error);
                if (error.response && error.response.data && error.response.data.errors) {
                    let msg = "Validation Error:\n";
                    for (const key in error.response.data.errors) {
                        msg += `- ${error.response.data.errors[key][0]}\n`;
                    }
                    alert(msg);
                } else {
                    alert('An error occurred during calculation.');
                }
            })
            .finally(() => {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            });
    });

    window.copyScript = function () {
        const text = cmdScript.textContent.trim();
        if (text.includes('...')) return;

        navigator.clipboard.writeText(text).then(() => {
            const btn = document.querySelector('button[onclick="copyScript()"]');
            const originalIcon = btn.innerHTML;
            btn.innerHTML = '<i class="bi bi-check-lg text-success"></i>';
            setTimeout(() => {
                btn.innerHTML = originalIcon;
            }, 2000);
        });
    }
});
