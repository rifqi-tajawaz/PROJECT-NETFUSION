

document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('pcqForm');
    const resultsContent = document.getElementById('resultsContent');
    const placeholder = document.getElementById('placeholder');
    const pcqScript = document.getElementById('pcqScript');

    // Results Els
    const resLimitDown = document.getElementById('resLimitDown');
    const resLimitUp = document.getElementById('resLimitUp');
    const resTotalDown = document.getElementById('resTotalDown');
    const resTotalUp = document.getElementById('resTotalUp');

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

                // Render Results
                resLimitDown.textContent = data.limit_down;
                resLimitUp.textContent = data.limit_up;
                resTotalDown.textContent = data.total_limit_down;
                resTotalUp.textContent = data.total_limit_up;
                pcqScript.textContent = data.script;

                placeholder.style.display = 'none';
                resultsContent.style.display = 'block';
                resultsContent.classList.add('animate__animated', 'animate__fadeIn');
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
});
