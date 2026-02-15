import axios from 'axios';

document.addEventListener('DOMContentLoaded', function () {
    console.log('Wireless Link Planner Initialized');

    // Initialize form from local storage if utilized
    loadConfig();

    // Device Database (Name, max_tx (dBm), gain (dBi), band (GHz))
    // Derived from reference code
    const devices = [
        { id: 'Groove06', name: 'Groove/Metal + 6dBi', tx: 27, gain: 6, band: '2/5' },
        { id: 'Groove09', name: 'Groove/Metal + 9dBi', tx: 27, gain: 9, band: '5' },
        { id: 'DB1258', name: 'DuxBase 120 (5GHz)', tx: 27, gain: 17, band: '5' },
        { id: 'mANTbox15S', name: 'mANTbox 15s', tx: 30, gain: 15, band: '5' },
        { id: 'RBSXT', name: 'SXT 5 (16dBi)', tx: 30, gain: 16, band: '5' },
        { id: 'RBSXTsq5', name: 'SXTsq 5 (16dBi)', tx: 28, gain: 16, band: '5' },
        { id: 'RBSXTLite5', name: 'SXT Lite5', tx: 27, gain: 16, band: '5' },
        { id: 'RBSXT5ac', name: 'SXT 5 ac', tx: 30, gain: 16, band: '5' },
        { id: 'RBLHG5', name: 'LHG 5', tx: 25, gain: 24.5, band: '5' },
        { id: 'RBLHG5ac', name: 'LHG 5 ac', tx: 25, gain: 27, band: '5' },
        { id: 'QRT5', name: 'QRT 5', tx: 30, gain: 24, band: '5' },
        { id: 'DynaDish', name: 'DynaDish 5', tx: 30, gain: 25, band: '5' },
        { id: 'OmniTik', name: 'OmniTik 5', tx: 30, gain: 7.5, band: '5' },
        { id: 'DuxLinkDuo', name: 'DuxLink Duo', tx: 30, gain: 25, band: '5' },
        { id: 'DuxLinkLongHaul', name: 'DuxLink LongHaul', tx: 30, gain: 30, band: '5' }
    ];

    // Populate dropdowns
    const dropdowns = document.querySelectorAll('.device-preset');
    dropdowns.forEach(dd => {
        devices.forEach(dev => {
            const opt = document.createElement('option');
            opt.value = dev.id;
            opt.textContent = dev.name;
            dd.appendChild(opt);
        });

        // Add change listener
        dd.addEventListener('change', function () {
            const targetPrefix = this.dataset.target; // site_a or site_b
            const selectedId = this.value;
            const device = devices.find(d => d.id === selectedId);

            if (device && targetPrefix) {
                const gainInput = document.getElementById(`${targetPrefix}_ant_gain`);
                const txInput = document.getElementById(`${targetPrefix}_tx_power`);

                if (gainInput) gainInput.value = device.gain;
                if (txInput) txInput.value = device.tx;

                // If it's a 5GHz only device, maybe suggest frequency? 
                // For now, just update gain/power as these are the tedious ones.

                saveConfig(); // Auto-save
            }
        });
    });

    // Event listeners
    const form = document.getElementById('link-planner-form');
    if (form) {
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            calculateLink();
        });
    }

    // Input change listeners for real-time validation or auto-save
    const inputs = form.querySelectorAll('input, select');
    inputs.forEach(input => {
        // For standard inputs, trigger on 'change' (blur) to save/calc
        if (!input.id.includes('_lat') && !input.id.includes('_lon')) {
            input.addEventListener('change', saveConfig);
        }
    });

    // Special listeners for Lat/Lon to update distance in real-time (on typing)
    const geoInputs = ['site_a_lat', 'site_a_lon', 'site_b_lat', 'site_b_lon'];
    geoInputs.forEach(id => {
        const el = document.getElementById(id);
        if (el) {
            el.addEventListener('input', () => {
                updateDistance(); // Updates distance field
            });
            // We'll rely on the distance field's change event (triggered manually below) 
            // to fire the debounced calculation
        }
    });

    // Initial calc if data present
    if (document.getElementById('distance').value) {
        debouncedCalculate();
    }
});

// Utility to safely get element value
function getElementValue(id) {
    const el = document.getElementById(id);
    return el ? el.value : 0;
}

