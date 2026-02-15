document.addEventListener('DOMContentLoaded', function () {
    // --- 1. Slug Auto-generation ---
    const titleInput = document.getElementById('title');
    const slugInput = document.getElementById('slug');

    if (titleInput && slugInput) {
        titleInput.addEventListener('input', function () {
            const slug = titleInput.value.toLowerCase()
                .replace(/[^a-z0-9 -]/g, '') // remove invalid chars
                .replace(/\s+/g, '-') // collapse whitespace and replace by -
                .replace(/-+/g, '-'); // collapse dashes
            slugInput.value = slug;
        });
    }

    // --- 2. Live Markdown Preview ---
    const markdownInput = document.getElementById('markdown-input');
    const markdownPreview = document.getElementById('markdown-preview');

    if (markdownInput && markdownPreview) {
        // Configure Marked.js options
        marked.setOptions({
            gfm: true,
            breaks: true,
            headerIds: true,
            sanitize: false
        });

        // Function to update preview
        function updatePreview() {
            const rawMarkdown = markdownInput.value;
            // Parse Markdown
            const htmlContent = marked.parse(rawMarkdown);
            // Update Preview Pane
            markdownPreview.innerHTML = htmlContent;

            // Render Alerts
            renderAlerts();
        }

        // Custom Renderer for GitHub-style Alerts
        function renderAlerts() {
            const blockquotes = markdownPreview.querySelectorAll('blockquote');
            blockquotes.forEach(bq => {
                const text = bq.innerText;
                const noteRegex = /^\[!NOTE\]\s*\n?/;
                const warningRegex = /^\[!WARNING\]\s*\n?/;
                const tipRegex = /^\[!TIP\]\s*\n?/;

                if (noteRegex.test(text)) {
                    bq.classList.add('alert', 'alert-primary', 'border-0', 'border-start', 'border-4', 'border-primary', 'bg-primary', 'bg-opacity-10');
                    bq.innerHTML = bq.innerHTML.replace(noteRegex, '<strong class="d-block mb-1 text-primary"><i class="bx bx-info-circle me-1"></i>Note</strong>');
                } else if (warningRegex.test(text)) {
                    bq.classList.add('alert', 'alert-warning', 'border-0', 'border-start', 'border-4', 'border-warning', 'bg-warning', 'bg-opacity-10');
                    bq.innerHTML = bq.innerHTML.replace(warningRegex, '<strong class="d-block mb-1 text-warning"><i class="bx bx-error me-1"></i>Warning</strong>');
                } else if (tipRegex.test(text)) {
                    bq.classList.add('alert', 'alert-success', 'border-0', 'border-start', 'border-4', 'border-success', 'bg-success', 'bg-opacity-10');
                    bq.innerHTML = bq.innerHTML.replace(tipRegex, '<strong class="d-block mb-1 text-success"><i class="bx bx-bulb me-1"></i>Tip</strong>');
                }
            });
        }

        // Event Listener for typing
        markdownInput.addEventListener('input', updatePreview);

        // Initial Render
        updatePreview();
    }

    // --- 3. Image Upload ---
    const imageUpload = document.getElementById('imageUpload');
    if (imageUpload) {
        imageUpload.addEventListener('change', function () {
            const file = this.files[0];
            if (file) {
                const formData = new FormData();
                formData.append('image', file);

                // Add CSRF Token
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                formData.append('_token', csrfToken);

                // Show loading state (optional)
                const input = document.getElementById('markdown-input');
                const originalPlaceholder = input.placeholder;
                input.placeholder = "Uploading image...";

                // Determine upload URL mostly dynamically or hardcoded if route name is stable
                // Using a data attribute on the input would be cleaner, but hardcoding for now as verified
                const uploadUrl = '/admin/support/documentation/upload';

                fetch(uploadUrl, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    }
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.url) {
                            const imageMarkdown = `![Image](${data.url})`;
                            insertMarkdown(imageMarkdown, '');
                        } else {
                            alert('Upload failed: ' + (data.error || 'Unknown error'));
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Upload failed.');
                    })
                    .finally(() => {
                        input.placeholder = originalPlaceholder;
                        imageUpload.value = ''; // Reset
                    });
            }
        });
    }

    // --- 4. Filter Parents Logic ---
    const categorySelect = document.querySelector('select[name="category_id"]');
    const parentSelect = document.querySelector('select[name="parent_id"]');

    if (categorySelect && parentSelect) {
        const allParentOptions = Array.from(parentSelect.querySelectorAll('option'));

        function filterParents() {
            const selectedCatId = categorySelect.value;
            const currentSelected = parentSelect.selectedOptions[0];

            if (currentSelected && currentSelected.value !== "" && currentSelected.getAttribute('data-category') != selectedCatId) {
                parentSelect.value = "";
            }

            allParentOptions.forEach(option => {
                const optionCatId = option.getAttribute('data-category');
                if (option.value === "") {
                    option.hidden = false;
                    option.style.display = '';
                    return;
                }
                if (selectedCatId && optionCatId === selectedCatId) {
                    option.hidden = false;
                    option.style.display = '';
                } else {
                    option.hidden = true;
                    option.style.display = 'none';
                }
            });
        }

        categorySelect.addEventListener('change', filterParents);
        filterParents();
    }
});

// --- Exported Helper Functions (Global Scope) ---
window.insertMarkdown = function (start, end) {
    const input = document.getElementById('markdown-input');
    const scrollPos = input.scrollTop;
    let caretPos = input.selectionStart;

    const front = (input.value).substring(0, caretPos);
    const mid = (input.value).substring(caretPos, input.selectionEnd);
    const back = (input.value).substring(input.selectionEnd, input.value.length);

    input.value = front + start + mid + end + back;
    input.selectionStart = input.selectionEnd = caretPos + start.length + mid.length;
    input.focus();
    input.dispatchEvent(new Event('input'));
};

window.insertTable = function () {
    const tableTemplate =
        `| Header 1 | Header 2 |
|----------|----------|
| Cell 1   | Cell 2   |
`;
    insertMarkdown(tableTemplate, '');
};
