<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MikrotikSuite\Connectivity\HotspotController;
use App\Http\Controllers\MikrotikSuite\Connectivity\IotController;
use App\Http\Controllers\MikrotikSuite\Connectivity\PppoeController;
use App\Http\Controllers\MikrotikSuite\Connectivity\VpnConfigurationController;
use App\Http\Controllers\MikrotikSuite\Customization\CustomizationController;
use App\Http\Controllers\MikrotikSuite\Monitoring\DnsTimeController;
use App\Http\Controllers\MikrotikSuite\Monitoring\NetworkDiscoveryController;
use App\Http\Controllers\MikrotikSuite\Monitoring\NetworkMonitoringController;
use App\Http\Controllers\MikrotikSuite\Monitoring\ProfilerController;
use App\Http\Controllers\MikrotikSuite\Monitoring\LogParserController;
use App\Http\Controllers\MikrotikSuite\Network\EnterpriseMplsController;
use App\Http\Controllers\MikrotikSuite\Network\Ipv6Controller;
use App\Http\Controllers\MikrotikSuite\Network\LoadBalancingController;
use App\Http\Controllers\MikrotikSuite\Network\RoutingGatewayController;
use App\Http\Controllers\MikrotikSuite\Network\VlanController;
use App\Http\Controllers\MikrotikSuite\Network\SwitchingController;
use App\Http\Controllers\MikrotikSuite\Qos\ApplicationRoutingController;
use App\Http\Controllers\MikrotikSuite\Qos\QueuesQosController;
use App\Http\Controllers\MikrotikSuite\Resources\BillingIntegrationController;
use App\Http\Controllers\MikrotikSuite\Resources\DownloadsController;
use App\Http\Controllers\MikrotikSuite\Security\SecurityController;
use App\Http\Controllers\MikrotikSuite\Security\AdvancedProtectionController;
use App\Http\Controllers\MikrotikSuite\Security\AdvancedSecurityController;
use App\Http\Controllers\MikrotikSuite\Security\FirewallNatController;
use App\Http\Controllers\MikrotikSuite\System\MaintenanceController;
use App\Http\Controllers\MikrotikSuite\System\IdentityController;
use App\Http\Controllers\MikrotikSuite\System\FirstTimeController;
use App\Http\Controllers\MikrotikSuite\System\AutomationController;
use App\Http\Controllers\MikrotikSuite\Utilities\BatchOperationsController;
use App\Http\Controllers\MikrotikSuite\Utilities\CalculatorsController;
use App\Http\Controllers\MikrotikSuite\Utilities\ContainerController;
use App\Http\Controllers\MikrotikSuite\Utilities\SimulatorsController;
use App\Http\Controllers\MikrotikSuite\Wireless\WirelessController;
use App\Http\Controllers\MikrotikSuite\Wireless\WirelessLinkPlannerController;

