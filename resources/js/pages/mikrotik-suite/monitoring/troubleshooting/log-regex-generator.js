document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('regexForm');
    const out = document.getElementById('matchOutput');
    const box = document.getElementById('resultBox');
    const rosRule = document.getElementById('rosRule');

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

        out.innerText = "Checking...";
        out.className = "text-white fw-bold";
        box.className = "p-3 rounded-3 border border-secondary border-opacity-25";

        const route = form.getAttribute('data-route');
        const formData = new FormData(form);

        axios.post(route, formData)
            .then(response => {
                const data = response.data;
                if (data.status === 'success') {
                    if (data.error) {
                        out.innerText = "INVALID REGEX: " + data.error;
                        out.className = "text-warning fw-bold";
                    } else if (data.match) {
                        out.innerText = "MATCH FOUND!";
                        out.className = "text-success fw-bold";
                        box.className = "p-3 rounded-3 border border-success border-opacity-50 settings-group";
                    } else {
                        out.innerText = "NO MATCH";
                        out.className = "text-danger fw-bold";
                        box.className = "p-3 rounded-3 border border-danger border-opacity-50 settings-group";
                    }
                    rosRule.innerText = data.script;
                }
            })
            .catch(error => {
                console.error(error);
                let msg = 'An error occurred.';
                if (error.response && error.response.data && error.response.data.message) {
                    msg = error.response.data.message;
                }
                out.innerText = "Error: " + msg;
                out.className = "text-danger fw-bold";
            })
            .finally(() => {
                // Restore UI
                if (btnLabel) btnLabel.classList.remove('d-none');
                if (btnLoader) btnLoader.classList.add('d-none');
                btn.disabled = false;
            });
    });
});
