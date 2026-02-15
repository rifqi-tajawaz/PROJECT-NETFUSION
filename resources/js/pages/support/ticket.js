document.addEventListener('DOMContentLoaded', function () {
    const input = document.getElementById('attachments');
    const previewContainer = document.getElementById('file-preview-container');
    const dropZone = document.getElementById('drop-zone');

    if (!input || !previewContainer || !dropZone) return;

    // Handle file selection
    input.addEventListener('change', function (e) {
        handleFiles(this.files);
    });

    // Drag and drop effects
    dropZone.addEventListener('dragover', (e) => {
        e.preventDefault();
        dropZone.classList.add('border-primary', 'bg-primary', 'bg-opacity-10');
    });

    dropZone.addEventListener('dragleave', (e) => {
        e.preventDefault();
        dropZone.classList.remove('border-primary', 'bg-primary', 'bg-opacity-10');
    });

    dropZone.addEventListener('drop', (e) => {
        e.preventDefault();
        dropZone.classList.remove('border-primary', 'bg-primary', 'bg-opacity-10');

        if (e.dataTransfer.files.length > 0) {
            input.files = e.dataTransfer.files;
            handleFiles(e.dataTransfer.files);
        }
    });

    function handleFiles(files) {
        // Clear previous previews
        previewContainer.innerHTML = '';

        Array.from(files).forEach(file => {
            const reader = new FileReader();

            // Main Preview Item
            const col = document.createElement('div');
            col.className = 'col-auto animate-fade-up';

            const card = document.createElement('div');
            card.className = 'position-relative border rounded p-2 d-flex align-items-center gap-2 bg-white shadow-sm';
            card.style.minWidth = '200px';

            // Icon/Image
            const iconBox = document.createElement('div');
            iconBox.className = 'rounded overflow-hidden d-flex align-items-center justify-content-center bg-light';
            iconBox.style.width = '48px';
            iconBox.style.height = '48px';

            if (file.type.startsWith('image/')) {
                const img = document.createElement('img');
                img.className = 'w-100 h-100 object-fit-cover';
                reader.onload = (e) => { img.src = e.target.result; };
                reader.readAsDataURL(file);
                iconBox.appendChild(img);
            } else {
                const icon = document.createElement('i');
                icon.className = 'bx bx-file fs-4 text-secondary';
                iconBox.appendChild(icon);
            }

            // Text Info
            const info = document.createElement('div');
            info.innerHTML = `
                <div class="small fw-bold text-truncate" style="max-width: 140px;">${file.name}</div>
                <div class="extra-small text-secondary">${formatSize(file.size)}</div>
            `;

            // Remove Button
            const closeBtn = document.createElement('button');
            closeBtn.type = 'button';
            closeBtn.className = 'btn btn-sm cursor-pointer p-0 rounded-circle position-absolute top-0 end-0 m-1 bg-white border shadow-sm d-flex align-items-center justify-content-center';
            closeBtn.style.width = '24px';
            closeBtn.style.height = '24px';
            closeBtn.style.zIndex = '10';
            closeBtn.innerHTML = '<i class="bx bx-x fs-5 text-danger"></i>';

            closeBtn.onclick = function () {
                col.remove();
                if (previewContainer.children.length === 0) input.value = '';
            };

            card.appendChild(iconBox);
            card.appendChild(info);
            card.appendChild(closeBtn);
            col.appendChild(card);
            previewContainer.appendChild(col);
        });
    }

    function formatSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }
});
