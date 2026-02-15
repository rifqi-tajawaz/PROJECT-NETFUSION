

document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('abForm');
    const scriptOutput = document.getElementById('scriptOutput');

    form.addEventListener('submit', function (e) {
        e.preventDefault();

        const submitBtn = form.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Generating...';

        const formData = new FormData(form);

        axios.post(form.dataset.route, formData)
            .then(response => {
                const data = response.data;
                scriptOutput.textContent = data.script;
            })
            .catch(error => {
                console.error('Generation Error:', error);
                alert('An error occurred during generation.');
            })
            .finally(() => {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            });
    });

    window.copyScript = function () {
        const text = scriptOutput.textContent;
        navigator.clipboard.writeText(text).then(() => {
            alert('Script copied to clipboard!');
        });
    }
});
