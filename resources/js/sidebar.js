/**
 * Modern Sidebar - Professional Interaction
 * Features: Toggle, Search, Keyboard Navigation, Animations
 */

class ModernSidebar {
    constructor() {
        this.sidebar = document.querySelector('[data-sidebar-state]');
        this.state = localStorage.getItem('sidebarState') || 'expanded';
        this.searchInput = document.querySelector('[data-sidebar-search]');

        this.init();
    }

    init() {
        this.applyState();
        this.bindEvents();
        this.initKeyboardShortcuts();
        this.initSearch();
        this.initActiveStates();
    }

    /**
     * Apply saved state
     */
    applyState() {
        if (this.sidebar) {
            this.sidebar.setAttribute('data-sidebar-state', this.state);
        }
    }

    /**
     * Bind all event listeners
     */
    bindEvents() {
        // Toggle button
        const toggleBtn = document.querySelector('[data-sidebar-toggle]');
        if (toggleBtn) {
            toggleBtn.addEventListener('click', () => this.toggle());
        }

        // Close button (mobile)
        const closeBtn = document.querySelector('[data-sidebar-close]');
        if (closeBtn) {
            closeBtn.addEventListener('click', () => this.close());
        }

        // Nav items with children
        const navItems = document.querySelectorAll('.nav-item-has-children');
        navItems.forEach(item => {
            item.addEventListener('click', (e) => {
                e.preventDefault();
                this.toggleSubmenu(item);
            });
        });

        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', (e) => {
            if (window.innerWidth < 1024 && this.state === 'expanded') {
                if (!this.sidebar.contains(e.target)) {
                    this.close();
                }
            }
        });

        // Handle window resize
        window.addEventListener('resize', () => this.handleResize());
    }

    /**
     * Toggle sidebar expanded/collapsed
     */
    toggle() {
        this.state = this.state === 'expanded' ? 'collapsed' : 'expanded';
        this.applyState();
        this.saveState();
        this.emitEvent();
    }

    /**
     * Close sidebar (mobile)
     */
    close() {
        this.state = 'collapsed';
        this.applyState();
        this.saveState();
    }

    /**
     * Save state to localStorage
     */
    saveState() {
        localStorage.setItem('sidebarState', this.state);
    }

    /**
     * Emit custom event
     */
    emitEvent() {
        window.dispatchEvent(new CustomEvent('sidebarStateChanged', {
            detail: { state: this.state }
        }));
    }

    /**
     * Toggle submenu
     */
    toggleSubmenu(item) {
        const isExpanded = item.classList.contains('expanded');
        const submenu = item.nextElementSibling;

        if (isExpanded) {
            item.classList.remove('expanded');
            if (submenu) {
                submenu.classList.remove('show');
            }
        } else {
            item.classList.add('expanded');
            if (sidebar) {
                submenu.classList.add('show');
            }
        }
    }

    /**
     * Handle window resize
     */
    handleResize() {
        if (window.innerWidth >= 1024) {
            // Desktop - restore saved state
            this.applyState();
        } else {
            // Mobile - always collapsed initially
            this.sidebar.setAttribute('data-sidebar-state', 'collapsed');
        }
    }

    /**
     * Initialize keyboard shortcuts
     */
    initKeyboardShortcuts() {
        document.addEventListener('keydown', (e) => {
            // Press "/" to focus search
            if (e.key === '/' && !e.ctrlKey && !e.metaKey) {
                const activeElement = document.activeElement;
                const isInput = activeElement.tagName === 'INPUT' ||
                               activeElement.tagName === 'TEXTAREA';

                if (!isInput && this.searchInput) {
                    e.preventDefault();
                    this.searchInput.focus();
                }
            }

            // Press "Escape" to close sidebar on mobile
            if (e.key === 'Escape' && window.innerWidth < 1024) {
                this.close();
            }

            // Arrow key navigation
            if (e.key === 'ArrowDown' || e.key === 'ArrowUp') {
                const isSearchFocused = document.activeElement === this.searchInput;
                if (isSearchFocused) {
                    this.navigateResults(e.key === 'ArrowDown' ? 1 : -1);
                }
            }
        });
    }

    /**
     * Initialize search functionality
     */
    initSearch() {
        if (!this.searchInput) return;

        let searchTimeout;

        this.searchInput.addEventListener('input', (e) => {
            clearTimeout(searchTimeout);
            const query = e.target.value.trim();

            searchTimeout = setTimeout(() => {
                if (query.length >= 2) {
                    this.performSearch(query);
                } else {
                    this.clearSearchResults();
                }
            }, 300);
        });

        this.searchInput.addEventListener('keydown', (e) => {
            if (e.key === 'Enter') {
                const firstResult = document.querySelector('.search-result-item');
                if (firstResult) {
                    firstResult.click();
                }
            }
        });
    }

    /**
     * Perform search
     */
    performSearch(query) {
        // Search in menu items
        const navItems = document.querySelectorAll('.nav-item');
        const results = [];

        navItems.forEach(item => {
            const title = item.querySelector('.nav-title');
            if (title) {
                const text = title.textContent.toLowerCase();
                if (text.includes(query.toLowerCase())) {
                    results.push({
                        element: item,
                        title: title.textContent,
                        url: item.getAttribute('href')
                    });
                }
            }
        });

        this.displaySearchResults(results);
    }

    /**
     * Display search results
     */
    displaySearchResults(results) {
        this.clearSearchResults();

        if (results.length === 0) {
            const noResults = document.createElement('div');
            noResults.className = 'search-result-item no-results';
            noResults.textContent = 'No results found';
            this.searchInput.parentElement.nextElementSibling.appendChild(noResults);
            return;
        }

        const resultsContainer = document.createElement('div');
        resultsContainer.className = 'search-results';

        results.slice(0, 5).forEach(result => {
            const item = document.createElement('a');
            item.href = result.url;
            item.className = 'search-result-item';
            item.innerHTML = `
                <i class="ph ph-arrow-right"></i>
                <span>${result.title}</span>
            `;
            resultsContainer.appendChild(item);
        });

        this.searchInput.parentElement.nextElementSibling.appendChild(resultsContainer);
    }

    /**
     * Clear search results
     */
    clearSearchResults() {
        const existing = document.querySelectorAll('.search-results');
        existing.forEach(el => el.remove());
    }

    /**
     * Navigate search results
     */
    navigateResults(direction) {
        const results = document.querySelectorAll('.search-result-item');
        if (results.length === 0) return;

        const currentIndex = Array.from(results).findIndex(
            item => item.classList.contains('highlighted')
        );

        let nextIndex;
        if (direction === 1) {
            nextIndex = currentIndex < results.length - 1 ? currentIndex + 1 : 0;
        } else {
            nextIndex = currentIndex > 0 ? currentIndex - 1 : results.length - 1;
        }

        results.forEach(item => item.classList.remove('highlighted'));
        results[nextIndex].classList.add('highlighted');
        results[nextIndex].scrollIntoView({ block: 'nearest' });
    }

    /**
     * Initialize active states
     */
    initActiveStates() {
        const currentPath = window.location.pathname;
        const navItems = document.querySelectorAll('.nav-item, .nav-submenu-item');

        navItems.forEach(item => {
            const href = item.getAttribute('href');
            if (href && currentPath.startsWith(new URL(href).pathname)) {
                item.classList.add('active');

                // Expand parent if submenu item
                const parentGroup = item.closest('.nav-group');
                if (parentGroup) {
                    parentGroup.classList.add('expanded');
                    const submenu = parentGroup.querySelector('.nav-submenu');
                    if (submenu) {
                        submenu.classList.add('show');
                    }
                }
            }
        });
    }
}

// Initialize on DOM ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => {
        window.sidebar = new ModernSidebar();
    });
} else {
    window.sidebar = new ModernSidebar();
}

// Export for potential module usage
if (typeof module !== 'undefined' && module.exports) {
    module.exports = ModernSidebar;
}