/*
|--------------------------------------------------------------------------
| Mikrotik Routes
|--------------------------------------------------------------------------
|
| Registered via bootstrap/app.php
| Prefix: /mikrotik
| Name: mikrotik.
| Middleware: auth, verified, two-factor
|
*/

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// 1. Connectivity
Route::prefix('connectivity')->name('connectivity.')->group(function () {
    // Hotspot Tools
    Route::prefix('hotspot')->name('hotspot.')->group(function () {
        Route::get('wizard', [HotspotController::class, 'hotspotWizard'])->name('wizard');
        Route::post('wizard/generate', [HotspotController::class, 'generateWizard'])->name('wizard.generate');
        Route::post('user-generator/generate', [HotspotController::class, 'generateUsers'])->name('user-generator.generate');
        Route::get('user-generator', [HotspotController::class, 'userGenerator'])->name('user-generator');
        Route::get('qr-code', [HotspotController::class, 'qrCodeWifi'])->name('qr-code');
        Route::post('qr-code/generate', [HotspotController::class, 'generateQrCode'])->name('qr-code.generate');
        Route::get('login-template', [HotspotController::class, 'loginTemplate'])->name('login-template');
        Route::get('bandwidth-limiter', [HotspotController::class, 'bandwidthLimiter'])->name('bandwidth-limiter');
        Route::post('bandwidth-limiter/generate', [HotspotController::class, 'generateBandwidthLimiter'])->name('bandwidth-limiter.generate');
        Route::get('block-sharing', [HotspotController::class, 'blockSharing'])->name('block-sharing');
        Route::post('block-sharing/generate', [HotspotController::class, 'generateBlockSharing'])->name('block-sharing.generate');
        Route::get('expired-notification', [HotspotController::class, 'expiredNotification'])->name('expired-notification');
        Route::post('expired-notification/generate', [HotspotController::class, 'generateExpiredNotification'])->name('expired-notification.generate');
    });

    // IoT
    Route::prefix('iot')->name('iot.')->group(function () {
        Route::get('mqtt', [IotController::class, 'mqttConfig'])->name('mqtt-config');
        Route::post('mqtt/generate', [IotController::class, 'generateMqtt'])->name('mqtt.generate');
        Route::get('mqtt-publisher', [IotController::class, 'mqttPublisher'])->name('mqtt-publisher');
        Route::get('lorawan-gateway', [IotController::class, 'lorawanGateway'])->name('lorawan-gateway');
        Route::post('lorawan-gateway/generate', [IotController::class, 'generateLorawan'])->name('lorawan-gateway.generate');
    });

    // PPPoE
    Route::prefix('pppoe')->name('pppoe.')->group(function () {
        Route::get('server', [PppoeController::class, 'pppoeServer'])->name('server');
        Route::post('server/generate', [PppoeController::class, 'generateServer'])->name('server.generate');
        Route::get('secrets-generator', [PppoeController::class, 'secretsGenerator'])->name('secrets-generator');
        Route::post('secrets-generator/generate', [PppoeController::class, 'generateSecrets'])->name('secrets-generator.generate');
        Route::get('telegram-reporter', [PppoeController::class, 'telegramReporter'])->name('telegram-reporter');
        Route::post('telegram-reporter/generate', [PppoeController::class, 'generateTelegram'])->name('telegram-reporter.generate');
    });

    // VPN
    Route::prefix('vpn')->name('vpn.')->group(function () {
        Route::get('tunnel', [VpnConfigurationController::class, 'vpnTunnel'])->name('tunnel');
        Route::post('tunnel/generate', [VpnConfigurationController::class, 'generateVpnTunnel'])->name('tunnel.generate');
        Route::get('l2tp-server', [VpnConfigurationController::class, 'l2tpServer'])->name('l2tp-server');
        Route::post('l2tp-server/generate', [VpnConfigurationController::class, 'generateL2tpServer'])->name('l2tp-server.generate');
        Route::get('pptp-server', [VpnConfigurationController::class, 'pptpServer'])->name('pptp-server');
        Route::post('pptp-server/generate', [VpnConfigurationController::class, 'generatePptpServer'])->name('pptp-server.generate');
        Route::get('sstp-server', [VpnConfigurationController::class, 'sstpServer'])->name('sstp-server');
        Route::post('sstp-server/generate', [VpnConfigurationController::class, 'generateSstpServer'])->name('sstp-server.generate');
        Route::get('openvpn', [VpnConfigurationController::class, 'openvpn'])->name('openvpn');
        Route::post('openvpn/generate', [VpnConfigurationController::class, 'generateOpenVpn'])->name('openvpn.generate');
        Route::get('wireguard', [VpnConfigurationController::class, 'wireguard'])->name('wireguard');
        Route::post('wireguard/generate', [VpnConfigurationController::class, 'generateWireGuard'])->name('wireguard.generate');
    });
});

// 2. Customization
Route::prefix('customization')->name('customization.')->group(function () {
    Route::prefix('branding-maker')->name('branding-maker.')->group(function () {
        Route::get('/', [CustomizationController::class, 'brandingMaker'])->name('index');
        Route::post('generate', [CustomizationController::class, 'generateBranding'])->name('generate');
    });

    Route::get('logo-assets', [CustomizationController::class, 'logoAssets'])->name('logo-assets');
    Route::get('webfig-skin', [CustomizationController::class, 'webfigSkin'])->name('webfig-skin');

    Route::prefix('hotspot-templates')->name('hotspot-templates.')->group(function () {
        Route::get('custom', [CustomizationController::class, 'customTemplate'])->name('custom');
        Route::get('login-v6', [CustomizationController::class, 'loginTemplateV6'])->name('login-v6');
        Route::get('login-v7', [CustomizationController::class, 'loginTemplateV7'])->name('login-v7');
    });

    Route::prefix('special-tools')->name('special-tools.')->group(function () {
        Route::get('rsc-beautifier', [CustomizationController::class, 'rscBeautifier'])->name('rsc-beautifier');
        Route::get('supout-reader', [CustomizationController::class, 'supoutReader'])->name('supout-reader');
        Route::get('wifiid-auto-login', [CustomizationController::class, 'wifiidAutoLogin'])->name('wifiid-auto-login');
        Route::post('wifiid-auto-login/generate', [CustomizationController::class, 'generateWifiId'])->name('wifiid-auto-login.generate');
    });
});

