document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('wizardForm');
    const scriptOutput = document.getElementById('scriptOutput');

    // Add CSRF to axios
    if (window.axios) {
        const token = document.querySelector('meta[name="csrf-token"]');
        if (token) {
            window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
        }
    }

    form.addEventListener('submit', function (e) {
        e.preventDefault();

        // UI State: Loading
        const btn = form.querySelector('button[type="submit"]');
        const btnLabel = btn.querySelector('.btn-label');
        const btnLoader = btn.querySelector('.btn-loader');

        if (btnLabel) btnLabel.classList.add('d-none');
        if (btnLoader) btnLoader.classList.remove('d-none');
        btn.disabled = true;

        scriptOutput.textContent = '// Generating...';

        // Gather Data
        const formData = new FormData(form);
        const route = form.getAttribute('data-route');

        axios.post(route, formData)
            .then(response => {
                const data = response.data;
                if (data.status === 'success') {
                    scriptOutput.textContent = data.script;
                }
            })
            .catch(error => {
                console.error(error);
                let msg = 'An error occurred.';
                if (error.response && error.response.data && error.response.data.message) {
                    msg = error.response.data.message;
                } else if (error.message) {
                    msg = error.message;
                }
                scriptOutput.textContent = '// Error: ' + msg;
            })
            .finally(() => {
                // Restore UI
                if (btnLabel) btnLabel.classList.remove('d-none');
                if (btnLoader) btnLoader.classList.add('d-none');
                btn.disabled = false;
            });
    });

    window.copyText = function (id) {
        const text = document.getElementById(id).innerText;
        navigator.clipboard.writeText(text).then(() => {
            // Optional: Toast feedback could go here
            console.log('Copied to clipboard');
        }).catch(err => {
            console.error('Failed to copy', err);
        });
    }
});
