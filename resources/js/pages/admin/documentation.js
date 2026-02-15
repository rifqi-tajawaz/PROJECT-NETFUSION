document.addEventListener('DOMContentLoaded', function () {
    // Delete Documentation Modal Handling
    window.setDeleteAction = function(actionUrl) {
        const deleteForm = document.getElementById('deleteForm');
        if (deleteForm) {
            deleteForm.action = actionUrl;
        }
    };
});
