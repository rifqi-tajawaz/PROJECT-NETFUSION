@props([
    'name' => 'attachments',
    'label' => '',
    'hint' => '',
    'types' => '',
])

<div class="mb-4">
    <label for="{{ $name }}" class="form-label fw-bold">{{ $label }}</label>
    <div class="p-4 border border-dashed rounded-3 bg-light text-center upload-drop-zone position-relative clickable-upload">
        <input type="file" id="{{ $name }}" name="{{ $name }}[]" multiple
            class="position-absolute top-0 start-0 w-100 h-100 opacity-0 cursor-pointer"
            accept="{{ $types }}">
        <div class="mb-2">
            <span class="material-icons-outlined fs-1 text-secondary">cloud_upload</span>
        </div>
        <h6 class="mb-1 text-dark">{{ $hint }}</h6>
        <span class="small text-secondary">{{ $types }}</span>
    </div>
    @error($name) <div class="text-danger small mt-1">{{ $message }}</div> @enderror
    @error($name . '.*') <div class="text-danger small mt-1">{{ $message }}</div> @enderror

    <!-- Preview Container -->
    <div id="file-preview-container" class="mt-3 row g-3"></div>
</div>
