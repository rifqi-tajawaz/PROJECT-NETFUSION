document.addEventListener('DOMContentLoaded', () => {
    // CSRF for Axios
    if (window.axios) {
        const token = document.querySelector('meta[name="csrf-token"]');
        if (token) {
            window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
        }
    }

    const form = document.getElementById('scriptForm');

    // Auto-generation on input change logic removed in favor of manual generation button
    // because server round-trip on every keystroke is inefficient for this simple tool.
    // However, if we want to keep "instant feel", we could debounce it. 
    // The previous implementation had a "Generate Script" button added in Blade, let's use that.

    form.addEventListener('submit', function (e) {
        e.preventDefault();

        // UI State: Loading
        const btn = form.querySelector('button[type="submit"]');
        const btnLabel = btn.querySelector('.btn-label');
        const btnLoader = btn.querySelector('.btn-loader');

        if (btnLabel) btnLabel.classList.add('d-none');
        if (btnLoader) btnLoader.classList.remove('d-none');
        btn.disabled = true;

        const route = form.getAttribute('data-route');
        const formData = new FormData(form);

        axios.post(route, formData)
            .then(response => {
                const data = response.data;
                if (data.status === 'success') {
                    document.getElementById('outputScript').innerText = data.script;
                }
            })
            .catch(error => {
                console.error(error);
                document.getElementById('outputScript').innerText = "// Error generating script";
            })
            .finally(() => {
                // Restore UI
                if (btnLabel) btnLabel.classList.remove('d-none');
                if (btnLoader) btnLoader.classList.add('d-none');
                btn.disabled = false;
            });
    });
});
