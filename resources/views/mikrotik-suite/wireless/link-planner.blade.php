@extends('layouts.app')

@section('title', 'Wireless Link Planner')

@section('content')
    <div class="row mb-3">
        <div class="col-12">
            <div class="card rounded-4 border-0 shadow-sm">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                        <div>
                            <h4 class="mb-1 text-primary fw-bold">{{ __('wireless.title') }}</h4>
                            <p class="mb-0 text-secondary">{{ __('wireless.subtitle') }}</p>
                        </div>
                        <div class="d-flex gap-2">
                            <button class="btn btn-outline-secondary rounded-3 text-nowrap" onclick="window.print()">
                                <i class="bi bi-printer me-2"></i>{{ __('wireless.print') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Left Column: Input Form -->
        <div class="col-lg-4 mb-4">
            <div class="card rounded-4 border-0 shadow-sm h-100">
                <div class="card-header bg-transparent border-bottom-0 py-3 px-4">
                    <div class="d-flex align-items-center gap-3">
                        <div class="icon-box bg-light-primary text-primary rounded-circle"
                            style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                            <i class="bi bi-sliders fs-5"></i>
                        </div>
                        <h5 class="mb-0 fw-bold">{{ __('wireless.parameters') }}</h5>
                    </div>
                </div>
                <div class="card-body px-4 pb-4">
                    <form id="link-planner-form" class="no-loader needs-validation"
                        data-route="{{ route('mikrotik-suite.wireless.link-planner.calculate') }}" novalidate>
                        @csrf

                        <!-- Group 1: Site Configuration -->
                        <div class="mb-4">
                            <h6 class="text-uppercase text-secondary fw-bold small mb-3">{{ __('wireless.site_config') }}
                            </h6>
                            <div class="row g-3">
                                <!-- Site A -->
                                <div class="col-6 border-end">
                                    <div class="text-center mb-3">
                                        <span
                                            class="badge bg-light-primary text-primary px-3 py-2 rounded-pill fw-bold">{{ __('wireless.site_a') }}</span>
                                    </div>
                                    <div class="mb-2">
                                        <label
                                            class="form-label small text-muted">{{ __('wireless.device_preset') }}</label>
                                        <select class="form-select form-select-sm device-preset rounded-3"
                                            data-target="site_a">
                                            <option value="">{{ __('wireless.manual') }}</option>
                                            <!-- JS will populate -->
                                        </select>
                                    </div>
                                    <div class="form-floating mb-2">
                                        <input type="number" class="form-control" id="site_a_height" name="site_a_height"
                                            placeholder="Height" value="15" required>
                                        <label for="site_a_height">{{ __('wireless.ant_height') }} (m)</label>
                                    </div>
                                    <div class="row g-2 mb-2">
                                        <div class="col-6">
                                            <div class="input-group input-group-sm">
                                                <span class="input-group-text text-muted bg-light"><i
                                                        class="bi bi-geo-alt"></i></span>
                                                <input type="text" class="form-control" id="site_a_lat" name="site_a_lat"
                                                    placeholder="Lat">
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="input-group input-group-sm">
                                                <span class="input-group-text text-muted bg-light"><i
                                                        class="bi bi-geo"></i></span>
                                                <input type="text" class="form-control" id="site_a_lon" name="site_a_lon"
                                                    placeholder="Lon">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-floating mb-2">
                                        <input type="number" class="form-control" id="site_a_tx_power"
                                            name="site_a_tx_power" placeholder="TX Power" value="27" required>
                                        <label for="site_a_tx_power">{{ __('wireless.tx_power') }} (dBm)</label>
                                    </div>
                                    <div class="form-floating mb-2">
                                        <input type="number" class="form-control" id="site_a_ant_gain"
                                            name="site_a_ant_gain" placeholder="Gain" value="24" required>
                                        <label for="site_a_ant_gain">{{ __('wireless.ant_gain') }} (dBi)</label>
                                    </div>
                                    <div class="form-floating">
                                        <input type="number" class="form-control" id="site_a_cable_loss"
                                            name="site_a_cable_loss" placeholder="Loss" value="1" step="0.1">
                                        <label for="site_a_cable_loss">{{ __('wireless.cable_loss') }} (dB)</label>
                                    </div>
                                </div>

                                <!-- Site B -->
                                <div class="col-6">
                                    <div class="text-center mb-3">
                                        <span
                                            class="badge bg-light-info text-info px-3 py-2 rounded-pill fw-bold">{{ __('wireless.site_b') }}</span>
                                    </div>
                                    <div class="mb-2">
                                        <label
                                            class="form-label small text-muted">{{ __('wireless.device_preset') }}</label>
                                        <select class="form-select form-select-sm device-preset rounded-3"
                                            data-target="site_b">
                                            <option value="">{{ __('wireless.manual') }}</option>
                                            <!-- JS will populate -->
                                        </select>
                                    </div>
                                    <div class="form-floating mb-2">
                                        <input type="number" class="form-control" id="site_b_height" name="site_b_height"
                                            placeholder="Height" value="15" required>
                                        <label for="site_b_height">{{ __('wireless.ant_height') }} (m)</label>
                                    </div>
                                    <div class="row g-2 mb-2">
                                        <div class="col-6">
                                            <div class="input-group input-group-sm">
                                                <span class="input-group-text text-muted bg-light"><i
                                                        class="bi bi-geo-alt"></i></span>
                                                <input type="text" class="form-control" id="site_b_lat" name="site_b_lat"
                                                    placeholder="Lat">
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="input-group input-group-sm">
                                                <span class="input-group-text text-muted bg-light"><i
                                                        class="bi bi-geo"></i></span>
                                                <input type="text" class="form-control" id="site_b_lon" name="site_b_lon"
                                                    placeholder="Lon">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-floating mb-2">
                                        <input type="number" class="form-control" id="site_b_rx_sens" name="site_b_rx_sens"
                                            placeholder="RX Sens" value="-89" required>
                                        <label for="site_b_rx_sens">{{ __('wireless.rx_sens') }} (dBm)</label>
                                    </div>
                                    <div class="form-floating mb-2">
                                        <input type="number" class="form-control" id="site_b_ant_gain"
                                            name="site_b_ant_gain" placeholder="Gain" value="24" required>
                                        <label for="site_b_ant_gain">{{ __('wireless.ant_gain') }} (dBi)</label>
                                    </div>
                                    <div class="form-floating">
                                        <input type="number" class="form-control" id="site_b_cable_loss"
                                            name="site_b_cable_loss" placeholder="Loss" value="1" step="0.1">
                                        <label for="site_b_cable_loss">{{ __('wireless.cable_loss') }} (dB)</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Group 2: Link Parameters -->
                        <div class="mb-4">
                            <h6 class="text-uppercase text-secondary fw-bold small mb-3">{{ __('wireless.link_config') }}
                            </h6>
                            <div class="row g-2">
                                <div class="col-6">
                                    <div class="form-floating">
                                        <input type="number" class="form-control" id="distance" name="distance"
                                            placeholder="Dist" step="0.01" required>
                                        <label for="distance">{{ __('wireless.distance') }} (km)</label>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-floating">
                                        <input type="number" class="form-control" id="frequency" name="frequency"
                                            placeholder="Freq" value="5800" required>
                                        <label for="frequency">{{ __('wireless.frequency') }} (MHz)</label>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-floating">
                                        <select class="form-select" id="protocol" name="protocol">
                                            <option value="802.11n">802.11n</option>
                                            <option value="802.11ac" selected>802.11ac</option>
                                            <option value="802.11ax">802.11ax</option>
                                        </select>
                                        <label for="protocol">{{ __('wireless.protocol') }}</label>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-floating">
                                        <select class="form-select" id="channel_width" name="channel_width">
                                            <option value="20">20 MHz</option>
                                            <option value="40">40 MHz</option>
                                            <option value="80" selected>80 MHz</option>
                                            <option value="160">160 MHz</option>
                                        </select>
                                        <label for="channel_width">{{ __('wireless.channel_width') }}</label>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-floating">
                                        <select class="form-select" id="polarization" name="polarization">
                                            <option value="1x1">1x1 (SISO)</option>
                                            <option value="2x2" selected>2x2 (MIMO)</option>
                                            <option value="3x3">3x3 (MIMO)</option>
                                            <option value="4x4">4x4 (MIMO)</option>
                                        </select>
                                        <label for="polarization">{{ __('wireless.polarization') }}</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Group 3: Environment -->
                        <div class="mb-4">
                            <h6 class="text-uppercase text-secondary fw-bold small mb-3">{{ __('wireless.environment') }}
                            </h6>
                            <label class="form-label small text-muted mb-1">{{ __('wireless.rain_rate') }} <span
                                    class="ms-1 fw-bold" id="rain-val">0</span> mm/hr</label>
                            <input type="range" class="form-range" id="rain_rate" name="rain_rate" min="0" max="100"
                                value="0" oninput="document.getElementById('rain-val').textContent = this.value">
                            <div class="d-flex justify-content-between small text-muted mt-1">
                                <span>{{ __('wireless.rain_clear') }}</span>
                                <span>{{ __('wireless.rain_medium') }}</span>
                                <span>{{ __('wireless.rain_heavy') }}</span>
                            </div>
                        </div>

                        <!-- Group 4: Obstacles -->
                        <div class="mb-4">
                            <h6 class="text-uppercase text-secondary fw-bold small mb-3">{{ __('wireless.obstacles') }}</h6>
                            <div class="table-responsive mb-2">
                                <table class="table table-sm table-borderless align-middle" id="obstacles-table">
                                    <thead class="text-secondary small">
                                        <tr>
                                            <th>{{ __('wireless.dist_from_a') }} (km)</th>
                                            <th>{{ __('wireless.obs_height') }} (m)</th>
                                            <th style="width: 40px;"></th>
                                        </tr>
                                    </thead>
                                    <tbody id="obstacles-list">
                                        <!-- Dynamic rows -->
                                    </tbody>
                                </table>
                            </div>
                            <button type="button" class="btn btn-outline-primary btn-sm w-100 rounded-3 dashed-border"
                                onclick="addObstacle()">
                                <i class="bi bi-plus-circle me-2"></i>{{ __('wireless.add_obstacle') }}
                            </button>
                            <small class="text-muted d-block mt-2 fst-italic">
                                {{ __('wireless.obs_note') }}
                            </small>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg rounded-3 fw-bold">
                                <i class="bi bi-calculator me-2"></i>{{ __('wireless.calculate') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Right Column: Results -->
        <div class="col-lg-8">
            <div class="card rounded-4 border-0 shadow-sm h-100">
                <div class="card-header bg-transparent border-bottom-0 py-3 px-4">
                    <div class="d-flex align-items-center gap-3">
                        <div class="icon-box bg-light-success text-success rounded-circle"
                            style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                            <i class="bi bi-bar-chart-line fs-5"></i>
                        </div>
                        <h5 class="mb-0 fw-bold">{{ __('wireless.results') }}</h5>
                    </div>
                </div>
                <div class="card-body px-4">
                    <div id="results-placeholder" class="text-center py-5">
                        <div class="mb-3">
                            <div class="icon-box bg-light text-secondary rounded-circle mx-auto"
                                style="width: 80px; height: 80px; display: flex; align-items: center; justify-content: center;">
                                <i class="bi bi-search fs-1"></i>
                            </div>
                        </div>
                        <h5 class="text-secondary">{{ __('wireless.placeholder_title') }}</h5>
                        <p class="text-muted small">{{ __('wireless.placeholder_text') }}</p>
                    </div>

                    <div id="results-container" style="display: none;">
                        <!-- Top Summary Cards -->
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <div class="card bg-light border-0 rounded-4 p-3 h-100 position-relative overflow-hidden">
                                    <div class="position-absolute top-0 end-0 p-3 opacity-25">
                                        <i class="bi bi-speedometer2 fs-1 text-primary"></i>
                                    </div>
                                    <div class="d-flex flex-column">
                                        <small
                                            class="text-muted font-weight-bold text-uppercase">{{ __('wireless.link_quality') }}</small>
                                        <div class="mt-2">
                                            <h3 class="mb-0 fw-bold text-primary" id="quality-text">--</h3>
                                            <span class="badge bg-primary text-white mt-1"
                                                id="quality-status">WAITING</span>
                                        </div>
                                        <div class="mt-3 small text-muted">
                                            {{ __('wireless.based_on') }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card bg-light border-0 rounded-4 p-3 h-100 position-relative overflow-hidden">
                                    <div class="position-absolute top-0 end-0 p-3 opacity-25">
                                        <i class="bi bi-hdd-network fs-1 text-info"></i>
                                    </div>
                                    <div class="d-flex flex-column">
                                        <small
                                            class="text-muted font-weight-bold text-uppercase">{{ __('wireless.est_capacity') }}</small>
                                        <div class="mt-2">
                                            <h3 class="mb-0 fw-bold text-dark" id="val-throughput">--</h3>
                                            <span class="text-secondary small">Mbps</span>
                                        </div>
                                        <div class="mt-3 small text-muted">
                                            {{ __('wireless.udp_capacity') }}: <strong id="val-udp">--</strong> Mbps
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Metrics Grid -->
                        <div class="row row-cols-2 row-cols-md-4 g-3 mb-4">
                            <div class="col">
                                <div class="p-3 border rounded-4 text-center h-100 bg-white shadow-sm">
                                    <div class="text-primary mb-1"><i class="bi bi-wifi fs-4"></i></div>
                                    <div class="text-secondary small text-uppercase fw-bold">{{ __('wireless.rx_signal') }}
                                    </div>
                                    <div class="fs-5 fw-bold text-dark" id="val-rss">--</div>
                                    <small class="text-muted">dBm</small>
                                </div>
                            </div>
                            <div class="col">
                                <div class="p-3 border rounded-4 text-center h-100 bg-white shadow-sm">
                                    <div class="text-warning mb-1"><i class="bi bi-graph-down-arrow fs-4"></i></div>
                                    <div class="text-secondary small text-uppercase fw-bold">{{ __('wireless.path_loss') }}
                                    </div>
                                    <div class="fs-5 fw-bold text-dark" id="val-fspl">--</div>
                                    <small class="text-muted">dB</small>
                                </div>
                            </div>
                            <div class="col">
                                <div class="p-3 border rounded-4 text-center h-100 bg-white shadow-sm">
                                    <div class="text-success mb-1"><i class="bi bi-shield-check fs-4"></i></div>
                                    <div class="text-secondary small text-uppercase fw-bold">
                                        {{ __('wireless.fade_margin') }}
                                    </div>
                                    <div class="fs-5 fw-bold text-dark" id="val-fade">--</div>
                                    <small class="text-muted">dB</small>
                                </div>
                            </div>
                            <div class="col">
                                <div class="p-3 border rounded-4 text-center h-100 bg-white shadow-sm">
                                    <div class="text-info mb-1"><i class="bi bi-circle fs-4"></i></div>
                                    <div class="text-secondary small text-uppercase fw-bold">{{ __('wireless.fresnel_60') }}
                                    </div>
                                    <div class="fs-5 fw-bold text-dark" id="val-fresnel">--</div>
                                    <small class="text-muted">meters</small>
                                </div>
                            </div>
                        </div>

                        <!-- Diagram -->
                        <h6 class="text-uppercase text-secondary fw-bold small mb-3">{{ __('wireless.link_visualization') }}
                        </h6>
                        <div class="diagram-container bg-light rounded-4 border p-2 mb-4 d-flex justify-content-center align-items-center"
                            id="link-diagram" style="min-height: 300px;">
                            <!-- SVG will be injected here -->
                        </div>

                        <!-- Detailed Table -->
                        <h6 class="text-uppercase text-secondary fw-bold small mb-3">{{ __('wireless.detailed_metrics') }}
                        </h6>
                        <div class="card rounded-4 border-0 shadow-sm mb-4">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0 align-middle">
                                    <thead class="bg-light">
                                        <tr>
                                            <th class="border-top-0 ps-4 text-secondary small text-uppercase">
                                                {{ __('wireless.metric') }}
                                            </th>
                                            <th class="border-top-0 text-end pe-4 text-secondary small text-uppercase">
                                                {{ __('wireless.value') }}
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="ps-4">{{ __('wireless.phy_rate') }}</td>
                                            <td class="text-end pe-4 fw-bold" id="val-phy">--</td>
                                        </tr>
                                        <tr>
                                            <td class="ps-4">{{ __('wireless.fresnel_radius') }}</td>
                                            <td class="text-end pe-4 fw-bold" id="val-fresnel-full">--</td>
                                        </tr>
                                        <tr>
                                            <td class="ps-4">{{ __('wireless.rain_attenuation') }}</td>
                                            <td class="text-end pe-4 fw-bold" id="val-rain-loss">--</td>
                                        </tr>
                                        <tr>
                                            <td class="ps-4">{{ __('wireless.signal_rain') }}</td>
                                            <td class="text-end pe-4 fw-bold" id="val-rss-rain">--</td>
                                        </tr>
                                        <tr>
                                            <td class="ps-4">{{ __('wireless.eirp') }}</td>
                                            <td class="text-end pe-4 fw-bold" id="val-eirp">--</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Recommendations -->
                        <h6 class="text-uppercase text-secondary fw-bold small mb-3">
                            {{ __('wireless.system_recommendations') }}
                        </h6>
                        <div id="recommendations-list" class="d-flex flex-column gap-2">
                            <!-- JS populated -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        window.wirelessLang = {
            site_a: "{{ __('wireless.site_a') }}",
            site_b: "{{ __('wireless.site_b') }}",
            rec_low_fade: "{{ __('wireless.rec_low_fade') }}",
            rec_weak_signal: "{{ __('wireless.rec_weak_signal') }}",
            rec_fresnel_issue: "{{ __('wireless.rec_fresnel_issue') }}",
            rec_good_link: "{{ __('wireless.rec_good_link') }}",
        };
    </script>
    @vite(['resources/sass/pages/mikrotik-suite/wireless/link-planner.scss', 'resources/js/pages/mikrotik-suite/wireless/wireless-link-planner.js'])
@endsection
