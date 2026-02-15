document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('gameForm');
    if (form) {
        form.addEventListener('submit', async function (e) {
            e.preventDefault();

            const submitBtn = form.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            const output = document.getElementById('scriptOutput');

            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Generating...';
            if (output) output.textContent = '// Generating...';

            // Get selected game preset
            const gamePreset = document.getElementById('gamePreset').value;

            // Gather inputs
            const data = {
                game_preset: gamePreset,
                custom_ports: document.getElementById('ports').value,
                src_address: document.getElementById('src').value,
                priority: document.getElementById('prio').value
            };

            try {
                const response = await window.axios.post(form.getAttribute('data-route'), data);

                if (response.data && response.data.script) {
                    if (output) updateScriptOutput(output, response.data.script);
                    // showToast('Success', 'Gaming routes generated successfully!', 'success');
                }
            } catch (error) {
                console.error('Error:', error);
                const msg = error.response?.data?.message || 'An error occurred';
                // showToast('Error', msg, 'danger');
                if (output) output.textContent = '// Error: ' + msg;
                alert(msg);
            } finally {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            }
        });
    }

    function updateScriptOutput(element, script) {
        element.innerHTML = script;
        element.classList.remove('text-muted');
        element.classList.add('text-warning');
    }
});
