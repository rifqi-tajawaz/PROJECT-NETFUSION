/**
 * Documentation Page Logic
 * Handles Table of Contents (TOC) generation and ScrollSpy
 */

document.addEventListener('DOMContentLoaded', function () {
    // Generate TOC
    const content = document.querySelector('.doc-content-checker');
    const tocNav = document.getElementById('toc');

    if (content && tocNav) {
        const headers = content.querySelectorAll('h2, h3, h4, h5, h6'); // Capture all relevant headers

        if (headers.length > 0) {
            tocNav.innerHTML = ''; // Clear loading

            headers.forEach((header, index) => {
                // Create ID if missing
                if (!header.id) {
                    header.id = 'section-' + index;
                }

                const link = document.createElement('a');
                link.className = 'nav-link';
                link.href = '#' + header.id;
                link.innerText = header.innerText;

                // Dynamic Indentation
                const tagName = header.tagName;
                if (tagName === 'H4') link.classList.add('ps-3', 'opacity-75');
                if (tagName === 'H5') link.classList.add('ps-4', 'small', 'opacity-75');
                if (tagName === 'H6') link.classList.add('ps-5', 'small', 'opacity-50', 'fst-italic');

                // Data attribute for ScrollSpy matching
                link.setAttribute('data-target', header.id);

                tocNav.appendChild(link);
            });

            // --- ScrollSpy Implementation ---
            const observerOptions = {
                root: null,
                rootMargin: '-10% 0px -65% 0px', // Active zone near top
                threshold: 0
            };

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        // Remove active from all
                        tocNav.querySelectorAll('.nav-link').forEach(l => l.classList.remove('active'));

                        // Add active to current
                        const id = entry.target.id;
                        const activeLink = tocNav.querySelector(`.nav-link[data-target="${id}"]`);
                        if (activeLink) {
                            activeLink.classList.add('active');
                        }
                    }
                });
            }, observerOptions);

            headers.forEach(header => observer.observe(header));

        } else {
            tocNav.innerHTML = '<span class="text-muted ps-3 small">No sections found</span>';
        }
    }

    // --- Sidebar Search Implementation ---
    const searchInput = document.getElementById('sidebar-search');
    const accordionContainer = document.getElementById('docsSidebarAccordion');

    if (searchInput && accordionContainer) {
        searchInput.addEventListener('input', function (e) {
            const searchTerm = e.target.value.toLowerCase().trim();
            const allLinks = accordionContainer.querySelectorAll('a.list-group-item');

            // 1. Reset if empty
            if (searchTerm === '') {
                // Show all links
                allLinks.forEach(link => link.classList.remove('d-none'));

                // Show all Categories
                accordionContainer.querySelectorAll('.accordion-item, .accordion-header, .accordion-collapse').forEach(el => {
                    el.classList.remove('d-none');
                });

                // Reset Accordion State (Collapse All)
                accordionContainer.querySelectorAll('.accordion-collapse').forEach(collapse => {
                    collapse.classList.remove('show');
                });
                accordionContainer.querySelectorAll('.accordion-button').forEach(btn => {
                    btn.classList.add('collapsed');
                    btn.setAttribute('aria-expanded', 'false');
                });

                // Restore Active State (Re-expand current page)
                // We identify active item by the specific class we used in Blade
                const activeLink = accordionContainer.querySelector('a.bg-primary.bg-opacity-10');
                if (activeLink) {
                    let parent = activeLink.closest('.accordion-collapse');
                    while (parent) {
                        parent.classList.add('show');
                        const id = parent.id;
                        const btn = document.querySelector(`button[data-bs-target="#${id}"]`);
                        if (btn) {
                            btn.classList.remove('collapsed');
                            btn.setAttribute('aria-expanded', 'true');
                        }
                        parent = parent.parentElement.closest('.accordion-collapse');
                    }
                }

                return;
            }

            // 2. Filter Logic
            let hasGlobalMatch = false;

            // Loop through all TERMINAL items (links)
            allLinks.forEach(link => {
                const text = link.innerText.toLowerCase();
                const isMatch = text.includes(searchTerm);

                if (isMatch) {
                    link.classList.remove('d-none');
                    hasGlobalMatch = true;

                    // Expand Parent Accordions
                    let parentCollapse = link.closest('.accordion-collapse');
                    while (parentCollapse) {
                        // Add show class to collapse
                        parentCollapse.classList.add('show');

                        // Update trigger button
                        const id = parentCollapse.id;
                        const btn = document.querySelector(`button[data-bs-target="#${id}"]`);
                        if (btn) {
                            btn.classList.remove('collapsed');
                            btn.setAttribute('aria-expanded', 'true');
                        }

                        // Traverse Up
                        parentCollapse = parentCollapse.parentElement.closest('.accordion-collapse');
                    }

                    // Show parent Item
                    const parentItem = link.closest('.accordion-item');
                    if (parentItem) parentItem.classList.remove('d-none');

                } else {
                    link.classList.add('d-none');
                }
            });

            // 3. Hide Empty Categories (Optional polish)
            // Loop all top-level accordion items
            const topItems = accordionContainer.querySelectorAll('.accordion-item');
            topItems.forEach(item => {
                // Check if it has any visible links inside
                const visibleLinks = item.querySelectorAll('a.list-group-item:not(.d-none)');
                if (visibleLinks.length === 0) {
                    item.classList.add('d-none');
                } else {
                    item.classList.remove('d-none');
                }
            });
        });
    }
});