// Debounce Utility
function debounce(func, wait) {
    let timeout;
    return function (...args) {
        const context = this;
        clearTimeout(timeout);
        timeout = setTimeout(() => func.apply(context, args), wait);
    };
}

// Debounced Calculation Function
const debouncedCalculate = debounce(() => {
    // Only calculate if form is effectively valid (at least distance exists)
    const dist = document.getElementById('distance').value;
    if (dist && parseFloat(dist) > 0) {
        calculateLink(true); // true = silent mode (no full spinner overlay if possible)
    }
}, 800); // 800ms delay

function updateDistance() {
    const lat1 = parseFloat(document.getElementById('site_a_lat').value);
    const lon1 = parseFloat(document.getElementById('site_a_lon').value);
    const lat2 = parseFloat(document.getElementById('site_b_lat').value);
    const lon2 = parseFloat(document.getElementById('site_b_lon').value);

    // Only calculate if all 4 are valid numbers
    if (!isNaN(lat1) && !isNaN(lon1) && !isNaN(lat2) && !isNaN(lon2)) {
        const R = 6371; // Earth radius in km
        const dLat = (lat2 - lat1) * Math.PI / 180;
        const dLon = (lon2 - lon1) * Math.PI / 180;
        const a =
            Math.sin(dLat / 2) * Math.sin(dLat / 2) +
            Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
            Math.sin(dLon / 2) * Math.sin(dLon / 2);
        const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
        const d = R * c; // Distance in km

        // Update distance input (formatted to 3 decimals)
        const distInput = document.getElementById('distance');
        if (distInput) {
            const oldVal = distInput.value;
            const newVal = d.toFixed(3);

            if (oldVal !== newVal) {
                distInput.value = newVal;
                // Trigger change event to save config
                distInput.dispatchEvent(new Event('change'));

                // Trigger calculation
                debouncedCalculate();
            }
        }
    }
}

function calculateLink(silent = false) {
    const form = document.getElementById('link-planner-form');
    // Aggressively hide global loader if it somehow triggered
    const globalLoader = document.getElementById('global-loader');
    if (globalLoader) globalLoader.style.display = 'none';

    if (!form.checkValidity()) {
        form.classList.add('was-validated');
        return;
    }

    const formData = new FormData(form);
    const submitBtn = form.querySelector('button[type="submit"]');

    // Disable button to prevent double submit
    const originalText = submitBtn.innerHTML;
    submitBtn.disabled = true;

    // Only show spinner text if NOT silent (manual click)
    // If silent (auto-calc), we keep button plain but disabled to show 'working' state minimally
    // or we can add a small indicator elsewhere. For now, let's just show '...'
    if (!silent) {
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Calculating...';
    } else {
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Auto-Sync...';
    }

    const route = form.dataset.route || '/mikrotik-suite/wireless/link-planner/calculate'; // Fallback

    axios.post(route, formData, {
        timeout: 10000 // 10 seconds timeout
    })
        .then(response => {
            console.log('Calculation Result:', response.data);
            renderResults(response.data);
        })

        .catch(error => {
            console.error('Calculation Error:', error);
            if (error.code === 'ECONNABORTED') {
                alert('Request timed out. Please check your connection or try again.');
            } else if (error.response && error.response.data && error.response.data.errors) {
                let errorMsg = 'Validation Error:\n';
                Object.values(error.response.data.errors).forEach(err => {
                    errorMsg += '- ' + err[0] + '\n';
                });
                alert(errorMsg);
            } else if (error.response && error.response.data && error.response.data.message) {
                alert('Output Error: ' + error.response.data.message);
            } else {
                // Show the actual JS error message if available
                alert('An error occurred: ' + (error.message || 'Check input values.'));
            }
        })
        .finally(() => {
            // Restore button
            if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            }
            // Ensure global loader is hidden
            if (globalLoader) globalLoader.style.display = 'none';
        });
}

