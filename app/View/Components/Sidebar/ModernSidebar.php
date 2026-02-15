<?php

namespace App\View\Components\Sidebar;

use Illuminate\View\Component;
use Illuminate\Support\Facades\Auth;

class ModernSidebar extends Component
{
    public $user;
    public $menuItems;
    public $recentItems;

    public function __construct()
    {
        $this->user = Auth::user();
        $this->menuItems = $this->getMenuItems();
        $this->recentItems = $this->getRecentItems();
    }

    /**
     * Get streamlined menu structure (30-40 items max)
     */
    protected function getMenuItems(): array
    {
        $menu = [
            [
                'section' => 'main',
                'items' => [
                    [
                        'title' => 'Dashboard',
                        'icon' => 'ph ph-house',
                        'route' => 'mikrotik-suite.dashboard',
                        'badge' => null,
                        'children' => null,
                    ],
                    [
                        'title' => 'Network',
                        'icon' => 'ph ph-wifi-high',
                        'route' => null,
                        'badge' => null,
                        'children' => [
                            [
                                'title' => 'Hotspot Users',
                                'icon' => 'ph ph-users',
                                'route' => 'mikrotik-suite.netfusion.users.index',
                            ],
                            [
                                'title' => 'Active Users',
                                'icon' => 'ph ph-user-circle',
                                'route' => 'mikrotik-suite.netfusion.active.index',
                            ],
                            [
                                'title' => 'PPP/PPPoE',
                                'icon' => 'ph ph-plugs',
                                'route' => 'mikrotik-suite.netfusion.ppp.secrets.index',
                            ],
                            [
                                'title' => 'DHCP Leases',
                                'icon' => 'ph ph-device-mobile',
                                'route' => 'mikrotik-suite.netfusion.dhcp.leases.index',
                            ],
                            [
                                'title' => 'System Tools',
                                'icon' => 'ph ph-wrench',
                                'route' => 'mikrotik-suite.netfusion.system.index',
                            ],
                            [
                                'title' => 'Reports',
                                'icon' => 'ph ph-chart-bar',
                                'route' => 'mikrotik-suite.netfusion.reports.index',
                            ],
                        ],
                    ],
                ],
            ],
            [
                'section' => 'tools',
                'items' => [
                    [
                        'title' => 'Connectivity',
                        'icon' => 'ph ph-plugs-connected',
                        'route' => null,
                        'badge' => 'New',
                        'children' => [
                            [
                                'title' => 'Hotspot Wizard',
                                'icon' => 'ph ph-magic-wand',
                                'route' => 'mikrotik-suite.connectivity.hotspot.wizard',
                            ],
                            [
                                'title' => 'VPN Configuration',
                                'icon' => 'ph ph-shield',
                                'route' => 'mikrotik-suite.connectivity.vpn.tunnel',
                            ],
                            [
                                'title' => 'PPPoE Server',
                                'icon' => 'ph ph-plugs',
                                'route' => 'mikrotik-suite.connectivity.pppoe.server',
                            ],
                        ],
                    ],
                    [
                        'title' => 'Monitoring',
                        'icon' => 'ph ph-monitor',
                        'route' => null,
                        'badge' => null,
                        'children' => [
                            [
                                'title' => 'Traffic Monitor',
                                'icon' => 'ph ph-activity',
                                'route' => 'mikrotik-suite.monitoring.traffic-monitor',
                            ],
                            [
                                'title' => 'Network Discovery',
                                'icon' => 'ph ph-radar',
                                'route' => 'mikrotik-suite.monitoring.neighbour-viewer',
                            ],
                            [
                                'title' => 'Troubleshooting',
                                'icon' => 'ph ph-bug',
                                'route' => 'mikrotik-suite.monitoring.troubleshooting.graphing',
                            ],
                        ],
                    ],
                    [
                        'title' => 'Security',
                        'icon' => 'ph ph-shield-check',
                        'route' => null,
                        'badge' => null,
                        'children' => [
                            [
                                'title' => 'Firewall Generator',
                                'icon' => 'ph ph-firewall',
                                'route' => 'mikrotik-suite.security.advanced.generator',
                            ],
                            [
                                'title' => 'Port Forwarding',
                                'icon' => 'ph ph-arrows-left-right',
                                'route' => 'mikrotik-suite.security.firewall.port-forwarding',
                            ],
                            [
                                'title' => 'System Hardening',
                                'icon' => 'ph ph-lock',
                                'route' => 'mikrotik-suite.security.hardening.hide-identity',
                            ],
                        ],
                    ],
                    [
                        'title' => 'QoS',
                        'icon' => 'ph ph-speedometer',
                        'route' => null,
                        'badge' => null,
                        'children' => [
                            [
                                'title' => 'Simple Queue',
                                'icon' => 'ph ph-list-numbers',
                                'route' => 'mikrotik-suite.qos.queues.simple',
                            ],
                            [
                                'title' => 'Application Routing',
                                'icon' => 'ph ph-git-branch',
                                'route' => 'mikrotik-suite.qos.application.gaming',
                            ],
                        ],
                    ],
                    [
                        'title' => 'Utilities',
                        'icon' => 'ph ph-toolbox',
                        'route' => null,
                        'badge' => null,
                        'children' => [
                            [
                                'title' => 'IP Calculator',
                                'icon' => 'ph ph-calculator',
                                'route' => 'mikrotik-suite.utilities.calculators.ip',
                            ],
                            [
                                'title' => 'Bandwidth Calculator',
                                'icon' => 'ph ph-chart-line-up',
                                'route' => 'mikrotik-suite.utilities.calculators.bandwidth',
                            ],
                            [
                                'title' => 'Wireless Tools',
                                'icon' => 'ph ph-broadcast',
                                'route' => 'mikrotik-suite.wireless.link-planner',
                            ],
                        ],
                    ],
                ],
            ],
            [
                'section' => 'settings',
                'items' => [
                    [
                        'title' => 'Settings',
                        'icon' => 'ph ph-gear',
                        'route' => 'mikrotik-suite.netfusion.settings.index',
                        'badge' => null,
                        'children' => null,
                    ],
                    [
                        'title' => 'Templates',
                        'icon' => 'ph ph-layout',
                        'route' => 'mikrotik-suite.customization.branding-maker.index',
                        'badge' => null,
                        'children' => null,
                    ],
                ],
            ],
        ];

        // Add admin section if user is admin
        if ($this->user && $this->user->isAdmin()) {
            $menu[] = [
                'section' => 'admin',
                'items' => [
                    [
                        'title' => 'User Management',
                        'icon' => 'ph ph-users-three',
                        'route' => 'admin.users.index',
                        'badge' => null,
                        'children' => null,
                    ],
                    [
                        'title' => 'Activity Logs',
                        'icon' => 'ph ph-scroll',
                        'route' => 'admin.activity-logs.index',
                        'badge' => null,
                        'children' => null,
                    ],
                    [
                        'title' => 'Support Center',
                        'icon' => 'ph ph-headset',
                        'route' => 'admin.support.tickets.index',
                        'badge' => $this->getPendingTicketsCount(),
                        'children' => null,
                    ],
                ],
            ];
        }

        return $menu;
    }

    /**
     * Get recent items for quick access
     */
    protected function getRecentItems(): array
    {
        // This can be fetched from session or database
        return session()->get('recent_items', [
            [
                'title' => 'Hotspot Users',
                'url' => route('mikrotik-suite.netfusion.users.index'),
                'icon' => 'ph ph-users',
            ],
            [
                'title' => 'Traffic Monitor',
                'url' => route('mikrotik-suite.monitoring.traffic-monitor'),
                'icon' => 'ph ph-activity',
            ],
        ]);
    }

    /**
     * Get pending tickets count for admin
     */
    protected function getPendingTicketsCount(): ?string
    {
        try {
            $count = \App\Models\Ticket::where('status', 'open')->count();
            return $count > 0 ? (string) $count : null;
        } catch (\Exception $e) {
            return null;
        }
    }

    public function render(): \Illuminate\View\View
    {
        return view('components.sidebar.modern-sidebar');
    }
}
