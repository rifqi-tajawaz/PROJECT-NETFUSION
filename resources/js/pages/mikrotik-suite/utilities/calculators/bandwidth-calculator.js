import axios from 'axios';

document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('bandwidthForm');
    const tiersBody = document.getElementById('tiersBody');
    const addTierBtn = document.getElementById('addTierBtn');

    // Stats Els
    const sumTotalDown = document.getElementById('sumTotalDown');
    const sumTotalUp = document.getElementById('sumTotalUp');
    const sumResDown = document.getElementById('sumResDown');
    const sumResUp = document.getElementById('sumResUp');
    const sumSoldDown = document.getElementById('sumSoldDown');
    const sumSoldUp = document.getElementById('sumSoldUp');
    const sumAvailDown = document.getElementById('sumAvailDown');
    const sumAvailUp = document.getElementById('sumAvailUp');

    // Default Data
    let tiers = [
        { name: 'Basic', down: 10, up: 2, clients: 50 },
        { name: 'Pro', down: 20, up: 5, clients: 20 },
        { name: 'Ultra', down: 50, up: 10, clients: 5 }
    ];

    let downChart = null;
    let upChart = null;

    function renderTiers() {
        tiersBody.innerHTML = '';
        tiers.forEach((tier, index) => {
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td><input type="text" class="form-control form-control-sm bg-transparent text-white border-0" value="${tier.name}" onchange="updateTier(${index}, 'name', this.value)" required></td>
                <td><input type="number" class="form-control form-control-sm bg-transparent text-info border-0" value="${tier.down}" onchange="updateTier(${index}, 'down', this.value)" style="width:70px" required min="0"></td>
                <td><input type="number" class="form-control form-control-sm bg-transparent text-warning border-0" value="${tier.up}" onchange="updateTier(${index}, 'up', this.value)" style="width:70px" required min="0"></td>
                <td><input type="number" class="form-control form-control-sm bg-transparent text-white border-0" value="${tier.clients}" onchange="updateTier(${index}, 'clients', this.value)" style="width:70px" required min="0"></td>
                <td class="text-end"><button type="button" class="btn btn-sm text-danger" onclick="removeTier(${index})"><i class="bi bi-trash"></i></button></td>
            `;
            tiersBody.appendChild(tr);
        });
    }

    window.updateTier = (index, field, value) => {
        if (field !== 'name') value = parseFloat(value) || 0;
        tiers[index][field] = value;
    };

    window.removeTier = (index) => {
        tiers.splice(index, 1);
        renderTiers();
    };

    addTierBtn.addEventListener('click', () => {
        tiers.push({ name: 'New', down: 0, up: 0, clients: 0 });
        renderTiers();
    });

    form.addEventListener('submit', function (e) {
        e.preventDefault();

        // Manual validation if needed, or browser validation
        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }

        const submitBtn = form.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Calculating...';

        // Construct Data
        const payload = {
            total_down: parseFloat(document.getElementById('totalDown').value),
            total_up: parseFloat(document.getElementById('totalUp').value),
            res_down_pct: parseFloat(document.getElementById('resDownPct').value),
            res_up_pct: parseFloat(document.getElementById('resUpPct').value),
            tiers: tiers
        };

        axios.post(form.dataset.route, payload)
            .then(response => {
                const data = response.data;
                // Update UI with Server Data (validation double check)

                // Render Stats
                sumTotalDown.textContent = data.total_down;
                sumTotalUp.textContent = data.total_up;
                sumResDown.textContent = data.reserved_down;
                sumResUp.textContent = data.reserved_up;
                sumSoldDown.textContent = data.sold_down;
                sumSoldUp.textContent = data.sold_up;

                sumAvailDown.textContent = data.avail_down;
                sumAvailDown.className = data.avail_down < 0 ? 'text-end text-danger fw-bold' : 'text-end text-success fw-bold';

                sumAvailUp.textContent = data.avail_up;
                sumAvailUp.className = data.avail_up < 0 ? 'text-end text-danger fw-bold' : 'text-end text-success fw-bold';

                updateCharts(
                    data.total_down, data.reserved_down, data.sold_down, data.avail_down,
                    data.total_up, data.reserved_up, data.sold_up, data.avail_up
                );
            })
            .catch(error => {
                console.error('Calculation Error:', error);
                if (error.response && error.response.data && error.response.data.errors) {
                    let msg = "Validation Error:\n";
                    for (const key in error.response.data.errors) {
                        msg += `- ${error.response.data.errors[key][0]}\n`;
                    }
                    alert(msg);
                } else {
                    alert('An error occurred during calculation.');
                }
            })
            .finally(() => {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            });
    });

    const updateCharts = (td, rd, sd, ad, tu, ru, su, au) => {
        // Down Chart
        if (downChart) downChart.destroy();
        const ctxD = document.getElementById('downChart').getContext('2d');
        downChart = new Chart(ctxD, {
            type: 'doughnut',
            data: {
                labels: ['Reserved', 'Sold', 'Available (Free)', 'Overbooked'],
                datasets: [{
                    data: [rd, sd, Math.max(0, ad), Math.abs(Math.min(0, ad))],
                    backgroundColor: ['#fd7e14', '#0dcaf0', '#198754', '#dc3545'],
                    borderWidth: 0
                }]
            },
            options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'bottom', labels: { color: 'white' } } } }
        });

        // Up Chart
        if (upChart) upChart.destroy();
        const ctxU = document.getElementById('upChart').getContext('2d');
        upChart = new Chart(ctxU, {
            type: 'doughnut',
            data: {
                labels: ['Reserved', 'Sold', 'Available (Free)', 'Overbooked'],
                datasets: [{
                    data: [ru, su, Math.max(0, au), Math.abs(Math.min(0, au))],
                    backgroundColor: ['#fd7e14', '#6610f2', '#198754', '#dc3545'],
                    borderWidth: 0
                }]
            },
            options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'bottom', labels: { color: 'white' } } } }
        });
    };

    renderTiers();
    // Initial calculate not possible without user interaction or default POST, 
    // better to wait for user to click calculate or trigger it if needed.
    // For now, let's trigger a click if we want immediate feedback, but we need valid data.
    // We'll let the user click.
});