function renderResults(data) {
    // Show container, hide placeholder
    document.getElementById('results-placeholder').style.display = 'none';
    const container = document.getElementById('results-container');
    container.style.display = 'block';

    // 1. Hero Section
    // 1. Hero Section (Updated for new UI)
    const qText = document.getElementById('quality-text');
    const qStatus = document.getElementById('quality-status');

    if (qText && qStatus) {
        qText.textContent = data.quality.text;
        qStatus.textContent = data.quality.status.toUpperCase();

        // Update colors
        // Reset classes first
        qText.className = 'mb-0 fw-bold';
        qStatus.className = 'badge mt-1';

        // Add specific color classes
        qText.classList.add('text-' + data.quality.color);
        qStatus.classList.add('bg-' + data.quality.color);
        qStatus.classList.add('text-white');
    }

    // 2. Metrics Cards
    document.getElementById('val-rss').textContent = data.rss;
    document.getElementById('val-throughput').textContent = data.throughput.tcp;
    document.getElementById('val-fade').textContent = data.fade_margin;
    document.getElementById('val-fresnel').textContent = data.fresnel_60;

    // 3. Detailed Table
    document.getElementById('val-phy').textContent = data.throughput.phy + ' Mbps';
    document.getElementById('val-udp').textContent = data.throughput.udp + ' Mbps';
    document.getElementById('val-fspl').textContent = data.fspl + ' dB';
    document.getElementById('val-fresnel-full').textContent = data.fresnel_radius + ' m';
    document.getElementById('val-rain-loss').textContent = data.rain_loss + ' dB';
    document.getElementById('val-rss-rain').textContent = data.rss_rain + ' dBm';

    // Calculate EIRP (Tx + Gain - Loss)
    const txA = parseFloat(getElementValue('site_a_tx_power')) || 0;
    const gainA = parseFloat(getElementValue('site_a_ant_gain')) || 0;
    const lossA = parseFloat(getElementValue('site_a_cable_loss')) || 0;
    const eirpA = (txA + gainA - lossA).toFixed(1);

    document.getElementById('val-eirp').innerHTML = eirpA > 30
        ? `<span class="text-danger fw-bold" title="Exceeds 30dBm">${eirpA} <i class="bi bi-exclamation-triangle-fill"></i></span> dBm`
        : `${eirpA} dBm`;

    // 4. Recommendations
    const recList = document.getElementById('recommendations-list');
    recList.innerHTML = ''; // clear previous

    let recs = [];
    if (data.fade_margin < 10) {
        recs.push({ icon: 'bi-arrow-up-circle', color: 'danger', text: window.wirelessLang.rec_low_fade });
    }
    if (data.rss < -75) {
        recs.push({ icon: 'bi-wifi-off', color: 'warning', text: window.wirelessLang.rec_weak_signal });
    }
    if (data.fresnel_60 < 1) {
        recs.push({ icon: 'bi-tree', color: 'info', text: window.wirelessLang.rec_fresnel_issue });
    }
    if (recs.length === 0) {
        recs.push({ icon: 'bi-check-circle', color: 'success', text: window.wirelessLang.rec_good_link });
    }

    recs.forEach(rec => {
        const div = document.createElement('div');
        div.className = `alert alert-${rec.color} d-flex align-items-center mb-2`;
        div.innerHTML = `<i class="bi ${rec.icon} me-2 fs-5"></i> <div>${rec.text}</div>`;
        recList.appendChild(div);
    });

    // Scroll to results on mobile
    if (window.innerWidth < 992) {
        container.scrollIntoView({ behavior: 'smooth' });
    }

    // 5. Draw Diagram
    drawDiagram(data);
}

// Store last data for redraw on resize
let lastCalculationData = null;

// Obstacle Management
window.addObstacle = function () {
    const list = document.getElementById('obstacles-list');
    const index = list.children.length;
    const row = document.createElement('tr');
    row.innerHTML = `
        <td>
            <input type="number" step="0.01" class="form-control form-control-sm" name="obstacles[${index}][distance]" placeholder="km" required>
        </td>
        <td>
            <input type="number" step="1" class="form-control form-control-sm" name="obstacles[${index}][height]" placeholder="m" required>
        </td>
        <td>
            <button type="button" class="btn btn-link text-danger p-0" onclick="removeObstacle(this)">
                <i class="bi bi-x-circle-fill"></i>
            </button>
        </td>
    `;
    list.appendChild(row);

    // Auto-save on input
    row.querySelectorAll('input').forEach(input => {
        input.addEventListener('change', saveConfig);
    });
};

window.removeObstacle = function (btn) {
    const row = btn.closest('tr');
    row.remove();
    // Re-index? Not strictly necessary for PHP array parsing usually, but cleaner if we do. 
    // Laravel handles non-consecutive indices fine for arrays.
    saveConfig();
};

