<div {{ $attributes->merge(['class' => 'card border-0 shadow-sm rounded-4']) }}>
    <div class="card-header bg-transparent border-0 pt-4 px-4">
        <h6 class="mb-0 fw-bold text-body-emphasis d-flex align-items-center gap-2">
            <span class="material-icons-outlined text-brand">terminal</span>
            Script Output
        </h6>
        <button type="button" class="btn btn-sm btn-outline-secondary d-flex align-items-center gap-1 ms-auto"
            onclick="NetFusion.copyToClipboard('scriptOutput', this)">
            <span class="material-icons-outlined fs-6">content_copy</span> Copy
        </button>
    </div>
    <div class="card-body p-0">
        <div id="scriptOutput" class="bg-dark rounded-bottom-4 overflow-hidden" style="min-height: 400px;">
            <div class="d-flex align-items-center justify-content-center text-white-50 h-100 p-5">
                <div class="text-center">
                    <i class="material-icons-outlined fs-2 mb-2 d-block opacity-25">code_off</i>
                    <span>Output will appear here after generation...</span>
                </div>
            </div>
        </div>
    </div>
</div>
