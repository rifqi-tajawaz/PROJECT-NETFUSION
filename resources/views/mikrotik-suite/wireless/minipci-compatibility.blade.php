@extends('layouts.app')

@section('content')
    <div class="container-fluid px-4 py-4">
        <div class="mb-4 d-flex flex-column align-items-center justify-content-center text-center">
            <h2 class="display-6 fw-bold mb-2 text-white" style="text-shadow: 0 2px 10px rgba(0,0,0,0.3);">
                <i class="bi bi-gpu-card me-2 text-success"></i> MiniPCI Compatibility
            </h2>
            <p class="lead opacity-75 mb-0" style="max-width: 700px;">
                Database of supported wireless cards and their drivers for RouterOS.
            </p>
        </div>

        <!-- Filters -->
        <div class="row mb-4 justify-content-center">
            <div class="col-lg-10">
                <div class="glass-card p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label text-white small text-uppercase">Search</label>
                            <div class="input-group">
                                <span class="input-group-text bg-dark border-secondary text-white"><i
                                        class="bi bi-search"></i></span>
                                <input type="text" class="form-control" id="searchInput" placeholder="Model, Chipset...">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label text-white small text-uppercase">Form Factor</label>
                            <select class="form-select" id="formFilter">
                                <option value="all">All</option>
                                <option value="MiniPCI">MiniPCI (Legacy)</option>
                                <option value="MiniPCI-e">MiniPCI-e</option>
                                <option value="M.2">M.2 (NGFF)</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label text-white small text-uppercase">Driver</label>
                            <select class="form-select" id="driverFilter">
                                <option value="all">All</option>
                                <option value="wireless">Legacy Wireless</option>
                                <option value="wifiwave2">WifiWave2 (AX)</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Table -->
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="glass-card overflow-hidden">
                    <div class="table-responsive">
                        <table class="table table-dark table-hover mb-0 align-middle">
                            <thead>
                                <tr class="bg-dark bg-opacity-50 text-uppercase small text-white-50">
                                    <th class="ps-4 py-3 border-0">Model</th>
                                    <th class="py-3 border-0">Chipset</th>
                                    <th class="py-3 border-0">Form Factor</th>
                                    <th class="py-3 border-0">Bands</th>
                                    <th class="py-3 border-0 text-center">MIMO</th>
                                    <th class="py-3 border-0 text-center">Power</th>
                                    <th class="py-3 pe-4 border-0 text-end">Driver</th>
                                </tr>
                            </thead>
                            <tbody id="hardwareTable" class="border-top-0">
                                <!-- JS Populated -->
                            </tbody>
                        </table>
                    </div>
                    <div id="noResults" class="text-center py-5 d-none">
                        <p class="text-white-50">No hardware matches criteria.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const DB = [
            { model: "R52Hn", chipset: "Atheros AR9220", form: "MiniPCI", bands: "2.4/5GHz", mimo: "2x2", power: "25dBm", driver: "wireless" },
            { model: "R11e-5HacT", chipset: "QCA9880", form: "MiniPCI-e", bands: "5GHz AC", mimo: "3x3", power: "28dBm", driver: "wireless" },
            { model: "QCN9074", chipset: "Qualcomm QCN9074", form: "M.2", bands: "AX 6GHz", mimo: "4x4", power: "22dBm", driver: "wifiwave2" },
            { model: "MT7915", chipset: "MediaTek MT7915", form: "MiniPCI-e", bands: "AX", mimo: "4x4", power: "23dBm", driver: "wifiwave2" },
            { model: "R11e-LTE6", chipset: "Qualcomm", form: "MiniPCI-e", bands: "LTE", mimo: "2x2", power: "-", driver: "lte" }
        ];

        document.addEventListener('DOMContentLoaded', () => {
            render(DB);

            const filter = () => {
                const term = document.getElementById('searchInput').value.toLowerCase();
                const fForm = document.getElementById('formFilter').value;
                const fDriver = document.getElementById('driverFilter').value;

                const res = DB.filter(i => {
                    const m1 = i.model.toLowerCase().includes(term) || i.chipset.toLowerCase().includes(term);
                    const m2 = fForm === 'all' || i.form === fForm;
                    const m3 = fDriver === 'all' || i.driver === fDriver;
                    return m1 && m2 && m3;
                });
                render(res);
            };

            document.getElementById('searchInput').addEventListener('input', filter);
            document.getElementById('formFilter').addEventListener('change', filter);
            document.getElementById('driverFilter').addEventListener('change', filter);
        });

        function render(data) {
            const tbody = document.getElementById('hardwareTable');
            tbody.innerHTML = '';

            if (data.length === 0) {
                document.getElementById('noResults').classList.remove('d-none');
                return;
            }
            document.getElementById('noResults').classList.add('d-none');

            data.forEach(item => {
                let badge = 'bg-secondary';
                if (item.driver === 'wifiwave2') badge = 'bg-primary';
                else if (item.driver === 'wireless') badge = 'bg-success';

                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td class="ps-4 py-3 fw-bold text-white">${item.model}</td>
                    <td class="py-3 text-white-50">${item.chipset}</td>
                    <td class="py-3"><span class="badge bg-dark border border-secondary">${item.form}</span></td>
                    <td class="py-3 text-white-50">${item.bands}</td>
                    <td class="py-3 text-center text-white">${item.mimo}</td>
                    <td class="py-3 text-center text-white-50">${item.power}</td>
                    <td class="py-3 pe-4 text-end"><span class="badge ${badge} rounded-pill">${item.driver}</span></td>
                `;
                tbody.appendChild(tr);
            });
        }
    </script>
@endsection
