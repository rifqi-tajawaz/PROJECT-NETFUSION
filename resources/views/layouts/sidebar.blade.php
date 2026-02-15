<!--start sidebar-->
<aside class="sidebar-wrapper d-flex flex-column">
      <div class="sidebar-header">
            <div class="logo-icon">
                  <img src="{{ URL::asset('build/images/logo-icon.png') }}" class="logo-img" alt="">
            </div>
            <div class="logo-name flex-grow-1 d-flex flex-column justify-content-center">
                  <h5 class="mb-0 lh-1 text-nowrap logo-text">NetFusion</h5>
                  <div class="logo-subtitle">By Tajawaz Solutions</div>
            </div>
            <div class="sidebar-close nav-box-item border border-secondary border-opacity-25">
                  <span class="material-icons-outlined">close</span>
            </div>
      </div>
      <div class="sidebar-nav flex-grow-1 overflow-auto">
            <!--navigation-->
            <ul class="metismenu" id="sidenav">
                  <li
                        class="{{ request()->routeIs(['mikrotik-suite.dashboard', 'mikrotik-suite.netfusion.dashboard']) ? 'mm-active' : '' }}">
                        <a href="javascript:;"
                              class="has-arrow {{ request()->routeIs(['mikrotik-suite.dashboard', 'mikrotik-suite.netfusion.dashboard']) ? 'mm-active' : '' }}"
                              aria-expanded="{{ request()->routeIs(['mikrotik-suite.dashboard', 'mikrotik-suite.netfusion.dashboard']) ? 'true' : 'false' }}"
                              data-tooltip="{{ __('menu.dashboard') }}">
                              <div class="parent-icon"><i class="material-icons-outlined">home</i>
                              </div>
                              <div class="menu-title">{{ __('menu.dashboard') }}</div>
                        </a>

                        <ul
                              class="mm-collapse {{ request()->routeIs(['mikrotik-suite.dashboard', 'mikrotik-suite.netfusion.dashboard']) ? 'mm-show' : '' }}">
                              <li class="{{ request()->routeIs('mikrotik-suite.dashboard') ? 'mm-active' : '' }}">
                                    <a href="{{ route('mikrotik-suite.dashboard') }}"
                                          data-tooltip="{{ __('menu.main_dashboard') }}">
                                          <i
                                                class="material-icons-outlined">arrow_right</i>{{ __('menu.main_dashboard') }}
                                    </a>
                              </li>
                              <li
                                    class="{{ request()->routeIs('mikrotik-suite.netfusion.dashboard') ? 'mm-active' : '' }}">
                                    <a href="{{ route('mikrotik-suite.netfusion.dashboard') }}"
                                          data-tooltip="{{ __('menu.netfusion_dashboard') }}">
                                          <i
                                                class="material-icons-outlined">arrow_right</i>{{ __('menu.netfusion_dashboard') }}
                                    </a>
                              </li>


                        </ul>
                  </li>

                  <li class="menu-label">{{ __('menu.netfusion_manager') }}</li>

                  <!-- NetFusion Manager Items Flattened -->
                  <li>
                        <a class="has-arrow" href="javascript:;"
                              aria-expanded="{{ request()->routeIs('mikrotik-suite.netfusion.users.*') ? 'true' : 'false' }}">
                              <div class="parent-icon"><i class="material-icons-outlined">wifi</i></div>
                              <div class="menu-title">{{ __('menu.hotspot') }}</div>
                        </a>
                        <ul
                              class="mm-collapse {{ request()->routeIs('mikrotik-suite.netfusion.users.*') ? 'mm-show' : '' }}">
                              <li>
                                    <a class="has-arrow" href="javascript:;"><i
                                                class="material-icons-outlined">arrow_right</i>{{ __('menu.users') }}</a>
                                    <ul>
                                          <li><a href="{{ route('mikrotik-suite.netfusion.users.index') }}"><i
                                                            class="material-icons-outlined">list</i>{{ __('menu.user_list') }}</a>
                                          </li>
                                          <li><a href="{{ route('mikrotik-suite.netfusion.users.create') }}"><i
                                                            class="material-icons-outlined">person_add</i>{{ __('menu.add_user') }}</a>
                                          </li>
                                          <li><a href="{{ route('mikrotik-suite.netfusion.users.generate') }}"><i
                                                            class="material-icons-outlined">qr_code</i>{{ __('menu.generate') }}</a>
                                          </li>
                                    </ul>
                              </li>
                              <li>
                                    <a class="has-arrow" href="javascript:;"><i
                                                class="material-icons-outlined">arrow_right</i>{{ __('menu.user_profile') }}</a>
                                    <ul>
                                          <li><a href="{{ route('mikrotik-suite.netfusion.profiles.index') }}"><i
                                                            class="material-icons-outlined">list</i>{{ __('menu.profile_list') }}</a>
                                          </li>
                                    </ul>
                              </li>
                              <li><a href="{{ route('mikrotik-suite.netfusion.active.index') }}"><i
                                                class="material-icons-outlined">arrow_right</i>{{ __('menu.active') }}</a>
                              </li>
                              <li><a href="{{ route('mikrotik-suite.netfusion.hosts.index') }}"><i
                                                class="material-icons-outlined">arrow_right</i>{{ __('menu.hosts') }}</a>
                              </li>
                              <li><a href="{{ route('mikrotik-suite.netfusion.ip-binding.index') }}"><i
                                                class="material-icons-outlined">arrow_right</i>{{ __('menu.ip_binding') }}</a>
                              </li>
                              <li><a href="{{ route('mikrotik-suite.netfusion.cookies.index') }}"><i
                                                class="material-icons-outlined">arrow_right</i>{{ __('menu.cookies') }}</a>
                              </li>
                        </ul>
                  </li>

                  <li>
                        <a href="{{ route('mikrotik-suite.netfusion.printing.index') }}">
                              <div class="parent-icon"><i class="material-icons-outlined">print</i></div>
                              <div class="menu-title">{{ __('menu.quick_print') }}</div>
                        </a>
                  </li>
                  <li>
                        <a href="{{ route('mikrotik-suite.netfusion.users.batches') }}">
                              <div class="parent-icon"><i class="material-icons-outlined">confirmation_number</i></div>
                              <div class="menu-title">{{ __('menu.vouchers') }}</div>
                        </a>
                  </li>

                  <li>
                        <a class="has-arrow" href="javascript:;">
                              <div class="parent-icon"><i class="material-icons-outlined">router</i></div>
                              <div class="menu-title">{{ __('menu.ppp') }}</div>
                        </a>
                        <ul>
                              <li><a href="{{ route('mikrotik-suite.netfusion.ppp.secrets.index') }}"><i
                                                class="material-icons-outlined">vpn_key</i>{{ __('menu.secrets') }}</a>
                              </li>
                              <li><a href="{{ route('mikrotik-suite.netfusion.ppp.profiles.index') }}"><i
                                                class="material-icons-outlined">settings_input_component</i>{{ __('menu.profiles') }}</a>
                              </li>
                              <li><a href="{{ route('mikrotik-suite.netfusion.ppp.active.index') }}"><i
                                                class="material-icons-outlined">monitor_heart</i>{{ __('menu.active') }}</a>
                              </li>
                        </ul>
                  </li>

                  <li>
                        <a href="{{ route('mikrotik-suite.netfusion.dhcp.leases.index') }}">
                              <div class="parent-icon"><i class="material-icons-outlined">devices</i></div>
                              <div class="menu-title">{{ __('menu.dhcp_leases') }}</div>
                        </a>
                  </li>

                  <li>
                        <a href="{{ route('mikrotik-suite.netfusion.system.index') }}">
                              <div class="parent-icon"><i class="material-icons-outlined">settings_power</i></div>
                              <div class="menu-title">{{ __('menu.system_tools') }}</div>
                        </a>
                  </li>

                  <li>
                        <a href="{{ route('mikrotik-suite.netfusion.reports.index') }}">
                              <div class="parent-icon"><i class="material-icons-outlined">monetization_on</i></div>
                              <div class="menu-title">{{ __('menu.report') }}</div>
                        </a>
                  </li>

                  <li>
                        <a href="{{ route('mikrotik-suite.netfusion.reports.logs') }}">
                              <div class="parent-icon"><i class="material-icons-outlined">list</i></div>
                              <div class="menu-title">{{ __('menu.system_logs') }}</div>
                        </a>
                  </li>

                  <li>
                        <a class="has-arrow" href="javascript:;">
                              <div class="parent-icon"><i class="material-icons-outlined">settings</i></div>
                              <div class="menu-title">{{ __('menu.settings') }}</div>
                        </a>
                        <ul>
                              <li><a href="{{ route('mikrotik-suite.netfusion.settings.index') }}"><i
                                                class="material-icons-outlined">arrow_right</i>{{ __('menu.admin_settings') }}</a>
                              </li>
                              <li><a href="{{ route('mikrotik-suite.netfusion.tools.upload-logo') }}"><i
                                                class="material-icons-outlined">arrow_right</i>{{ __('menu.upload_logo') }}</a>
                              </li>
                              <li><a href="{{ route('mikrotik-suite.netfusion.tools.template-editor') }}"><i
                                                class="material-icons-outlined">arrow_right</i>{{ __('menu.template_editor') }}</a>
                              </li>
                        </ul>
                  </li>




                  <!-- Mikrotik Suite Flattened -->
                  <li class="menu-label">{{ __('menu.mikrotik_suite') }}</li>

                  <!-- 1. Connectivity -->
                  <li>
                        <a href="javascript:;" class="has-arrow" data-tooltip="{{ __('menu.connectivity') }}">
                              <div class="parent-icon"><i class="material-icons-outlined">cast_connected</i>
                              </div>
                              <div class="menu-title">{{ __('menu.connectivity') }}</div>
                        </a>
                        <ul
                              class="mm-collapse {{ request()->routeIs('mikrotik-suite.connectivity.*') ? 'mm-show' : '' }}">
                              <li>
                                    <a class="has-arrow" href="javascript:;" data-tooltip="{{ __('menu.hotspot') }}"><i
                                                class="material-icons-outlined">arrow_right</i>{{ __('menu.hotspot') }}</a>
                                    <ul>
                                          <li><a href="{{ route('mikrotik-suite.connectivity.hotspot.wizard') }}"><i
                                                            class="material-icons-outlined">arrow_right</i>{{ __('menu.hotspot_wizard') }}</a>
                                          </li>
                                          <li><a href="{{ route('mikrotik-suite.connectivity.hotspot.user-generator') }}"><i
                                                            class="material-icons-outlined">arrow_right</i>{{ __('menu.user_generator') }}</a>
                                          </li>
                                          <li><a href="{{ route('mikrotik-suite.connectivity.hotspot.qr-code') }}"><i
                                                            class="material-icons-outlined">arrow_right</i>{{ __('menu.qr_code_wifi') }}</a>
                                          </li>
                                          <li><a href="{{ route('mikrotik-suite.connectivity.hotspot.login-template') }}"><i
                                                            class="material-icons-outlined">arrow_right</i>{{ __('menu.login_template') }}</a>
                                          </li>
                                          <li><a href="{{ route('mikrotik-suite.connectivity.hotspot.bandwidth-limiter') }}"><i
                                                            class="material-icons-outlined">arrow_right</i>{{ __('menu.bandwidth_limiter') }}</a>
                                          </li>
                                          <li><a href="{{ route('mikrotik-suite.connectivity.hotspot.block-sharing') }}"><i
                                                            class="material-icons-outlined">arrow_right</i>{{ __('menu.block_sharing') }}</a>
                                          </li>
                                          <li><a href="{{ route('mikrotik-suite.connectivity.hotspot.expired-notification') }}"><i
                                                            class="material-icons-outlined">arrow_right</i>{{ __('menu.expired_notification') }}</a>
                                          </li>
                                    </ul>
                              </li>
                              <li>
                                    <a class="has-arrow" href="javascript:;" data-tooltip="{{ __('menu.iot') }}"><i
                                                class="material-icons-outlined">arrow_right</i>{{ __('menu.iot') }}</a>
                                    <ul>
                                          <li><a href="{{ route('mikrotik-suite.connectivity.iot.mqtt-config') }}"><i
                                                            class="material-icons-outlined">arrow_right</i>{{ __('menu.mqtt_config') }}</a>
                                          </li>
                                          <li><a href="{{ route('mikrotik-suite.connectivity.iot.mqtt-publisher') }}"><i
                                                            class="material-icons-outlined">arrow_right</i>{{ __('menu.mqtt_publisher') }}</a>
                                          </li>
                                          <li><a href="{{ route('mikrotik-suite.connectivity.iot.lorawan-gateway') }}"><i
                                                            class="material-icons-outlined">arrow_right</i>{{ __('menu.lorawan_gateway') }}</a>
                                          </li>
                                    </ul>
                              </li>
                              <li>
                                    <a class="has-arrow" href="javascript:;" data-tooltip="{{ __('menu.pppoe') }}"><i
                                                class="material-icons-outlined">arrow_right</i>{{ __('menu.pppoe') }}</a>
                                    <ul>
                                          <li><a href="{{ route('mikrotik-suite.connectivity.pppoe.server') }}"><i
                                                            class="material-icons-outlined">arrow_right</i>{{ __('menu.pppoe_server') }}</a>
                                          </li>
                                          <li><a href="{{ route('mikrotik-suite.connectivity.pppoe.secrets-generator') }}"><i
                                                            class="material-icons-outlined">arrow_right</i>{{ __('menu.secrets_generator') }}</a>
                                          </li>
                                          <li><a href="{{ route('mikrotik-suite.connectivity.pppoe.telegram-reporter') }}"><i
                                                            class="material-icons-outlined">arrow_right</i>{{ __('menu.telegram_reporter') }}</a>
                                          </li>
                                    </ul>
                              </li>
                              <li>
                                    <a class="has-arrow" href="javascript:;"
                                          data-tooltip="{{ __('menu.vpn_configuration') }}"><i
                                                class="material-icons-outlined">arrow_right</i>{{ __('menu.vpn_configuration') }}</a>
                                    <ul>
                                          <li><a href="{{ route('mikrotik-suite.connectivity.vpn.tunnel') }}"><i
                                                            class="material-icons-outlined">arrow_right</i>{{ __('menu.vpn_tunnel') }}</a>
                                          </li>
                                          <li><a href="{{ route('mikrotik-suite.connectivity.vpn.sstp-server') }}"><i
                                                            class="material-icons-outlined">arrow_right</i>{{ __('menu.sstp_server') }}</a>
                                          </li>
                                          <li><a href="{{ route('mikrotik-suite.connectivity.vpn.l2tp-server') }}"><i
                                                            class="material-icons-outlined">arrow_right</i>{{ __('menu.l2tp_server') }}</a>
                                          </li>
                                          <li><a href="{{ route('mikrotik-suite.connectivity.vpn.pptp-server') }}"><i
                                                            class="material-icons-outlined">arrow_right</i>{{ __('menu.pptp_server') }}</a>
                                          </li>
                                          <li><a href="{{ route('mikrotik-suite.connectivity.vpn.openvpn') }}"><i
                                                            class="material-icons-outlined">arrow_right</i>{{ __('menu.openvpn') }}</a>
                                          </li>
                                          <li><a href="{{ route('mikrotik-suite.connectivity.vpn.wireguard') }}"><i
                                                            class="material-icons-outlined">arrow_right</i>{{ __('menu.wireguard') }}</a>
                                          </li>
                                    </ul>
                              </li>
                        </ul>
                  </li>

                  <!-- 2. Customization -->
                  <li>
                        <a href="javascript:;" class="has-arrow" data-tooltip="{{ __('menu.customization') }}">
                              <div class="parent-icon"><i class="material-icons-outlined">palette</i></div>
                              <div class="menu-title">{{ __('menu.customization') }}</div>
                        </a>
                        <ul
                              class="mm-collapse {{ request()->routeIs('mikrotik-suite.customization.*') ? 'mm-show' : '' }}">
                              <li>
                                    <a class="has-arrow" href="javascript:;"
                                          data-tooltip="{{ __('menu.branding_theme') }}"><i
                                                class="material-icons-outlined">arrow_right</i>{{ __('menu.branding_theme') }}</a>
                                    <ul>
                                          <li><a href="{{ route('mikrotik-suite.customization.webfig-skin') }}"><i
                                                            class="material-icons-outlined">arrow_right</i>{{ __('menu.webfig_skin') }}</a>
                                          </li>
                                          <li><a href="{{ route('mikrotik-suite.customization.branding-maker.index') }}"><i
                                                            class="material-icons-outlined">arrow_right</i>{{ __('menu.branding_maker') }}</a>
                                          </li>
                                          <li><a href="{{ route('mikrotik-suite.customization.logo-assets') }}"><i
                                                            class="material-icons-outlined">arrow_right</i>{{ __('menu.logo_assets') }}</a>
                                          </li>
                                    </ul>
                              </li>
                              <li>
                                    <a class="has-arrow" href="javascript:;"
                                          data-tooltip="{{ __('menu.hotspot_templates') }}"><i
                                                class="material-icons-outlined">arrow_right</i>{{ __('menu.hotspot_templates') }}</a>
                                    <ul>
                                          <li><a href="{{ route('mikrotik-suite.customization.hotspot-templates.login-v6') }}"><i
                                                            class="material-icons-outlined">arrow_right</i>{{ __('menu.login_template_v6') }}</a>
                                          </li>
                                          <li><a href="{{ route('mikrotik-suite.customization.hotspot-templates.login-v7') }}"><i
                                                            class="material-icons-outlined">arrow_right</i>{{ __('menu.login_template_v7') }}</a>
                                          </li>
                                          <li><a href="{{ route('mikrotik-suite.customization.hotspot-templates.custom') }}"><i
                                                            class="material-icons-outlined">arrow_right</i>{{ __('menu.custom_template') }}</a>
                                          </li>
                                    </ul>
                              </li>
                              <li>
                                    <a class="has-arrow" href="javascript:;"
                                          data-tooltip="{{ __('menu.special_tools') }}"><i
                                                class="material-icons-outlined">arrow_right</i>{{ __('menu.special_tools') }}</a>
                                    <ul>
                                          <li><a href="{{ route('mikrotik-suite.customization.special-tools.supout-reader') }}"><i
                                                            class="material-icons-outlined">arrow_right</i>{{ __('menu.supout_reader') }}</a>
                                          </li>
                                          <li><a href="{{ route('mikrotik-suite.customization.special-tools.rsc-beautifier') }}"><i
                                                            class="material-icons-outlined">arrow_right</i>{{ __('menu.rsc_beautifier') }}</a>
                                          </li>
                                          <li><a href="{{ route('mikrotik-suite.customization.special-tools.wifiid-auto-login') }}"><i
                                                            class="material-icons-outlined">arrow_right</i>{{ __('menu.wifiid_autologin') }}</a>
                                          </li>
                                    </ul>
                              </li>
                        </ul>
                  </li>

                  <!-- 3. Monitoring -->
                  <li>
                        <a href="javascript:;" class="has-arrow" data-tooltip="{{ __('menu.monitoring') }}">
                              <div class="parent-icon"><i class="material-icons-outlined">monitor</i></div>
                              <div class="menu-title">{{ __('menu.monitoring') }}</div>
                        </a>
                        <ul
                              class="mm-collapse {{ request()->routeIs('mikrotik-suite.monitoring.*') ? 'mm-show' : '' }}">
                              <li>
                                    <a class="has-arrow" href="javascript:;" data-tooltip="{{ __('menu.dns_time') }}"><i
                                                class="material-icons-outlined">arrow_right</i>{{ __('menu.dns_time') }}</a>
                                    <ul>
                                          <li><a href="{{ route('mikrotik-suite.monitoring.dns-over-https') }}"><i
                                                            class="material-icons-outlined">arrow_right</i>{{ __('menu.dns_over_https') }}</a>
                                          </li>
                                          <li><a href="{{ route('mikrotik-suite.monitoring.ntp-client') }}"><i
                                                            class="material-icons-outlined">arrow_right</i>{{ __('menu.ntp_client') }}</a>
                                          </li>
                                    </ul>
                              </li>
                              <li>
                                    <a class="has-arrow" href="javascript:;"
                                          data-tooltip="{{ __('menu.network_discovery') }}"><i
                                                class="material-icons-outlined">arrow_right</i>{{ __('menu.network_discovery') }}</a>
                                    <ul>
                                          <li><a href="{{ route('mikrotik-suite.monitoring.neighbour-viewer') }}"><i
                                                            class="material-icons-outlined">arrow_right</i>{{ __('menu.neighbour_viewer') }}</a>
                                          </li>
                                          <li><a href="{{ route('mikrotik-suite.monitoring.mac-address-tools') }}"><i
                                                            class="material-icons-outlined">arrow_right</i>{{ __('menu.mac_address_tools') }}</a>
                                          </li>
                                          <li><a href="{{ route('mikrotik-suite.monitoring.interface-bonding') }}"><i
                                                            class="material-icons-outlined">arrow_right</i>{{ __('menu.interface_bonding') }}</a>
                                          </li>
                                    </ul>
                              </li>
                              <li>
                                    <a class="has-arrow" href="javascript:;"
                                          data-tooltip="{{ __('menu.network_monitoring') }}"><i
                                                class="material-icons-outlined">arrow_right</i>{{ __('menu.network_monitoring') }}</a>
                                    <ul>
                                          <li><a href="{{ route('mikrotik-suite.monitoring.traffic-monitor') }}"><i
                                                            class="material-icons-outlined">arrow_right</i>{{ __('menu.traffic_monitor') }}</a>
                                          </li>
                                          <li><a href="{{ route('mikrotik-suite.monitoring.traffic-sniffer') }}"><i
                                                            class="material-icons-outlined">arrow_right</i>{{ __('menu.traffic_sniffer') }}</a>
                                          </li>
                                          <li><a href="{{ route('mikrotik-suite.monitoring.attix5-monitor') }}"><i
                                                            class="material-icons-outlined">arrow_right</i>{{ __('menu.attix5_monitor') }}</a>
                                          </li>
                                          <li><a href="{{ route('mikrotik-suite.monitoring.netwatch-alert') }}"><i
                                                            class="material-icons-outlined">arrow_right</i>{{ __('menu.netwatch_alert') }}</a>
                                          </li>
                                    </ul>
                              </li>
                              <li>
                                    <a class="has-arrow" href="javascript:;"
                                          data-tooltip="{{ __('menu.troubleshooting') }}"><i
                                                class="material-icons-outlined">arrow_right</i>{{ __('menu.troubleshooting') }}</a>
                                    <ul>
                                          <li><a href="{{ route('mikrotik-suite.monitoring.troubleshooting.graphing') }}"><i
                                                            class="material-icons-outlined">arrow_right</i>{{ __('menu.graphing') }}</a>
                                          </li>
                                          <li><a href="{{ route('mikrotik-suite.monitoring.troubleshooting.cpu-profiling') }}"><i
                                                            class="material-icons-outlined">arrow_right</i>{{ __('menu.cpu_profiling') }}</a>
                                          </li>
                                          <li><a href="{{ route('mikrotik-suite.monitoring.troubleshooting.packet-sniffer') }}"><i
                                                            class="material-icons-outlined">arrow_right</i>{{ __('menu.packet_sniffer') }}</a>
                                          </li>
                                          <li><a href="{{ route('mikrotik-suite.monitoring.troubleshooting.log-regex') }}"><i
                                                            class="material-icons-outlined">arrow_right</i>{{ __('menu.log_regex_generator') }}</a>
                                          </li>
                                    </ul>
                              </li>
                        </ul>
                  </li>

                  <!-- 4. Network -->
                  <li>
                        <a href="javascript:;" class="has-arrow" data-tooltip="{{ __('menu.network') }}">
                              <div class="parent-icon"><i class="material-icons-outlined">hub</i></div>
                              <div class="menu-title">{{ __('menu.network') }}</div>
                        </a>
                        <ul class="mm-collapse {{ request()->routeIs('mikrotik-suite.network.*') ? 'mm-show' : '' }}">
                              <li>
                                    <a class="has-arrow" href="javascript:;"
                                          data-tooltip="{{ __('menu.enterprise') }}"><i
                                                class="material-icons-outlined">arrow_right</i>{{ __('menu.enterprise') }}</a>
                                    <ul>
                                          <li><a href="{{ route('mikrotik-suite.network.enterprise.ldp-vpls') }}"><i
                                                            class="material-icons-outlined">arrow_right</i>{{ __('menu.ldp_vpls') }}</a>
                                          </li>
                                          <li><a href="{{ route('mikrotik-suite.network.enterprise.traffic-engineering') }}"><i
                                                            class="material-icons-outlined">arrow_right</i>{{ __('menu.traffic_engineering') }}</a>
                                          </li>
                                    </ul>
                              </li>
                              <li>
                                    <a class="has-arrow" href="javascript:;" data-tooltip="{{ __('menu.ipv6') }}"><i
                                                class="material-icons-outlined">arrow_right</i>{{ __('menu.ipv6') }}</a>
                                    <ul>
                                          <li><a href="{{ route('mikrotik-suite.network.ipv6.eui64-calculator') }}"><i
                                                            class="material-icons-outlined">arrow_right</i>{{ __('menu.eui64_calculator') }}</a>
                                          </li>
                                          <li><a href="{{ route('mikrotik-suite.network.ipv6.subnetting-generator') }}"><i
                                                            class="material-icons-outlined">arrow_right</i>{{ __('menu.subnetting_generator') }}</a>
                                          </li>
                                          <li><a href="{{ route('mikrotik-suite.network.ipv6.firewall-generator') }}"><i
                                                            class="material-icons-outlined">arrow_right</i>{{ __('menu.firewall_generator') }}</a>
                                          </li>
                                          <li><a href="{{ route('mikrotik-suite.network.ipv6.neighbor-discovery') }}"><i
                                                            class="material-icons-outlined">arrow_right</i>{{ __('menu.neighbor_discovery') }}</a>
                                          </li>
                                    </ul>
                              </li>
                              <li>
                                    <a class="has-arrow" href="javascript:;"
                                          data-tooltip="{{ __('menu.load_balancing') }}"><i
                                                class="material-icons-outlined">arrow_right</i>{{ __('menu.load_balancing') }}</a>
                                    <ul>
                                          <li><a href="{{ route('mikrotik-suite.network.load-balancing.pcc') }}"><i
                                                            class="material-icons-outlined">arrow_right</i>{{ __('menu.pcc') }}</a>
                                          </li>
                                          <li><a href="{{ route('mikrotik-suite.network.load-balancing.nth') }}"><i
                                                            class="material-icons-outlined">arrow_right</i>{{ __('menu.nth') }}</a>
                                          </li>
                                          <li><a href="{{ route('mikrotik-suite.network.load-balancing.ecmp') }}"><i
                                                            class="material-icons-outlined">arrow_right</i>{{ __('menu.ecmp') }}</a>
                                          </li>
                                    </ul>
                              </li>
                              <li>
                                    <a class="has-arrow" href="javascript:;"
                                          data-tooltip="{{ __('menu.routing_gateway') }}"><i
                                                class="material-icons-outlined">arrow_right</i>{{ __('menu.routing_gateway') }}</a>
                                    <ul>
                                          <li><a href="{{ route('mikrotik-suite.network.routing.bgp-generator') }}"><i
                                                            class="material-icons-outlined">arrow_right</i>{{ __('menu.bgp_generator') }}</a>
                                          </li>
                                          <li><a href="{{ route('mikrotik-suite.network.routing.ospf-generator') }}"><i
                                                            class="material-icons-outlined">arrow_right</i>{{ __('menu.ospf_generator') }}</a>
                                          </li>
                                          <li><a href="{{ route('mikrotik-suite.network.routing.static-route') }}"><i
                                                            class="material-icons-outlined">arrow_right</i>{{ __('menu.static_route') }}</a>
                                          </li>
                                          <li><a href="{{ route('mikrotik-suite.network.routing.failover-gateway') }}"><i
                                                            class="material-icons-outlined">arrow_right</i>{{ __('menu.failover_gateway') }}</a>
                                          </li>
                                          <li><a href="{{ route('mikrotik-suite.network.routing.policy-routing') }}"><i
                                                            class="material-icons-outlined">arrow_right</i>{{ __('menu.policy_routing') }}</a>
                                          </li>
                                    </ul>
                              </li>
                              <li>
                                    <a class="has-arrow" href="javascript:;"
                                          data-tooltip="{{ __('menu.switching') }}"><i
                                                class="material-icons-outlined">arrow_right</i>{{ __('menu.switching') }}</a>
                                    <ul>
                                          <li><a href="{{ route('mikrotik-suite.network.switching.bridge-vlan') }}"><i
                                                            class="material-icons-outlined">arrow_right</i>{{ __('menu.bridge_vlan_filtering') }}</a>
                                          </li>
                                          <li><a href="{{ route('mikrotik-suite.network.switching.management-vlan') }}"><i
                                                            class="material-icons-outlined">arrow_right</i>{{ __('menu.management_vlan') }}</a>
                                          </li>
                                          <li><a href="{{ route('mikrotik-suite.network.switching.bonding') }}"><i
                                                            class="material-icons-outlined">arrow_right</i>{{ __('menu.bonding') }}</a>
                                          </li>
                                          <li><a href="{{ route('mikrotik-suite.network.switching.spanning-tree') }}"><i
                                                            class="material-icons-outlined">arrow_right</i>{{ __('menu.spanning_tree') }}</a>
                                          </li>
                                    </ul>
                              </li>
                        </ul>
                  </li>

                  <!-- 5. QoS -->
                  <li>
                        <a href="javascript:;" class="has-arrow" data-tooltip="{{ __('menu.qos') }}">
                              <div class="parent-icon"><i class="material-icons-outlined">speed</i></div>
                              <div class="menu-title">{{ __('menu.qos') }}</div>
                        </a>
                        <ul class="mm-collapse {{ request()->routeIs('mikrotik-suite.qos.*') ? 'mm-show' : '' }}">
                              <li>
                                    <a class="has-arrow" href="javascript:;"
                                          data-tooltip="{{ __('menu.application_routing') }}"><i
                                                class="material-icons-outlined">arrow_right</i>{{ __('menu.application_routing') }}</a>
                                    <ul>
                                          <li><a href="{{ route('mikrotik-suite.qos.application.gaming') }}"><i
                                                            class="material-icons-outlined">arrow_right</i>{{ __('menu.gaming_routes') }}</a>
                                          </li>
                                          <li><a href="{{ route('mikrotik-suite.qos.application.social-media') }}"><i
                                                            class="material-icons-outlined">arrow_right</i>{{ __('menu.social_media_routes') }}</a>
                                          </li>
                                          <li><a href="{{ route('mikrotik-suite.qos.application.streaming') }}"><i
                                                            class="material-icons-outlined">arrow_right</i>{{ __('menu.streaming_routes') }}</a>
                                          </li>
                                          <li><a href="{{ route('mikrotik-suite.qos.application.website') }}"><i
                                                            class="material-icons-outlined">arrow_right</i>{{ __('menu.website_routes') }}</a>
                                          </li>
                                    </ul>
                              </li>
                              <li>
                                    <a class="has-arrow" href="javascript:;" data-tooltip="{{ __('menu.queues') }}"><i
                                                class="material-icons-outlined">arrow_right</i>{{ __('menu.queues') }}</a>
                                    <ul>
                                          <li><a href="{{ route('mikrotik-suite.qos.queues.simple') }}"><i
                                                            class="material-icons-outlined">arrow_right</i>{{ __('menu.simple_queue') }}</a>
                                          </li>
                                          <li><a href="{{ route('mikrotik-suite.qos.queues.tree') }}"><i
                                                            class="material-icons-outlined">arrow_right</i>{{ __('menu.queue_tree') }}</a>
                                          </li>
                                          <li><a href="{{ route('mikrotik-suite.qos.queues.optimizer') }}"><i
                                                            class="material-icons-outlined">arrow_right</i>{{ __('menu.queue_optimizer') }}</a>
                                          </li>
                                          <li><a href="{{ route('mikrotik-suite.qos.queues.pcq') }}"><i
                                                            class="material-icons-outlined">arrow_right</i>{{ __('menu.pcq_configuration') }}</a>
                                          </li>
                                          <li><a href="{{ route('mikrotik-suite.qos.queues.token-bucket') }}"><i
                                                            class="material-icons-outlined">arrow_right</i>{{ __('menu.token_bucket') }}</a>
                                          </li>
                                          <li><a href="{{ route('mikrotik-suite.qos.queues.shared') }}"><i
                                                            class="material-icons-outlined">arrow_right</i>{{ __('menu.shared_bandwidth') }}</a>
                                          </li>
                                          <li><a href="{{ route('mikrotik-suite.qos.queues.burst') }}"><i
                                                            class="material-icons-outlined">arrow_right</i>{{ __('menu.burst_configuration') }}</a>
                                          </li>
                                          <li><a href="{{ route('mikrotik-suite.qos.queues.priority') }}"><i
                                                            class="material-icons-outlined">arrow_right</i>{{ __('menu.qos_priority') }}</a>
                                          </li>
                                    </ul>
                              </li>
                        </ul>
                  </li>

                  <!-- 6. Resources -->
                  <li>
                        <a href="javascript:;" class="has-arrow" data-tooltip="{{ __('menu.resources') }}">
                              <div class="parent-icon"><i class="material-icons-outlined">folder</i></div>
                              <div class="menu-title">{{ __('menu.resources') }}</div>
                        </a>
                        <ul class="mm-collapse {{ request()->routeIs('mikrotik-suite.resources.*') ? 'mm-show' : '' }}">
                              <li>
                                    <a class="has-arrow" href="javascript:;"
                                          data-tooltip="{{ __('menu.billing_integration') }}"><i
                                                class="material-icons-outlined">arrow_right</i>{{ __('menu.billing_integration') }}</a>
                                    <ul>
                                          <li><a href="{{ route('mikrotik-suite.resources.billing.mikhmon') }}"><i
                                                            class="material-icons-outlined">arrow_right</i>{{ __('menu.mikhmon') }}</a>
                                          </li>
                                          <li><a href="{{ route('mikrotik-suite.resources.billing.freeradius') }}"><i
                                                            class="material-icons-outlined">arrow_right</i>{{ __('menu.freeradius') }}</a>
                                          </li>
                                          <li><a href="{{ route('mikrotik-suite.resources.billing.daloradius') }}"><i
                                                            class="material-icons-outlined">arrow_right</i>{{ __('menu.daloradius') }}</a>
                                          </li>
                                          <li><a href="{{ route('mikrotik-suite.resources.billing.dma-radius') }}"><i
                                                            class="material-icons-outlined">arrow_right</i>{{ __('menu.dma_radius') }}</a>
                                          </li>
                                    </ul>
                              </li>
                              <li><a href="{{ route('mikrotik-suite.resources.downloads') }}"><i
                                                class="material-icons-outlined">arrow_right</i>{{ __('menu.downloads_center') }}</a>
                              </li>
                        </ul>
                  </li>

                  <!-- 7. Security -->
                  <li>
                        <a href="javascript:;" class="has-arrow" data-tooltip="{{ __('menu.security') }}">
                              <div class="parent-icon"><i class="material-icons-outlined">shield</i></div>
                              <div class="menu-title">{{ __('menu.security') }}</div>
                        </a>
                        <ul class="mm-collapse {{ request()->routeIs('mikrotik-suite.security.*') ? 'mm-show' : '' }}">
                              <li>
                                    <a class="has-arrow" href="javascript:;"
                                          data-tooltip="{{ __('menu.system_hardening') }}"><i
                                                class="material-icons-outlined">arrow_right</i>{{ __('menu.system_hardening') }}</a>
                                    <ul>
                                          <li><a href="{{ route('mikrotik-suite.security.hardening.hide-identity') }}"><i
                                                            class="material-icons-outlined">arrow_right</i>{{ __('menu.hide_router_identity') }}</a>
                                          </li>
                                          <li><a href="{{ route('mikrotik-suite.security.hardening.dhcp-rogue') }}"><i
                                                            class="material-icons-outlined">arrow_right</i>{{ __('menu.dhcp_rogue_detection') }}</a>
                                          </li>
                                          <li><a href="{{ route('mikrotik-suite.security.hardening.content-filter') }}"><i
                                                            class="material-icons-outlined">arrow_right</i>{{ __('menu.block_websites') }}</a>
                                          </li>
                                          <li><a href="{{ route('mikrotik-suite.security.hardening.port-knocking') }}"><i
                                                            class="material-icons-outlined">arrow_right</i>{{ __('menu.port_knocking') }}</a>
                                          </li>
                                          <li><a href="{{ route('mikrotik-suite.security.hardening.mangle-obfuscator') }}"><i
                                                            class="material-icons-outlined">arrow_right</i>{{ __('menu.mangle_obfuscator') }}</a>
                                          </li>
                                          <li><a href="{{ route('mikrotik-suite.security.hardening.auto-backup') }}"><i
                                                            class="material-icons-outlined">arrow_right</i>{{ __('menu.auto_backup') }}</a>
                                          </li>
                                    </ul>
                              </li>
                              <li>
                                    <a class="has-arrow" href="javascript:;" data-tooltip="{{ __('menu.advanced') }}"><i
                                                class="material-icons-outlined">arrow_right</i>{{ __('menu.advanced') }}</a>
                                    <ul>
                                          <li><a href="{{ route('mikrotik-suite.security.advanced.generator') }}"><i
                                                            class="material-icons-outlined">arrow_right</i>{{ __('menu.firewall_generator') }}</a>
                                          </li>
                                          <li><a href="{{ route('mikrotik-suite.security.advanced.input-chain') }}"><i
                                                            class="material-icons-outlined">arrow_right</i>{{ __('menu.input_chain') }}</a>
                                          </li>
                                          <li><a href="{{ route('mikrotik-suite.security.advanced.forward-chain') }}"><i
                                                            class="material-icons-outlined">arrow_right</i>{{ __('menu.forward_chain') }}</a>
                                          </li>
                                          <li><a href="{{ route('mikrotik-suite.security.advanced.ddos') }}"><i
                                                            class="material-icons-outlined">arrow_right</i>{{ __('menu.ddos_protection') }}</a>
                                          </li>
                                          <li><a href="{{ route('mikrotik-suite.security.advanced.bogon') }}"><i
                                                            class="material-icons-outlined">arrow_right</i>{{ __('menu.bogon_ips') }}</a>
                                          </li>
                                          <li><a href="{{ route('mikrotik-suite.security.advanced.l7') }}"><i
                                                            class="material-icons-outlined">arrow_right</i>{{ __('menu.layer_7_protocol') }}</a>
                                          </li>
                                    </ul>
                              </li>
                              <li>
                                    <a class="has-arrow" href="javascript:;"
                                          data-tooltip="{{ __('menu.firewall_nat') }}"><i
                                                class="material-icons-outlined">arrow_right</i>{{ __('menu.firewall_nat') }}</a>
                                    <ul>
                                          <li><a href="{{ route('mikrotik-suite.security.firewall.fasttrack') }}"><i
                                                            class="material-icons-outlined">arrow_right</i>{{ __('menu.fasttrack_rules') }}</a>
                                          </li>
                                          <li><a href="{{ route('mikrotik-suite.security.firewall.port-forwarding') }}"><i
                                                            class="material-icons-outlined">arrow_right</i>{{ __('menu.port_forwarding') }}</a>
                                          </li>
                                          <li><a href="{{ route('mikrotik-suite.security.firewall.static-route') }}"><i
                                                            class="material-icons-outlined">arrow_right</i>{{ __('menu.port_static_routing') }}</a>
                                          </li>
                                          <li><a href="{{ route('mikrotik-suite.security.firewall.mangle') }}"><i
                                                            class="material-icons-outlined">arrow_right</i>{{ __('menu.mangle_rules') }}</a>
                                          </li>
                                          <li><a href="{{ route('mikrotik-suite.security.firewall.filter') }}"><i
                                                            class="material-icons-outlined">arrow_right</i>{{ __('menu.filter_rules') }}</a>
                                          </li>
                                    </ul>
                              </li>
                        </ul>
                  </li>

                  <!-- 8. System -->
                  <li>
                        <a href="javascript:;" class="has-arrow" data-tooltip="{{ __('menu.system') }}">
                              <div class="parent-icon"><i class="material-icons-outlined">settings</i></div>
                              <div class="menu-title">{{ __('menu.system') }}</div>
                        </a>
                        <ul class="mm-collapse {{ request()->routeIs('mikrotik-suite.system.*') ? 'mm-show' : '' }}">
                              <li>
                                    <a class="has-arrow" href="javascript:;"
                                          data-tooltip="{{ __('menu.maintenance') }}"><i
                                                class="material-icons-outlined">arrow_right</i>{{ __('menu.maintenance') }}</a>
                                    <ul>
                                          <li><a href="{{ route('mikrotik-suite.system.maintenance.auto-upgrade') }}"><i
                                                            class="material-icons-outlined">arrow_right</i>{{ __('menu.auto_upgrade') }}</a>
                                          </li>
                                          <li><a href="{{ route('mikrotik-suite.system.maintenance.backup-automation') }}"><i
                                                            class="material-icons-outlined">arrow_right</i>{{ __('menu.backup_automation') }}</a>
                                          </li>
                                          <li><a href="{{ route('mikrotik-suite.system.maintenance.user-management') }}"><i
                                                            class="material-icons-outlined">arrow_right</i>{{ __('menu.user_management') }}</a>
                                          </li>
                                    </ul>
                              </li>
                              <li><a href="{{ route('mikrotik-suite.system.banner') }}"><i
                                                class="material-icons-outlined">arrow_right</i>{{ __('menu.banner_generator') }}</a>
                              </li>
                              <li><a href="{{ route('mikrotik-suite.system.first-time-wizard') }}"><i
                                                class="material-icons-outlined">arrow_right</i>{{ __('menu.first_time_wizard') }}</a>
                              </li>
                              <li><a href="{{ route('mikrotik-suite.system.identity') }}"><i
                                                class="material-icons-outlined">arrow_right</i>{{ __('menu.identity_generator') }}</a>
                              </li>
                              <li>
                                    <a class="has-arrow" href="javascript:;"
                                          data-tooltip="{{ __('menu.automation') }}"><i
                                                class="material-icons-outlined">arrow_right</i>{{ __('menu.automation') }}</a>
                                    <ul>
                                          <li><a href="{{ route('mikrotik-suite.system.automation.scheduler') }}"><i
                                                            class="material-icons-outlined">arrow_right</i>{{ __('menu.scheduler_builder') }}</a>
                                          </li>
                                          <li><a href="{{ route('mikrotik-suite.system.automation.auto-reboot') }}"><i
                                                            class="material-icons-outlined">arrow_right</i>{{ __('menu.auto_reboot') }}</a>
                                          </li>
                                          <li><a href="{{ route('mikrotik-suite.system.automation.bandwidth') }}"><i
                                                            class="material-icons-outlined">arrow_right</i>{{ __('menu.bandwidth_scheduler') }}</a>
                                          </li>
                                    </ul>
                              </li>
                        </ul>
                  </li>

                  <!-- 9. Utilities -->
                  <li>
                        <a href="javascript:;" class="has-arrow" data-tooltip="{{ __('menu.utilities') }}">
                              <div class="parent-icon"><i class="material-icons-outlined">handyman</i></div>
                              <div class="menu-title">{{ __('menu.utilities') }}</div>
                        </a>
                        <ul class="mm-collapse {{ request()->routeIs('mikrotik-suite.utilities.*') ? 'mm-show' : '' }}">
                              <li>
                                    <a class="has-arrow" href="javascript:;"
                                          data-tooltip="{{ __('menu.batch_operations') }}"><i
                                                class="material-icons-outlined">arrow_right</i>{{ __('menu.batch_operations') }}</a>
                                    <ul>
                                          <li><a href="{{ route('mikrotik-suite.utilities.batch.dns-ping') }}"><i
                                                            class="material-icons-outlined">arrow_right</i>{{ __('menu.batch_dns_ping') }}</a>
                                          </li>
                                          <li><a href="{{ route('mikrotik-suite.utilities.batch.port-scanner') }}"><i
                                                            class="material-icons-outlined">arrow_right</i>{{ __('menu.batch_port_scanner') }}</a>
                                          </li>
                                          <li><a href="{{ route('mikrotik-suite.utilities.batch.backup') }}"><i
                                                            class="material-icons-outlined">arrow_right</i>{{ __('menu.batch_backup') }}</a>
                                          </li>
                                          <li><a href="{{ route('mikrotik-suite.utilities.batch.session-restore') }}"><i
                                                            class="material-icons-outlined">arrow_right</i>{{ __('menu.batch_session_restore') }}</a>
                                          </li>
                                    </ul>
                              </li>
                              <li>
                                    <a class="has-arrow" href="javascript:;"
                                          data-tooltip="{{ __('menu.calculators') }}"><i
                                                class="material-icons-outlined">arrow_right</i>{{ __('menu.calculators') }}</a>
                                    <ul>
                                          <li><a href="{{ route('mikrotik-suite.utilities.calculators.ip') }}"><i
                                                            class="material-icons-outlined">arrow_right</i>{{ __('menu.ip_calculator') }}</a>
                                          </li>
                                          <li><a href="{{ route('mikrotik-suite.utilities.calculators.bandwidth') }}"><i
                                                            class="material-icons-outlined">arrow_right</i>{{ __('menu.bandwidth_calculator') }}</a>
                                          </li>
                                          <li><a href="{{ route('mikrotik-suite.utilities.calculators.burst') }}"><i
                                                            class="material-icons-outlined">arrow_right</i>{{ __('menu.burst_calculator') }}</a>
                                          </li>
                                          <li><a href="{{ route('mikrotik-suite.utilities.calculators.pcq') }}"><i
                                                            class="material-icons-outlined">arrow_right</i>{{ __('menu.pcq_calculator') }}</a>
                                          </li>
                                          <li><a href="{{ route('mikrotik-suite.utilities.calculators.lb-pcc') }}"><i
                                                            class="material-icons-outlined">arrow_right</i>{{ __('menu.lb_pcc') }}</a>
                                          </li>
                                          <li><a href="{{ route('mikrotik-suite.utilities.calculators.lb-nth') }}"><i
                                                            class="material-icons-outlined">arrow_right</i>{{ __('menu.lb_nth') }}</a>
                                          </li>
                                          <li><a href="{{ route('mikrotik-suite.utilities.calculators.lb-ecmp') }}"><i
                                                            class="material-icons-outlined">arrow_right</i>{{ __('menu.lb_ecmp') }}</a>
                                          </li>
                                          <li><a href="{{ route('mikrotik-suite.utilities.calculators.ram-proxy') }}"><i
                                                            class="material-icons-outlined">arrow_right</i>{{ __('menu.ram_proxy') }}</a>
                                          </li>
                                          <li><a href="{{ route('mikrotik-suite.utilities.calculators.antenna-height') }}"><i
                                                            class="material-icons-outlined">arrow_right</i>{{ __('menu.antenna_height') }}</a>
                                          </li>
                                    </ul>
                              </li>
                              <li>
                                    <a class="has-arrow" href="javascript:;"
                                          data-tooltip="{{ __('menu.container') }}"><i
                                                class="material-icons-outlined">arrow_right</i>{{ __('menu.container') }}</a>
                                    <ul>
                                          <li><a href="{{ route('mikrotik-suite.utilities.container.pihole') }}"><i
                                                            class="material-icons-outlined">arrow_right</i>{{ __('menu.pihole_installer') }}</a>
                                          </li>
                                          <li><a href="{{ route('mikrotik-suite.utilities.container.adblock') }}"><i
                                                            class="material-icons-outlined">arrow_right</i>{{ __('menu.adblock_installer') }}</a>
                                          </li>
                                          <li><a href="{{ route('mikrotik-suite.utilities.container.adguard') }}"><i
                                                            class="material-icons-outlined">arrow_right</i>{{ __('menu.adguard_home') }}</a>
                                          </li>
                                          <li><a href="{{ route('mikrotik-suite.utilities.container.speedtest') }}"><i
                                                            class="material-icons-outlined">arrow_right</i>{{ __('menu.speedtest_server') }}</a>
                                          </li>
                                    </ul>
                              </li>
                              <li>
                                    <a class="has-arrow" href="javascript:;"
                                          data-tooltip="{{ __('menu.simulators') }}"><i
                                                class="material-icons-outlined">arrow_right</i>{{ __('menu.simulators') }}</a>
                                    <ul>
                                          <li><a href="{{ route('mikrotik-suite.utilities.simulators.queue') }}"><i
                                                            class="material-icons-outlined">arrow_right</i>{{ __('menu.queue_simulator') }}</a>
                                          </li>
                                    </ul>
                              </li>
                        </ul>
                  </li>

                  <!-- 10. Wireless -->
                  <li>
                        <a href="javascript:;" class="has-arrow" data-tooltip="{{ __('menu.wireless') }}">
                              <div class="parent-icon"><i class="material-icons-outlined">wifi</i></div>
                              <div class="menu-title">{{ __('menu.wireless') }}</div>
                        </a>
                        <ul class="mm-collapse {{ request()->routeIs('mikrotik-suite.wireless.*') ? 'mm-show' : '' }}">
                              <li><a href="{{ route('mikrotik-suite.wireless.antenna') }}"><i
                                                class="material-icons-outlined">arrow_right</i>{{ __('menu.antenna_calculator') }}</a>
                              </li>
                              <li><a href="{{ route('mikrotik-suite.wireless.freq-unlock') }}"><i
                                                class="material-icons-outlined">arrow_right</i>{{ __('menu.frequency_unlock') }}</a>
                              </li>
                              <li><a href="{{ route('mikrotik-suite.wireless.link-budget') }}"><i
                                                class="material-icons-outlined">arrow_right</i>{{ __('menu.link_budget_calculator') }}</a>
                              </li>
                              <li><a href="{{ route('mikrotik-suite.wireless.link-planner') }}"><i
                                                class="material-icons-outlined">arrow_right</i>{{ __('menu.link_planner') }}</a>
                              </li>
                              <li><a href="{{ route('mikrotik-suite.wireless.lockpack') }}"><i
                                                class="material-icons-outlined">arrow_right</i>{{ __('menu.lockpack_creator') }}</a>
                              </li>
                              <li><a href="{{ route('mikrotik-suite.wireless.minipci') }}"><i
                                                class="material-icons-outlined">arrow_right</i>{{ __('menu.minipci_compatibility') }}</a>
                              </li>
                        </ul>
                  </li>

                  <!-- Billing Section Flattened -->
                  <li class="menu-label">{{ __('menu.billing') }}</li>

                  <li>
                        <a href="javascript:;">
                              <div class="parent-icon"><i class="material-icons-outlined">badge</i></div>
                              <div class="menu-title">{{ __('menu.member') }}</div>
                        </a>
                  </li>
                  <li>
                        <a href="javascript:;">
                              <div class="parent-icon"><i class="material-icons-outlined">receipt_long</i></div>
                              <div class="menu-title">{{ __('menu.invoice') }}</div>
                        </a>
                  </li>
                  <li>
                        <a href="javascript:;">
                              <div class="parent-icon"><i class="material-icons-outlined">point_of_sale</i></div>
                              <div class="menu-title">{{ __('menu.transaction') }}</div>
                        </a>
                  </li>
                  <li>
                        <a href="javascript:;">
                              <div class="parent-icon"><i class="material-icons-outlined">group_add</i></div>
                              <div class="menu-title">{{ __('menu.referral') }}</div>
                        </a>
                  </li>
                  <li>
                        <a href="javascript:;" class="has-arrow" data-tooltip="{{ __('menu.report') }}">
                              <div class="parent-icon"><i class="material-icons-outlined">analytics</i></div>
                              <div class="menu-title">{{ __('menu.report') }}</div>
                        </a>
                        <ul class="mm-collapse">
                              <li><a href="javascript:;"><i
                                                class="material-icons-outlined">arrow_right</i>{{ __('menu.report_daily') }}</a>
                              </li>
                              <li><a href="javascript:;"><i
                                                class="material-icons-outlined">arrow_right</i>{{ __('menu.report_monthly') }}</a>
                              </li>
                              <li><a href="javascript:;"><i
                                                class="material-icons-outlined">arrow_right</i>{{ __('menu.report_export') }}</a>
                              </li>
                        </ul>
                  </li>
                  <li>
                        <a href="javascript:;" class="has-arrow" data-tooltip="{{ __('menu.payment_channel') }}">
                              <div class="parent-icon"><i class="material-icons-outlined">account_balance</i></div>
                              <div class="menu-title">{{ __('menu.payment_channel') }}</div>
                        </a>
                        <ul class="mm-collapse">
                              <li><a href="javascript:;"><i
                                                class="material-icons-outlined">arrow_right</i>{{ __('menu.xendit_xenplatform') }}</a>
                              </li>
                              <li><a href="javascript:;"><i
                                                class="material-icons-outlined">arrow_right</i>{{ __('menu.xendit_mandiri') }}</a>
                              </li>
                              <li><a href="javascript:;"><i
                                                class="material-icons-outlined">arrow_right</i>{{ __('menu.terms_conditions') }}</a>
                              </li>
                        </ul>
                  </li>
                  <li>
                        <a href="javascript:;">
                              <div class="parent-icon"><i class="material-icons-outlined">settings</i></div>
                              <div class="menu-title">{{ __('menu.settings') }}</div>
                        </a>
                  </li>

                  <!-- FTTH Section Flattened -->
                  <li class="menu-label">{{ __('menu.ftth') }}</li>

                  <li>
                        <a href="javascript:;" class="has-arrow" data-tooltip="{{ __('menu.tr069') }}">
                              <div class="parent-icon"><i class="material-icons-outlined">router</i></div>
                              <div class="menu-title">{{ __('menu.tr069') }}</div>
                        </a>
                        <ul class="mm-collapse">
                              <li><a href="javascript:;"><i
                                                class="material-icons-outlined">arrow_right</i>{{ __('menu.genieacs_api') }}</a>
                              </li>
                              <li><a href="javascript:;"><i
                                                class="material-icons-outlined">arrow_right</i>{{ __('menu.genieacs_cloud') }}</a>
                              </li>
                        </ul>
                  </li>

                  <!-- Utility Section Flattened -->
                  <li class="menu-label">{{ __('menu.utility') }}</li>

                  <li><a href="javascript:;">
                              <div class="parent-icon"><i class="material-icons-outlined">admin_panel_settings</i></div>
                              <div class="menu-title">{{ __('menu.admin') }}</div>
                        </a></li>
                  <li><a href="javascript:;">
                              <div class="parent-icon"><i class="material-icons-outlined">chat</i></div>
                              <div class="menu-title">{{ __('menu.whatsapp') }}</div>
                        </a></li>
                  <li><a href="javascript:;">
                              <div class="parent-icon"><i class="material-icons-outlined">settings</i></div>
                              <div class="menu-title">{{ __('menu.service') }}</div>
                        </a></li>
                  <li><a href="javascript:;">
                              <div class="parent-icon"><i class="material-icons-outlined">cloud_download</i></div>
                              <div class="menu-title">{{ __('menu.backup') }}</div>
                        </a></li>
                  <li><a href="javascript:;">
                              <div class="parent-icon"><i class="material-icons-outlined">map</i></div>
                              <div class="menu-title">{{ __('menu.map') }}</div>
                        </a></li>
                  <li><a href="javascript:;">
                              <div class="parent-icon"><i class="material-icons-outlined">dvr</i></div>
                              <div class="menu-title">{{ __('menu.syslog') }}</div>
                        </a></li>
                  <li><a href="javascript:;">
                              <div class="parent-icon"><i class="material-icons-outlined">article</i></div>
                              <div class="menu-title">{{ __('menu.documentation') }}</div>
                        </a></li>
                  <li><a href="javascript:;">
                              <div class="parent-icon"><i class="material-icons-outlined">history</i></div>
                              <div class="menu-title">{{ __('menu.changelog') }}</div>
                        </a></li>
                  <li><a href="javascript:;">
                              <div class="parent-icon"><i class="material-icons-outlined">policy</i></div>
                              <div class="menu-title">{{ __('menu.privacy_policy') }}</div>
                        </a></li>

                  @if(Auth::check() && Auth::user()->isAdmin())
                        <li class="menu-label">{{ __('menu.admin_access') }}</li>

                        <!-- Admin Support Section -->
                        <li class="{{ request()->routeIs('admin.support.*') ? 'mm-active' : '' }}">
                              <a class="has-arrow {{ request()->routeIs('admin.support.*') ? 'mm-active' : '' }}"
                                    href="javascript:;"
                                    aria-expanded="{{ request()->routeIs('admin.support.*') ? 'true' : 'false' }}">
                                    <div class="parent-icon"><i class="material-icons-outlined">support_agent</i></div>
                                    <div class="menu-title">{{ __('menu.support_center') }}</div>
                              </a>
                              <ul class="mm-collapse {{ request()->routeIs('admin.support.*') ? 'mm-show' : '' }}">
                                    <li><a href="{{ route('admin.support.tickets.index') }}"><i
                                                      class="material-icons-outlined">confirmation_number</i>{{ __('menu.tickets') }}</a>
                                    </li>
                                    <li><a href="{{ route('admin.support.faqs.index') }}"><i
                                                      class="material-icons-outlined">help_center</i>{{ __('menu.faqs') }}</a>
                                    </li>
                                    <li><a href="{{ route('admin.support.documentation.index') }}"><i
                                                      class="material-icons-outlined">library_books</i>{{ __('menu.documentation') }}</a>
                                    </li>
                              </ul>
                        </li>

                        <li>
                              <a href="{{ route('admin.users.index') }}" data-tooltip="{{ __('menu.user_management') }}">
                                    <div class="parent-icon"><i class="material-icons-outlined">manage_accounts</i></div>
                                    <div class="menu-title">{{ __('menu.user_management') }}</div>
                              </a>
                        </li>
                        <li>
                              <a href="{{ route('admin.activity-logs.index') }}"
                                    data-tooltip="{{ __('menu.activity_logs') }}">
                                    <div class="parent-icon"><i class="material-icons-outlined">history</i></div>
                                    <div class="menu-title">{{ __('menu.activity_logs') }}</div>
                              </a>
                        </li>
                  @endif
                  <li>
                        <a href="{{ url('/timeline') }}" data-tooltip="{{ __('menu.timeline') }}">
                              <div class="parent-icon"><i class="material-icons-outlined">join_right</i></div>
                              <div class="menu-title">{{ __('menu.timeline') }}</div>
                        </a>
                  </li>

            </ul>
      </div>
      <div class="sidebar-bottom p-1 border-top border-secondary border-opacity-25 mt-auto">
            <a href="{{ route('support') }}"
                  class="d-flex align-items-center justify-content-center p-2 rounded bg-primary text-white text-decoration-none w-100">
                  <i class="material-icons-outlined me-2 text-white">support_agent</i>
                  <span class="fw-bold fs-6 support-text">{{ __('menu.technical_support') }}</span>
            </a>
      </div>
</aside>
<!--end sidebar-->