

document.addEventListener('DOMContentLoaded', function () {
    const wanCountInput = document.getElementById('wanCount');
    const wanCountDisplay = document.getElementById('wanCountDisplay');
    const wanInputsContainer = document.getElementById('wanInputsContainer');
    const form = document.getElementById('pccForm');
    const resultContainer = document.getElementById('resultContainer');
    const scriptOutput = document.getElementById('scriptOutput');
    const colors = ['danger', 'warning', 'success', 'info', 'primary', 'secondary', 'light', 'dark'];

    window.updateWanInputs = function () {
        const count = parseInt(wanCountInput.value);
        wanCountDisplay.textContent = count + ' WANs';

        // Get current values to preserve them
        const currentIfs = Array.from(document.querySelectorAll('input[name="wan_if[]"]')).map(i => i.value);
        const currentGws = Array.from(document.querySelectorAll('input[name="wan_gw[]"]')).map(i => i.value);

        wanInputsContainer.innerHTML = '';

        for (let i = 0; i < count; i++) {
            const num = i + 1;
            const color = colors[i % colors.length];
            const textClass = (color === 'warning' || color === 'light') ? 'text-dark' : '';

            const ifVal = currentIfs[i] || `ether${num}`;
            const gwVal = currentGws[i] || `192.168.${num}.1`;

            const html = `
                <div class="col-12 wan-item animate__animated animate__fadeIn">
                    <div class="p-3 rounded-3" style="background: rgba(255,255,255,0.05); border: 1px solid var(--glass-border);">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <span class="badge bg-${color} ${textClass} rounded-pill">WAN ${num}</span>
                            </div>
                            <div class="col-md-4">
                                <label class="small text-muted mb-1">Interface Name</label>
                                <input type="text" class="form-control form-control-sm border border-secondary border-opacity-25" name="wan_if[]" value="${ifVal}" placeholder="ether${num}" required>
                            </div>
                            <div class="col-md-5">
                                <label class="small text-muted mb-1">Gateway IP</label>
                                <input type="text" class="form-control form-control-sm border border-secondary border-opacity-25" name="wan_gw[]" value="${gwVal}" placeholder="192.168.${num}.1" required>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            wanInputsContainer.insertAdjacentHTML('beforeend', html);
        }
    };

    // Init Logic
    window.updateWanInputs();

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
