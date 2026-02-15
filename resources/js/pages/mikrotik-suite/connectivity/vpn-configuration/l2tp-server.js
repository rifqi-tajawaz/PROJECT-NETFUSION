document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('l2tpForm');
    const scriptOutput = document.getElementById('scriptOutput');
    const ipsecInput = document.getElementById('ipsecSecret');

    // Add CSRF to axios
    if (window.axios) {
        const token = document.querySelector('meta[name="csrf-token"]');
        if (token) {
            window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
        }
    }

    window.toggleUserFields = function () {
        const check = document.getElementById('checkUser').checked;
        document.getElementById('userFields').style.display = check ? 'block' : 'none';

        // Disable inputs if hidden to prevent validation/submission issues if any
        const inputs = document.querySelectorAll('#userFields input');
        inputs.forEach(input => input.disabled = !check);
    };

    window.generateRandomKey = function () {
        const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        let result = '';
        for (let i = 0; i < 16; i++) {
            result += chars.charAt(Math.floor(Math.random() * chars.length));
        }
        ipsecInput.value = result;
    };

    window.copyScript = function () {
        const text = scriptOutput.innerText;
        navigator.clipboard.writeText(text).then(() => {
            alert('Script copied!');
        });
    };

    // Initialize state
    toggleUserFields();

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
