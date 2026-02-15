document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('hsgForm');
    const scriptOutput = document.getElementById('scriptOutput');
    const csvOutput = document.getElementById('csvOutput');
    const cardsContainer = document.getElementById('cardsContainer');

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

        // Clear previous outputs
        scriptOutput.textContent = '// Generating...';
        csvOutput.textContent = 'Generating...';
        cardsContainer.innerHTML = `
             <div class="col-12 text-center text-muted py-5">
                <span class="spinner-border text-brand" role="status"></span>
                <p class="mt-2 text-muted small">Processing request...</p>
            </div>
        `;

        // Gather Data
        const formData = new FormData(form);
        const route = form.getAttribute('data-route');

        axios.post(route, formData)
            .then(response => {
                const data = response.data;

                if (data.status === 'success') {
                    // Update Outputs
                    scriptOutput.textContent = data.script;
                    csvOutput.textContent = data.csv;

                    // Update Cards
                    let cardsHtml = '';
                    if (data.users && data.users.length > 0) {
                        data.users.forEach(u => {
                            cardsHtml += `
                                <div class="col-md-4 col-sm-6 animate__animated animate__fadeIn">
                                    <div class="p-3 bg-white text-dark rounded-3 shadow-sm border border-light">
                                        <div class="d-flex justify-content-between border-bottom pb-2 mb-2">
                                             <span class="fw-bold small text-uppercase">Voucher</span>
                                             <span class="badge bg-dark">${u.limit || 'Unlimited'}</span>
                                        </div>
                                        <div class="mb-1">
                                            <span class="d-block text-muted small">Username</span>
                                            <span class="fw-bold font-monospace">${u.user}</span>
                                        </div>
                                         <div>
                                            <span class="d-block text-muted small">Password</span>
                                            <span class="fw-bold font-monospace">${u.pass}</span>
                                        </div>
                                    </div>
                                </div>
                            `;
                        });
                    } else {
                        cardsHtml = `
                             <div class="col-12 text-center text-muted py-5">
                                <i class="bi bi-exclamation-circle fs-1 d-block mb-3 opacity-25"></i>
                                No users returned.
                            </div>
                        `;
                    }
                    cardsContainer.innerHTML = cardsHtml;
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
                csvOutput.textContent = 'Error: ' + msg;
                cardsContainer.innerHTML = `
                     <div class="col-12 text-center text-danger py-5">
                        <i class="bi bi-x-circle fs-1 d-block mb-3"></i>
                        ${msg}
                    </div>
                `;
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
