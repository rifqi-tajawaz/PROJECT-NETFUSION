@extends('layouts.app')

@section('title', __('netfusion.traffic_monitor'))

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // --- 1. CONFIGURATION ---
            const ctx = document.getElementById('trafficChart').getContext('2d');
            const interfaceSelect = document.getElementById('interface-select');
            let currentInterface = '{{ request("interface", "ether1") }}';
            let trafficChart;

            // Gradients (Subtle)
            let gradientRx = ctx.createLinearGradient(0, 0, 0, 400);
            gradientRx.addColorStop(0, 'rgba(25, 135, 84, 0.2)'); // Success Green
            gradientRx.addColorStop(1, 'rgba(25, 135, 84, 0.0)');

            let gradientTx = ctx.createLinearGradient(0, 0, 0, 400);
            gradientTx.addColorStop(0, 'rgba(13, 110, 253, 0.2)'); // Primary Blue
            gradientTx.addColorStop(1, 'rgba(13, 110, 253, 0.0)');

            // --- 2. CHART INIT ---
            const initialData = {
                labels: Array(20).fill(''),
                datasets: [{
                        label: '{{ __('netfusion.download_rx') }}',
                        borderColor: '#198754',
                        backgroundColor: gradientRx,
                        borderWidth: 2,
                        pointRadius: 0,
                        tension: 0.4,
                        fill: true,
                        data: Array(20).fill(0)
                    },
                    {
                        label: '{{ __('netfusion.upload_tx') }}',
                        borderColor: '#0d6efd',
                        backgroundColor: gradientTx,
                        borderWidth: 2,
                        pointRadius: 0,
                        tension: 0.4,
                        fill: true,
                        data: Array(20).fill(0)
                    }
                ]
            };

            const config = {
                type: 'line',
                data: initialData,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    animation: false,
                    interaction: {
                        mode: 'nearest',
                        axis: 'x',
                        intersect: false
                    },
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top',
                            align: 'end',
                            labels: {
                                usePointStyle: true,
                                boxWidth: 8,
                                padding: 20,
                                font: { family: "'Inter', sans-serif", size: 11 }
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(255, 255, 255, 0.95)',
                            titleColor: '#000',
                            bodyColor: '#333',
                            borderColor: '#e9ecef',
                            borderWidth: 1,
                            padding: 10,
                            displayColors: true,
                            callbacks: {
                                label: function(context) {
                                    return context.dataset.label + ': ' + formatBytes(context.parsed.y);
                                }
                            }
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
                            grid: {
                                borderDash: [2, 4],
                                color: '#f0f0f0'
                            },
                            ticks: {
                                font: { size: 10, family: "'Inter', sans-serif" },
                                callback: function(value) { return formatBytes(value); }
                            }
                        }
                    }
                }
            };

            trafficChart = new Chart(ctx, config);

            // --- 3. HELPER: FORMAT BYTES ---
            function formatBytes(bytes, decimals = 2) {
                if (!+bytes) return '0 bps';
                const k = 1000; // Networking uses 1000 for Mbps/Kbps usually, but 1024 for storage. adhering to Dashboard logic (1024)
                const dm = decimals < 0 ? 0 : decimals;
                const sizes = ['bps', 'Kbps', 'Mbps', 'Gbps', 'Tbps'];
                const i = Math.floor(Math.log(bytes) / Math.log(k));
                return `${parseFloat((bytes / Math.pow(k, i)).toFixed(dm))} ${sizes[i]}`;
            }

            // --- 4. DATA POLLING ---
            interfaceSelect.addEventListener('change', function() {
                currentInterface = this.value;
                // Clear Data
                trafficChart.data.datasets[0].data = Array(20).fill(0);
                trafficChart.data.datasets[1].data = Array(20).fill(0);
                trafficChart.update();
            });

            function fetchData() {
                fetch('{{ route("mikrotik.netfusion.monitor.traffic.data") }}?interface=' + encodeURIComponent(currentInterface))
                    .then(response => response.json())
                    .then(data => {
                        if (!data.error) {
                            // API returns Mbps (value / 1000 / 1000). 
                            // But Dashboard chart expects RAW BYTES to format them nicely.
                            // Let's modify logic: Convert Mbps back to bps or adjust controller to return raw.
                            // Wait, controller returns Mbps (line 56).
                            // Let's multiply by 1,000,000 to get bps so formatBytes works correctly

                            // API now returns raw bits (bps). No need to multiply.
                            const rxBps = parseFloat(data.rx);
                            const txBps = parseFloat(data.tx);

                            // Update Big Numbers
                            document.getElementById('rx-text').innerText = formatBytes(rxBps);
                            document.getElementById('tx-text').innerText = formatBytes(txBps);

                            // Update Chart
                            const dataRx = trafficChart.data.datasets[0].data;
                            const dataTx = trafficChart.data.datasets[1].data;

                            dataRx.push(rxBps); 
                            dataRx.shift();

                            dataTx.push(txBps);
                            dataTx.shift();

                            trafficChart.update('none');
                        }
                    })
                    .catch(console.error);
            }

            setInterval(fetchData, 2000);
            fetchData();
        });
    </script>
@endpush

@section('content')
    <div class="container-fluid px-4 py-4">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-bold text-dark mb-1">{{ __('netfusion.traffic_monitor') }}</h4>
                <p class="text-muted small mb-0">{{ __('netfusion.live_bandwidth_usage') }}</p>
            </div>

            <!-- Interface Selector -->
            <div class="d-flex align-items-center gap-2 bg-white rounded-pill px-3 py-2 shadow-sm border">
                <i class="bi bi-ethernet text-primary"></i>
                <span class="text-muted small fw-bold text-uppercase me-2 border-end pe-3">{{ __('netfusion.interface') }}</span>
                <select id="interface-select" class="form-select form-select-sm border-0 bg-transparent shadow-none p-0 fw-bold text-dark" style="width: auto; cursor: pointer;">
                    @foreach($interfaces as $iface)
                        <option value="{{ $iface['name'] }}" {{ $iface['name'] == request('interface', 'ether1') ? 'selected' : '' }}>
                            {{ $iface['name'] }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- Main Chart Card -->
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-4">

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="fw-bold text-dark m-0">{{ __('netfusion.live_traffic') }}</h5>

                    <!-- Live Stats Inline -->
                    <div class="d-flex gap-4">
                        <div class="d-flex align-items-center gap-2">
                            <span class="d-block rounded-circle bg-success" style="width: 8px; height: 8px;"></span>
                            <span class="text-muted small fw-bold text-uppercase">{{ __('netfusion.rx') }}:</span>
                            <span class="fw-bold text-dark font-monospace" id="rx-text">0 bps</span>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <span class="d-block rounded-circle bg-primary" style="width: 8px; height: 8px;"></span>
                            <span class="text-muted small fw-bold text-uppercase">{{ __('netfusion.tx') }}:</span>
                            <span class="fw-bold text-dark font-monospace" id="tx-text">0 bps</span>
                        </div>
                    </div>
                </div>

                <!-- Chart Canvas -->
                <div style="height: 400px; width: 100%;">
                    <canvas id="trafficChart"></canvas>
                </div>

            </div>
        </div>
    </div>
@endsection
