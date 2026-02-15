import { describe, it, expect, vi, beforeEach, afterEach } from 'vitest';

// Mock dependencies
vi.mock('perfect-scrollbar', () => ({
    default: vi.fn()
}));

vi.mock('simplebar', () => ({
    default: vi.fn()
}));

// Mock CSS imports to avoid errors
vi.mock('simplebar/dist/simplebar.css', () => ({}));
vi.mock('metismenu', () => ({}));

// Import main.js logic
import { NetFusionUI } from '../../resources/js/main.js';
import PerfectScrollbar from 'perfect-scrollbar';
import SimpleBar from 'simplebar';

describe('NetFusionUI', () => {

    beforeEach(() => {
        document.body.innerHTML = '';
        vi.clearAllMocks();

        // Ensure global PerfectScrollbar is available as main.js expects it
        global.PerfectScrollbar = PerfectScrollbar;
    });

    afterEach(() => {
        delete global.PerfectScrollbar;
    });

    describe('Components.initScrollbars', () => {
        it('should initialize PerfectScrollbar for .notify-list if present', () => {
            const el = document.createElement('div');
            el.className = 'notify-list';
            document.body.appendChild(el);

            NetFusionUI.Components.initScrollbars();

            expect(PerfectScrollbar).toHaveBeenCalledWith('.notify-list');
        });

        it('should initialize PerfectScrollbar for .search-content if present', () => {
            const el = document.createElement('div');
            el.className = 'search-content';
            document.body.appendChild(el);

            NetFusionUI.Components.initScrollbars();

            expect(PerfectScrollbar).toHaveBeenCalledWith('.search-content');
        });

        it('should not initialize PerfectScrollbar if elements are missing', () => {
            NetFusionUI.Components.initScrollbars();

            expect(PerfectScrollbar).not.toHaveBeenCalled();
        });
    });

    describe('Layout.initSidebarScroll', () => {
        it('should initialize SimpleBar for .sidebar-nav if present', () => {
            const el = document.createElement('div');
            el.className = 'sidebar-nav';
            document.body.appendChild(el);

            NetFusionUI.Layout.initSidebarScroll();

            expect(SimpleBar).toHaveBeenCalledTimes(1);
            expect(SimpleBar).toHaveBeenCalledWith(el);
        });

        it('should not initialize SimpleBar if .sidebar-nav is missing', () => {
            NetFusionUI.Layout.initSidebarScroll();

            expect(SimpleBar).not.toHaveBeenCalled();
        });
    });
});
