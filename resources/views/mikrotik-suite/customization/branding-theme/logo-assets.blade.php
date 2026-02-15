@extends('layouts.app')

@section('content')
    <div class="container-fluid px-4 py-4">
        <div class="mb-4 d-flex flex-column align-items-center justify-content-center text-center">
            <h2 class="display-6 fw-bold mb-2 text-white" style="text-shadow: 0 2px 10px rgba(0,0,0,0.3);">
                <i class="bi bi-images me-2 text-info"></i> Logo Assets
            </h2>
            <p class="lead opacity-75 mb-0" style="max-width: 700px;">
                Tools to help manage and deploy logos to your RouterOS devices.
            </p>
        </div>

        <div class="row g-4 justify-content-center">
            <!-- Hotspot Logo Helper -->
            <div class="col-lg-5">
                <div class="glass-card h-100">
                    <div class="card-header border-0 bg-transparent pt-4 px-4">
                        <h5 class="card-title fw-bold text-white mb-0">Hotspot Logo Script</h5>
                    </div>
                    <div class="card-body p-4">
                        <p class="text-white-50 mb-3">Generate a script to download a hosted logo and replace the default
                            Hotspot image.</p>

                        <div class="mb-3">
                            <label class="form-label text-white small text-uppercase">Logo URL</label>
                            <input type="url" class="form-control" id="logoUrl" placeholder="https://example.com/logo.png">
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-white small text-uppercase">Target Filename</label>
                            <input type="text" class="form-control" id="targetFile" value="hotspot/img/logo.png">
                        </div>

                        <button class="btn btn-info w-100 mt-2" onclick="genLogoScript()">Generate Script</button>

                        <div class="mt-3 p-3 bg-dark bg-opacity-50 rounded-3 border border-secondary border-opacity-25">
                            <code class="text-warning text-wrap text-break" id="logoScript">// Script output...</code>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Placeholder for Image Converter -->
            <div class="col-lg-5">
                <div class="glass-card h-100">
                    <div class="card-body p-5 text-center d-flex flex-column align-items-center justify-content-center">
                        <i class="bi bi-file-earmark-image display-1 text-secondary mb-3"></i>
                        <h5 class="text-white">Image to Hex Converter</h5>
                        <p class="text-white-50">Coming soon. Will allow converting small bitmaps directly into RouterOS
                            scripting hex format for embedding.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function genLogoScript() {
            const url = document.getElementById('logoUrl').value;
            const file = document.getElementById('targetFile').value;

            if (!url) return;

            const script = `/tool fetch url="${url}" dst-path="${file}" mode=https`;
            document.getElementById('logoScript').innerText = script;
        }
    </script>
@endsection
