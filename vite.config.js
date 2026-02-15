import fs from 'fs-extra';
import laravel from 'laravel-vite-plugin';
import { defineConfig } from 'vite';

const folder = {
    src: "resources/", // source files
    src_assets: "resources/", // source assets files
    dist: "public/", // build files
    dist_assets: "public/build/" //build assets files
};

export default defineConfig({
    build: {
        manifest: 'manifest.json',
        rtl: true,
        outDir: 'public/build/',
        cssCodeSplit: true,
        rollupOptions: {
            output: {
                assetFileNames: (css) => {
                    if (css.name.split('.').pop() == 'css') {
                        return 'css/' + `[name]` + '.css';
                    } else {
                        return 'icons/' + css.name;
                    }
                },
                entryFileNames: (chunkInfo) => {
                    const facadeModuleId = chunkInfo.facadeModuleId ? chunkInfo.facadeModuleId.replace(/\\/g, '/') : null;
                    if (facadeModuleId && facadeModuleId.includes('/pages/')) {
                        // Extract path from 'pages/' onwards
                        const match = facadeModuleId.match(/\/js\/(pages\/.*)\.js$/);
                        if (match) {
                            return 'js/' + match[1] + '.js';
                        }
                    }
                    return 'js/[name].js';
                },
            },
        },
    },
    plugins: [
        laravel({
            input: [
                'resources/sass/main.scss',
                'resources/js/main.js',
                'resources/js/sidebar.js',
                'resources/js/pages/auth/auth.js',
                'resources/js/pages/admin/activity-logs.js',
                'resources/js/pages/admin/users.js',
                'resources/js/pages/admin/documentation.js',
                'resources/css/pages/auth/auth.css',
                'resources/css/dashboard-theme.css',
                'resources/js/layouts/topbar.js',
                'resources/css/dashboard.css',
                'resources/js/dashboard.js',
                'resources/js/data-widgets.js',
                'resources/sass/pages/error.scss',
                'resources/sass/pages/admin-support-center.scss',
                // Wireless
                'resources/sass/pages/mikrotik-suite/wireless/link-planner.scss',
                'resources/js/pages/mikrotik-suite/wireless/wireless-link-planner.js',
                'resources/js/pages/mikrotik-suite/wireless/antenna-calculator.js',
                'resources/js/pages/mikrotik-suite/wireless/frequency-unlock.js',
                'resources/js/pages/mikrotik-suite/wireless/link-budget-calculator.js',
                'resources/js/pages/mikrotik-suite/wireless/lockpack-creator.js',
                // Security
                'resources/js/pages/mikrotik-suite/security/advanced-protection.js',
                'resources/js/pages/mikrotik-suite/security/port-knocking.js',
                // Monitoring
                'resources/js/pages/mikrotik-suite/monitoring/troubleshooting/cpu-profiling.js',
                'resources/js/pages/mikrotik-suite/monitoring/troubleshooting/graphing.js',
                'resources/js/pages/mikrotik-suite/monitoring/troubleshooting/log-regex-generator.js',
                'resources/js/pages/mikrotik-suite/monitoring/troubleshooting/packet-sniffer.js',
                // Connectivity
                'resources/js/pages/mikrotik-suite/connectivity/vpn-configuration/openvpn.js',
                'resources/js/pages/mikrotik-suite/connectivity/vpn-configuration/vpn-tunnel.js',
                'resources/js/pages/mikrotik-suite/connectivity/vpn-configuration/wireguard.js',
                'resources/js/pages/mikrotik-suite/connectivity/vpn-configuration/sstp-server.js',
                'resources/js/pages/mikrotik-suite/connectivity/vpn-configuration/pptp-server.js',
                'resources/js/pages/mikrotik-suite/connectivity/vpn-configuration/l2tp-server.js',
                'resources/js/pages/mikrotik-suite/connectivity/pppoe/telegram-reporter.js',
                'resources/js/pages/mikrotik-suite/connectivity/pppoe/secrets-generator.js',
                'resources/js/pages/mikrotik-suite/connectivity/pppoe/pppoe-server.js',
                'resources/js/pages/mikrotik-suite/connectivity/iot/mqtt.js',
                'resources/js/pages/mikrotik-suite/connectivity/iot/lorawan.js',
                'resources/js/pages/mikrotik-suite/connectivity/hotspot/user-generator.js',
                'resources/js/pages/mikrotik-suite/connectivity/hotspot/qr-code-wifi.js',
                'resources/js/pages/mikrotik-suite/connectivity/hotspot/expired-notification.js',
                'resources/js/pages/mikrotik-suite/connectivity/hotspot/hotspot-wizard.js',
                'resources/js/pages/mikrotik-suite/connectivity/hotspot/block-sharing.js',
                'resources/js/pages/mikrotik-suite/connectivity/hotspot/bandwidth-limiter.js',
                // Monitoring - Troubleshooting
                'resources/js/pages/mikrotik-suite/monitoring/troubleshooting/packet-sniffer.js',
                'resources/js/pages/mikrotik-suite/monitoring/troubleshooting/log-regex-generator.js',
                'resources/js/pages/mikrotik-suite/monitoring/troubleshooting/graphing.js',
                // Monitoring - Network Monitoring
                'resources/js/pages/mikrotik-suite/monitoring/network-monitoring/traffic-sniffer.js',
                'resources/js/pages/mikrotik-suite/monitoring/network-monitoring/traffic-monitor.js',
                'resources/js/pages/mikrotik-suite/monitoring/network-monitoring/netwatch-alert.js',
                'resources/js/pages/mikrotik-suite/monitoring/network-monitoring/attix5-monitor.js',
                // Monitoring - Network Discovery
                'resources/js/pages/mikrotik-suite/monitoring/network-discovery/neighbour-viewer.js',
                'resources/js/pages/mikrotik-suite/monitoring/network-discovery/mac-address-tools.js',
                'resources/js/pages/mikrotik-suite/monitoring/network-discovery/interface-bonding.js',
                // Monitoring - DNS Time
                'resources/js/pages/mikrotik-suite/monitoring/dns-time/ntp-client.js',
                'resources/js/pages/mikrotik-suite/monitoring/dns-time/dns-over-https.js',
                // Network - Load Balancing
                'resources/js/pages/mikrotik-suite/network/load-balancing/pcc.js',
                'resources/js/pages/mikrotik-suite/network/load-balancing/nth.js',
                'resources/js/pages/mikrotik-suite/network/load-balancing/ecmp.js',
                'resources/sass/pages/mikrotik-suite/network/load-balancing.scss',
                // Network - VLAN
                'resources/js/pages/mikrotik-suite/network/vlan/management-vlan.js',
                'resources/js/pages/mikrotik-suite/network/vlan/bridge-vlan-filtering.js',
                // Network - Switching
                'resources/js/pages/mikrotik-suite/network/switching/spanning-tree.js',
                'resources/js/pages/mikrotik-suite/network/switching/bonding.js',
                // Network - IPv6
                'resources/js/pages/mikrotik-suite/network/ipv6/neighbor-discovery.js',
                'resources/js/pages/mikrotik-suite/network/ipv6/firewall-generator.js',
                // Network - Enterprise
                'resources/js/pages/mikrotik-suite/network/enterprise/traffic-engineering.js',
                'resources/js/pages/mikrotik-suite/network/enterprise/ldp-vpls.js',
                // Network - Config
                'resources/js/pages/mikrotik-suite/network/config/static-route-generator.js',
                'resources/js/pages/mikrotik-suite/network/config/policy-routing.js',
                'resources/js/pages/mikrotik-suite/network/config/ospf-generator.js',
                'resources/js/pages/mikrotik-suite/network/config/failover-gateway.js',
                'resources/js/pages/mikrotik-suite/network/config/bgp-generator.js',
                // Utilities - Calculators
                'resources/js/pages/mikrotik-suite/utilities/calculators/ip-calculator.js',
                'resources/js/pages/mikrotik-suite/utilities/calculators/bandwidth-calculator.js',
                'resources/js/pages/mikrotik-suite/utilities/calculators/burst-calculator.js',
                'resources/js/pages/mikrotik-suite/utilities/calculators/pcq-calculator.js',
                'resources/js/pages/mikrotik-suite/utilities/calculators/lb-pcc-calculator.js',
                'resources/js/pages/mikrotik-suite/utilities/calculators/lb-nth-calculator.js',
                'resources/js/pages/mikrotik-suite/utilities/calculators/lb-ecmp-calculator.js',
                'resources/js/pages/mikrotik-suite/utilities/calculators/ram-proxy-calculator.js',
                // Utilities - Container
                'resources/js/pages/mikrotik-suite/utilities/container/pihole.js',
                'resources/js/pages/mikrotik-suite/utilities/container/adguard.js',
                'resources/js/pages/mikrotik-suite/utilities/container/speedtest.js',
                'resources/js/pages/mikrotik-suite/utilities/container/adblock.js',
                // System
                'resources/js/pages/mikrotik-suite/system/maintenance/auto-upgrade.js',
                'resources/js/pages/mikrotik-suite/system/maintenance/user-management.js',
                'resources/js/pages/mikrotik-suite/system/identity/banner.js',
                'resources/js/pages/mikrotik-suite/system/identity/identity.js',
                'resources/js/pages/mikrotik-suite/system/first-time-wizard.js',
                'resources/js/pages/mikrotik-suite/system/automation/scheduler-builder.js',
                'resources/js/pages/mikrotik-suite/system/automation/auto-reboot.js',
                'resources/js/pages/mikrotik-suite/system/automation/bandwidth-scheduler.js',
                // QoS
                'resources/js/pages/mikrotik-suite/qos/application-routing/gaming-routes.js',
                'resources/js/pages/mikrotik-suite/qos/application-routing/social-media-routes.js',
                'resources/js/pages/mikrotik-suite/qos/application-routing/streaming-routes.js',
                'resources/js/pages/mikrotik-suite/qos/queues/simple-queue.js',
                'resources/js/pages/mikrotik-suite/qos/queues/queue-tree.js',
                'resources/js/pages/mikrotik-suite/qos/queues/pcq-configuration.js',
                'resources/js/pages/mikrotik-suite/qos/shaping/burst-configuration.js',
                'resources/js/pages/mikrotik-suite/qos/shaping/qos-priority.js',
                'resources/js/pages/mikrotik-suite/qos/shaping/shared-bandwidth.js',

                // Resources
                'resources/js/pages/mikrotik-suite/resources/billing/mikhmon.js',
                'resources/js/pages/mikrotik-suite/resources/billing/freeradius.js',

                // Customization
                'resources/js/pages/mikrotik-suite/customization/branding-theme/branding-maker.js',
                'resources/js/pages/mikrotik-suite/customization/branding-theme/webfig-skin.js',
                'resources/js/pages/mikrotik-suite/customization/special-tools/wifiid-auto-login.js',
                'resources/js/pages/mikrotik-suite/customization/special-tools/wifiid-auto-login.js',
                'resources/js/pages/mikrotik-suite/customization/special-tools/rsc-beautifier.js',

                // NetFusion
                'resources/css/pages/netfusion/dashboard.css',
                'resources/js/pages/netfusion/dashboard.js',

                // Documentation
                'resources/js/pages/documentation.js',

                // FAQs
                'resources/js/pages/admin/faq.js',

                // Admin Support Tickets
                'resources/js/pages/admin/support-tickets.js',

                // User My Tickets
                'resources/js/pages/support/my-tickets.js',

                // Admin Documentation Editor
                'resources/js/pages/admin/documentation-editor.js',
            ],
            refresh: true,
        }),
        {
            name: 'copy-assets',
            async writeBundle() {
                try {
                    // Copy specific resource folders that are not part of Vite build pipeline
                    await Promise.all([
                        fs.copy(folder.src_assets + 'images', folder.dist_assets + 'images'),
                        fs.copy(folder.src_assets + 'fonts', folder.dist_assets + 'fonts'),
                        fs.copy(folder.src_assets + 'js', folder.dist_assets + 'js'),
                        fs.copy(folder.src_assets + 'css', folder.dist_assets + 'css'),
                        fs.copy(folder.src_assets + 'plugins', folder.dist_assets + 'plugins'),
                    ]);
                } catch (error) {
                    console.error('Error copying assets:', error);
                }
            },
        },
    ],
});
