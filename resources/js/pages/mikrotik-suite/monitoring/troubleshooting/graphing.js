document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('graphForm');
    const scriptOutput = document.getElementById('scriptOutput');
    const ifaceGraph = document.getElementById('ifaceGraph');
    const ifaceOpts = document.getElementById('ifaceOpts');

    ifaceGraph.addEventListener('change', function () {
        ifaceOpts.style.display = this.checked ? 'block' : 'none';
    });

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

        scriptOutput.innerText = '// Generating...';

        const route = form.getAttribute('data-route');
        const formData = new FormData(form);

        axios.post(route, formData)
            .then(response => {
                const data = response.data;
                if (data.status === 'success') {
                    scriptOutput.innerText = data.script;
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
                scriptOutput.innerText = '// Error: ' + msg;
            })
            .finally(() => {
                // Restore UI
                if (btnLabel) btnLabel.classList.remove('d-none');
                if (btnLoader) btnLoader.classList.add('d-none');
                btn.disabled = false;
            });
    });
});
