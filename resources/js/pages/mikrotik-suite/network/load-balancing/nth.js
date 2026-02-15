// Imports
import { createWanRow } from '../../../../utils/dom.js';

document.addEventListener('DOMContentLoaded', function () {
    const wanCountSelect = document.getElementById('wanCount');
    const featureRatioCheck = document.getElementById('featureRatio');
    const featureFailoverCheck = document.getElementById('featureFailover');
    const wanContainer = document.getElementById('wanContainer');
    const form = document.getElementById('nthForm'); // nth.js uses 'form' var
    const scriptOutput = document.getElementById('scriptOutput');
    const localTypeSelect = document.getElementById('localType');
    const localTargetInput = document.getElementById('localTarget');
    const localTargetLabel = document.getElementById('localTargetLabel');
    const rosVersionSelect = document.getElementById('rosVersion');

    const btnReset = document.getElementById('btnReset');

    // Ensure CSRF Token
    const token = document.querySelector('meta[name="csrf-token"]');
    if (token) {
        window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
    }

    // Default Init
    generateWanInputs(wanCountSelect.value);
    updateLocalTargetUI();
    toggleLogicFields();

    // Event Listeners
    wanCountSelect.addEventListener('input', (e) => {
        let val = parseInt(e.target.value);
        if (val >= 2 && val <= 50) {
            generateWanInputs(val);
        }
    });

    featureRatioCheck.addEventListener('change', toggleLogicFields);
    featureFailoverCheck.addEventListener('change', toggleLogicFields);
    if (btnReset) btnReset.addEventListener('click', resetForm);

    if (localTypeSelect) {
        localTypeSelect.addEventListener('change', updateLocalTargetUI);
    }

    form.addEventListener('submit', function (e) {
        e.preventDefault();

        const btn = form.querySelector('button[type="submit"]');
        const originalText = btn.innerHTML;
        const route = form.dataset.route;

        // UI Loading State
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Generating...';
        scriptOutput.style.opacity = '0.5';

        // --- CLIENT SIDE VALIDATION ---
        const wanCount = parseInt(wanCountSelect.value);
        let isValid = true;

        for (let i = 1; i <= wanCount; i++) {
            const ifaceInput = form.querySelector(`[name="wan_interface_${i}"]`);
            const gwInput = form.querySelector(`[name="wan_gateway_${i}"]`);

            if (!ifaceInput.value.trim()) {
                alert(`Please fill in the Interface for WAN ${i}`);
                ifaceInput.focus();
                isValid = false;
                break;
            }

            if (!gwInput.value.trim()) {
                alert(`Please fill in the Gateway for WAN ${i}`);
                gwInput.focus();
                isValid = false;
                break;
            }
        }

        if (!isValid) {
            btn.disabled = false;
            btn.innerHTML = originalText;
            scriptOutput.style.opacity = '1';
            return;
        }

        const formData = new FormData(form);

        axios.post(route, formData)
            .then(response => {
                if (response.data.status === 'success') {
                    const script = response.data.script;
                    scriptOutput.innerHTML = highlightScript(script);
                    updateLineNumbers(script);
                } else {
                    alert('Something went wrong, please try again.');
                }
            })
            .catch(error => {
                console.error(error);
                let msg = 'Failed to generate script.';
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

    // --- Core Functions ---

    function generateWanInputs(count) {
        wanContainer.innerHTML = '';
        const useRatio = featureRatioCheck.checked;
        const useFailover = featureFailoverCheck.checked;

        for (let i = 1; i <= count; i++) {
            // Use Factory
            const html = createWanRow(i, {
                useRatio: useRatio,
                useFailover: useFailover,
                ratioName: 'wan_weight', // NTH uses wan_weight
                ratioLabel: 'Seq' // Or just let default if appropriate, but NTH usually implies sequence/weight
            });
            wanContainer.insertAdjacentHTML('beforeend', html);
        }
        // Ensure visibility is correct after re-render
        toggleLogicFields();
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

        // Show/Hide Headers too (Optional, but good for cleanliness)
        const thWeight = document.querySelector('th:nth-child(3)');
        const thFailover = document.querySelector('th:nth-child(4)');
        if (thWeight) thWeight.classList.toggle('d-none', !useRatio);
        if (thFailover) thFailover.classList.toggle('d-none', !useFailover);
    }

    function resetForm() {
        form.reset();
        generateWanInputs(2);
        updateLocalTargetUI();
        toggleLogicFields();
        scriptOutput.innerText = '# Generated routeros script will appear here...';
        updateLineNumbers('#');
    }


    function updateLocalTargetUI() {
        const type = localTypeSelect.value;
        if (type === 'address-list') {
            localTargetLabel.innerText = 'Local IP Target';
            localTargetInput.placeholder = 'e.g. 192.168.24.0/24';
            localTargetInput.value = '192.168.0.0/16, 172.16.0.0/12, 10.0.0.0/8';
        } else if (type === 'interface') {
            localTargetLabel.innerText = 'Interface Target';
            localTargetInput.placeholder = 'e.g. bridge, ether5';
            localTargetInput.value = '';
        } else {
            localTargetLabel.innerText = 'Interface List Target';
            localTargetInput.placeholder = 'e.g. LOCAL-LIST';
            localTargetInput.value = '';
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

// Reuse global copy

