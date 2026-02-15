document.addEventListener('DOMContentLoaded', () => {
    // CSRF for Axios
    if (window.axios) {
        const token = document.querySelector('meta[name="csrf-token"]');
        if (token) {
            window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
        }
    }

    const bandSelect = document.getElementById('bandSelect');
    const superCheck = document.getElementById('superchannelcheck');
    const generateListBtn = document.getElementById('generateListBtn');
    const selectAllBtn = document.getElementById('selectAllBtn');
    const selectNoneBtn = document.getElementById('selectNoneBtn');
    const generateScriptBtn = document.getElementById('generateScriptBtn');
    const copyBtn = document.getElementById('copyBtn');

    if (bandSelect) bandSelect.addEventListener('change', updateRange);
    if (superCheck) superCheck.addEventListener('change', updateRange);
    if (generateListBtn) generateListBtn.addEventListener('click', generateChannels);

    if (selectAllBtn) selectAllBtn.addEventListener('click', () => selectAll(true));
    if (selectNoneBtn) selectNoneBtn.addEventListener('click', () => selectAll(false));

    if (generateScriptBtn) generateScriptBtn.addEventListener('click', generateScriptFromServer);
    if (copyBtn) copyBtn.addEventListener('click', copyScript);

    // Initial Range Set
    updateRange();
});

const BANDS = {
    '5ghz': { start: 5180, end: 5825 },
    '2.4ghz': { start: 2412, end: 2462 }
};
const SUPER_5GHZ = { start: 4920, end: 6100 };
const SUPER_24GHZ = { start: 2312, end: 2732 };

function updateRange() {
    const mode = document.getElementById('bandSelect').value;
    const isSuper = document.getElementById('superchannelcheck').checked;

    if (mode === 'custom') return;

    let start = BANDS['5ghz'].start;
    let end = BANDS['5ghz'].end;
    let step = 20;

    if (mode === '2.4ghz') {
        start = BANDS['2.4ghz'].start;
        end = BANDS['2.4ghz'].end;
        step = 5;
        if (isSuper) {
            start = SUPER_24GHZ.start;
            end = SUPER_24GHZ.end;
        }
    } else {
        if (isSuper) {
            start = SUPER_5GHZ.start;
            end = SUPER_5GHZ.end;
        }
    }

    document.getElementById('startFreq').value = start;
    document.getElementById('endFreq').value = end;
    document.getElementById('stepSelect').value = step;
}

function generateChannels() {
    const start = parseInt(document.getElementById('startFreq').value);
    const end = parseInt(document.getElementById('endFreq').value);
    const step = parseInt(document.getElementById('stepSelect').value);
    const container = document.getElementById('channelContainer');

    container.innerHTML = '';

    if (start >= end) return;

    for (let f = start; f <= end; f += step) {
        const btn = document.createElement('input');
        btn.type = 'checkbox';
        btn.className = 'btn-check channel-check';
        btn.id = `ch-${f}`;
        btn.value = f;
        btn.checked = true;
        // Previously we updated script instantly on change. 
        // Now valid script generation is server-side, so we might just clear output or do nothing until "Create Script" is clicked.
        // Let's invalid the script output on change to encourage re-generation.
        btn.addEventListener('change', () => {
            document.getElementById('scriptOutput').innerText = "// Selection changed. Click 'Create Script' to update.";
        });

        const lbl = document.createElement('label');
        lbl.className = 'btn btn-outline-info btn-sm rounded-pill font-monospace';
        lbl.htmlFor = `ch-${f}`;
        lbl.innerText = f;

        container.appendChild(btn);
        container.appendChild(lbl);
    }

    document.getElementById('scriptOutput').innerText = "// Channels generated. Select desired frequencies and click 'Create Script'.";
}

function selectAll(val) {
    document.querySelectorAll('.channel-check').forEach(c => c.checked = val);
    document.getElementById('scriptOutput').innerText = "// Selection changed. Click 'Create Script' to update.";
}

function generateScriptFromServer() {
    const form = document.getElementById('lockpackForm');
    const checkedBoxes = document.querySelectorAll('.channel-check:checked');

    if (checkedBoxes.length === 0) {
        document.getElementById('scriptOutput').innerText = "# No channels selected";
        return;
    }

    const frequencies = Array.from(checkedBoxes).map(c => c.value).join(',');
    const iface = document.getElementById('ifaceName').value;
    const route = form.getAttribute('data-route');

    // UI Loading
    const btn = document.getElementById('generateScriptBtn');
    const btnLabel = btn.querySelector('.btn-label');
    const btnLoader = btn.querySelector('.btn-loader');

    if (btnLabel) btnLabel.classList.add('d-none');
    if (btnLoader) btnLoader.classList.remove('d-none');
    btn.disabled = true;

    axios.post(route, {
        interface: iface,
        frequencies: frequencies
    })
        .then(response => {
            if (response.data.status === 'success') {
                document.getElementById('scriptOutput').innerText = response.data.script;
            } else {
                document.getElementById('scriptOutput').innerText = "# Error: " + response.data.message;
            }
        })
        .catch(error => {
            console.error(error);
            document.getElementById('scriptOutput').innerText = "# Server Error";
        })
        .finally(() => {
            if (btnLabel) btnLabel.classList.remove('d-none');
            if (btnLoader) btnLoader.classList.add('d-none');
            btn.disabled = false;
        });
}

function copyScript() {
    const text = document.getElementById('scriptOutput').innerText;
    navigator.clipboard.writeText(text).then(() => alert('Copied!'));
}
