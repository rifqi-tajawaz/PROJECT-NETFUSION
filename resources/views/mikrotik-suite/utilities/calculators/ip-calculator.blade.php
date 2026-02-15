@extends('layouts.app')

@section('content')
    <div class="container-fluid px-4 py-4">
        <!-- Hero Header -->
        <div class="mb-4 d-flex flex-column align-items-center justify-content-center text-center">
            <h2 class="display-6 fw-bold mb-2 text-white" style="text-shadow: 0 2px 10px rgba(0,0,0,0.3);">
                <i class="bi bi-calculator me-2 text-info"></i> IPv4 Calculator
            </h2>
            <p class="lead opacity-75 mb-0" style="max-width: 700px;">
                Calculate network parameters, subnet masks, and usable host ranges instantly.
            </p>
        </div>

        <form id="ipCalcForm" class="needs-validation"
            data-route="{{ route('mikrotik-suite.utilities.calculators.ip.calculate') }}" novalidate>
            <div class="row g-4 justify-content-center">
                <!-- Configuration -->
                <div class="col-lg-5">
                    <div class="glass-card h-100">
                        <div class="card-header border-0 bg-transparent pt-4 px-4">
                            <h5 class="card-title fw-bold text-white mb-0">
                                <i class="bi bi-input-cursor-text me-2 text-primary"></i>Input
                            </h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="mb-3">
                                <label class="form-label text-white small text-uppercase fw-bold">IP Address</label>
                                <input type="text"
                                    class="form-control font-monospace border border-secondary border-opacity-25"
                                    id="ipAddress" name="ip" placeholder="192.168.88.1" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label text-white small text-uppercase fw-bold">Subnet Mask (CIDR)</label>
                                <select class="form-select font-monospace" id="cidr" name="cidr" required>
                                    <option value="" disabled selected>Select Mask</option>
                                    @for ($i = 0; $i <= 32; $i++)
                                        @php
                                            $hosts = ($i == 32) ? 1 : (($i == 31) ? 2 : pow(2, 32 - $i) - 2);
                                            if ($hosts < 0)
                                                $hosts = 0;
                                            $maskLong = -1 << (32 - $i);
                                            $maskStr = long2ip($maskLong);
                                            $selected = ($i == 24) ? 'selected' : '';
                                        @endphp
                                        <option value="{{ $i }}" {{ $selected }}>/{{ $i }} - {{ $maskStr }}
                                            ({{ number_format($hosts) }} Hosts)</option>
                                    @endfor
                                </select>
                            </div>

                            <div class="d-grid mt-4">
                                <button type="submit" class="btn btn-primary btn-lg shadow-lg"
                                    style="background: linear-gradient(90deg, #0dcaf0, #0d6efd); border:none;">
                                    <i class="bi bi-gear-wide-connected me-2"></i> Calculate
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Result -->
                <div class="col-lg-7">
                    <div class="glass-card h-100 position-relative overhead-hidden">
                        <div class="card-header border-0 bg-transparent pt-4 px-4">
                            <h5 class="card-title fw-bold text-white mb-0">
                                <i class="bi bi-hdd-network me-2 text-success"></i>Network Layout
                            </h5>
                        </div>
                        <div class="card-body p-4">
                            <div id="resultContent" style="display:none;" class="animate__animated animate__fadeIn">
                                <div class="row g-3 mb-4">
                                    <div class="col-md-6">
                                        <div
                                            class="p-3 bg-dark bg-opacity-50 rounded-3 border border-secondary text-center">
                                            <small class="text-muted d-block text-uppercase">Network Address</small>
                                            <h4 class="text-white font-monospace mb-0" id="resNetwork">...</h4>
                                            <span class="badge bg-primary" id="resCidr">/24</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div
                                            class="p-3 bg-dark bg-opacity-50 rounded-3 border border-secondary text-center">
                                            <small class="text-muted d-block text-uppercase">Broadcast</small>
                                            <h4 class="text-warning font-monospace mb-0" id="resBroadcast">...</h4>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="p-2 bg-dark bg-opacity-25 rounded-3 text-center border border-dark">
                                            <small class="text-secondary d-block">Subnet Mask</small>
                                            <div class="text-white font-monospace" id="resMask">...</div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="p-2 bg-dark bg-opacity-25 rounded-3 text-center border border-dark">
                                            <small class="text-secondary d-block">Usable Hosts</small>
                                            <div class="text-success font-monospace" id="resHosts">...</div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="p-2 bg-dark bg-opacity-25 rounded-3 text-center border border-dark">
                                            <small class="text-secondary d-block">IP Class</small>
                                            <div class="text-info font-monospace" id="resClass">...</div>
                                        </div>
                                    </div>
                                </div>

                                <div class="bg-dark bg-opacity-25 p-3 rounded-3 border border-secondary">
                                    <h6 class="text-white small text-uppercase mb-3"><i
                                            class="bi bi-arrows-expand me-2"></i>Host Range</h6>
                                    <div class="d-flex justify-content-between align-items-center text-white px-2">
                                        <div class="text-start">
                                            <small class="text-secondary d-block">First IP</small>
                                            <span class="font-monospace fs-5" id="resFirst">...</span>
                                        </div>
                                        <div class="text-secondary opacity-50"><i class="bi bi-arrow-right fs-4"></i></div>
                                        <div class="text-end">
                                            <small class="text-secondary d-block">Last IP</small>
                                            <span class="font-monospace fs-5" id="resLast">...</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="bg-dark bg-opacity-25 p-3 rounded-3 border border-secondary mt-3">
                                    <pre class="m-0 text-white-50 small font-monospace" id="resBinary">Binary info...</pre>
                                </div>
                            </div>

                            <div id="placeholder" class="text-center text-muted py-5 opacity-50">
                                <i class="bi bi-calculator display-1 mb-3 d-block"></i>
                                <p>Enter IP details to calculate</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    @vite(['resources/js/pages/mikrotik-suite/utilities/calculators/ip-calculator.js'])
@endpush
