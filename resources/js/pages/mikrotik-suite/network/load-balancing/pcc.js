// Imports
import { createWanRow } from '../../../../utils/dom.js';

document.addEventListener('DOMContentLoaded', function () {
    const wanCountSelect = document.getElementById('wanCount');
    const featureRatioCheck = document.getElementById('featureRatio');
    const featureFailoverCheck = document.getElementById('featureFailover');
    const wanContainer = document.getElementById('wanContainer');
    const pccForm = document.getElementById('pccForm');
    const scriptOutput = document.getElementById('scriptOutput');
    const btnReset = document.getElementById('btnReset');

    // Ensure CSRF Token
    const token = document.querySelector('meta[name="csrf-token"]');
    if (token) {
        window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
    }

    // Default configuration for generating fields
    generateWanInputs(wanCountSelect.value);

    const localTypeSelect = document.getElementById('localType');
    const localTargetInput = document.getElementById('localTarget');
    const localTargetLabel = document.getElementById('localTargetLabel');

    // Event Listeners
    wanCountSelect.addEventListener('input', (e) => {
        let val = parseInt(e.target.value);
        if (val >= 2 && val <= 50) {
            generateWanInputs(val);
        }
    });

    featureRatioCheck.addEventListener('change', toggleLogicFields);
    featureFailoverCheck.addEventListener('change', toggleLogicFields);

    btnReset.addEventListener('click', function () {
        pccForm.reset();
        generateWanInputs(2);
        scriptOutput.textContent = '# Generated routeros script will appear here...';
        updateLineNumbers('#');
        // Reset dynamic label
        localTypeSelect.dispatchEvent(new Event('change'));
    });

    // Dynamic Local Target Placeholder & Label
    localTypeSelect.addEventListener('change', function () {
        if (this.value === 'address-list') {
            localTargetInput.placeholder = 'e.g. 192.168.24.0/24';
            localTargetInput.value = '192.168.0.0/16, 172.16.0.0/12, 10.0.0.0/8';
            localTargetLabel.textContent = 'Local IP Target';
        } else if (this.value === 'interface') {
            localTargetInput.placeholder = 'e.g. bridge, ether5';
            localTargetInput.value = '';
            localTargetLabel.textContent = 'Interface Target';
        } else {
            // interface-list
            localTargetInput.placeholder = 'e.g. LOCAL-LIST';
            localTargetInput.value = '';
            localTargetLabel.textContent = 'Interface List Target';
        }
    });

    // SUBMIT HANDLER (Server-Side Generation)
    pccForm.addEventListener('submit', function (e) {
        e.preventDefault();

        const btn = pccForm.querySelector('button[type="submit"]');
        const originalText = btn.innerHTML;
        const route = pccForm.dataset.route;

        // UI Loading State
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Generating...';
        scriptOutput.style.opacity = '0.5';

        // --- CLIENT SIDE VALIDATION ---
        // Requirement: User MUST fill all generated columns.
        const wanCount = parseInt(wanCountSelect.value);
        let isValid = true;

        for (let i = 1; i <= wanCount; i++) {
            const ifaceInput = pccForm.querySelector(`[name="wan_interface_${i}"]`);
            const gwInput = pccForm.querySelector(`[name="wan_gateway_${i}"]`);

            if (!ifaceInput.value.trim()) {
                alert(`Please fill in the Interface for WAN ${i}`);
                ifaceInput.focus();
                isValid = false;
                break;
            }

            if (!gwInput.value.trim()) {
                alert(`Please fill in the Gateway IP/Interface for WAN ${i}`);
                gwInput.focus();
                isValid = false;
                break;
            }
        }

        if (!isValid) {
            // Revert UI State
            btn.disabled = false;
            btn.innerHTML = originalText;
            scriptOutput.style.opacity = '1';
            return; // Stop execution
        }

        const formData = new FormData(pccForm);

        axios.post(route, formData)
            .then(response => {
                if (response.data.status === 'success') {
                    const script = response.data.script;
                    // Apply Syntax Highlighting
                    scriptOutput.innerHTML = highlightScript(script);
                    updateLineNumbers(script);
                } else {
                    alert('Something went wrong, please try again.');
                }
            })
            .catch(error => {
                console.error(error);
                let msg = 'Failed to generate script. Please check your inputs.';
                if (error.response && error.response.data && error.response.data.message) {
                    msg = error.response.data.message;
                }
                alert(msg);
            })
            .finally(() => {
                btn.disabled = false;
                btn.innerHTML = originalText;
                scriptOutput.style.opacity = '1';
            });
    });

    // Helper: Dynamic WAN Input Fields
    function generateWanInputs(count) {
        wanContainer.innerHTML = '';
        const useRatio = featureRatioCheck.checked;
        const useFailover = featureFailoverCheck.checked;

        for (let i = 1; i <= count; i++) {
            // Use Factory
            const html = createWanRow(i, {
                useRatio: useRatio,
                useFailover: useFailover,
                ratioName: 'wan_speed', // PCC uses 'wan_speed'
                ratioLabel: 'Mbps'
            });
            wanContainer.insertAdjacentHTML('beforeend', html);
        }
    }

    function toggleLogicFields() {
        const useRatio = featureRatioCheck.checked;
        const useFailover = featureFailoverCheck.checked;

        document.querySelectorAll('.ratio-field').forEach(el => {
            el.classList.toggle('d-none', !useRatio);
        });

        document.querySelectorAll('.failover-field').forEach(el => {
            el.classList.toggle('d-none', !useFailover);
        });
    }

    function updateLineNumbers(text) {
        const lines = text.split('\n').length;
        const lineNumbersInfo = document.getElementById('lineNumbers');
        if (lineNumbersInfo) {
            lineNumbersInfo.innerHTML = Array(lines).fill(0).map((_, i) => i + 1).join('<br>');
        }
    }

    // --- SYNTAX HIGHLIGHTER ---
    function highlightScript(text) {
        // Safely escape HTML first
        let html = text.replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;");

        // Split text by lines to process each line safely
        return html.split('\n').map(line => {
            // 1. Comments
            if (line.trim().startsWith('#')) {
                return `<span class="token-comment">${line}</span>`;
            }

            // 2. Tokenize using placeholders
            let processed = line;

            // STRINGS (quoted)
            processed = processed.replace(/"(.*?)"/g, '§STR§"$1"§END§');

            // IP ADDRESSES
            processed = processed.replace(/\b(\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}(\/\d{1,2})?)\b/g, '§NUM§$1§END§');

            // KEYWORDS (RouterOS commands)
            const keywords = ['add', 'set', 'remove', 'enable', 'disable', 'print', 'export', 'find', 'get'];
            keywords.forEach(kw => {
                const regex = new RegExp(`\\b${kw}\\b`, 'g');
                processed = processed.replace(regex, `§KEY§${kw}§END§`);
            });

            // CONTEXT PATHS (e.g., /ip firewall)
            processed = processed.replace(/(\/[\w-]+(\s+[\w-]+)*)/g, '§KC§$1§END§');

            // PROPERTY KEYS (key=)
            processed = processed.replace(/\b([a-z0-9-]+)=/g, '§CMD§$1§END§=');

            // --- RESTORE PLACEHOLDERS ---
            processed = processed
                .replace(/§STR§(.*?)§END§/g, '<span class="token-string">$1</span>')
                .replace(/§NUM§(.*?)§END§/g, '<span class="token-number">$1</span>')
                .replace(/§KC§(.*?)§END§/g, '<span class="token-keyword">$1</span>')
                .replace(/§KEY§(.*?)§END§/g, '<span class="token-keyword">$1</span>')
                .replace(/§CMD§(.*?)§END§/g, '<span class="token-command">$1</span>');

            return processed;
        }).join('\n');
    }

    function updateLineNumbers(text) {
        const lines = text.split('\n').length;
        const lineNumbersInfo = document.getElementById('lineNumbers');
        if (lineNumbersInfo) {
            lineNumbersInfo.innerHTML = Array(lines).fill(0).map((_, i) => i + 1).join('<br>');
        }
    }
});

