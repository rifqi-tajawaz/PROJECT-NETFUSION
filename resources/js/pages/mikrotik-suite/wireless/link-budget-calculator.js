document.addEventListener('DOMContentLoaded', () => {
    // CSRF for Axios
    if (window.axios) {
        const token = document.querySelector('meta[name="csrf-token"]');
        if (token) {
            window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
        }
    }

    const form = document.getElementById('calcForm');

    // Attach listener to inputs for manual adjustments?
    // Original script had auto-calc on input "document.querySelectorAll('.input-param').forEach(el => el.addEventListener('input', calculate));"
    // Since we are moving to server-side, auto-calc purely on input might be spammy for network requests.
    // Let's stick to Submit button for calculation to be clean, or denounce it.
    // For now, let's just make the user click "Calculate".

    form.addEventListener('submit', function (e) {
        e.preventDefault();
        calculate();
    });

    // Initial calculation on load? Maybe not needed for server-side logic unless we want to show default values immediately.
    // Let's hold off auto-calc on load to save a request, user can click calculate.
    // Actually, to keep consistency with previous behavior (if it was auto), we might want to, but default values are placeholders.
    // Let's trigger one calc on load so the visualizer isn't empty.
    calculate();
});

function calculate() {
    const form = document.getElementById('calcForm');
    const route = form.getAttribute('data-route');

    // UI Loading
    const btn = form.querySelector('button[type="submit"]');
    const btnLabel = btn.querySelector('.btn-label');
    const btnLoader = btn.querySelector('.btn-loader');

    if (btnLabel) btnLabel.classList.add('d-none');
    if (btnLoader) btnLoader.classList.remove('d-none');
    btn.disabled = true;

    const formData = new FormData(form);

    axios.post(route, formData)
        .then(response => {
            const data = response.data;
            if (data.status === 'success') {
                document.getElementById('resFSPL').innerText = data.fspl + ' dB';
                document.getElementById('resRSL').innerText = data.rsl;
                document.getElementById('resMargin').innerText = (data.margin > 0 ? '+' : '') + data.margin + ' dB';
                document.getElementById('resF1').innerText = data.fresnel_radius_60 + ' m';

                updateGauge(data.rsl, data.margin);

                // Get distance from input and fresnel from response for visual
                // Note: inputs might have changed since request if async is slow, 
                // but usually user waits. We can use formData values or current input values.
                const d = parseFloat(document.getElementById('dist').value) || 5;
                drawVisual(d, data.fresnel_radius_60);
            }
        })
        .catch(error => {
            console.error(error);
        })
        .finally(() => {
            if (btnLabel) btnLabel.classList.remove('d-none');
            if (btnLoader) btnLoader.classList.add('d-none');
            btn.disabled = false;
        });
}

function updateGauge(rsl, margin) {
    // Range -90 to -40
    const min = -90;
    const max = -40;
    let pct = (rsl - min) / (max - min);
    if (pct < 0) pct = 0; if (pct > 1) pct = 1;

    // SVG Arc: Length of semi-circle radius 40 = 40 * PI = 125.66
    const totalLen = 126;
    const val = totalLen * pct;

    const path = document.getElementById('signalArc');
    path.style.strokeDasharray = `${val} ${totalLen}`;

    // Color
    let color = '#dc3545'; // red
    let text = 'Weak / Down';
    let bg = 'bg-danger';

    if (margin > 20) { color = '#198754'; text = 'Excellent'; bg = 'bg-success'; }
    else if (margin > 10) { color = '#0dcaf0'; text = 'Good'; bg = 'bg-info text-dark'; }
    else if (margin > 0) { color = '#ffc107'; text = 'Marginal'; bg = 'bg-warning text-dark'; }

    path.setAttribute('stroke', color);
    const badge = document.getElementById('verdictBadge');
    badge.className = `badge ${bg} px-4 py-2 rounded-pill fs-6`;
    badge.innerText = text;
}

function drawVisual(dist, f1) {
    const container = document.getElementById('diagram-container');
    const w = container.clientWidth || 800;
    const h = container.clientHeight || 250;

    let svg = `<svg width="100%" height="100%" viewBox="0 0 ${w} ${h}">`;

    // Ground
    svg += `<line x1="0" y1="${h - 20}" x2="${w}" y2="${h - 20}" stroke="#495057" stroke-width="2"/>`;

    // Towers
    const p = 50;
    const tH = 100;
    const tY = h - 20 - tH;

    // A
    svg += `<line x1="${p}" y1="${h - 20}" x2="${p}" y2="${tY}" stroke="#adb5bd" stroke-width="4"/>`;
    svg += `<circle cx="${p}" cy="${tY}" r="4" fill="#0d6efd"/>`;

    // B
    svg += `<line x1="${w - p}" y1="${h - 20}" x2="${w - p}" y2="${tY}" stroke="#adb5bd" stroke-width="4"/>`;
    svg += `<circle cx="${w - p}" cy="${tY}" r="4" fill="#0d6efd"/>`;

    // LoS
    svg += `<line x1="${p}" y1="${tY}" x2="${w - p}" y2="${tY}" stroke="#ffc107" stroke-width="2" stroke-dasharray="5,5"/>`;

    // Fresnel
    const cx = w / 2;
    const cy = tY;
    const rx = (w - 2 * p) / 2;
    // Scale for visual: f1 is in meters, typical dist is km. 
    // Just scaling f1 (m) * X to look like ellipse on screen is tricky without scale.
    // Original JS used `f1 * 5` clamped to 80. Let's stick to that.
    const ry = Math.min(f1 * 5, 80);

    svg += `<ellipse cx="${cx}" cy="${cy}" rx="${rx}" ry="${ry}" fill="rgba(13, 202, 240, 0.1)" stroke="#0dcaf0" stroke-width="1"/>`;

    svg += `</svg>`;
    container.innerHTML = svg;
}
