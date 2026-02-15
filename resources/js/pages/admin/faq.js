/**
 * Admin FAQ Management Scripts
 * Handles Offcanvas form population and delete confirmation.
 */

document.addEventListener('DOMContentLoaded', function () {
    // --- 1. Offcanvas Logic ---
    const faqOffcanvas = document.getElementById('faqOffcanvas');
    const form = document.getElementById('faqForm');
    const modalTitle = document.getElementById('offcanvasTitle');

    // Inputs
    const inputQuestion = document.getElementById('question');
    const inputCategory = document.getElementById('category');
    const inputAnswer = document.getElementById('answer');
    const inputPublished = document.getElementById('is_published');
    const methodField = document.getElementById('methodField');

    // Reset form when opening for "Create"
    document.querySelectorAll('[data-bs-target="#faqOffcanvas"]').forEach(btn => {
        btn.addEventListener('click', function () {
            // Check if it's an Edit trigger
            const faqData = this.dataset.faq;
            if (faqData) {
                // EDIT MODE
                const faq = JSON.parse(faqData);
                modalTitle.innerText = form.dataset.editTitle;
                form.action = `/admin/support/faqs/${faq.id}`;
                methodField.value = 'PUT';

                inputQuestion.value = faq.question;
                inputCategory.value = faq.category;
                inputAnswer.value = faq.answer;
                // Handle boolean conversion correctly (JSON true/1)
                inputPublished.checked = (faq.is_published == 1 || faq.is_published === true);
            } else {
                // CREATE MODE
                modalTitle.innerText = form.dataset.createTitle;
                form.action = form.dataset.storeUrl;
                methodField.value = 'POST';
                form.reset();
                // Default to checked for new
                inputPublished.checked = true;
            }
        });
    });

    // --- 2. Delete Confirmation ---
    const deleteModal = document.getElementById('deleteConfirmModal');
    if (deleteModal) {
        deleteModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const deleteUrl = button.getAttribute('data-delete-url');
            const form = deleteModal.querySelector('#deleteForm');
            form.action = deleteUrl;
        });
    }
});
