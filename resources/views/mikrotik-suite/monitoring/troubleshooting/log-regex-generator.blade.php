@extends('layouts.app')

@section('content')
    <div class="container-fluid px-4 py-4">
        <div class="mb-4 d-flex flex-column align-items-center justify-content-center text-center">
            <h2 class="display-6 fw-bold mb-2 text-white" style="text-shadow: 0 2px 10px rgba(0,0,0,0.3);">
                <i class="bi bi-regex me-2 text-info"></i> Log Regex Generator
            </h2>
            <p class="lead opacity-75 mb-0" style="max-width: 700px;">
                Test and generate Regular Expressions for MikroTik Log filtering.
            </p>
        </div>

        <div class="row g-4 justify-content-center">
            <div class="col-lg-10">
                <div class="glass-card h-100">
                    <div class="card-body p-4">
                        <form id="regexForm"
                            data-route="{{ route('mikrotik-suite.monitoring.troubleshooting.log-regex.generate') }}">
                            <div class="mb-4">
                                <label class="form-label text-white small text-uppercase">Input String (Sample Log)</label>
                                <input type="text" class="form-control font-monospace" name="input_string" id="inputStr"
                                    value="firewall,info forward: in:ether1 out:bridge1, src-mac 00:0c:29:4f:8e:34, proto TCP (SYN), 192.168.88.10:54321->8.8.8.8:53, len 60">
                            </div>
                            <div class="mb-4">
                                <label class="form-label text-white small text-uppercase">Regex Pattern</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-dark border-secondary text-white-50">/</span>
                                    <input type="text" class="form-control font-monospace text-warning" name="pattern"
                                        id="pattern" value="proto TCP \(SYN\)">
                                    <span class="input-group-text bg-dark border-secondary text-white-50">/</span>
                                </div>
                            </div>

                            <div class="d-grid mb-4">
                                <button type="submit" class="btn btn-warning shadow-lg text-dark">
                                    <span class="btn-label">Check Match & Generate Rule</span>
                                    <span class="btn-loader d-none"><span
                                            class="spinner-border spinner-border-sm me-2"></span>Processing...</span>
                                </button>
                            </div>

                            <div class="row mb-4">
                                <div class="col-12">
                                    <div class="p-3 rounded-3 border border-secondary border-opacity-25" id="resultBox"
                                        style="background: rgba(0,0,0,0.3);">
                                        <h6 class="text-white-50 text-uppercase small mb-2">Match Result</h6>
                                        <div id="matchOutput" class="text-white fw-bold">Ready to check...</div>
                                    </div>
                                </div>
                            </div>
                        </form>

                        <hr class="border-secondary opacity-25 my-4">

                        <h6 class="text-white small text-uppercase mb-3">Generated RouterOS Rule</h6>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <small class="text-white-50">Use this to filter logs locally or send to remote syslog.</small>
                            <button class="btn btn-sm btn-outline-light" onclick="copyText('rosRule')">Copy</button>
                        </div>
                        <code
                            class="d-block p-3 rounded bg-dark bg-opacity-50 text-success font-monospace text-wrap text-break"
                            id="rosRule">
                                   /system logging add topics=firewall action=memory message="proto TCP \\(SYN\\)"
                                </code>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    @vite(['resources/js/pages/mikrotik-suite/monitoring/troubleshooting/log-regex-generator.js'])
@endpush