// 3. Monitoring
Route::prefix('monitoring')->name('monitoring.')->group(function () {
    Route::get('dns-over-https', [DnsTimeController::class, 'dnsOverHttps'])->name('dns-over-https');
    Route::post('dns-over-https/generate', [DnsTimeController::class, 'generateDoH'])->name('dns-over-https.generate');
    Route::get('ntp-client', [DnsTimeController::class, 'ntpClient'])->name('ntp-client');
    Route::post('ntp-client/generate', [DnsTimeController::class, 'generateNtp'])->name('ntp-client.generate');

    Route::get('neighbour-viewer', [NetworkDiscoveryController::class, 'neighbourViewer'])->name('neighbour-viewer');
    Route::post('neighbour-viewer/generate', [NetworkDiscoveryController::class, 'generateNeighbourDiscovery'])->name('neighbour-viewer.generate');
    Route::get('mac-address-tools', [NetworkDiscoveryController::class, 'macAddressTools'])->name('mac-address-tools');
    Route::post('mac-address-tools/scan', [NetworkDiscoveryController::class, 'generateMacScan'])->name('mac-address-tools.scan');
    Route::post('mac-address-tools/ping', [NetworkDiscoveryController::class, 'generateMacPing'])->name('mac-address-tools.ping');
    Route::get('interface-bonding', [NetworkDiscoveryController::class, 'interfaceBonding'])->name('interface-bonding');
    Route::post('interface-bonding/generate', [NetworkDiscoveryController::class, 'generateBonding'])->name('interface-bonding.generate');

    Route::get('traffic-monitor', [NetworkMonitoringController::class, 'trafficMonitor'])->name('traffic-monitor');
    Route::post('traffic-monitor/generate', [NetworkMonitoringController::class, 'generateTrafficMonitor'])->name('traffic-monitor.generate');
    Route::get('traffic-sniffer', [NetworkMonitoringController::class, 'trafficSniffer'])->name('traffic-sniffer');
    Route::post('traffic-sniffer/generate', [NetworkMonitoringController::class, 'generateSniffer'])->name('traffic-sniffer.generate');
    Route::get('attix5-monitor', [NetworkMonitoringController::class, 'attix5Monitor'])->name('attix5-monitor');
    Route::post('attix5-monitor/generate', [NetworkMonitoringController::class, 'generateAttix'])->name('attix5-monitor.generate');
    Route::get('netwatch-alert', [NetworkMonitoringController::class, 'netwatchAlert'])->name('netwatch-alert');
    Route::post('netwatch-alert/generate', [NetworkMonitoringController::class, 'generateNetwatchAlert'])->name('netwatch-alert.generate');

    // Troubleshooting
    Route::prefix('troubleshooting')->name('troubleshooting.')->group(function () {
        Route::get('graphing', [ProfilerController::class, 'graphing'])->name('graphing');
        Route::post('graphing/generate', [ProfilerController::class, 'generateGraphing'])->name('graphing.generate');
        Route::get('cpu-profiling', [ProfilerController::class, 'cpuProfiling'])->name('cpu-profiling');
        Route::post('cpu-profiling/generate', [ProfilerController::class, 'generateCpuProfile'])->name('cpu-profiling.generate');
        Route::get('packet-sniffer', [LogParserController::class, 'packetSniffer'])->name('packet-sniffer');
        Route::post('packet-sniffer/generate', [LogParserController::class, 'generateTorch'])->name('packet-sniffer.generate');
        Route::get('log-regex', [LogParserController::class, 'logRegexGenerator'])->name('log-regex');
        Route::post('log-regex/generate', [LogParserController::class, 'generateLogRegex'])->name('log-regex.generate');
    });
});

