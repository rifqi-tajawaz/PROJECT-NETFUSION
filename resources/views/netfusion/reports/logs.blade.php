@extends('layouts.app')

@section('title', __('netfusion.system_logs'))

@section('content')
    <div class="container-fluid px-4 py-4">

        <!-- Controls Header -->
        <div class="d-flex flex-column flex-xl-row justify-content-between align-items-xl-center mb-4 gap-3">
            <div class="d-flex align-items-center gap-3">
                <div class="bg-white p-3 rounded-circle shadow-sm d-flex align-items-center justify-content-center"
                    style="width: 50px; height: 50px;">
                    <i class="bi bi-terminal-fill text-dark fs-4"></i>
                </div>
                <div>
                    <h4 class="fw-bold text-dark mb-1">{{ __('netfusion.system_logs') }}</h4>
                    <p class="text-muted small mb-0">{{ __('netfusion.monitor_routeros_events') }}</p>
                </div>
            </div>

            <div class="d-flex gap-2 flex-grow-1 flex-xl-grow-0 align-items-center w-100 w-xl-auto overflow-visible">
                <!-- Search Form -->
                <form action="{{ route('mikrotik-suite.netfusion.reports.logs') }}" method="GET"
                    class="d-flex gap-2 flex-grow-1 w-100 w-xl-auto min-w-0">
                    <div class="dropdown">
                        <input type="hidden" name="topic" id="topicInput" value="{{ $selectedTopic }}">
                        <button
                            class="btn btn-light form-select border border-secondary border-opacity-25 shadow-sm rounded-pill bg-white bg-opacity-75 flex-shrink-1 text-start text-truncate"
                            style="width: 130px; height: 48px; backdrop-filter: blur(10px);" type="button"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            {{ $selectedTopic ? ucfirst($selectedTopic) : __('netfusion.topics') }}
                        </button>
                        <ul class="dropdown-menu border-0 shadow-lg rounded-4 p-2 bg-white custom-scrollbar"
                            style="z-index: 1050; max-height: 300px; overflow-y: auto;">
                            <li>
                                <a class="dropdown-item rounded-3 mb-1 {{ $selectedTopic == '' ? 'active bg-light text-dark fw-bold' : '' }}"
                                    href="#"
                                    onclick="event.preventDefault(); document.getElementById('topicInput').value=''; this.closest('form').submit();">
                                    {{ __('netfusion.all_topics') }}
                                </a>
                            </li>
                            @foreach($topics as $topic)
                                <li>
                                    <a class="dropdown-item rounded-3 mb-1 {{ $selectedTopic == $topic ? 'active bg-light text-dark fw-bold' : '' }}"
                                        href="#"
                                        onclick="event.preventDefault(); document.getElementById('topicInput').value='{{ $topic }}'; this.closest('form').submit();">
                                        {{ ucfirst($topic) }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="position-relative flex-grow-1 min-w-0">
                        <input type="text" name="search" id="searchInput"
                            class="form-control border border-secondary border-opacity-25 shadow-sm rounded-pill ps-4 pe-5 bg-white bg-opacity-75"
                            style="height: 48px; backdrop-filter: blur(10px);" placeholder="Search..."
                            value="{{ $search ?? '' }}">
                        <i
                            class="bi bi-search position-absolute top-50 end-0 translate-middle-y me-3 text-secondary opacity-50"></i>
                    </div>
                </form>

                <div class="d-flex gap-2 flex-shrink-0">
                    <div class="dropdown">
                        <button
                            class="btn btn-light border-0 shadow-sm rounded-circle p-2 hover-scale bg-white d-flex align-items-center justify-content-center"
                            type="button" data-bs-toggle="dropdown" aria-expanded="false"
                            style="width: 48px; height: 48px;">
                            <i class="bi bi-gear-fill text-secondary fs-5"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 rounded-4 p-2 bg-white"
                            style="min-width: 260px; z-index: 1050;">
                            <li>
                                <h6 class="dropdown-header text-uppercase small fw-bold text-muted ls-1">
                                    {{ __('netfusion.actions') }}</h6>
                            </li>
                            <li>
                                <a class="dropdown-item d-flex align-items-center gap-2 rounded-3 mb-1 py-2" href="#"
                                    id="btn-export">
                                    <i class="bi bi-download text-success"></i> {{ __('netfusion.export_csv') }}
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item d-flex align-items-center gap-2 rounded-3 mb-1 py-2"
                                    href="{{ route('mikrotik-suite.netfusion.reports.logs') }}">
                                    <i class="bi bi-arrow-clockwise text-primary"></i> {{ __('netfusion.force_refresh') }}
                                </a>
                            </li>
                            <li>
                                <hr class="dropdown-divider my-2 opacity-10">
                            </li>
                            <li>
                                <h6 class="dropdown-header text-uppercase small fw-bold text-muted ls-1">
                                    {{ __('netfusion.view_settings') }}</h6>
                            </li>
                            <li>
                                <div
                                    class="dropdown-item-text d-flex justify-content-between align-items-center py-2 rounded-3">
                                    <label class="form-check-label small fw-medium text-dark cursor-pointer mb-0"
                                        for="groupDuplicatesToggle">{{ __('netfusion.group_duplicates') }}</label>
                                    <div class="form-check form-switch mb-0 min-h-0">
                                        <input class="form-check-input ms-0 cursor-pointer" type="checkbox"
                                            id="groupDuplicatesToggle">
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div
                                    class="dropdown-item-text d-flex justify-content-between align-items-center py-2 rounded-3">
                                    <label class="form-check-label small fw-medium text-dark cursor-pointer mb-0"
                                        for="soundAlertToggle">{{ __('netfusion.error_sound') }}</label>
                                    <div class="form-check form-switch mb-0 min-h-0">
                                        <input class="form-check-input ms-0 cursor-pointer" type="checkbox"
                                            id="soundAlertToggle">
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div
                                    class="dropdown-item-text d-flex justify-content-between align-items-center py-2 rounded-3">
                                    <label class="form-check-label small fw-medium text-dark cursor-pointer mb-0"
                                        for="autoRefreshToggle">{{ __('netfusion.auto_refresh_5s') }}</label>
                                    <div class="form-check form-switch mb-0 min-h-0">
                                        <input class="form-check-input ms-0 cursor-pointer" type="checkbox"
                                            id="autoRefreshToggle">
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Smart Analysis Panel -->
        <div class="row g-3 mb-4" id="analysis-panel">
            <div class="col-md-4">
                <div class="card bg-danger bg-opacity-10 border-danger border-opacity-25 shadow-sm rounded-4 h-100 hover-lift"
                    style="backdrop-filter: blur(10px);">
                    <div class="card-body py-3 d-flex align-items-center gap-3">
                        <div class="bg-danger text-white rounded-circle d-flex align-items-center justify-content-center shadow-sm"
                            style="width: 48px; height: 48px;">
                            <i class="bi bi-exclamation-triangle-fill fs-5"></i>
                        </div>
                        <div>
                            <h6 class="text-danger fw-bold mb-0">{{ __('netfusion.errors_critical') }}</h6>
                            <span class="small text-muted" id="stat-error-count">0 {{ __('netfusion.events_found') }}</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-warning bg-opacity-10 border-warning border-opacity-25 shadow-sm rounded-4 h-100 hover-lift"
                    style="backdrop-filter: blur(10px);">
                    <div class="card-body py-3 d-flex align-items-center gap-3">
                        <div class="bg-warning text-white rounded-circle d-flex align-items-center justify-content-center shadow-sm"
                            style="width: 48px; height: 48px;">
                            <i class="bi bi-cone-striped fs-5"></i>
                        </div>
                        <div>
                            <h6 class="text-warning fw-bold mb-0">{{ __('netfusion.warnings') }}</h6>
                            <span class="small text-muted" id="stat-warning-count">0 {{ __('netfusion.events_found') }}</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-info bg-opacity-10 border-info border-opacity-25 shadow-sm rounded-4 h-100 hover-lift"
                    style="backdrop-filter: blur(10px);">
                    <div class="card-body py-3">
                        <div class="d-flex align-items-center gap-3 mb-2">
                            <div class="bg-info text-white rounded-circle d-flex align-items-center justify-content-center shadow-sm"
                                style="width: 48px; height: 48px;">
                                <i class="bi bi-activity fs-5"></i>
                            </div>
                            <div>
                                <h6 class="text-info fw-bold mb-0">{{ __('netfusion.log_composition') }}</h6>
                                <span class="small text-muted"
                                    id="stat-activity-status">{{ __('netfusion.analyzing') }}</span>
                            </div>
                        </div>
                        <!-- Distribution Bar -->
                        <div class="progress bg-white bg-opacity-50 shadow-sm" style="height: 6px;">
                            <div class="progress-bar bg-danger" id="bar-error" role="progressbar" style="width: 0%"></div>
                            <div class="progress-bar bg-warning" id="bar-warning" role="progressbar" style="width: 0%">
                            </div>
                            <div class="progress-bar bg-success opacity-75" id="bar-info" role="progressbar"
                                style="width: 0%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Copy Toast -->
        <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 1080">
            <div id="copyToast" class="toast align-items-center text-white bg-success border-0 rounded-4" role="alert"
                aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body d-flex align-items-center gap-2">
                        <i class="bi bi-clipboard-check-fill"></i> <span
                            id="toast-message">{{ __('netfusion.copied') }}</span>
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                        aria-label="Close"></button>
                </div>
            </div>
        </div>

        <!-- Terminal Card -->
        <div class="card border-0 shadow-lg rounded-4 overflow-hidden bg-dark text-light d-flex flex-column"
            style="height: 75vh;">
            <div
                class="card-header bg-black bg-opacity-50 border-bottom border-secondary border-opacity-25 p-3 d-flex align-items-center justify-content-between flex-shrink-0">
                <div class="d-flex align-items-center gap-2">
                    <div class="d-flex gap-1 me-2">
                        <div class="rounded-circle bg-danger" style="width: 10px; height: 10px;"></div>
                        <div class="rounded-circle bg-warning" style="width: 10px; height: 10px;"></div>
                        <div class="rounded-circle bg-success" style="width: 10px; height: 10px;"></div>
                    </div>
                    <span class="font-monospace text-muted small">
                        user@mikrotik:~/logs$
                        @if($selectedTopic) grep "{{ $selectedTopic }}" @endif
                        @if($search) | grep "{{ $search }}" @endif
                    </span>
                </div>
                <div class="d-flex align-items-center gap-3">
                    <span id="refresh-indicator"
                        class="badge bg-secondary bg-opacity-25 text-light border border-secondary border-opacity-25 rounded-pill fw-normal px-2 small d-none">
                        <i class="bi bi-arrow-repeat spin"></i> Syncing...
                    </span>
                    <span
                        class="badge bg-success bg-opacity-25 text-success border border-success border-opacity-25 rounded-pill fw-normal px-2 small">
                        <span id="visible-count">{{ count($logs) }}</span> / {{ count($logs) }} Lines
                    </span>
                </div>
            </div>
            <div class="card-body p-0 d-flex flex-column overflow-hidden">
                <div class="table-responsive flex-grow-1" style="overflow-y: auto;" id="logs-container">
                    <table class="table table-dark table-hover mb-0 font-monospace" style="font-size: 0.85rem;"
                        id="logs-table">
                        <thead
                            class="bg-black bg-opacity-50 text-secondary text-uppercase border-bottom border-secondary border-opacity-25 sticky-top">
                            <tr>
                                <th class="ps-4 py-2 fw-bold border-0" style="width: 160px;">{{ __('netfusion.time') }}</th>
                                <th class="py-2 fw-bold border-0" style="width: 220px;">{{ __('netfusion.topic') }}</th>
                                <th class="py-2 fw-bold border-0">{{ __('netfusion.message') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($logs as $log)
                                <tr class="border-secondary border-opacity-25 border-bottom log-row">
                                    <td class="ps-4 py-1 text-secondary opacity-75 small align-middle text-nowrap">
                                        {{ $log['time'] ?? '-' }}
                                    </td>
                                    <td class="py-1 align-middle">
                                        @php
                                            $topics = explode(',', $log['topics'] ?? 'system');
                                        @endphp
                                        <div class="d-flex flex-wrap gap-1">
                                            @foreach($topics as $topic)
                                                @php
                                                    $colorClass = match ($topic) {
                                                        'error', 'critical' => 'text-danger',
                                                        'warning' => 'text-warning',
                                                        'account' => 'text-info',
                                                        'hotspot' => 'text-success',
                                                        'dhcp', 'pppoe' => 'text-primary',
                                                        'info' => 'text-light',
                                                        'debug' => 'text-secondary',
                                                        default => 'text-secondary'
                                                    };
                                                @endphp
                                                <span class="{{ $colorClass }} fw-bold small">{{ $topic }}</span>
                                            @endforeach
                                        </div>
                                    </td>
                                    <td class="py-1 text-light opacity-90 align-middle text-break">
                                        @php
                                            $msg = $log['message'] ?? '-';
                                            // Smart Highlighting & Interactivity
                                            // 1. IP Addresses (Click to Copy)
                                            $msg = preg_replace('/(\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3})/', '<span class="text-info fw-bold cursor-pointer" onclick="copyToClipboard(this.innerText)" title="Click to copy IP" role="button">$1</span>', $msg);
                                            // 2. MAC Addresses (Click to Copy)
                                            $msg = preg_replace('/([0-9A-Fa-f]{2}[:-]){5}([0-9A-Fa-f]{2})/', '<span class="text-primary font-monospace cursor-pointer" onclick="copyToClipboard(this.innerText)" title="Click to copy MAC" role="button">$0</span>', $msg);
                                            // 3. Usernames
                                            $msg = preg_replace('/(user) (\w+)/', '$1 <span class="text-white fw-bold">$2</span>', $msg);
                                            // 4. Status Keywords
                                            $msg = preg_replace('/\b(down|failed|error|critical|failure)\b/i', '<span class="text-danger fw-bold text-uppercase" style="font-size:0.85em;">$1</span>', $msg);
                                            $msg = preg_replace('/\b(up|running|established|success)\b/i', '<span class="text-success fw-bold text-uppercase" style="font-size:0.85em;">$1</span>', $msg);
                                        @endphp
                                        {!! $msg !!}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center py-5">
                                        <div class="text-muted opacity-50">
                                            <i class="bi bi-terminal-x fs-1 d-block mb-3"></i>
                                            <h6>{{ __('netfusion.no_logs_found') }}</h6>
                                            <p class="small mb-0">{{ __('netfusion.try_changing_search') }}</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- Status Bar Footer -->
            <div class="card-footer bg-dark border-top border-secondary border-opacity-25 py-1 px-3 rounded-bottom-4">
                <div class="d-flex justify-content-between align-items-center">
                    <span class="text-secondary small font-monospace" style="font-size: 0.75rem;">
                        <span class="text-success">●</span> {{ __('netfusion.connected_to') }}
                        {{ session('router_ip') ?? 'Router' }}
                    </span>
                    <span class="text-secondary small font-monospace" style="font-size: 0.75rem;">
                        UTF-8 | LF | <span id="clock-display" class="text-light">00:00:00</span>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Live Clock for Footer
            function updateClock() {
                const now = new Date();
                const timeString = now.toLocaleTimeString('en-US', { hour12: false });
                const el = document.getElementById('clock-display');
                if (el) el.innerText = timeString;
            }
            setInterval(updateClock, 1000);
            updateClock();

            // --- Helper Functions ---

            // 1. Copy to Clipboard
            window.copyToClipboard = function (text) {
                if (!navigator.clipboard) return;
                navigator.clipboard.writeText(text).then(() => {
                    const toastEl = document.getElementById('copyToast');
                    if (toastEl) {
                        const messageEl = document.getElementById('toast-message');
                        if (messageEl) messageEl.innerText = `{{ __('netfusion.copied_text', ['text' => '${text}']) }}`;
                        const toast = new bootstrap.Toast(toastEl, { delay: 2000 });
                        toast.show();
                    }
                }).catch(err => console.error("Clipboard Error:", err));
            };

            // 2. Play Audio Alert
            function playBeep() {
                try {
                    const ctx = new (window.AudioContext || window.webkitAudioContext)();
                    const osc = ctx.createOscillator();
                    osc.type = 'sine';
                    osc.frequency.setValueAtTime(880, ctx.currentTime);
                    osc.connect(ctx.destination);
                    osc.start();
                    osc.stop(ctx.currentTime + 0.15); // 150ms beep
                } catch (e) {
                    // Audio context might be blocked if no user interaction yet
                    console.warn("Audio alert prevented:", e);
                }
            }

            // --- Main Logic ---

            const rows = document.querySelectorAll('.log-row');
            const searchInput = document.getElementById('searchInput');
            const countSpan = document.getElementById('visible-count');
            const groupToggle = document.getElementById('groupDuplicatesToggle');
            const soundToggle = document.getElementById('soundAlertToggle');
            const refreshToggle = document.getElementById('autoRefreshToggle');
            const refreshIndicator = document.getElementById('refresh-indicator');
            const exportBtn = document.getElementById('btn-export');


            // A. Analysis Update Logic
            function updateAnalysis() {
                const visibleRows = Array.from(rows).filter(row => row.style.display !== 'none');
                const total = visibleRows.length || 1;

                let errorCount = 0;
                let warningCount = 0;
                let infoCount = 0;

                visibleRows.forEach(row => {
                    const text = row.innerText.toLowerCase();
                    if (text.includes('error') || text.includes('critical') || text.includes('failure')) errorCount++;
                    else if (text.includes('warning')) warningCount++;
                    else infoCount++;
                });

                const errEl = document.getElementById('stat-error-count');
                const warnEl = document.getElementById('stat-warning-count');
                const actEl = document.getElementById('stat-activity-status');

                if (errEl) errEl.innerText = `${errorCount} {{ __('netfusion.events_found') }}`;
                if (warnEl) warnEl.innerText = `${warningCount} {{ __('netfusion.events_found') }}`;
                if (actEl) actEl.innerText = `${visibleRows.length} Visible Logs`;

                const barErr = document.getElementById('bar-error');
                const barWarn = document.getElementById('bar-warning');
                const barInfo = document.getElementById('bar-info');

                if (barErr) barErr.style.width = `${(errorCount / total) * 100}%`;
                if (barWarn) barWarn.style.width = `${(warningCount / total) * 100}%`;
                if (barInfo) barInfo.style.width = `${(infoCount / total) * 100}%`;
            }

            // B. Advanced Features (Deduplication + Sound)
            function processAdvancedFeatures() {
                const isGrouped = groupToggle ? groupToggle.checked : false;
                const isSound = soundToggle ? soundToggle.checked : false;

                let previousMessage = '';
                let duplicateCount = 1;
                let masterRow = null;
                let hasFreshErrors = false;

                // 1. Reset specific duplicate-hiding class and check for sound
                rows.forEach(row => {
                    // Reset duplicate hiding state
                    if (row.classList.contains('d-none-dup')) {
                        row.classList.remove('d-none-dup');
                        row.style.display = '';
                    }

                    // Remove old badges
                    const countBadge = row.querySelector('.dup-badge');
                    if (countBadge) countBadge.remove();

                    // Check for fresh errors (only on visible rows)
                    if (isSound && row.style.display !== 'none') {
                        const txt = row.innerText.toLowerCase();
                        if (txt.includes('error') || txt.includes('critical')) hasFreshErrors = true;
                    }
                });

                // 2. Apply Deduplication Grouping
                if (isGrouped) {
                    rows.forEach(row => {
                        if (row.style.display === 'none') return; // Skip currently hidden rows (by search)

                        const messageCell = row.querySelectorAll('td')[2];
                        const text = messageCell ? messageCell.innerText.trim() : '';

                        if (text === previousMessage && masterRow) {
                            // Found duplicate
                            row.style.display = 'none';
                            row.classList.add('d-none-dup'); // Mark as hidden by dedupe
                            duplicateCount++;

                            // Add badge to master
                            let badge = masterRow.querySelector('.dup-badge');
                            if (!badge) {
                                badge = document.createElement('span');
                                badge.className = 'badge bg-secondary rounded-pill ms-2 dup-badge';
                                masterRow.querySelectorAll('td')[2].appendChild(badge);
                            }
                            badge.innerText = `x${duplicateCount}`;
                        } else {
                            // New entry
                            previousMessage = text;
                            masterRow = row;
                            duplicateCount = 1;
                        }
                    });
                }

                // 3. Play Sound if needed
                // Only play if we are in a "fresh" state (e.g. indicator is showing implies a refresh just happened)
                // Or we can just play it if we find errors in the current view?
                // To avoid annoyance, we only play if the refresh indicator was recently active?
                // For now, let's keep it simple: If sound is on and we see errors, play ONCE per refresh.
                // But this function runs on search input too. We don't want beeping while typing.
                // So we should verify if this call was triggered by search or load.
                // We will rely on the fact that sound is usually for Monitoring (Auto Refresh).
                if (hasFreshErrors && refreshIndicator && !refreshIndicator.classList.contains('d-none')) {
                    playBeep();
                }
            }


            // --- Event Listeners ---

            // 1. Search Logic
            if (searchInput) {
                let debounceTimer;
                const form = searchInput.closest('form');

                searchInput.addEventListener('input', function (e) {
                    const term = e.target.value.toLowerCase();

                    // A. Instant Client-Side Filter
                    let visible = 0;
                    rows.forEach(row => {
                        // Reset "dup" hidden state first so we search everything
                        if (row.classList.contains('d-none-dup')) {
                            row.classList.remove('d-none-dup');
                            row.style.display = '';
                        }

                        const text = row.innerText.toLowerCase();
                        if (text.includes(term)) {
                            row.style.display = ''; // Show
                            visible++;
                        } else {
                            row.style.display = 'none'; // Hide
                        }
                    });
                    if (countSpan) countSpan.innerText = visible;

                    // B. Run Search-Dependent Features
                    processAdvancedFeatures();

                    // C. Update Stats
                    updateAnalysis();

                    // D. Server Side Debounce
                    clearTimeout(debounceTimer);
                    debounceTimer = setTimeout(() => {
                        if (refreshIndicator) {
                            refreshIndicator.classList.remove('d-none');
                            refreshIndicator.innerHTML = '<i class="bi bi-search spin"></i> Searching Server...';
                        }
                        form.submit();
                    }, 1500);
                });
            }

            // 2. Toggles
            if (groupToggle) {
                if (localStorage.getItem('logsGroupDuplicates') === 'true') {
                    groupToggle.checked = true;
                }

                groupToggle.addEventListener('change', () => {
                    localStorage.setItem('logsGroupDuplicates', groupToggle.checked);
                    if (searchInput && searchInput.value.length > 0) {
                        searchInput.dispatchEvent(new Event('input'));
                    } else {
                        // If no search term, just re-run features or reset
                        if (!groupToggle.checked) {
                            // Reset if untoggled
                            rows.forEach(row => {
                                if (row.classList.contains('d-none-dup')) {
                                    row.classList.remove('d-none-dup');
                                    row.style.display = '';
                                }
                                const b = row.querySelector('.dup-badge');
                                if (b) b.remove();
                            });
                        } else {
                            processAdvancedFeatures();
                        }
                    }
                });
            }

            if (soundToggle) {
                if (localStorage.getItem('logsSoundAlert') === 'true') soundToggle.checked = true;
                soundToggle.addEventListener('change', () => localStorage.setItem('logsSoundAlert', soundToggle.checked));
            }

            // 3. Auto Refresh
            let refreshInterval;
            function startRefresh() {
                if (refreshIndicator) refreshIndicator.classList.remove('d-none');
                refreshInterval = setInterval(() => window.location.reload(), 5000);
            }
            function stopRefresh() {
                if (refreshIndicator) refreshIndicator.classList.add('d-none');
                clearInterval(refreshInterval);
            }

            if (refreshToggle) {
                if (localStorage.getItem('logsAutoRefresh') === 'true') {
                    refreshToggle.checked = true;
                    startRefresh();
                }
                refreshToggle.addEventListener('change', function () {
                    localStorage.setItem('logsAutoRefresh', this.checked);
                    if (this.checked) startRefresh(); else stopRefresh();
                });
            }

            // 4. Export CSV
            if (exportBtn) {
                exportBtn.addEventListener('click', function (e) {
                    e.preventDefault();
                    let csvContent = "data:text/csv;charset=utf-8,";
                    csvContent += "Time,Topic,Message\n";
                    let visibleCount = 0;

                    const rows = document.querySelectorAll('.log-row');

                    rows.forEach(function (row) {
                        // Smart Export: Only export what the user currently sees
                        if (row.style.display !== 'none') {
                            let cols = row.querySelectorAll('td');
                            let rowData = [];
                            // Time
                            rowData.push('"' + cols[0].innerText + '"');
                            // Topic (clean up text)
                            rowData.push('"' + cols[1].innerText.replace(/\n/g, '').trim() + '"');
                            // Message
                            rowData.push('"' + cols[2].innerText.replace(/"/g, '""') + '"');

                            csvContent += rowData.join(",") + "\n";
                            visibleCount++;
                        }
                    });

                    if (visibleCount === 0) {
                        alert("{{ __('netfusion.no_logs_to_export') }}");
                        return;
                    }

                    // Smart Filename
                    const topicSelect = document.querySelector('select[name="topic"]');
                    const selectedTopic = topicSelect ? topicSelect.value : '';
                    const baseName = selectedTopic ? `mikrotik_logs_${selectedTopic}` : 'mikrotik_logs_all';
                    const dateStr = new Date().toISOString().slice(0, 10);
                    const fileName = `${baseName}_${dateStr}.csv`;

                    const encodedUri = encodeURI(csvContent);
                    const link = document.createElement("a");
                    link.setAttribute("href", encodedUri);
                    link.setAttribute("download", fileName);
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                });
            }

            // --- Initial Run ---
            setTimeout(() => {
                if (groupToggle && groupToggle.checked) {
                    processAdvancedFeatures();
                }
                updateAnalysis();
            }, 100);

        });
    </script>

    <style>
        .hover-scale {
            transition: transform 0.2s ease;
        }

        .hover-scale:hover {
            transform: scale(1.05);
        }

        .hover-lift {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .hover-lift:hover {
            transform: translateY(-4px);
            box-shadow: 0 .5rem 1rem rgba(0, 0, 0, .15) !important;
        }

        .ls-1 {
            letter-spacing: 1px;
        }

        .spin {
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            100% {
                transform: rotate(360deg);
            }
        }


        /* Dark Scrollbar */
        .table-responsive::-webkit-scrollbar,
        .custom-scrollbar::-webkit-scrollbar {
            width: 8px;
        }

        .table-responsive::-webkit-scrollbar-track,
        .custom-scrollbar::-webkit-scrollbar-track {
            background: #1a1d20;
        }

        .table-responsive::-webkit-scrollbar-thumb,
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #495057;
            border-radius: 4px;
        }

        .table-responsive::-webkit-scrollbar-thumb:hover,
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #6c757d;
        }



        @media (min-width: 1200px) {
            .w-xl-auto {
                width: auto !important;
            }
        }
    </style>
@endsection
