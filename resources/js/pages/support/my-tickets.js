document.addEventListener('DOMContentLoaded', function () {
    // Auto-submit filters on change
    const filterSelects = document.querySelectorAll('.filter-select');
    if (filterSelects.length > 0) {
        filterSelects.forEach(select => {
            select.addEventListener('change', function () {
                this.form.submit();
            });
        });
    }
});