// 4. Network
Route::prefix('network')->name('network.')->group(function () {
    Route::prefix('enterprise')->name('enterprise.')->group(function () {
        Route::get('ldp-vpls', [EnterpriseMplsController::class, 'mplsLdpVpls'])->name('ldp-vpls');
        Route::post('ldp-vpls/generate', [EnterpriseMplsController::class, 'generateLdpVpls'])->name('ldp-vpls.generate');
        Route::get('traffic-engineering', [EnterpriseMplsController::class, 'trafficEngineering'])->name('traffic-engineering');
        Route::post('traffic-engineering/generate', [EnterpriseMplsController::class, 'generateTrafficEngineering'])->name('traffic-engineering.generate');
    });

    Route::prefix('ipv6')->name('ipv6.')->group(function () {
        Route::get('eui64-calculator', [Ipv6Controller::class, 'eui64Calculator'])->name('eui64-calculator');
        Route::get('subnetting-generator', [Ipv6Controller::class, 'subnettingGenerator'])->name('subnetting-generator');
        Route::get('firewall-generator', [Ipv6Controller::class, 'firewallV6Generator'])->name('firewall-generator');
        Route::post('firewall-generator/generate', [Ipv6Controller::class, 'generateFirewall'])->name('firewall-generator.generate');
        Route::get('neighbor-discovery', [Ipv6Controller::class, 'neighborDiscovery'])->name('neighbor-discovery');
        Route::post('neighbor-discovery/generate', [Ipv6Controller::class, 'generateNeighborDiscovery'])->name('neighbor-discovery.generate');
    });

    Route::prefix('load-balancing')->name('load-balancing.')->group(function () {
        Route::get('pcc', [LoadBalancingController::class, 'lbPcc'])->name('pcc');
        Route::post('pcc/generate', [LoadBalancingController::class, 'generatePcc'])->name('pcc.generate');
        Route::get('nth', [LoadBalancingController::class, 'lbNth'])->name('nth');
        Route::post('nth/generate', [LoadBalancingController::class, 'generateNth'])->name('nth.generate');
        Route::get('ecmp', [LoadBalancingController::class, 'lbEcmp'])->name('ecmp');
        Route::post('ecmp/generate', [LoadBalancingController::class, 'generateEcmp'])->name('ecmp.generate');
    });

    Route::prefix('routing')->name('routing.')->group(function () {
        Route::get('bgp-generator', [RoutingGatewayController::class, 'bgpGenerator'])->name('bgp-generator');
        Route::post('bgp-generator/generate', [RoutingGatewayController::class, 'generateBgp'])->name('bgp-generator.generate');
        Route::get('ospf-generator', [RoutingGatewayController::class, 'ospfGenerator'])->name('ospf-generator');
        Route::post('ospf-generator/generate', [RoutingGatewayController::class, 'generateOspf'])->name('ospf-generator.generate');
        Route::get('static-route', [RoutingGatewayController::class, 'staticRouteGenerator'])->name('static-route');
        Route::post('static-route/generate', [RoutingGatewayController::class, 'generateStaticRoute'])->name('static-route.generate');
        Route::get('failover-gateway', [RoutingGatewayController::class, 'failoverGateway'])->name('failover-gateway');
        Route::post('failover-gateway/generate', [RoutingGatewayController::class, 'generateFailover'])->name('failover-gateway.generate');
        Route::get('policy-routing', [RoutingGatewayController::class, 'policyRouting'])->name('policy-routing');
        Route::post('policy-routing/generate', [RoutingGatewayController::class, 'generatePolicyRouting'])->name('policy-routing.generate');
    });

    Route::prefix('switching')->name('switching.')->group(function () {
        Route::get('bridge-vlan', [VlanController::class, 'bridgeVlanFiltering'])->name('bridge-vlan');
        Route::post('bridge-vlan/generate', [VlanController::class, 'generateBridgeVlan'])->name('bridge-vlan.generate');
        Route::get('management-vlan', [VlanController::class, 'managementVlan'])->name('management-vlan');
        Route::post('management-vlan/generate', [VlanController::class, 'generateManagementVlan'])->name('management-vlan.generate');
        Route::get('bonding', [SwitchingController::class, 'bonding'])->name('bonding');
        Route::post('bonding/generate', [SwitchingController::class, 'generateBonding'])->name('bonding.generate');
        Route::get('spanning-tree', [SwitchingController::class, 'spanningTree'])->name('spanning-tree');
        Route::post('spanning-tree/generate', [SwitchingController::class, 'generateSpanningTree'])->name('spanning-tree.generate');
    });
});

