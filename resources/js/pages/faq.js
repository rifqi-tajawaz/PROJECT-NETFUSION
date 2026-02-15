document.addEventListener('DOMContentLoaded', function () {

    const navLinks = document.querySelectorAll('#faq-nav .nav-link');
    const sections = document.querySelectorAll('.faq-section');

    // Configuration
    // The visual top of the "content area" (below the sticky header)
    const HEADER_OFFSET = 180;

    /**
     * "Most Visible" ScrollSpy
     * Instead of a single trigger line, this logic calculates which section
     * actively occupies the most screen real estate.
     * This prevents skipping short sections or getting stuck on tall ones.
     */
    function onScrollSpy() {
        let maxVisibleHeight = 0;
        let activeId = '';

        // 1. Bottom Override: Absolute priority when reaching absolute bottom.
        // Prevents the "Last Item" from being ignored if it's very short and user is at the end.
        const scrollPosition = window.innerHeight + Math.ceil(window.scrollY);
        const bottomThreshold = document.documentElement.scrollHeight - 1;

        if (scrollPosition >= bottomThreshold && window.scrollY > 100) {
            const lastSection = sections[sections.length - 1];
            if (lastSection) {
                activateLink(lastSection.getAttribute('id'));
                return;
            }
        }

        // 2. Intersection Calculation
        // Viewport Range: [HEADER_OFFSET, window.innerHeight]
        const viewTop = HEADER_OFFSET;
        const viewBottom = window.innerHeight;

        sections.forEach(section => {
            const rect = section.getBoundingClientRect();

            // Calculate intersection of Section and "Active Viewport"
            // Section Range (relative to viewport): [rect.top, rect.bottom]

            // Constrain section top/bottom to viewport constraints
            const visibleTop = Math.max(rect.top, viewTop);
            const visibleBottom = Math.min(rect.bottom, viewBottom);

            // Calculate visible height (pixels)
            const visibleHeight = Math.max(0, visibleBottom - visibleTop);

            // Special weighting: 
            // If a section starts *above* the viewTop and ends *below* the viewBottom (covering the whole screen),
            // it's the dominant one.

            if (visibleHeight > maxVisibleHeight) {
                maxVisibleHeight = visibleHeight;
                activeId = section.getAttribute('id');
            }
        });

        // 3. Fallback for "Above the Fold" gaps
        // If nothing is "visible" (e.g. at the very top, before the header offset), highlight first
        if (!activeId && sections.length > 0 && window.scrollY < 100) {
            activeId = sections[0].getAttribute('id');
        } else if (!activeId && maxVisibleHeight === 0) {
            // Edge case: In a huge gap? Stick to previous logic or do nothing.
            // But with "most visible", we usually find something unless screen is empty.
            // Let's try to find the last section that passed the top.
            let bestCandidate = null;
            let maxTop = -Infinity;
            sections.forEach(section => {
                const r = section.getBoundingClientRect();
                if (r.top < viewTop) {
                    if (r.top > maxTop) {
                        maxTop = r.top;
                        bestCandidate = section;
                    }
                }
            });
            if (bestCandidate) activeId = bestCandidate.getAttribute('id');
        }

        // 4. Update UI
        if (activeId) {
            activateLink(activeId);
        }
    }

    // Helper: Efficiently toggle active class
    function activateLink(id) {
        // Only update DOM if necessary
        const currentActive = document.querySelector('#faq-nav .nav-link.active');
        const newActive = document.querySelector(`#faq-nav .nav-link[href="#${id}"]`);

        // If the same link is already active, do nothing
        if (currentActive === newActive) return;

        if (currentActive) currentActive.classList.remove('active');
        if (newActive) newActive.classList.add('active');
    }

    // Attach Listeners
    window.addEventListener('scroll', onScrollSpy);
    window.addEventListener('resize', onScrollSpy);

    // Init on load
    onScrollSpy();


    // Smooth Scroll Click Handler
    navLinks.forEach(link => {
        link.addEventListener('click', function (e) {
            e.preventDefault();

            const targetId = this.getAttribute('href');
            const targetSection = document.querySelector(targetId);

            if (targetSection) {
                // Optimistic UI Update
                navLinks.forEach(l => l.classList.remove('active'));
                this.classList.add('active');

                // const offset = 140; 
                // Using a slightly more spacious landing for visual comfort
                const offset = 120;

                const bodyRect = document.body.getBoundingClientRect().top;
                const elementRect = targetSection.getBoundingClientRect().top;
                const elementPosition = elementRect - bodyRect;
                const offsetPosition = elementPosition - offset;

                window.scrollTo({
                    top: offsetPosition,
                    behavior: "smooth"
                });
            }
        });
    });

    /**
     * Search Functionality (Previous logic maintained)
     */
    const searchInput = document.getElementById('faqSearch');
    const emptyState = document.getElementById('searchResultsEmpty');

    if (searchInput) {
        searchInput.addEventListener('input', function (e) {
            const term = e.target.value.toLowerCase().trim();
            let totalMatches = 0;

            document.querySelectorAll('.accordion-item').forEach(item => {
                const button = item.querySelector('.accordion-button');
                const body = item.querySelector('.accordion-body');
                const text = (button.textContent + " " + body.textContent).toLowerCase();

                if (term === '' || text.includes(term)) {
                    item.classList.remove('d-none');
                    if (term !== '') totalMatches++;
                } else {
                    item.classList.add('d-none');
                }
            });

            document.querySelectorAll('.faq-section').forEach(section => {
                const visibleItems = section.querySelectorAll('.accordion-item:not(.d-none)');
                if (visibleItems.length === 0) {
                    section.classList.add('d-none');
                } else {
                    section.classList.remove('d-none');
                }
            });

            if (emptyState) {
                if (term !== '' && totalMatches === 0) {
                    emptyState.classList.remove('d-none');
                    emptyState.classList.add('d-flex');
                } else {
                    emptyState.classList.add('d-none');
                    emptyState.classList.remove('d-flex');
                }
            }

            setTimeout(onScrollSpy, 100);
        });
    }

});
