/**
 * NetFusion Dashboard Core JS
 * Professional Modular Architecture
 */

"use strict";

import $ from 'jquery';
window.$ = window.jQuery = $;

import axios from 'axios';
window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// Prevent double-loading Bootstrap. Use the global instance from common-scripts.
const bootstrap = window.bootstrap; 

import 'metismenu';
import SimpleBar from 'simplebar';
import 'simplebar/dist/simplebar.css';
window.SimpleBar = SimpleBar;

const NetFusionUI = {
  // --- Initialization ---
  init() {
    this.Layout.init();
    this.Search.init();
    this.Components.init();
    this.Utils.init();
  },

  // --- Layout Module (Sidebar, Header, Theme) ---
  Layout: {
    init() {
      this.initTheme();
      this.initMenuActive();
      this.bindSidebarEvents();
      this.bindStickyHeader();
      this.bindThemeEvents();
      this.initSidebarScroll();
    },

    initSidebarScroll() {
      // Explicitly init SimpleBar for sidebar navigation
      if (typeof SimpleBar !== 'undefined' && document.querySelector('.sidebar-nav')) {
        new SimpleBar(document.querySelector('.sidebar-nav'));
      }
    },

    initTheme() {
      // Check if theme is locked (e.g. Auth pages)
      if ($("html").data("theme-locked")) {
        return;
      }
      const savedTheme = localStorage.getItem("nf-theme") || "dark";
      $("html").attr("data-bs-theme", savedTheme);
      this.updateThemeIcon(savedTheme);
    },

    bindSidebarEvents() {
      const self = this;
      $(document).on("click", ".btn-toggle", function (e) {
        e.preventDefault();
        const isMobile = window.innerWidth <= 1199;
        const body = $("body");

        if (body.hasClass("toggled")) {
          body.removeClass("toggled");
          if (!isMobile) $(".sidebar-wrapper").unbind("mouseenter mouseleave");
        } else {
          body.addClass("toggled");
          if (!isMobile) {
            $(".sidebar-wrapper").hover(
              () => body.addClass("sidebar-hovered"),
              () => body.removeClass("sidebar-hovered")
            );
          }
        }
      });

      // Mobile: Close sidebar when clicking outside or close button
      $(document).on("click", ".sidebar-close, .overlay", () => {
        $("body").removeClass("toggled");
      });

      // Mobile: Close sidebar when clicking a link (optional UX improvement)
      $(document).on("click", ".sidebar-wrapper .metismenu a", function () {
        if (window.innerWidth <= 1199 && !$(this).parent().hasClass("mm-active") && !$(this).hasClass("has-arrow")) {
          $("body").removeClass("toggled");
        }
      });

      if ($.fn.metisMenu) {
        $('#sidenav').metisMenu();
      }
    },

    bindStickyHeader() {
      $(window).on("scroll", function () {
        if ($(this).scrollTop() > 60) {
          $('.top-header .navbar').addClass('sticky-header');
        } else {
          $('.top-header .navbar').removeClass('sticky-header');
        }
      });
    },

    bindThemeEvents() {
      const self = this;

      // Topbar Dropdown Selection (Delegation)
      $(document).on("click", ".theme-select", function (e) {
        e.preventDefault();
        const theme = $(this).attr("data-theme");
        if (theme) self.setTheme(theme);
      });
    },

    setTheme(theme) {
      $("html").attr("data-bs-theme", theme);
      localStorage.setItem("nf-theme", theme);
      this.updateThemeIcon(theme);

    },

    updateThemeIcon(theme) {
      const icon = $(".theme-icon-active");
      if (theme === "dark") {
        icon.text("dark_mode");
      } else if (theme === "light") {
        icon.text("light_mode");
      } else if (theme === "semi-dark") {
        icon.text("contrast");
      } else {
        icon.text("light_mode");
      }
    },

    initMenuActive() {
      // Normalize URL (ignore query params/hash for matching)
      const currentUrl = window.location.href.split(/[?#]/)[0];

      $(".metismenu li a").filter(function () {
        // Compare href without query params as well
        return this.href.split(/[?#]/)[0] === currentUrl;
      }).addClass("").parent().addClass("mm-active").parents("ul").addClass("mm-show").parent().addClass("mm-active");
    }
  },

  // --- Search Module ---
  Search: {
    init() {
      this.vars = {
        input: $(".search-control"),
        popup: $(".search-popup"),
        close: $(".search-close"),
        mobileBtn: $(".mobile-search-btn"),
        mobileClose: $(".mobile-search-close"),
        container: $(".kewords-wrapper"),
        isAdmin: document.querySelector('meta[name="is-admin"]')?.getAttribute('content') === '1'
      };

      this.pages = [
        { name: "Dashboard", url: "/mikrotik-suite/dashboard", icon: "home" },
        { name: "NetFusion Dashboard", url: "/mikrotik-suite/netfusion/dashboard", icon: "grid_view" },
        { name: "Hide Router Identity", url: "/mikrotik-suite/security/hide-router-identity", icon: "visibility_off" },
        { name: "DHCP Rogue Detection", url: "/mikrotik-suite/security/dhcp-rogue", icon: "router" },
        { name: "Port Knocking", url: "/mikrotik-suite/security/port-knocking", icon: "lock_open" },
        { name: "Content Filter", url: "/mikrotik-suite/security/content-filter", icon: "filter_alt" },
        { name: "Timeline", url: "/timeline", icon: "join_right" },
        { name: "Support", url: "/support", icon: "support" },
        { name: "Profile", url: "/user/profile", icon: "person" }
      ];

      if (this.vars.isAdmin) {
        this.pages.push({ name: "User Management", url: "/admin/users", icon: "manage_accounts" });
        this.pages.push({ name: "Activity Logs", url: "/admin/activity-logs", icon: "history" });
      }

      this.bindEvents();
      this.loadHistory();
    },

    bindEvents() {
      const self = this;
      this.vars.input.on("focus click input", function () {
        const query = $(this).val().trim().toLowerCase();
        self.vars.popup.addClass("d-block");
        self.vars.close.addClass("d-block");

        if (query.length > 0) {
          const results = self.pages.filter(p => p.name.toLowerCase().includes(query));
          self.renderResults(results, query);
        } else {
          self.loadHistory();
        }
      });

      this.vars.input.on("keydown", function (e) {
        if (e.key === 'Enter') {
          const query = $(this).val().trim();
          if (query) {
            self.saveHistory(query);
            const firstResult = self.vars.popup.find('.search-list-item a').first().attr('href');
            if (firstResult && firstResult !== 'javascript:;') window.location.href = firstResult;
          }
        }
      });

      $(document).on("click", (e) => {
        if (!$(e.target).closest(".search-bar, .mobile-search-btn").length) {
          this.vars.popup.removeClass("d-block");
          this.vars.close.removeClass("d-block");
        }
      });

      this.vars.close.click(() => {
        this.vars.popup.removeClass("d-block");
        this.vars.close.removeClass("d-block");
        this.vars.input.val('');
        this.loadHistory();
      });

      this.vars.mobileBtn.click(() => this.vars.popup.addClass("d-block"));
      this.vars.mobileClose.click(() => this.vars.popup.removeClass("d-block"));
    },

    renderResults(results, query) {
      this.vars.container.empty().append(`<p class="search-title w-100 mb-2">Search Results for "${query}"</p>`);
      const listDiv = $('<div class="search-list d-flex flex-column gap-2 w-100"></div>');

      if (results.length > 0) {
        results.forEach(page => {
          listDiv.append(`
            <div class="search-list-item d-flex align-items-center gap-3">
              <div class="list-icon"><i class="material-icons-outlined fs-5">${page.icon}</i></div>
              <div class="flex-grow-1">
                <h5 class="mb-0 search-list-title"><a href="${page.url}" class="text-reset text-decoration-none stretched-link">${page.name}</a></h5>
              </div>
            </div>
          `);
        });
      } else {
        listDiv.append(`<div class="text-center py-3 text-muted"><p class="small mb-0">No pages found matching "${query}"</p></div>`);
      }
      this.vars.container.append(listDiv);
    },

    loadHistory() {
      let searches = JSON.parse(localStorage.getItem('recent_searches') || '[]');
      if (searches.length === 0) {
        searches = ["Dashboard", "Activity Logs", "Content Filter"];
        localStorage.setItem('recent_searches', JSON.stringify(searches));
      }
      this.renderHistory(searches);
    },

    saveHistory(query) {
      let searches = JSON.parse(localStorage.getItem('recent_searches') || '[]');
      searches = searches.filter(s => s.toLowerCase() !== query.toLowerCase());
      searches.unshift(query);
      if (searches.length > 8) searches.pop();
      localStorage.setItem('recent_searches', JSON.stringify(searches));
    },

    renderHistory(searches) {
      this.vars.container.empty().append(`<p class="search-title w-100 mb-2">Recent & Suggestions</p>`);
      const tagsWrapper = $('<div class="d-flex align-items-start flex-wrap gap-2"></div>');

      searches.forEach(term => {
        tagsWrapper.append(`
          <a href="javascript:;" class="kewords" onclick="$('.search-control').val('${term}').trigger('input');">
            <span>${term}</span><i class="material-icons-outlined fs-6">history</i>
          </a>
        `);
      });
      this.vars.container.append(tagsWrapper);
    }
  },

  // --- Components Module (Scrollbar, Email, Chat) ---
  Components: {
    init() {
      this.initScrollbars();
      this.initEmail();
      this.initChat();
      this.initTooltips();
    },

    initTooltips() {
      // 1. Standard Bootstrap Tooltips (always enabled)
      const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
      tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
      });

      // 2. Smart Sidebar Tooltips (Robust Implementation)
      // Cleanup any existing tooltips first
      $('.tooltip').remove();

      // Target ALL sidebar links, not just those with data-tooltip
      $(document).on('mouseenter', '.sidebar-wrapper .metismenu a', function () {
        const $this = $(this);
        
        // Use a delay to allow sidebar transitions (approx 300ms) to complete
        setTimeout(() => {
          // If user moved mouse away, do nothing
          if (!$this.is(':hover')) return;

          // 1. Determine the Tooltip Title (Content)
          let title = $this.attr('data-tooltip');
          const $menuTitle = $this.find('.menu-title');
          
          if (!title) {
              if ($menuTitle.length > 0) {
                  title = $menuTitle.text().trim();
              } else {
                  // Fallback for submenus: Clone and remove icons to get pure text
                  const $clone = $this.clone();
                  $clone.find('i, .material-icons-outlined, .parent-icon').remove();
                  title = $clone.text().trim();
              }
          }

          if (!title) return; // Should not happen, but safety check

          // 2. Determine Truncation Target
          const titleEl = $menuTitle[0];
          // Fallback: If no .menu-title (e.g. submenu items), use the element itself ($this)
          const targetEl = titleEl || $this[0];
          
          let shouldShow = false;

          if (titleEl) {
            // Case 1: Icon-only mode (Collapsed Sidebar)
            // If .menu-title exists but has 0 width (hidden via CSS), show tooltip
            if (titleEl.clientWidth === 0) {
              shouldShow = true;
            }
            // Case 2: Truncation Check for .menu-title
            else if (titleEl.scrollWidth > titleEl.clientWidth) {
              shouldShow = true;
            }
          } else {
            // Case 3: Submenu items (no .menu-title wrapper)
            // Check if the anchor tag itself is truncated
            if (targetEl.scrollWidth > targetEl.clientWidth) {
               shouldShow = true;
            }
          }

          // Debug mode (Optional: remove in production)
          // console.log('Tooltip Check:', { text: title, scroll: targetEl.scrollWidth, client: targetEl.clientWidth, show: shouldShow });

          if (shouldShow) {
            let tooltip = bootstrap.Tooltip.getInstance($this[0]);
            if (!tooltip) {
              tooltip = new bootstrap.Tooltip($this[0], {
                trigger: 'manual',
                container: 'body', // Critical: Append to body to avoid clipping
                placement: 'auto',
                title: title, // Use the dynamically determined title
                boundary: 'window'
              });
            }
            tooltip.show();
          }
        }, 300); // 300ms delay matches sidebar CSS transition
      });

      // Hide on mouseleave
      $(document).on('mouseleave', '.sidebar-wrapper .metismenu a', function () {
        const tooltip = bootstrap.Tooltip.getInstance(this);
        if (tooltip) {
          tooltip.hide();
        }
      });
    },

    initScrollbars() {
      if (typeof PerfectScrollbar !== "undefined") {
        if (document.querySelector(".notify-list")) new PerfectScrollbar(".notify-list");
        if (document.querySelector(".search-content")) new PerfectScrollbar(".search-content");
      }
    },

    initEmail() {
      $(".email-toggle-btn").on("click", () => $(".email-wrapper").toggleClass("email-toggled"));
      $(".email-toggle-btn-mobile").on("click", () => $(".email-wrapper").removeClass("email-toggled"));
      $(".compose-mail-btn").on("click", () => $(".compose-mail-popup").show());
      $(".compose-mail-close").on("click", () => $(".compose-mail-popup").hide());
    },

    initChat() {
      $(".chat-toggle-btn").on("click", () => $(".chat-wrapper").toggleClass("chat-toggled"));
      $(".chat-toggle-btn-mobile").on("click", () => $(".chat-wrapper").removeClass("chat-toggled"));
    }
  },

  // --- Utilities ---
  Utils: {
    init() {
      // Global utility initialization
      this.initGlobalLoader();
      this.initClickableCards();
    },

    initGlobalLoader() {
      // Show loader on Form Submit
      document.querySelectorAll('form').forEach(form => {
        form.addEventListener('submit', function () {
          if (!this.checkValidity() || this.classList.contains('no-loader')) return;
          const loader = document.getElementById('global-loader');
          if (loader) loader.style.display = 'flex';
        });
      });

      // Show loader on specific links
      document.querySelectorAll('.show-loader').forEach(link => {
        link.addEventListener('click', function () {
          const loader = document.getElementById('global-loader');
          if (loader) loader.style.display = 'flex';
        });
      });

      // Hide on page show (bfcache restore)
      window.addEventListener('pageshow', function (event) {
        if (event.persisted) {
          const loader = document.getElementById('global-loader');
          if (loader) loader.style.display = 'none';
        }
      });
    },

    initClickableCards() {
      // Handle cards with data-href attribute
      document.querySelectorAll('.clickable[data-href]').forEach(card => {
        card.addEventListener('click', function (e) {
          const href = this.getAttribute('data-href');
          if (href && href !== '#' && href !== 'javascript:;') {
            const loader = document.getElementById('global-loader');
            if (loader) loader.style.display = 'flex';
            window.location.href = href;
          }
        });
      });

      // Handle list items with data-link attribute
      document.querySelectorAll('.clickable[data-link]').forEach(item => {
        item.addEventListener('click', function (e) {
          const link = this.getAttribute('data-link');
          if (link && link !== '#' && link !== 'javascript:;') {
            const loader = document.getElementById('global-loader');
            if (loader) loader.style.display = 'flex';
            window.location.href = link;
          }
        });
      });
    }
  }
};

// --- Boot Application ---
$(() => NetFusionUI.init());

window.NetFusionUI = NetFusionUI;

// --- Global API ---
import { copyText } from './utils/clipboard';
window.copyText = copyText;
window.NetFusion = {
  copyToClipboard: copyText // Backward compatibility alias
};

export { NetFusionUI };