window.addEventListener('resize', function () {
    if (lastCalculationData) {
        drawDiagram(lastCalculationData);
    }
});

function drawDiagram(data) {
    if (!data) return;
    lastCalculationData = data;

    const container = document.getElementById('link-diagram');

    // Define Grid/ViewBox Dimensions (Abstract units, not pixels)
    // This allows true responsiveness via SVG scaling
    const viewBoxWidth = 1000;
    const viewBoxHeight = 400;
    const padding = 60; // Increased padding for "zoom out" feel and text space

    // Parse values
    const hA = parseFloat(document.getElementById('site_a_height').value) || 15;
    const hB = parseFloat(document.getElementById('site_b_height').value) || 15;
    const distanceKm = parseFloat(document.getElementById('distance').value) || 1;
    const fresnelR = data.fresnel_radius;

    // SCALING LOGIC
    // We need to fit the heights (sites + fresnel zone) into the viewbox vertically
    // MaxY = Max Tower Height + Fresnel Radius + Top Buffer
    const maxHeight = Math.max(hA, hB);
    const topBuffer = fresnelR * 0.5 + 20; // Extra space above
    const maxY = maxHeight + fresnelR + topBuffer;

    // Scale factor: how many units of viewbox height per meter
    // Use (viewBoxHeight - padding*2) as usable drawing area
    const scaleY = (viewBoxHeight - (padding * 2)) / maxY;

    // Scale X: pixels per km
    // We map 0 to distanceKm onto xA to xB
    const xA = padding;
    const xB = viewBoxWidth - padding;
    const drawWidth = xB - xA;
    const scaleX = drawWidth / distanceKm;

    // Y coordinates (Canvas 0 is top)
    // Ground is at bottom minus padding
    const groundY = viewBoxHeight - padding;

    // Tower tips
    const yA = groundY - (hA * scaleY);
    const yB = groundY - (hB * scaleY);

    // SVG Construction (Dark Mode / Neon Tech)
    // preserveAspectRatio="xMidYMid meet" ensures it scales nicely without distortion
    let svg = `<svg viewBox="0 0 ${viewBoxWidth} ${viewBoxHeight}" preserveAspectRatio="xMidYMid meet" style="width: 100%; height: auto; max-height: 400px;" xmlns="http://www.w3.org/2000/svg">`;

    // 1. Sky/Background (Transparent to let Glass shine)
    // No background rect needed, or maybe a very subtle grid?

    // 2. Ground Line (Neon Horizon)
    svg += `<line x1="0" y1="${groundY}" x2="${viewBoxWidth}" y2="${groundY}" stroke="rgba(255,255,255,0.2)" stroke-width="2" />`;
    // Ground Fill Area (Dark Tech)
    svg += `<rect x="0" y="${groundY}" width="${viewBoxWidth}" height="${padding}" fill="rgba(255,255,255,0.03)" />`;

    // 3. Towers (Silver / Cyber)
    // Tower A
    svg += `<line x1="${xA}" y1="${groundY}" x2="${xA}" y2="${yA}" stroke="#cbd5e0" stroke-width="4" stroke-linecap="round" />`;
    svg += `<circle cx="${xA}" cy="${yA}" r="4" fill="#5e72e4" />`; // Neon tip
    svg += `<text x="${xA}" y="${groundY + 25}" font-family="sans-serif" font-size="14" font-weight="bold" fill="#e2e8f0" text-anchor="middle">${window.wirelessLang.site_a}</text>`;
    svg += `<text x="${xA}" y="${groundY + 45}" font-family="sans-serif" font-size="12" fill="#a0aec0" text-anchor="middle">${hA}m</text>`;

    // Tower B
    svg += `<line x1="${xB}" y1="${groundY}" x2="${xB}" y2="${yB}" stroke="#cbd5e0" stroke-width="4" stroke-linecap="round" />`;
    svg += `<circle cx="${xB}" cy="${yB}" r="4" fill="#5e72e4" />`;
    svg += `<text x="${xB}" y="${groundY + 25}" font-family="sans-serif" font-size="14" font-weight="bold" fill="#e2e8f0" text-anchor="middle">${window.wirelessLang.site_b}</text>`;
    svg += `<text x="${xB}" y="${groundY + 45}" font-family="sans-serif" font-size="12" fill="#a0aec0" text-anchor="middle">${hB}m</text>`;

    // 4. Line of Sight (LOS) - Neon Blue Dashed
    svg += `<line x1="${xA}" y1="${yA}" x2="${xB}" y2="${yB}" stroke="#00f2c3" stroke-width="2" stroke-dasharray="8,4" />`;

    // 5. Fresnel Zone (Holographic Blue)
    // Midpoint
    const cx = (xA + xB) / 2;
    const cy = (yA + yB) / 2;

    // Calculate dimensions
    const dx = xB - xA;
    const dy = yB - yA;
    const len = Math.sqrt(dx * dx + dy * dy);
    // Angle in degrees for rotation
    const angle = Math.atan2(dy, dx) * 180 / Math.PI;

    // Radii
    const rx = len / 2;
    const ry = fresnelR * scaleY;

    // Gradients for Fresnel
    svg += `<defs>
        <radialGradient id="fresnelZoneGrad">
            <stop offset="0%" stop-color="#5e72e4" stop-opacity="0.3"/>
            <stop offset="80%" stop-color="#5e72e4" stop-opacity="0.1"/>
            <stop offset="100%" stop-color="#5e72e4" stop-opacity="0"/>
        </radialGradient>
    </defs>`;

    // Draw main Fresnel Zone
    svg += `<ellipse cx="${cx}" cy="${cy}" rx="${rx}" ry="${ry}" fill="url(#fresnelZoneGrad)" stroke="#5e72e4" stroke-width="1.5" transform="rotate(${angle} ${cx} ${cy})" />`;

    // Draw 60% Clearance Zone (Bright Dashed)
    const ry60 = ry * 0.6;
    svg += `<ellipse cx="${cx}" cy="${cy}" rx="${rx}" ry="${ry60}" fill="none" stroke="#2dce89" stroke-width="1" stroke-dasharray="4,4" stroke-opacity="0.8" transform="rotate(${angle} ${cx} ${cy})" />`;

    // 6. Draw Obstacles
    const obsRows = document.querySelectorAll('#obstacles-list tr');
    obsRows.forEach(row => {
        const dInput = row.querySelector('input[name*="[distance]"]');
        const hInput = row.querySelector('input[name*="[height]"]');

        if (dInput && hInput) {
            const dKm = parseFloat(dInput.value);
            const obsH = parseFloat(hInput.value);

            if (!isNaN(dKm) && !isNaN(obsH) && dKm < distanceKm) {
                // Determine X position
                const xObs = xA + (dKm * scaleX);

                // Determine Y position (height from ground)
                const yObsTop = groundY - (obsH * scaleY);

                // Draw Obstacle (Line/Stick) - Red neon for danger
                svg += `<line x1="${xObs}" y1="${groundY}" x2="${xObs}" y2="${yObsTop}" stroke="#ff3688" stroke-width="3" />`;
                svg += `<circle cx="${xObs}" cy="${yObsTop}" r="3" fill="#ff3688" />`;
                // Label
                svg += `<text x="${xObs}" y="${groundY + 15}" font-family="sans-serif" font-size="10" fill="#ff3688" text-anchor="middle">${obsH}m</text>`;
            }
        }
    });

    // Distance Label in Middle
    const distText = document.getElementById('distance').value + ' km';
    // Label Background (Dark Pill)
    svg += `<rect x="${cx - 45}" y="${groundY - 12}" width="90" height="24" rx="12" fill="rgba(0,0,0,0.6)" stroke="rgba(255,255,255,0.2)" />`;
    svg += `<text x="${cx}" y="${groundY + 2}" font-family="sans-serif" font-size="12" fill="#fff" text-anchor="middle" dominant-baseline="middle">${distText}</text>`;

    svg += `</svg>`;

    container.innerHTML = svg;
}

function saveConfig() {
    // Save current form state to localStorage
    const formData = new FormData(document.getElementById('link-planner-form'));
    const config = Object.fromEntries(formData.entries());
    localStorage.setItem('link_planner_config', JSON.stringify(config));
}

function loadConfig() {
    const saved = localStorage.getItem('link_planner_config');
    if (saved) {
        try {
            const config = JSON.parse(saved);
            Object.entries(config).forEach(([key, value]) => {
                const el = document.querySelector(`[name="${key}"]`);
                if (el) el.value = value;
            });
        } catch (e) {
            console.error('Error loading config:', e);
            localStorage.removeItem('link_planner_config');
        }
    }
}
