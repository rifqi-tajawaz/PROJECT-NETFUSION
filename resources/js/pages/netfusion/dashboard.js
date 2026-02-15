import Chart from 'chart.js/auto';
import $ from 'jquery';

/**
 * NetFusion Dashboard Logic
 */
const Dashboard = {
    init() {
        this.initChart();
        this.initLiveData();
    },

    initChart() {
        // -- Configuration --
        Chart.defaults.font.family = "'Inter', system-ui, -apple-system, sans-serif";
        Chart.defaults.color = '#677788';

        const ctx = document.getElementById('trafficChart');

        if (ctx) {
            const chartCtx = ctx.getContext('2d');

            // Gradients
            const gradientRx = chartCtx.createLinearGradient(0, 0, 0, 400);
            gradientRx.addColorStop(0, 'rgba(25, 135, 84, 0.4)'); // Green start
            gradientRx.addColorStop(1, 'rgba(25, 135, 84, 0.0)'); // Green end

            const gradientTx = chartCtx.createLinearGradient(0, 0, 0, 400);
            gradientTx.addColorStop(0, 'rgba(13, 110, 253, 0.4)'); // Blue start
            gradientTx.addColorStop(1, 'rgba(13, 110, 253, 0.0)'); // Blue end

            window.trafficChart = new Chart(chartCtx, {
                type: 'line',
                data: {
                    labels: Array(15).fill(''),
                    datasets: [{
                        label: 'Download (RX)',
                        borderColor: '#198754',
                        backgroundColor: gradientRx,
                        borderWidth: 2,
                        pointRadius: 0,
                        pointHoverRadius: 4,
                        fill: true,
                        tension: 0.4,
                        data: Array(15).fill(0)
                    }, {
                        label: 'Upload (TX)',
                        borderColor: '#0d6efd',
                        backgroundColor: gradientTx,
                        borderWidth: 2,
                        pointRadius: 0,
                        pointHoverRadius: 4,
                        fill: true,
                        tension: 0.4,
                        data: Array(15).fill(0)
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'top', align: 'end', labels: { usePointStyle: true, boxWidth: 8, padding: 20 } },
                        tooltip: {
                            mode: 'index',
                            intersect: false,
                            backgroundColor: 'rgba(255, 255, 255, 0.9)',
                            titleColor: '#1e2022',
                            bodyColor: '#677788',
                            borderColor: '#e7eaf3',
                            borderWidth: 1,
                            padding: 10,
                            displayColors: true
                        }
                    },
                    scales: {
                        x: {
                            grid: { display: false },
                            ticks: { display: false }
                        },
                        y: {
                            beginAtZero: true,
                            border: { display: false },
                            grid: { borderDash: [2, 4], color: '#f0f0f0' },
                            ticks: { callback: function (value) { return Dashboard.formatBytes(value); }, font: { size: 11 } }
                        }
                    },
                    interaction: { mode: 'nearest', axis: 'x', intersect: false }
                }
            });
        }
    },

    initLiveData() {
        const liveRoute = document.querySelector('meta[name="route-live"]')?.content;

        // If we can't find the route in meta, likely passed via data attribute or we need to hardcode (bad) or inject variable
        // The original blade used {{ route(...) }}.
        // Best practice: Add a data attribute to a dedicated element or meta tag.
        // We will assume the blade will provide it via a data attribute on the chart canvas or a container.
    },

    startLiveUpdate(url) {
        if (!url) return;

        function updateLiveData() {
            const iface = $('#interface-select').val();
            $.get(url, { interface: iface })
                .done(function (data) {
                    if (data.error) return;

                    // Update Stats Text & Bars
                    $('#cpu-text').text(data.cpu_load + '%');
                    $('#cpu-bar').css('width', data.cpu_load + '%').attr('aria-valuenow', data.cpu_load);

                    $('#mem-text').text(data.memory_usage + '%');
                    $('#mem-bar').css('width', data.memory_usage + '%');

                    // Update Counts
                    $('#active-count-live').text(data.active_users);
                    $('#pppoe-count-live').text(data.pppoe_active);

                    // Pulse Animation Trigger
                    $('.status-pulse').fadeIn(100).fadeOut(100).fadeIn(100);

                    if (window.trafficChart) {
                        const chart = window.trafficChart;

                        // Remove oldest
                        chart.data.datasets[0].data.shift();
                        chart.data.datasets[1].data.shift();

                        // Add newest (raw bytes)
                        chart.data.datasets[0].data.push(data.traffic.raw_rx);
                        chart.data.datasets[1].data.push(data.traffic.raw_tx);

                        chart.update();
                    }
                })
                .fail(function (error) {
                    console.error('Live data fetch failed:', error);
                });
        }

        setInterval(updateLiveData, 3000);
    },

    formatBytes(bytes, decimals = 2) {
        if (!+bytes) return '0 B';
        const k = 1024;
        const dm = decimals < 0 ? 0 : decimals;
        const sizes = ['B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];
        const i = bytes === 0 ? 0 : Math.floor(Math.log(bytes) / Math.log(k));
        const index = Math.max(0, i);
        return `${parseFloat((bytes / Math.pow(k, index)).toFixed(dm))} ${sizes[index]}`;
    }
};

$(document).ready(() => {
    Dashboard.init();

    // Get URL from data attribute
    const container = document.getElementById('trafficChart');
    if (container) {
        const url = container.dataset.url; // We will add this in blade
        Dashboard.startLiveUpdate(url);
    }
});

export default Dashboard;