// 5. QoS
Route::prefix('qos')->name('qos.')->group(function () {
    Route::prefix('application')->name('application.')->group(function () {
        Route::get('gaming', [ApplicationRoutingController::class, 'gamingRoutes'])->name('gaming');
        Route::post('gaming/generate', [ApplicationRoutingController::class, 'generateGaming'])->name('gaming.generate');
        Route::get('social-media', [ApplicationRoutingController::class, 'socialMediaRoutes'])->name('social-media');
        Route::post('social-media/generate', [ApplicationRoutingController::class, 'generateSocialMedia'])->name('social-media.generate');
        Route::get('streaming', [ApplicationRoutingController::class, 'streamingRoutes'])->name('streaming');
        Route::post('streaming/generate', [ApplicationRoutingController::class, 'generateStreaming'])->name('streaming.generate');
        Route::get('website', [ApplicationRoutingController::class, 'websiteRoutes'])->name('website');
        Route::post('website/generate', [ApplicationRoutingController::class, 'generateWebsite'])->name('website.generate');
    });

    Route::prefix('queues')->name('queues.')->group(function () {
        Route::get('simple', [QueuesQosController::class, 'simpleQueue'])->name('simple');
        Route::post('simple/generate', [QueuesQosController::class, 'generateSimpleQueue'])->name('simple.generate');
        Route::get('tree', [QueuesQosController::class, 'queueTree'])->name('tree');
        Route::post('tree/generate', [QueuesQosController::class, 'generateQueueTree'])->name('tree.generate');
        Route::get('optimizer', [QueuesQosController::class, 'queueOptimizer'])->name('optimizer');
        Route::get('pcq', [QueuesQosController::class, 'pcqConfiguration'])->name('pcq');
        Route::post('pcq/generate', [QueuesQosController::class, 'generatePcq'])->name('pcq.generate');
        Route::get('token-bucket', [QueuesQosController::class, 'tokenBucket'])->name('token-bucket');
        Route::get('shared', [QueuesQosController::class, 'sharedBandwidth'])->name('shared');
        Route::post('shared/generate', [QueuesQosController::class, 'generateSharedBandwidth'])->name('shared.generate');
        Route::get('burst', [QueuesQosController::class, 'burstConfiguration'])->name('burst');
        Route::post('burst/generate', [QueuesQosController::class, 'generateBurst'])->name('burst.generate');
        Route::get('priority', [QueuesQosController::class, 'qosPriority'])->name('priority');
        Route::post('priority/generate', [QueuesQosController::class, 'generatePriority'])->name('priority.generate');
    });
});

// 6. Resources
Route::prefix('resources')->name('resources.')->group(function () {
    Route::prefix('billing')->name('billing.')->group(function () {
        Route::get('mikhmon', [BillingIntegrationController::class, 'mikhmon'])->name('mikhmon');
        Route::post('mikhmon/generate', [BillingIntegrationController::class, 'generateMikhmon'])->name('mikhmon.generate');
        Route::get('freeradius', [BillingIntegrationController::class, 'freeradius'])->name('freeradius');
        Route::post('freeradius/generate', [BillingIntegrationController::class, 'generateFreeradius'])->name('freeradius.generate');
        Route::get('daloradius', [BillingIntegrationController::class, 'daloradius'])->name('daloradius');
        Route::get('dma-radius', [BillingIntegrationController::class, 'dmaRadius'])->name('dma-radius');
    });

    Route::get('downloads', [DownloadsController::class, 'index'])->name('downloads');
});

