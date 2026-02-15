

$(function () {
  "use strict";


  /* scrollar */

  if (document.querySelector(".notify-list")) {
    new PerfectScrollbar(".notify-list")
  }

  if (document.querySelector(".search-content")) {
    new PerfectScrollbar(".search-content")
  }




  /* toggle button */
  $(document).on("click", ".btn-toggle", function (e) {
    e.preventDefault();
    const isMobile = window.innerWidth <= 1199;

    if ($("body").hasClass("toggled")) {
      $("body").removeClass("toggled");
      if (!isMobile) $(".sidebar-wrapper").unbind("hover");
    } else {
      $("body").addClass("toggled");
      if (!isMobile) {
        $(".sidebar-wrapper").hover(function () {
          $("body").addClass("sidebar-hovered")
        }, function () {
          $("body").removeClass("sidebar-hovered")
        });
      }
    }
  });




  /* menu */

  if ($.fn.metisMenu) {
    $('#sidenav').metisMenu();
  }

  $(document).on("click", ".sidebar-close", function () {
    $("body").removeClass("toggled")
  });

  $(document).on("click", ".overlay", function () {
    $("body").removeClass("toggled")
  });



  /* dark mode button */

  $(".dark-mode i").click(function () {
    $(this).text(function (i, v) {
      return v === 'dark_mode' ? 'light_mode' : 'dark_mode'
    })
  });


  $(".dark-mode").click(function () {
    $("html").attr("data-bs-theme", function (i, v) {
      return v === 'dark' ? 'light' : 'dark';
    })
  })


  /* sticky header */

  $(window).on("scroll", function () {
    if ($(this).scrollTop() > 60) {
      $('.top-header .navbar').addClass('sticky-header');
    } else {
      $('.top-header .navbar').removeClass('sticky-header');
    }
  });


  /* email */

  $(".email-toggle-btn").on("click", function () {
    $(".email-wrapper").toggleClass("email-toggled");
  });

  $(".email-toggle-btn-mobile").on("click", function () {
    $(".email-wrapper").removeClass("email-toggled");
  });

  $(".compose-mail-btn").on("click", function () {
    $(".compose-mail-popup").show();
  });

  $(".compose-mail-close").on("click", function () {
    $(".compose-mail-popup").hide();
  });


    /* chat */

    $(".chat-toggle-btn").on("click", function () {
      $(".chat-wrapper").toggleClass("chat-toggled");
    });

    $(".chat-toggle-btn-mobile").on("click", function () {
      $(".chat-wrapper").removeClass("chat-toggled");
    });


  /* theme switcher removed - using light theme only */



  /* search control */

  /* search control */
  const searchInput = $(".search-control");
  const searchPopup = $(".search-popup");
  const searchClose = $(".search-close");
  const mobileSearchBtn = $(".mobile-search-btn");
  const mobileSearchClose = $(".mobile-search-close");
  const recentSearchesContainer = $(".kewords-wrapper");

  // Load recent searches on boot
  loadRecentSearches();
  // Searchable Pages Index
  const isAdmin = document.querySelector('meta[name="is-admin"]')?.getAttribute('content') === '1';

  let searchablePages = [
    { name: "Dashboard", url: "/mikrotik/dashboard", icon: "home" },
    { name: "NetFusion Dashboard", url: "/mikrotik/netfusion/dashboard", icon: "grid_view" },
    { name: "Hide Router Identity", url: "/mikrotik/security/hide-router-identity", icon: "visibility_off" },
    { name: "DHCP Rogue Detection", url: "/mikrotik/security/dhcp-rogue", icon: "router" },
    { name: "Port Knocking", url: "/mikrotik/security/port-knocking", icon: "lock_open" },
    { name: "Content Filter", url: "/mikrotik/security/content-filter", icon: "filter_alt" },
    { name: "Timeline", url: "/timeline", icon: "join_right" },
    { name: "Support", url: "/support", icon: "support" },
    { name: "Profile", url: "/user/profile", icon: "person" }
  ];

  if (isAdmin) {
    searchablePages.push({ name: "User Management", url: "/admin/users", icon: "manage_accounts" });
    searchablePages.push({ name: "Admin Access (Activity Logs)", url: "/admin/activity-logs", icon: "history" });
  }

  // Event Listeners
  searchInput.on("focus click input", function () {
    const query = $(this).val().trim().toLowerCase();

    searchPopup.addClass("d-block");
    searchClose.addClass("d-block");

    if (query.length > 0) {
      // Perform Search
      const results = searchablePages.filter(page =>
        page.name.toLowerCase().includes(query)
      );
      renderSearchResults(results, query);
    } else {
      // Show Recent History if input is empty
      loadRecentSearches();
    }
  });

  searchInput.on("keydown", function (e) {
    if (e.key === 'Enter') {
      const query = $(this).val().trim();
      if (query) {
        saveRecentSearch(query);
        // If there's a top result, go to it
        const firstResult = searchPopup.find('.search-list-item a').first().attr('href');
        if (firstResult && firstResult !== 'javascript:;') {
          window.location.href = firstResult;
        }
      }
    }
  });

  // ... (Close handlers remain same) ...

  $(document).on("click", function (e) {
    if (!$(e.target).closest(".search-bar").length && !$(e.target).closest(".mobile-search-btn").length) {
      searchPopup.removeClass("d-block");
      searchClose.removeClass("d-block");
    }
  });

  searchClose.click(function () {
    searchPopup.removeClass("d-block");
    searchClose.removeClass("d-block");
    searchInput.val('');
    loadRecentSearches(); // Reset to recent
  });

  // ... (Mobile handlers remain same) ...
  mobileSearchBtn.click(function () {
    searchPopup.addClass("d-block");
  });

  mobileSearchClose.click(function () {
    searchPopup.removeClass("d-block");
  });


  // Render Functions
  function renderSearchResults(results, query) {
    recentSearchesContainer.empty();

    // Header
    recentSearchesContainer.append(`<p class="search-title w-100 mb-2">Search Results for "${query}"</p>`);

    const listDiv = $('<div class="search-list d-flex flex-column gap-2 w-100"></div>');

    if (results.length > 0) {
      results.forEach(page => {
        const item = `
                     <div class="search-list-item d-flex align-items-center gap-3">
                        <div class="list-icon">
                            <i class="material-icons-outlined fs-5">${page.icon}</i>
                        </div>
                        <div class="flex-grow-1">
                            <h5 class="mb-0 search-list-title"><a href="${page.url}" class="text-reset text-decoration-none stretched-link">${page.name}</a></h5>
                        </div>
                    </div>
                `;
        listDiv.append(item);
      });
    } else {
      listDiv.append(`
                <div class="text-center py-3 text-muted">
                    <i class="material-icons-outlined fs-3 mb-2">manage_search</i>
                    <p class="small mb-0">No pages found matching "${query}"</p>
                </div>
            `);
    }

    recentSearchesContainer.append(listDiv);
  }

  function loadRecentSearches() {
    // Mock data if empty
    let searches = JSON.parse(localStorage.getItem('recent_searches') || '[]');

    // Purge legacy template data if found
    const legacyTerms = ["Angular Template", "Admin Template", "Bootstrap 5 Admin", "Html eCommerce"];
    const hasLegacy = searches.some(term => legacyTerms.includes(term));

    if (searches.length === 0 || hasLegacy) {
      // Default suggestions (Clean)
      searches = ["Dashboard", "Admin Access", "Content Filter"];
      localStorage.setItem('recent_searches', JSON.stringify(searches));
    }

    renderRecentSearches(searches);
  }

  function saveRecentSearch(query) {
    let searches = JSON.parse(localStorage.getItem('recent_searches') || '[]');
    searches = searches.filter(s => s.toLowerCase() !== query.toLowerCase());
    searches.unshift(query);
    if (searches.length > 8) searches.pop();
    localStorage.setItem('recent_searches', JSON.stringify(searches));
  }

  function renderRecentSearches(searches) {
    recentSearchesContainer.empty();
    recentSearchesContainer.append(`<p class="search-title w-100 mb-2">Recent & Suggestions</p>`);

    const tagsWrapper = $('<div class="d-flex align-items-start flex-wrap gap-2"></div>');

    searches.forEach(term => {
      const tag = `
                <a href="javascript:;" class="kewords" onclick="$('.search-control').val('${term}').trigger('input');">
                    <span>${term}</span>
                    <i class="material-icons-outlined fs-6">history</i>
                </a>
            `;
      tagsWrapper.append(tag);
    });

    recentSearchesContainer.append(tagsWrapper);
  }




  /* menu active */

  for (var e = window.location, o = $(".metismenu li a").filter(function () {
    return this.href == e
  }).addClass("").parent().addClass("mm-active"); o.is("li");) o = o.parent("").addClass("mm-show").parent("").addClass("mm-active")



});

