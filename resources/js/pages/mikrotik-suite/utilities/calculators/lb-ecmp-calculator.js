

document.addEventListener('DOMContentLoaded', function () {
    const gwCountInput = document.getElementById('gwCount');
    const gwCountDisplay = document.getElementById('gwCountDisplay');
    const gwInputsContainer = document.getElementById('gwInputsContainer');
    const form = document.getElementById('ecmpForm');
    const resultContainer = document.getElementById('resultContainer');
    const scriptOutput = document.getElementById('scriptOutput');
    const colors = ['primary', 'info', 'success', 'warning', 'danger', 'secondary', 'light', 'dark'];

    window.updateGwInputs = function () {
        const count = parseInt(gwCountInput.value);
        gwCountDisplay.textContent = count + ' Gateways';

        // Preserve values
        const currentGws = Array.from(document.querySelectorAll('input[name="gw_ip[]"]')).map(i => i.value);

        gwInputsContainer.innerHTML = '';
        for (let i = 0; i < count; i++) {
            const num = i + 1;
            const color = colors[i % colors.length];
            const textClass = (color === 'warning' || color === 'light') ? 'text-dark' : '';
            const val = currentGws[i] || `192.168.${num}.1`;

            const html = `
             <div class="col-md-6 animate__animated animate__fadeIn">
                <div class="p-3 rounded-3" style="background: rgba(255,255,255,0.05); border: 1px solid var(--glass-border);">
                    <div class="d-flex align-items-center mb-2">
                        <span class="badge bg-${color} ${textClass} rounded-pill me-2">GW ${num}</span>
                    </div>
                    <label class="small text-muted mb-1">Gateway IP Address</label>
                    <input type="text" class="form-control form-control-sm border border-secondary border-opacity-25" name="gw_ip[]" value="${val}" placeholder="192.168.x.x" required>
                </div>
             </div>
            `;
            gwInputsContainer.insertAdjacentHTML('beforeend', html);
        }
    };

    // Init Logic
    window.updateGwInputs();

    window.copyScript = function () {
        const text = scriptOutput.innerText;
        navigator.clipboard.writeText(text).then(() => {
            alert('Script copied to clipboard!');
        });
    }

    form.addEventListener('submit', function (e) {
        e.preventDefault();

        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }

        const submitBtn = form.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Generating...';

        const formData = new FormData(form);

        axios.post(form.dataset.route, formData)
            .then(response => {
                const data = response.data;
                scriptOutput.textContent = data.script;
                resultContainer.style.display = 'block';
                resultContainer.scrollIntoView({ behavior: 'smooth' });
            })
            .catch(error => {
                console.error('Generation Error:', error);
                if (error.response && error.response.data && error.response.data.errors) {
                    let msg = "Validation Error:\n";
                    for (const key in error.response.data.errors) {
                        msg += `- ${error.response.data.errors[key][0]}\n`;
                    }
                    alert(msg);
                } else if (error.response && error.response.data && error.response.data.error) {
                    alert(error.response.data.error);
                } else {
                    alert('An error occurred during generation.');
                }
            })
            .finally(() => {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            });
    });
});