// 7. Security
Route::prefix('security')->name('security.')->group(function () {
    Route::prefix('hardening')->name('hardening.')->group(function () {
        Route::get('hide-identity', [SecurityController::class, 'hideRouterIdentity'])->name('hide-identity');
        Route::get('dhcp-rogue', [SecurityController::class, 'dhcpRogueDetection'])->name('dhcp-rogue');
        Route::get('content-filter', [SecurityController::class, 'contentFilter'])->name('content-filter');
        Route::get('port-knocking', [SecurityController::class, 'portKnocking'])->name('port-knocking');
        Route::get('mangle-obfuscator', [SecurityController::class, 'mangleObfuscator'])->name('mangle-obfuscator');
        Route::get('auto-backup', [SecurityController::class, 'autoBackup'])->name('auto-backup');
    });

    Route::prefix('advanced')->name('advanced.')->group(function () {
        Route::get('generator', [AdvancedProtectionController::class, 'index'])->name('generator');
        Route::post('generator', [AdvancedProtectionController::class, 'generate'])->name('generator.generate');
        Route::get('input-chain', [AdvancedSecurityController::class, 'inputChain'])->name('input-chain');
        Route::get('forward-chain', [AdvancedSecurityController::class, 'forwardChain'])->name('forward-chain');
        Route::get('ddos', [AdvancedSecurityController::class, 'ddosProtection'])->name('ddos');
        Route::get('bogon', [AdvancedSecurityController::class, 'bogonIps'])->name('bogon');
        Route::get('l7', [AdvancedSecurityController::class, 'layer7Protocol'])->name('l7');
    });

    Route::prefix('firewall')->name('firewall.')->group(function () {
        Route::get('fasttrack', [FirewallNatController::class, 'fasttrackRules'])->name('fasttrack');
        Route::get('port-forwarding', [FirewallNatController::class, 'portForwarding'])->name('port-forwarding');
        Route::get('static-route', [FirewallNatController::class, 'portStaticRouting'])->name('static-route');
        Route::get('mangle', [FirewallNatController::class, 'mangleRules'])->name('mangle');
        Route::get('filter', [FirewallNatController::class, 'filterRules'])->name('filter');
    });
});

// 8. System
Route::prefix('system')->name('system.')->group(function () {
    Route::prefix('maintenance')->name('maintenance.')->group(function () {
        Route::get('auto-upgrade', [MaintenanceController::class, 'autoUpgrade'])->name('auto-upgrade');
        Route::post('auto-upgrade/generate', [MaintenanceController::class, 'generateAutoUpgrade'])->name('auto-upgrade.generate');
        Route::get('backup-automation', [MaintenanceController::class, 'backupAutomation'])->name('backup-automation');
        Route::post('backup-automation/generate', [MaintenanceController::class, 'generateBackupAutomation'])->name('backup-automation.generate');
        Route::get('user-management', [MaintenanceController::class, 'userManagement'])->name('user-management');
        Route::post('user-management/generate', [MaintenanceController::class, 'generateUserManagement'])->name('user-management.generate');
    });

    Route::get('banner', [IdentityController::class, 'banner'])->name('banner');
    Route::post('banner/generate', [IdentityController::class, 'generateBanner'])->name('banner.generate');
    Route::get('identity', [IdentityController::class, 'identity'])->name('identity');
    Route::post('identity/generate', [IdentityController::class, 'generateIdentity'])->name('identity.generate');
    Route::get('first-time-wizard', [FirstTimeController::class, 'index'])->name('first-time-wizard');
    Route::post('first-time-wizard/generate', [FirstTimeController::class, 'generateFirstTime'])->name('first-time-wizard.generate');

    Route::prefix('automation')->name('automation.')->group(function () {
        Route::get('scheduler', [AutomationController::class, 'schedulerBuilder'])->name('scheduler');
        Route::post('scheduler/generate', [AutomationController::class, 'generateScheduler'])->name('scheduler.generate');
        Route::get('scheduler-builder', [AutomationController::class, 'schedulerBuilder'])->name('scheduler-builder'); // Keep alias
        Route::get('auto-reboot', [AutomationController::class, 'autoReboot'])->name('auto-reboot');
        Route::post('auto-reboot/generate', [AutomationController::class, 'generateAutoReboot'])->name('auto-reboot.generate');
        Route::get('bandwidth', [AutomationController::class, 'bandwidthScheduler'])->name('bandwidth');
        Route::post('bandwidth/generate', [AutomationController::class, 'generateBandwidth'])->name('bandwidth.generate');
    });
});

