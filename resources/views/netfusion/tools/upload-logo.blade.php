@extends('layouts.app')

@section('title', 'Upload Logo')

@section('content')
    <div class="container-fluid p-0" style="background-color: #F3F4F6; min-height: calc(100vh - 80px);">

        <div class="row g-0 h-100">

            <!-- Left Column: The "Canvas" (Preview Area) -->
            <div class="col-lg-8 d-flex flex-column align-items-center justify-content-center p-5 position-relative"
                style="background-image: radial-gradient(#E5E7EB 1px, transparent 1px); background-size: 24px 24px; min-height: 500px;">

                <div class="text-center mb-4">
                    <span
                        class="badge bg-white text-secondary border shadow-sm px-3 py-2 rounded-pill fw-medium text-uppercase small tracking-wide">
                        {{ __('netfusion.live_preview') }}
                    </span>
                </div>

                <!-- THE VOUCHER REPLICA (Preserved Exact Design) -->
                <!-- Wrapped in a white frame for isolation -->
                <div class="shadow-lg rounded" style="padding: 0; line-height: normal; display: inline-block;">

                    <div class="voucher-replica bg-white overflow-hidden position-relative" id="voucherCard"
                        style="width: 320px; height: auto; border: 1px solid #000; transition: all 0.3s ease;">

                        <!-- Background Triangle (The "Grey One") -->
                        <div class="position-absolute top-0 end-0" style="width: 0; height: 0; 
                                                                            border-top: 360px solid transparent; 
                                                                            border-left: 80px solid transparent; 
                                                                            border-right: 220px solid #DCDCDC; 
                                                                            margin-top: -160px; 
                                                                            z-index: 0;"></div>

                        <div class="position-relative p-2" style="z-index: 1;">
                            <!-- Header Row (Absolute Layout for Stability) -->
                            <div class="position-relative w-100" style="height: 40px; margin-bottom: 8px;">
                                <!-- Logo Area: Pinned Top Left -->
                                <div class="position-absolute top-0 start-0"
                                    style="width: 120px; height: 35px; overflow: hidden; display: flex; align-items: center;">
                                    @php
                                        $sName = session('router_session') ? \Illuminate\Support\Str::slug(session('router_session')['name']) : 'default';
                                        $logoPath = "images/logo-{$sName}.png";
                                    @endphp
                                    <img src="{{ asset($logoPath) }}?t={{ time() }}" alt="logo" id="mockupLogo"
                                        style="max-width: 100%; max-height: 100%; object-fit: contain; object-position: left;"
                                        onerror="this.src='{{ asset('images/logo.png') }}'; this.onerror=null;">
                                </div>
                                <!-- Price Area: Pinned Top Right -->
                                <div class="position-absolute top-0 end-0 text-end lh-1"
                                    style="width: 180px; white-space: nowrap;">
                                    <div class="fw-bold price-element"
                                        style="font-family: Tahoma, sans-serif; font-size: 32px; color: #FF4500; letter-spacing: -1px;">
                                        <small
                                            style="font-size: 16px; vertical-align: top; margin-right: 2px;">{{ __('netfusion.voucher_preview_price') }}</small><span
                                            id="previewPrice">5.000</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Body Row -->
                            <div class="d-flex">
                                <!-- Left: Info -->
                                <div class="flex-grow-1" style="width: 55%;">
                                    <div class="text-center mt-2 mb-2">
                                        <div class="fw-bold text-dark border-element"
                                            style="font-size: 14px; border-bottom: 2px solid #FF4500; display: inline-block; padding-bottom: 2px; margin-bottom: 4px;">
                                            {{ __('netfusion.voucher') }}
                                        </div>
                                        <div class="fw-bold text-black" style="font-size: 20px; font-family: monospace;">
                                            AB72-9X
                                        </div>
                                    </div>
                                    <div class="fw-bold text-dark lh-sm mt-2 text-truncate pb-1" style="font-size: 11px;">
                                        Login: <span
                                            style="color: #0d6efd;">http://{{ session('router_session')['dns_name'] ?? 'mikrotik.net' }}</span>
                                    </div>
                                </div>

                                <!-- Right: Validity & QR -->
                                <div class="text-end ps-2 position-relative" style="width: 45%;">
                                    <div class="fw-bold text-black mb-1 text-nowrap" style="font-size: 10px;">
                                        {{ __('netfusion.voucher_preview_validity') }} : <span id="previewDuration">3
                                            JAM</span>
                                    </div>
                                    <div class="d-flex justify-content-end mt-1">
                                        <div class="bg-white p-1" style="width: 85px; height: 85px;">
                                            <i class="material-icons-outlined text-dark"
                                                style="font-size: 77px; line-height: 1;">qr_code_2</i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Footer Strip -->
                        <div class="text-white fw-bold px-2 py-1 footer-element"
                            style="background: #FF4500; font-size: 14px; position: relative; z-index: 1;">
                            CS: 081945967926
                        </div>
                    </div>
                </div>
                <!-- END VOUCHER REPLICA -->

                <div class="mt-5 d-flex gap-3 align-items-center">
                    <button type="button"
                        class="btn btn-white shadow-sm border rounded-circle p-2 d-flex align-items-center justify-content-center"
                        style="width: 45px; height: 45px;" onclick="changeColor(-1)">
                        <i class="material-icons-outlined text-dark">chevron_left</i>
                    </button>
                    <div class="bg-white px-3 py-2 rounded-pill shadow-sm border border-light d-flex align-items-center justify-content-center gap-2"
                        style="min-width: 200px;">
                        <span class="rounded-circle d-inline-block" style="width: 12px; height: 12px; background: #FF4500;"
                            id="colorIndicator"></span>
                        <span class="fw-bold text-dark small text-nowrap" id="colorName">Orange (5K)</span>
                    </div>
                    <button type="button"
                        class="btn btn-white shadow-sm border rounded-circle p-2 d-flex align-items-center justify-content-center"
                        style="width: 45px; height: 45px;" onclick="changeColor(1)">
                        <i class="material-icons-outlined text-dark">chevron_right</i>
                    </button>
                </div>

            </div>

            <!-- Right Column: Controls & Upload -->
            <div class="col-lg-4 bg-white border-start border-light shadow-sm position-relative">
                <div class="h-100 d-flex flex-column p-4 p-xl-5 pt-5">

                    <div class="mb-4 mt-5">
                        <a href="{{ route('mikrotik-suite.netfusion.settings.index') }}"
                            class="text-decoration-none text-secondary d-inline-flex align-items-center mb-3">
                            <i class="material-icons-outlined me-1 fs-6">arrow_back</i>
                            {{ __('netfusion.return_to_settings') }}
                        </a>
                        <h3 class="fw-bold text-dark mb-2 text-nowrap">{{ __('netfusion.upload_branding') }}</h3>
                        <p class="text-secondary">{{ __('netfusion.upload_branding_description') }}</p>
                    </div>

                    <div class="card border border-dashed border-2 rounded-4 bg-light flex-grow-1 mb-4 position-relative overflow-hidden group"
                        id="dropZone" style="border-color: #E5E7EB !important; transition: all 0.2s ease;">

                        <form action="{{ route('mikrotik-suite.netfusion.tools.upload-logo-process') }}" method="POST"
                            enctype="multipart/form-data" id="uploadForm"
                            class="h-100 d-flex flex-column position-relative">
                            @csrf
                            <input type="file" name="logo" id="fileInput"
                                class="position-absolute w-100 h-100 top-0 start-0 opacity-0 cursor-pointer"
                                style="z-index: 20;" accept="image/png, image/jpeg">

                            <!-- Empty State -->
                            <div id="zoneContent"
                                class="d-flex flex-column align-items-center justify-content-center h-100 p-4 text-center">
                                <div class="bg-white p-3 rounded-circle shadow-sm mb-3">
                                    <i class="material-icons-outlined text-primary fs-2">cloud_upload</i>
                                </div>
                                <h6 class="fw-bold text-dark">{{ __('netfusion.click_or_drag') }}</h6>
                                <p class="text-secondary small">{{ __('netfusion.optimized_for') }}</p>
                            </div>

                            <!-- Preview State -->
                            <div id="previewContainer"
                                class="d-none flex-grow-1 w-100 bg-white d-flex flex-column align-items-center justify-content-center p-4 position-relative"
                                style="z-index: 50;">

                                <div class="mb-4 p-3 border rounded-3 position-relative" style="background-image: linear-gradient(45deg, #e5e7eb 25%, transparent 25%), linear-gradient(-45deg, #e5e7eb 25%, transparent 25%), linear-gradient(45deg, transparent 75%, #e5e7eb 75%), linear-gradient(-45deg, transparent 75%, #e5e7eb 75%);
                                                                                background-size: 20px 20px;
                                                                                background-position: 0 0, 0 10px, 10px -10px, -10px 0px;
                                                                                min-width: 200px;
                                                                                display: flex;
                                                                                align-items: center;
                                                                                justify-content: center;">
                                    <img id="imagePreview" src="" class="img-fluid"
                                        style="max-height: 150px; max-width: 100%; object-fit: contain;">
                                </div>

                                <div class="d-flex gap-3 w-100 justify-content-center">
                                    <button type="button"
                                        class="btn btn-outline-danger rounded-pill px-4 fw-medium text-nowrap d-inline-flex align-items-center justify-content-center gap-2"
                                        id="removeFileBtn" style="min-width: 120px;">
                                        <i class="material-icons-outlined fs-6">close</i> {{ __('netfusion.remove') }}
                                    </button>
                                    <button type="submit"
                                        class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm text-nowrap d-inline-flex align-items-center justify-content-center gap-2"
                                        id="submitBtn" style="min-width: 160px;">
                                        <i class="material-icons-outlined fs-6">cloud_upload</i>
                                        {{ __('netfusion.upload_logo') }}
                                    </button>
                                </div>
                            </div>

                            <!-- Drag Overlay -->
                            <div class="position-absolute top-0 start-0 w-100 h-100 bg-primary bg-opacity-10 d-flex align-items-center justify-content-center opacity-0 transition-opacity"
                                id="dragOverlay" style="pointer-events: none;">
                                <span
                                    class="fw-bold text-primary bg-white px-3 py-1 rounded-pill shadow-sm">{{ __('netfusion.drop_file_here') }}</span>
                            </div>
                        </form>
                    </div>

                    <div class="bg-light p-3 rounded-3 d-flex align-items-center gap-3 border border-light">
                        <div class="bg-white border rounded p-1 d-flex align-items-center justify-content-center"
                            style="width: 60px; height: 40px;">
                            @php $logoPath = "images/logo-" . (session('router_session') ? \Illuminate\Support\Str::slug(session('router_session')['name']) : 'default') . ".png"; @endphp
                            <img src="{{ asset($logoPath) }}?t={{ time() }}" alt="Current"
                                style="max-width: 100%; max-height: 100%;"
                                onerror="this.src='{{ asset('images/logo.png') }}';">
                        </div>
                        <div>
                            <div class="text-uppercase text-secondary fw-bold" style="font-size: 10px;">
                                {{ __('netfusion.current_file') }}</div>
                            <div class="fs-6 fw-bold text-dark">{{ session('router_session')['name'] ?? 'Default' }}</div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>



    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // --- Logic Preserved & Exposed Global ---
            const colorVariants = [
                { price: '2.000', color: '#5f2ded', name: 'NetFusion (2K)', duration: '2 JAM' },
                { price: '2.000', color: '#8B008B', name: 'Purple (2K)', duration: '2 JAM' },
                { price: '3.000', color: '#666666', name: 'Grey (3K)', duration: '3 JAM' },
                { price: '5.000', color: '#FF4500', name: 'Orange (5K)', duration: '5 JAM' },
                { price: '10.000', color: '#E65100', name: 'Dark Orange (10K)', duration: '10 JAM' },
                { price: '15.000', color: '#228B22', name: 'Forest Green (15K)', duration: '15 JAM' },
                { price: '20.000', color: '#008000', name: 'Green (20K)', duration: '20 JAM' },
                { price: '30.000', color: '#FF00FF', name: 'Magenta (30K)', duration: '30 JAM' },
                { price: '50.000', color: '#E60C00', name: 'Red (50K)', duration: '1 MINGGU' },
            ];
            let currentIndex = 3;

            // Expose to window for onclick handlers
            window.updateVoucherDisplay = function () {
                const variant = colorVariants[currentIndex];
                // No manual JS animation - let CSS handle it

                document.querySelectorAll('.price-element').forEach(el => el.style.color = variant.color);
                document.querySelectorAll('.border-element').forEach(el => el.style.borderBottomColor = variant.color);
                document.querySelectorAll('.footer-element').forEach(el => el.style.backgroundColor = variant.color);
                document.getElementById('previewPrice').innerText = variant.price;
                document.getElementById('previewDuration').innerText = variant.duration;
                document.getElementById('colorName').innerText = variant.name;
                document.getElementById('colorIndicator').style.backgroundColor = variant.color;
            }

            window.changeColor = function (direction) {
                currentIndex += direction;
                if (currentIndex >= colorVariants.length) currentIndex = 0;
                if (currentIndex < 0) currentIndex = colorVariants.length - 1;
                window.updateVoucherDisplay();
            }

            // Upload Logic
            const dropZone = document.getElementById('dropZone');
            const fileInput = document.getElementById('fileInput');

            if (dropZone && fileInput) {
                // Drag events
                ['dragenter', 'dragover'].forEach(e => dropZone.addEventListener(e, () => dropZone.classList.add('dragover')));
                ['dragleave', 'drop'].forEach(e => dropZone.addEventListener(e, () => dropZone.classList.remove('dragover')));

                fileInput.addEventListener('change', (e) => {
                    if (e.target.files.length) showPreview(e.target.files[0]);
                });

                dropZone.addEventListener('drop', (e) => {
                    e.preventDefault();
                    if (e.dataTransfer.files.length) {
                        fileInput.files = e.dataTransfer.files;
                        showPreview(e.dataTransfer.files[0]);
                    }
                });
            }

            function showPreview(file) {
                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        document.getElementById('imagePreview').src = e.target.result;
                        document.getElementById('mockupLogo').src = e.target.result;
                        document.getElementById('zoneContent').classList.add('d-none');
                        document.getElementById('previewContainer').classList.remove('d-none');
                    };
                    reader.readAsDataURL(file);
                }
            }

            const removeBtn = document.getElementById('removeFileBtn');
            if (removeBtn) {
                removeBtn.addEventListener('click', () => {
                    if (fileInput) fileInput.value = '';
                    document.getElementById('zoneContent').classList.remove('d-none');
                    document.getElementById('previewContainer').classList.add('d-none');
                    document.getElementById('mockupLogo').src = "{{ asset('images/logo.png') }}";
                });
            }

            // Initial call
            window.updateVoucherDisplay();
        });
    </script>
@endsection
