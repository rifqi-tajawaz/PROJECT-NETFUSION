/**
 * Global Clipboard Utility
 * Handles copying text to clipboard and providing UI feedback to the trigger button.
 * 
 * @param {string} elementId - The ID of the element containing text to copy.
 * @param {string|HTMLElement} [btnSelector] - Optional. Selector or Element of the button to update status. 
 *                                             If null, attempts to find button via onclick attribute match.
 */
export function copyText(elementId, btnSelector = null) {
    const el = document.getElementById(elementId);
    if (!el) {
        console.warn(`copyText: Element #${elementId} not found.`);
        return;
    }

    // Use textContent to verify EXACT text (avoiding hidden HTML)
    const text = el.textContent;

    if (!navigator.clipboard) {
        alert("Clipboard API not supported");
        return;
    }

    navigator.clipboard.writeText(text).then(() => {
        // 1. Resolve the button
        let btn = null;
        if (btnSelector) {
            btn = (typeof btnSelector === 'string') ? document.querySelector(btnSelector) : btnSelector;
        } else {
            // Auto-discovery for onClick handlers: button[onclick="copyText('id')"]
            // Note: Use exact match for safety
            btn = document.querySelector(`button[onclick="copyText('${elementId}')"]`);
            if (!btn) {
                // Try alternate quote style
                btn = document.querySelector(`button[onclick='copyText("${elementId}")']`);
            }
        }

        // 2. Update Button UI
        if (btn) {
            const originalHtml = btn.innerHTML;

            // Heuristic to check what kind of icon to show
            // If button has specific classes, maintain them or default to bootstrap checks
            const isDark = btn.classList.contains('btn-dark');

            btn.innerHTML = '<i class="bi bi-check2 me-1"></i> Copied!';

            // Visual state change
            btn.classList.remove('btn-dark', 'text-white-50', 'btn-light', 'text-secondary');
            // Use btn-light for a "flash" effect (White on Dark) or btn-primary
            btn.classList.add('btn-light', 'text-dark', 'fw-bold');

            setTimeout(() => {
                btn.innerHTML = originalHtml;
                btn.classList.remove('btn-light', 'text-dark', 'fw-bold');

                // Revert to original approximate state (naive) or just remove success
                // Ideal: read exact class list before. But for now reverting to common NetFusion styles:
                if (isDark) {
                    btn.classList.add('btn-dark', 'text-white-50');
                } else {
                    btn.classList.add('btn-light', 'text-secondary'); // Default assumed
                }
            }, 2000);
        }

        // 3. Optional Toast Feedback (Legacy support)
        const toast = document.getElementById('copyToast');
        if (toast) {
            toast.classList.remove('d-none');
            setTimeout(() => toast.classList.add('d-none'), 2000);
        }

    }).catch(err => {
        console.error('Failed to copy: ', err);
        alert('Failed to copy script to clipboard.');
    });
}
