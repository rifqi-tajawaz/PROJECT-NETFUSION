document.addEventListener('DOMContentLoaded', function () {
    // ==========================================
    // TICKET INDEX PAGE
    // ==========================================

    // Auto-submit filters on change
    const filterSelects = document.querySelectorAll('.filter-select');
    if (filterSelects.length > 0) {
        filterSelects.forEach(select => {
            select.addEventListener('change', function () {
                this.form.submit();
            });
        });
    }

    // Delete Ticket Confirmation (Index)
    const deleteForms = document.querySelectorAll('.delete-ticket-form');
    if (deleteForms.length > 0) {
        deleteForms.forEach(form => {
            form.addEventListener('submit', function (e) {
                if (!confirm('Delete this ticket?')) {
                    e.preventDefault();
                }
            });
        });
    }

    // ==========================================
    // TICKET SHOW PAGE
    // ==========================================

    // File Attachment Preview
    const fileInput = document.getElementById('attach-file');
    const fileCountSpan = document.getElementById('file-count');

    if (fileInput && fileCountSpan) {
        fileInput.addEventListener('change', function () {
            if (this.files.length > 0) {
                fileCountSpan.innerText = this.files.length + ' files selected';
            } else {
                fileCountSpan.innerText = '';
            }
        });
    }

    // Delete Ticket Confirmation (Show Page)
    const deleteSingleForm = document.getElementById('delete-ticket-form');
    if (deleteSingleForm) {
        deleteSingleForm.addEventListener('submit', function (e) {
            if (!confirm('Are you strictly sure? This action cannot be undone.')) {
                e.preventDefault();
            }
        });
    }
});
