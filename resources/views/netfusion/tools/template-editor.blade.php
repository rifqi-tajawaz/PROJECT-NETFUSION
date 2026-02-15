@extends('layouts.app')

@section('title', 'Template Editor')

@section('content')
    <div class="container-fluid p-0" style="height: calc(100vh - 80px); overflow: hidden;">
        <div class="row g-0 h-100">

            <!-- Left: Editor Pane -->
            <div class="col-lg-6 d-flex flex-column border-end border-light bg-white">
                <!-- Toolbar -->
                <div class="d-flex justify-content-between align-items-center px-4 py-3 border-bottom">
                    <div>
                        <h5 class="fw-bold text-dark mb-0">{{ __('netfusion.html_editor') }}</h5>
                        <small class="text-secondary">{{ __('netfusion.html_editor_description') }}</small>
                    </div>
                    <div class="d-flex gap-2">
                        <form action="{{ route('mikrotik-suite.netfusion.tools.template-editor.reset') }}" method="POST"
                            onsubmit="return confirm('{{ __('netfusion.confirm_reset_template') }}');">
                            @csrf
                            <button
                                class="btn btn-outline-danger btn-sm rounded-pill px-3">{{ __('netfusion.reset_default') }}</button>
                        </form>
                        <button type="button" class="btn btn-primary btn-sm rounded-pill px-4"
                            onclick="document.getElementById('saveForm').submit()">
                            <i class="material-icons-outlined fs-6 align-middle me-1">save</i> {{ __('netfusion.save') }}
                        </button>
                    </div>
                </div>

                <!-- Ace Editor Container -->
                <div class="flex-grow-1 position-relative">
                    <div id="editor" class="position-absolute w-100 h-100" style="font-size: 14px;">{{ $template }}</div>
                </div>

                <!-- Hidden Form for Saving -->
                <form id="saveForm" action="{{ route('mikrotik-suite.netfusion.tools.template-editor.save') }}" method="POST"
                    class="d-none">
                    @csrf
                    <textarea name="html_content" id="htmlContent"></textarea>
                </form>

                <!-- Variables Helper -->
                <div class="bg-light border-top p-2 px-4 small overflow-auto" style="height: 100px;">
                    <span class="fw-bold text-dark me-2">{{ __('netfusion.variables') }}:</span>
                    <code class="text-primary me-2 cursor-pointer" onclick="insertVar('%u_hotspot%')">%u_hotspot%</code>
                    <code class="text-primary me-2 cursor-pointer" onclick="insertVar('%u_user%')">%u_user%</code>
                    <code class="text-primary me-2 cursor-pointer" onclick="insertVar('%u_pass%')">%u_pass%</code>
                    <code class="text-primary me-2 cursor-pointer" onclick="insertVar('%u_money%')">%u_money%</code>
                    <code class="text-primary me-2 cursor-pointer" onclick="insertVar('%u_time%')">%u_time%</code>
                    <code class="text-primary me-2 cursor-pointer" onclick="insertVar('%u_limit%')">%u_limit%</code>
                </div>
            </div>

            <!-- Right: Preview Pane -->
            <div class="col-lg-6 d-flex flex-column bg-light">
                <div class="px-4 py-3 border-bottom bg-white d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold text-dark mb-0">{{ __('netfusion.live_preview') }}</h5>
                    <span
                        class="badge bg-success bg-opacity-10 text-success rounded-pill px-3">{{ __('netfusion.mock_data') }}</span>
                </div>
                <div
                    class="flex-grow-1 p-4 d-flex align-items-center justify-content-center bg-secondary bg-opacity-10 bg-dots">
                    <div class="shadow-lg bg-white overflow-hidden"
                        style="max-width: 100%; max-height: 100%; overflow: auto;">
                        <iframe id="previewFrame" class="border-0"
                            style="width: 400px; height: 600px; display: block;"></iframe>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Ace Editor CDN -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.32.7/ace.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.32.7/mode-php.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.32.7/theme-chrome.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Initialize Ace Editor
            const editor = ace.edit("editor");
            editor.setTheme("ace/theme/chrome");
            editor.session.setMode("ace/mode/php");
            editor.setOptions({
                fontSize: "14px",
                showPrintMargin: false,
                wrap: true
            });

            // Mock Data Dictionary
            const variables = {
                '%u_hotspot%': 'NetFusion WiFi',
                '%u_user%': 'user8821',
                '%u_pass%': 'ab555',
                '%u_money%': 'Rp 5.000',
                '%u_time%': '5 Jam',
                '%u_limit%': '2GB'
            };

            let debounceTimer;
            const updatePreview = () => {
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(() => {
                    const content = editor.getValue();
                    const frame = document.getElementById('previewFrame');
                    const doc = frame.contentDocument || frame.contentWindow.document;

                    // Fetch Rendered HTML from Server
                    fetch('{{ route('mikrotik-suite.netfusion.tools.template-editor.preview') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ html_content: content })
                    })
                        .then(response => response.json())
                        .then(data => {
                            doc.open();
                            doc.write(data.html);
                            doc.close();
                        })
                        .catch(console.error);

                    // Sync to hidden textarea
                    document.getElementById('htmlContent').value = content;
                }, 500); // 500ms debounce
            };

            // Event Listeners
            editor.session.on('change', updatePreview);

            // Initial Render
            updatePreview();

            // Helper to Insert Variables
            window.insertVar = (text) => {
                editor.insert(text);
                editor.focus();
            };
        });
    </script>
@endsection