// 9. Utilities
Route::prefix('utilities')->name('utilities.')->group(function () {
    Route::prefix('batch')->name('batch.')->group(function () {
        Route::get('dns-ping', [BatchOperationsController::class, 'batchDnsPing'])->name('dns-ping');
        Route::get('port-scanner', [BatchOperationsController::class, 'batchPortScanner'])->name('port-scanner');
        Route::get('backup', [BatchOperationsController::class, 'batchBackup'])->name('backup');
        Route::get('session-restore', [BatchOperationsController::class, 'batchSessionRestore'])->name('session-restore');
    });

    Route::prefix('calculators')->name('calculators.')->group(function () {
        Route::get('ip', [CalculatorsController::class, 'ipCalculator'])->name('ip');
        Route::post('ip/calculate', [CalculatorsController::class, 'calculateIp'])->name('ip.calculate');

        Route::get('bandwidth', [CalculatorsController::class, 'bandwidthCalculator'])->name('bandwidth');
        Route::post('bandwidth/calculate', [CalculatorsController::class, 'calculateBandwidth'])->name('bandwidth.calculate');

        Route::get('burst', [CalculatorsController::class, 'burstCalculator'])->name('burst');
        Route::post('burst/calculate', [CalculatorsController::class, 'calculateBurst'])->name('burst.calculate');

        Route::get('pcq', [CalculatorsController::class, 'pcqCalculator'])->name('pcq');
        Route::post('pcq/calculate', [CalculatorsController::class, 'calculatePcq'])->name('pcq.calculate');

        Route::get('lb-pcc', [CalculatorsController::class, 'lbPccCalculator'])->name('lb-pcc');
        Route::post('lb-pcc/calculate', [CalculatorsController::class, 'calculateLbPcc'])->name('lb-pcc.calculate');

        Route::get('lb-nth', [CalculatorsController::class, 'lbNthCalculator'])->name('lb-nth');
        Route::post('lb-nth/calculate', [CalculatorsController::class, 'calculateLbNth'])->name('lb-nth.calculate');

        Route::get('lb-ecmp', [CalculatorsController::class, 'lbEcmpCalculator'])->name('lb-ecmp');
        Route::post('lb-ecmp/calculate', [CalculatorsController::class, 'calculateLbEcmp'])->name('lb-ecmp.calculate');

        Route::get('ram-proxy', [CalculatorsController::class, 'ramProxyCalculator'])->name('ram-proxy');
        Route::post('ram-proxy/calculate', [CalculatorsController::class, 'calculateRamProxy'])->name('ram-proxy.calculate');
        Route::get('antenna-height', [CalculatorsController::class, 'antennaHeight'])->name('antenna-height');
    });

    Route::prefix('container')->name('container.')->group(function () {
        Route::get('pihole', [ContainerController::class, 'piholeInstaller'])->name('pihole');
        Route::post('pihole/generate', [ContainerController::class, 'generatePihole'])->name('pihole.generate');

        Route::get('adblock', [ContainerController::class, 'adblockInstaller'])->name('adblock');
        Route::post('adblock/generate', [ContainerController::class, 'generateAdblock'])->name('adblock.generate');

        Route::get('adguard', [ContainerController::class, 'adguardHome'])->name('adguard');
        Route::post('adguard/generate', [ContainerController::class, 'generateAdguard'])->name('adguard.generate');

        Route::get('speedtest', [ContainerController::class, 'speedtestServer'])->name('speedtest');
        Route::post('speedtest/generate', [ContainerController::class, 'generateSpeedtest'])->name('speedtest.generate');
    });

    Route::prefix('simulators')->name('simulators.')->group(function () {
        Route::get('queue', [SimulatorsController::class, 'queueSimulator'])->name('queue');
    });
});

// 10. Wireless
Route::prefix('wireless')->name('wireless.')->group(function () {
    Route::get('antenna', [WirelessController::class, 'antennaCalculator'])->name('antenna');
    Route::post('antenna/calculate', [WirelessController::class, 'calculateAntenna'])->name('antenna.calculate');
    Route::get('freq-unlock', [WirelessController::class, 'frequencyUnlock'])->name('freq-unlock');
    Route::post('freq-unlock/generate', [WirelessController::class, 'generateFrequencyUnlock'])->name('freq-unlock.generate');
    Route::get('link-budget', [WirelessController::class, 'linkBudgetCalculator'])->name('link-budget');
    Route::post('link-budget/calculate', [WirelessController::class, 'calculateLinkBudget'])->name('link-budget.calculate');
    Route::get('link-planner', [WirelessLinkPlannerController::class, 'index'])->name('link-planner');
    Route::post('link-planner/calculate', [WirelessLinkPlannerController::class, 'calculate'])->name('link-planner.calculate');
    Route::get('lockpack', [WirelessController::class, 'lockpackCreator'])->name('lockpack');
    Route::post('lockpack/generate', [WirelessController::class, 'generateLockpack'])->name('lockpack.generate');
    Route::get('minipci', [WirelessController::class, 'minipciCompatibility'])->name('minipci');
});