/* =========================================
   NETFUSION UI SYSTEM (Centralized JS)
   ========================================= */

window.NetFusion = {
  /**
   * Copy text from an element to clipboard
   */
  copyToClipboard: function (elementId, btnSelector) {
    const text = document.getElementById(elementId).innerText;
    navigator.clipboard.writeText(text).then(() => {
      const btn = (typeof btnSelector === 'string') ? document.querySelector(btnSelector) : btnSelector;
      if (btn) {
        const originalContent = btn.innerHTML;
        const isIconBtn = btn.querySelector('.material-icons-outlined');

        if (isIconBtn) {
          btn.innerHTML = '<span class="material-icons-outlined fs-6 align-middle me-1">check_circle</span> Copied!';
        } else {
          btn.textContent = 'Copied!';
        }

        btn.classList.add('btn-success');
        btn.classList.remove('btn-outline-secondary', 'btn-light', 'btn-dark');

        setTimeout(() => {
          btn.innerHTML = originalContent;
          btn.classList.remove('btn-success');
        }, 2000);
      }

      const toast = document.getElementById('copyToast');
      if (toast) {
        toast.classList.remove('d-none');
        setTimeout(() => toast.classList.add('d-none'), 2000);
      }
    });
  },

  /**
   * Toggle Port Knocking Method UI
   */
  toggleMethod: function () {
    const mode = document.getElementById('knockMode').value;
    const icmp = document.getElementById('method-icmp');
    const port = document.getElementById('method-port');

    if (mode === 'icmp') {
      icmp.classList.remove('d-none');
      port.classList.add('d-none');
    } else {
      icmp.classList.add('d-none');
      port.classList.remove('d-none');
    }
  },

  /**
   * Handle Port Knocking Generation (Via Backend)
   */
  generatePortKnocking: function (url, formId = 'pkForm') {
    const form = document.getElementById(formId);
    if (!form) return;

    if (!form.checkValidity()) {
      form.reportValidity();
      return;
    }

    const submitBtn = form.querySelector('button[type="button"]');
    const originalBtnContent = submitBtn ? submitBtn.innerHTML : '';

    if (submitBtn) {
      submitBtn.disabled = true;
      submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> Generating...';
    }

    const output = document.getElementById('scriptOutput');
    output.innerHTML = '<div class="d-flex align-items-center justify-content-center text-white-50 gap-2 w-100 h-100 p-4"><div class="spinner-border spinner-border-sm text-primary" role="status"></div> <span>Generating secure script...</span></div>';

    const formData = new FormData(form);
    const jsonData = Object.fromEntries(formData.entries());

    axios.post(url, jsonData)
      .then(function (response) {
        output.innerHTML = `<pre class="m-0 p-4 text-warning font-monospace small" style="white-space: pre-wrap;">${response.data.script}</pre>`;
      })
      .catch(function (error) {
        console.error(error);
        let msg = error.response?.data?.message || "Failed to generate script.";
        output.innerHTML = `<span class="text-danger"># Error: ${msg}</span>`;
      })
      .finally(function () {
        if (submitBtn) {
          submitBtn.disabled = false;
          submitBtn.innerHTML = originalBtnContent;
        }
      });
  }
};










