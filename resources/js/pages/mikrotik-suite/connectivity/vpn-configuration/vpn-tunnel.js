document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('tunnelForm');
    const typeSelect = document.getElementById('tunnelType');
    const eoipIdField = document.getElementById('eoipIdField');
    const ipsecCheck = document.getElementById('ipsecSecret');
    const secretField = document.getElementById('secretField');
    const scriptOutput = document.getElementById('scriptOutput');

    // Add CSRF to axios
    if (window.axios) {
        const token = document.querySelector('meta[name="csrf-token"]');
        if (token) {
            window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
        }
    }

    typeSelect.addEventListener('change', function () {
        const val = this.value;
        const nameInput = document.getElementById('ifaceName');

        // Auto-update name only if it matches default pattern
        if (nameInput.value.match(/^(gre|ipip|eoip)-tunnel\d+$/)) {
            nameInput.value = `${val}-tunnel1`;
        }

        if (val === 'eoip') eoipIdField.classList.remove('d-none');
        else eoipIdField.classList.add('d-none');
    });

    ipsecCheck.addEventListener('change', function () {
        if (this.checked) secretField.classList.remove('d-none');
        else secretField.classList.add('d-none');
    });

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
                    // Format validation errors
                    if (error.response.data.errors) {
                        msg += '\n' + JSON.stringify(error.response.data.errors, null, 2);
                    }
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
