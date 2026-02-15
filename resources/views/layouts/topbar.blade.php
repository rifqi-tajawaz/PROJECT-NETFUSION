<!--start header-->
<header class="top-header">
  <nav class="navbar navbar-expand align-items-center gap-4">
    <div class="btn-toggle nav-box-item">
      <i class="material-icons-outlined">menu</i>
    </div>
    <div class="search-bar flex-grow-1">
      <div class="position-relative">
        <input
          class="form-control rounded-5 px-5 search-control border border-secondary border-opacity-25 d-lg-block d-none"
          type="text" placeholder="Search">
        <span
          class="material-icons-outlined position-absolute d-lg-block d-none ms-3 translate-middle-y start-0 top-50">search</span>
        <span
          class="material-icons-outlined position-absolute me-3 translate-middle-y end-0 top-50 search-close">close</span>
        <div class="search-popup p-3">
          <div class="card rounded-4 overflow-hidden">
            <div class="card-header d-lg-none">
              <div class="position-relative">
                <input
                  class="form-control rounded-5 px-5 mobile-search-control border border-secondary border-opacity-25"
                  type="text" placeholder="Search">
                <span
                  class="material-icons-outlined position-absolute ms-3 translate-middle-y start-0 top-50">search</span>
                <span
                  class="material-icons-outlined position-absolute me-3 translate-middle-y end-0 top-50 mobile-search-close">close</span>
              </div>
            </div>
            <div class="card-body search-content">
              <p class="search-title">Recent Searches</p>
              <div class="d-flex align-items-start flex-wrap gap-2 kewords-wrapper">
                <a href="javascript:;" class="kewords"><span>Angular Template</span><i
                    class="material-icons-outlined fs-6">search</i></a>
                <a href="javascript:;" class="kewords"><span>Dashboard</span><i
                    class="material-icons-outlined fs-6">search</i></a>
                <a href="javascript:;" class="kewords"><span>Admin Template</span><i
                    class="material-icons-outlined fs-6">search</i></a>
                <a href="javascript:;" class="kewords"><span>Bootstrap 5 Admin</span><i
                    class="material-icons-outlined fs-6">search</i></a>
                <a href="javascript:;" class="kewords"><span>Html eCommerce</span><i
                    class="material-icons-outlined fs-6">search</i></a>
                <a href="javascript:;" class="kewords"><span>Sass</span><i
                    class="material-icons-outlined fs-6">search</i></a>
                <a href="javascript:;" class="kewords"><span>laravel 9</span><i
                    class="material-icons-outlined fs-6">search</i></a>
              </div>
            </div>
          </div>
          <div class="card-footer text-center bg-transparent">
            <a href="javascript:;" class="btn w-100">See All Search Results</a>
          </div>
        </div>
      </div>
    </div>
    </div>
    <ul class="navbar-nav gap-3 nav-right-links align-items-center">

      <li class="nav-item d-lg-none mobile-search-btn">
        <a class="nav-link" href="javascript:;"><i class="material-icons-outlined">search</i></a>
      </li>

      <!-- Quick Access -->
      <li class="nav-item dropdown position-static d-md-flex d-none">
        <div class="nav-box-item border border-secondary border-opacity-25" data-bs-auto-close="outside"
          data-bs-toggle="dropdown">
          <i class="material-icons-outlined">speed</i>
        </div>
        <div class="dropdown-menu dropdown-menu-end mega-menu shadow-lg p-4 p-lg-5">
          <div class="mega-menu-widgets">
            <h5 class="mb-4 fw-bold text-primary"><i class="bi bi-lightning-charge me-2"></i>Quick Access</h5>
            <div class="row row-cols-1 row-cols-lg-2 row-cols-xl-3 g-4">
              <div class="col">
                <a href="{{ route('mikrotik-suite.security.hardening.hide-identity') }}" class="text-decoration-none">
                  <div class="card rounded-4 shadow-none border mb-0 transition-hover h-100">
                    <div class="card-body">
                      <div class="d-flex align-items-center gap-3">
                        <div class="mega-menu-icon flex-shrink-0 bg-danger bg-opacity-10 text-danger rounded-3 p-2">
                          <i class="material-icons-outlined fs-3">security</i>
                        </div>
                        <div class="mega-menu-content">
                          <h6 class="mb-1 fw-bold text-dark">Security</h6>
                          <p class="mb-0 small text-secondary">Port Knocking, Filter, Hardening</p>
                        </div>
                      </div>
                    </div>
                  </div>
                </a>
              </div>
              <div class="col">
                <a href="{{ route('mikrotik-suite.utilities.calculators.ip') }}" class="text-decoration-none">
                  <div class="card rounded-4 shadow-none border mb-0 h-100">
                    <div class="card-body">
                      <div class="d-flex align-items-center gap-3">
                        <div class="mega-menu-icon flex-shrink-0 bg-primary bg-opacity-10 text-primary rounded-3 p-2">
                          <i class="material-icons-outlined fs-3">lan</i>
                        </div>
                        <div class="mega-menu-content">
                          <h6 class="mb-1 fw-bold text-dark">IP Management</h6>
                          <p class="mb-0 small text-secondary">Addresses, Firewall, Routes</p>
                        </div>
                      </div>
                    </div>
                  </div>
                </a>
              </div>
              <div class="col">
                <a href="{{ route('mikrotik-suite.monitoring.traffic-monitor') }}" class="text-decoration-none">
                  <div class="card rounded-4 shadow-none border mb-0 h-100">
                    <div class="card-body">
                      <div class="d-flex align-items-center gap-3">
                        <div class="mega-menu-icon flex-shrink-0 bg-success bg-opacity-10 text-success rounded-3 p-2">
                          <i class="material-icons-outlined fs-3">insights</i>
                        </div>
                        <div class="mega-menu-content">
                          <h6 class="mb-1 fw-bold text-dark">Monitoring</h6>
                          <p class="mb-0 small text-secondary">Traffic View, Active Users</p>
                        </div>
                      </div>
                    </div>
                  </div>
                </a>
              </div>
            </div><!--end row-->
          </div>
        </div>
      </li>

      <!-- System Tools -->
      <li class="nav-item dropdown d-none d-md-flex">
        <div class="nav-box-item border border-secondary border-opacity-25" data-bs-auto-close="outside"
          data-bs-toggle="dropdown">
          <i class="material-icons-outlined">build_circle</i>
        </div>
        <div class="dropdown-menu dropdown-menu-end dropdown-apps shadow-lg p-3">
          <h6 class="mb-3 px-2 fw-bold text-primary">System Tools</h6>
          <div class="border rounded-4 overflow-hidden">
            <div class="row row-cols-3 g-0 border-bottom">
              <div class="col border-end">
                <a href="javascript:;"
                  class="app-wrapper d-flex flex-column gap-2 text-center py-3 text-decoration-none">
                  <div class="app-icon text-primary">
                    <i class="material-icons-outlined">calculate</i>
                  </div>
                  <div class="app-name">
                    <p class="mb-0 small fw-bold">IP Calc</p>
                  </div>
                </a>
              </div>
              <div class="col border-end">
                <a href="javascript:;"
                  class="app-wrapper d-flex flex-column gap-2 text-center py-3 text-decoration-none">
                  <div class="app-icon text-warning">
                    <i class="material-icons-outlined">transform</i>
                  </div>
                  <div class="app-name">
                    <p class="mb-0 small fw-bold">Convert</p>
                  </div>
                </a>
              </div>
              <div class="col">
                <a href="javascript:;"
                  class="app-wrapper d-flex flex-column gap-2 text-center py-3 text-decoration-none">
                  <div class="app-icon text-success">
                    <i class="material-icons-outlined">history</i>
                  </div>
                  <div class="app-name">
                    <p class="mb-0 small fw-bold">Logs</p>
                  </div>
                </a>
              </div>
            </div><!--end row-->
          </div>
        </div>
      </li>

      <!-- Notifications -->
      <li class="nav-item dropdown">
        <div class="nav-box-item border border-secondary border-opacity-25" data-bs-toggle="dropdown">
          <i class="material-icons-outlined">notifications</i>
          <span class="badge-notify">8</span>
        </div>
        <div class="dropdown-menu dropdown-notify dropdown-menu-end shadow">
          <div class="px-3 py-1 d-flex align-items-center justify-content-between border-bottom">
            <h5 class="notiy-title mb-0">System Notifications</h5>
          </div>
          <div class="notify-list">
            <div>
              <a class="dropdown-item border-bottom py-2" href="javascript:;">
                <div class="d-flex align-items-center gap-3">
                  <div class="user-wrapper bg-warning text-warning bg-opacity-10">
                    <i class="bi bi-shield-exclamation fs-5"></i>
                  </div>
                  <div class="">
                    <h5 class="notify-title">Security Alert</h5>
                    <p class="mb-0 notify-desc">Frequent login attempts detected on SSH.</p>
                    <p class="mb-0 notify-time">Just now</p>
                  </div>
                </div>
              </a>
            </div>
          </div>
        </div>
      </li>

      <!-- Theme Switcher (Palette) -->
      <li class="nav-item dropdown d-none d-md-flex">
        <div class="nav-box-item border border-secondary border-opacity-25 dropdown-toggle dropdown-toggle-nocaret"
          data-bs-toggle="dropdown">
          <i class="material-icons-outlined theme-icon-active">palette</i>
        </div>
        <ul class="dropdown-menu dropdown-menu-end">
          <li>
            <a class="dropdown-item d-flex align-items-center gap-2 theme-select" href="javascript:;"
              data-theme="light">
              <i class="material-icons-outlined">light_mode</i> Light
            </a>
          </li>
          <li>
            <a class="dropdown-item d-flex align-items-center gap-2 theme-select" href="javascript:;" data-theme="dark">
              <i class="material-icons-outlined">dark_mode</i> Dark
            </a>
          </li>
          <li>
            <a class="dropdown-item d-flex align-items-center gap-2 theme-select" href="javascript:;"
              data-theme="semi-dark">
              <i class="material-icons-outlined">contrast</i> Semi Dark
            </a>
          </li>
        </ul>
      </li>

      <!-- Language (Translate) -->
      <li class="nav-item dropdown d-none d-md-flex">
        <div class="nav-box-item border border-secondary border-opacity-25 dropdown-toggle dropdown-toggle-nocaret"
          data-bs-toggle="dropdown">
          <i class="material-icons-outlined">translate</i>
        </div>
        <ul class="dropdown-menu dropdown-menu-end">
          <li><a class="dropdown-item d-flex align-items-center py-2" href="{{ route('lang.switch', 'id') }}">
              <img src="{{ URL::asset('build/images/county/id.png') }}" class="rounded-circle" width="20" height="20"
                alt=""><span class="ms-2">Indonesia</span>
            </a></li>
          <li><a class="dropdown-item d-flex align-items-center py-2" href="{{ route('lang.switch', 'en') }}">
              <img src="{{ URL::asset('build/images/county/en.png') }}" class="rounded-circle" width="20" height="20"
                alt=""><span class="ms-2">English</span>
            </a></li>
        </ul>
      </li>

      <!-- User Profile Pill -->
      <li class="nav-item dropdown" id="user-dropdown-item">
        <div
          class="user-pill border border-secondary border-opacity-25 d-flex align-items-center gap-3 ps-1 pe-3 rounded-pill cursor-pointer dropdown-toggle dropdown-toggle-nocaret"
          data-bs-toggle="dropdown">
          @if(optional(Auth::user())->avatar)
            <img src="{{ Auth::user()->avatar }}" class="rounded-circle" width="38" height="38" alt="">
          @else
            <div
              class="rounded-circle bg-brand text-white d-flex align-items-center justify-content-center fw-bold user-avatar-placeholder">
              {{ strtoupper(substr(optional(Auth::user())->name ?? 'Guest', 0, 1)) }}
            </div>
          @endif
          <i class="material-icons-outlined fs-5 user-setting-icon">settings</i>
        </div>

        <div class="dropdown-menu dropdown-user dropdown-menu-end shadow">
          <a class="dropdown-item gap-2 py-2" href="javascript:;">
            <div class="text-center">
              <h5 class="user-name mb-0 fw-bold text-truncate d-block mx-auto" style="max-width: 100%;">
                Hello, {{ optional(Auth::user())->name ?? 'Guest' }}
              </h5>
              <p class="mb-0 small text-secondary">{{ optional(Auth::user())->email ?? 'guest@netfusion.com' }}</p>
            </div>
          </a>
          {{-- System Tools Hidden on Mobile as per request --}}
          {{--
          <div class="d-block d-md-none border-bottom mb-2 pb-2">
            <h6 class="dropdown-header small text-uppercase text-secondary fw-bold">System Tools</h6>
            <a class="dropdown-item d-flex align-items-center gap-2 py-2"
              href="{{ route('mikrotik-suite.utilities.calculators.ip') }}">
              <i class="material-icons-outlined">calculate</i> IP Calc
            </a>
            <a class="dropdown-item d-flex align-items-center gap-2 py-2" href="javascript:;">
              <i class="material-icons-outlined">transform</i> Convert
            </a>
            <a class="dropdown-item d-flex align-items-center gap-2 py-2" href="javascript:;">
              <i class="material-icons-outlined">history</i> Logs
            </a>
          </div>
          --}}

          <div class="d-block d-md-none border-bottom mb-2 pb-2">
            <h6 class="dropdown-header small text-uppercase text-secondary fw-bold">Theme</h6>
            <a class="dropdown-item d-flex align-items-center gap-2 theme-select" href="javascript:;"
              data-theme="light">
              <i class="material-icons-outlined">light_mode</i> Light
            </a>
            <a class="dropdown-item d-flex align-items-center gap-2 theme-select" href="javascript:;" data-theme="dark">
              <i class="material-icons-outlined">dark_mode</i> Dark
            </a>
            <a class="dropdown-item d-flex align-items-center gap-2 theme-select" href="javascript:;"
              data-theme="semi-dark">
              <i class="material-icons-outlined">contrast</i> Semi Dark
            </a>
          </div>

          <div class="d-block d-md-none border-bottom mb-2 pb-2">
            <h6 class="dropdown-header small text-uppercase text-secondary fw-bold">Language</h6>
            <a class="dropdown-item d-flex align-items-center gap-2" href="{{ route('lang.switch', 'id') }}">
              <img src="{{ URL::asset('build/images/county/id.png') }}" class="rounded-circle" width="20" height="20"
                alt=""> Indonesia
            </a>
            <a class="dropdown-item d-flex align-items-center gap-2" href="{{ route('lang.switch', 'en') }}">
              <img src="{{ URL::asset('build/images/county/en.png') }}" class="rounded-circle" width="20" height="20"
                alt=""> English
            </a>
          </div>

          <hr class="dropdown-divider">
          <a class="dropdown-item d-flex align-items-center gap-2 py-2" href="{{ route('user.profile') }}"><i
              class="material-icons-outlined">person_outline</i>User Profile</a>

          <hr class="dropdown-divider">
          <a class="dropdown-item d-flex align-items-center gap-2 py-2" href="javascript:void(0);"
            onclick="document.getElementById('logout-form').submit()"><i
              class="material-icons-outlined">power_settings_new</i>Logout</a>
          <form action="{{ route('logout') }}" method="POST" id="logout-form">@csrf</form>
        </div>
      </li>
    </ul>

  </nav>
</header>
<!--end top header-->

@push('scripts')
  @vite(['resources/js/layouts/topbar.js'])
@endpush