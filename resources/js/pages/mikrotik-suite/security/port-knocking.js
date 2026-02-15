import $ from 'jquery';

/**
 * Port Knocking Generator Logic
 */
const PortKnockingGenerator = {
    init() {
        this.bindEvents();
    },

    bindEvents() {
        const knockModeSelect = document.getElementById('knockMode');
        if (knockModeSelect) {
            knockModeSelect.addEventListener('change', () => this.toggleMethod());
        }

        const generateBtn = document.querySelector('button[onclick*="generatePortKnocking"]');
        if (generateBtn) {
            // Remove inline handler and add event listener if we can identify it, 
            // but the blade has inline parameter (route). 
            // Better strategy: Expose simpler function or attach listener data-route.
        }
    },

    toggleMethod() {
        const mode = document.getElementById('knockMode').value;
        const icmpSection = document.getElementById('method-icmp');
        const portSection = document.getElementById('method-port');

        if (icmpSection) icmpSection.classList.toggle('d-none', mode !== 'icmp');
        if (portSection) portSection.classList.toggle('d-none', mode === 'icmp');
    },

    generate(url, formId = 'pkForm') {
        const form = document.getElementById(formId);
        if (!form || !form.checkValidity()) {
            if (form) form.reportValidity();
            return;
        }

        const btn = form.querySelector('button[type="button"]');
        const originalBtnText = btn ? btn.innerHTML : '';

        if (btn) {
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Generating...';
        }

        const outputDiv = document.getElementById('scriptOutput');
        outputDiv.innerHTML = '<div class="d-flex align-items-center justify-content-center p-4"><div class="spinner-border spinner-border-sm text-primary"></div> <span class="ms-2">Generating secure script...</span></div>';

        axios.post(url, new FormData(form))
            .then(response => {
                outputDiv.innerHTML = `<pre class="m-0 p-4 text-warning font-monospace small" style="white-space: pre-wrap;">${response.data.script}</pre>`;
            })
            .catch(error => {
                const msg = error.response?.data?.message || "Failed to generate script.";
                outputDiv.innerHTML = `<span class="text-danger"># Error: ${msg}</span>`;
            })
            .finally(() => {
                if (btn) {
                    btn.disabled = false;
                    btn.innerHTML = originalBtnText;
                }
            });
    }
};

// Expose to window for inline onclick compatibility if needed, 
// OR better: init on load.
// For now, let's keep it clean: Expose global for the blade transition, or rewrite blade.
// Rewriting blade to use data-route is better.

document.addEventListener('DOMContentLoaded', () => {
    // Check if we are on the port-knocking page
    if (document.getElementById('pkForm')) {
        PortKnockingGenerator.init();

        // Expose toggle globally if blade still uses inline onchange (we will fix blade too)
        window.toggleMethod = PortKnockingGenerator.toggleMethod.bind(PortKnockingGenerator);

        // Attach click handler to button
        const btn = document.querySelector('#pkForm button[type="button"]');
        if (btn) {
            btn.removeAttribute('onclick'); // parsing inline
            btn.addEventListener('click', function () {
                const route = this.getAttribute('data-action');
                // We need to pass the route from blade
                PortKnockingGenerator.generate(route);
            });
        }
    }
});
