document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('pppoeForm');
    const scriptOutput = document.getElementById('scriptOutput');
    const csvOutput = document.getElementById('csvOutput');
    const listBody = document.getElementById('listBody');

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

        const route = form.getAttribute('data-route');
        const formData = new FormData(form);

        axios.post(route, formData)
            .then(response => {
                const data = response.data;
                if (data.status === 'success') {
                    scriptOutput.textContent = data.script;
                    csvOutput.textContent = data.csv;

                    // Render List
                    let listHtml = '';
                    if (data.data && data.data.length > 0) {
                        data.data.forEach(user => {
                            listHtml += `
                                <tr>
                                    <td class="ps-4 text-white">${user.username}</td>
                                    <td class="text-secondary">${user.password}</td>
                                    <td class="text-info">${user.profile}</td>
                                    <td class="text-warning">${user.remote_address}</td>
                                </tr>
                            `;
                        });
                    } else {
                        listHtml += `<tr><td colspan="4" class="text-center p-4 text-muted">No data</td></tr>`;
                    }
                    listBody.innerHTML = listHtml;
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
            // Optional feedback
            console.log('Copied');
        }).catch(err => console.error(err));
    }
});
