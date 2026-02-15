<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DocumentationCategory;
use App\Models\DocumentationPage;
use Illuminate\Support\Str;

class DocumentationStructureSeeder extends Seeder
{
    public function run()
    {
        // Full Static Menu Structure
        $menu = [
            'getting_started' => [
                'title' => 'Getting Started',
                'icon' => 'rocket_launch',
                'order' => 1,
                'items' => [
                    'index' => 'Introduction',
                    'installation' => 'Installation',
                    'requirements' => 'Requirements',
                    'license' => 'License',
                ]
            ],
            'connectivity' => [
                'title' => 'Connectivity',
                'icon' => 'wifi_tethering',
                'order' => 2,
                'items' => [
                    'hotspot' => [
                        'title' => 'Hotspot System',
                        'items' => [
                            'hotspot-wizard' => 'Setup Wizard',
                            'hotspot-users' => 'User Generator',
                            'hotspot-qr' => 'QR Code Print',
                            'hotspot-templates' => 'Login Templates',
                        ]
                    ],
                    'iot' => [
                        'title' => 'IoT & Smart Home',
                        'items' => [
                            'iot-mqtt' => 'MQTT Config',
                            'iot-lorawan' => 'LoRaWAN Gateway',
                        ]
                    ],
                    'pppoe' => [
                        'title' => 'PPPoE Server',
                        'items' => [
                            'pppoe-server' => 'Server Setup',
                            'pppoe-secrets' => 'Secrets Generator',
                        ]
                    ],
                    'vpn_config' => [
                        'title' => 'VPN Configuration',
                        'items' => [
                            'vpn-tunnel' => 'Tunnel Manager',
                            'vpn-l2tp' => 'L2TP Server',
                            'vpn-openvpn' => 'OpenVPN',
                            'vpn-wireguard' => 'WireGuard',
                        ]
                    ]
                ]
            ],
            'customization' => [
                'title' => 'Customization',
                'icon' => 'palette',
                'order' => 3,
                'items' => [
                    'branding' => [
                        'title' => 'Branding & Logo',
                        'items' => [
                            'branding-maker' => 'Branding Maker',
                            'branding-assets' => 'Logo Assets',
                        ]
                    ],
                    'templates' => [
                        'title' => 'Templates',
                        'items' => [
                            'templates-editor' => 'Template Editor',
                            'templates-login' => 'Hotspot Login',
                        ]
                    ]
                ]
            ],
            'network' => [
                'title' => 'Network',
                'icon' => 'hub',
                'order' => 4,
                'items' => [
                    'load_balancing' => [
                        'title' => 'Load Balancing',
                        'items' => [
                            'pcc' => 'PCC Method',
                            'nth' => 'NTH Method',
                            'ecmp' => 'ECMP Method',
                        ]
                    ],
                    'routing' => [
                        'title' => 'Routing',
                        'items' => [
                            'routing-bgp' => 'BGP Generator',
                            'routing-ospf' => 'OSPF Generator',
                            'routing-static' => 'Static Routes',
                        ]
                    ],
                    'switching' => [
                        'title' => 'Switching',
                        'items' => [
                            'switching-vlan' => 'VLAN Management',
                            'switching-bonding' => 'Interface Bonding',
                        ]
                    ]
                ]
            ],
            'security' => [
                'title' => 'Security',
                'icon' => 'security',
                'order' => 5,
                'items' => [
                    'hardening' => [
                        'title' => 'Hardening',
                        'items' => [
                            'security-hardening' => 'System Hardening',
                            'security-rogue' => 'DHCP Rogue',
                        ]
                    ],
                    'firewall' => [
                        'title' => 'Firewall',
                        'items' => [
                            'firewall-rules' => 'Filter Rules',
                            'firewall-mangle' => 'Mangle Rules',
                            'firewall-l7' => 'Layer 7 Proto',
                        ]
                    ],
                    'advanced' => [
                        'title' => 'Advanced Security',
                        'items' => [
                            'security-advanced' => 'DDoS Protection',
                            'security-port-knocking' => 'Port Knocking',
                        ]
                    ]
                ]
            ],
            'monitoring' => [
                'title' => 'Monitoring',
                'icon' => 'insights',
                'order' => 6,
                'items' => [
                    'traffic_tools' => [
                        'title' => 'Traffic Tools',
                        'items' => [
                            'monitoring-traffic' => 'Traffic Monitor',
                            'monitoring-sniffer' => 'Traffic Sniffer',
                        ]
                    ],
                    'discovery' => [
                        'title' => 'Discovery',
                        'items' => [
                            'monitoring-scanner' => 'Network Scanner',
                            'monitoring-neighbours' => 'Neighbour Viewer',
                        ]
                    ],
                    'logs' => [
                        'title' => 'Logs',
                        'items' => [
                            'monitoring-logs' => 'Log Analyzer',
                            'monitoring-syslog' => 'Remote Syslog',
                        ]
                    ]
                ]
            ],
            'qos' => [
                'title' => 'QoS',
                'icon' => 'speed',
                'order' => 7,
                'items' => [
                    'queue_mgmt' => [
                        'title' => 'Queue Management',
                        'items' => [
                            'qos-simple' => 'Simple Queues',
                            'qos-tree' => 'Queue Tree',
                            'qos-pcq' => 'PCQ Setup',
                        ]
                    ],
                    'app_prioritization' => [
                        'title' => 'App Prioritization',
                        'items' => [
                            'qos-gaming' => 'Gaming Traffic',
                            'qos-streaming' => 'Streaming Traffic',
                            'qos-voip' => 'VoIP Traffic',
                        ]
                    ]
                ]
            ],
            'resources' => [
                'title' => 'Resources',
                'icon' => 'source',
                'order' => 8,
                'items' => [
                    'billing' => [
                        'title' => 'Billing',
                        'items' => [
                            'billing-mikhmon' => 'Mikhmon Server',
                            'billing-radius' => 'RADIUS Server',
                        ]
                    ],
                    'files' => [
                        'title' => 'Files',
                        'items' => [
                            'resources-downloads' => 'Download Manager',
                        ]
                    ]
                ]
            ],
            'system' => [
                'title' => 'System',
                'icon' => 'settings_suggest',
                'order' => 9,
                'items' => [
                    'maintenance' => [
                        'title' => 'Maintenance',
                        'items' => [
                            'system-backup' => 'Backup & Restore',
                            'system-identity' => 'Identity & Users',
                        ]
                    ],
                    'automation' => [
                        'title' => 'Automation',
                        'items' => [
                            'system-scheduler' => 'Scheduler Builder',
                            'system-scripts' => 'Script Repository',
                        ]
                    ]
                ]
            ],
            'utilities' => [
                'title' => 'Utilities',
                'icon' => 'construction',
                'order' => 10,
                'items' => [
                    'calculators' => [
                        'title' => 'Calculators',
                        'items' => [
                            'utilities-ip-calc' => 'IP Calculator',
                            'utilities-raid' => 'RAID Calculator',
                        ]
                    ],
                    'batch_tools' => [
                        'title' => 'Batch Tools',
                        'items' => [
                            'utilities-batch-cmd' => 'Batch Command',
                            'utilities-mass-config' => 'Mass Config',
                        ]
                    ]
                ]
            ],
            'wireless' => [
                'title' => 'Wireless',
                'icon' => 'wifi',
                'order' => 11,
                'items' => [
                    'planning' => [
                        'title' => 'Planning',
                        'items' => [
                            'wireless-link-calc' => 'Link Budget Calc',
                            'wireless-fresnel' => 'Fresnel Zone',
                        ]
                    ],
                    'configuration' => [
                        'title' => 'Configuration',
                        'items' => [
                            'wireless-ap' => 'AP Setup',
                            'wireless-capsman' => 'CAPsMAN',
                        ]
                    ]
                ]
            ],
        ];

        foreach ($menu as $catSlug => $catData) {
            // 1. Create/Update Category
            $category = DocumentationCategory::firstOrCreate(
                ['slug' => $catSlug],
                [
                    'name' => $catData['title'],
                    'icon' => $catData['icon'] ?? 'folder',
                    'order' => $catData['order'] ?? 99
                ]
            );

            // 2. Iterate Items (Pages)
            if (isset($catData['items'])) {
                foreach ($catData['items'] as $itemSlug => $itemData) {
                    if (is_array($itemData)) {
                        // Level 2 Is a Parent (Group)
                        $parentPage = DocumentationPage::firstOrCreate(
                            ['slug' => $itemSlug],
                            [
                                'category_id' => $category->id,
                                'title' => $itemData['title'],
                                'content' => '', // Empty content for wrapper pages
                                'is_published' => true,
                                'published_at' => now(),
                                'parent_id' => null
                            ]
                        );

                        // Level 3 Children
                        if (isset($itemData['items'])) {
                            foreach ($itemData['items'] as $childSlug => $childTitle) {
                                DocumentationPage::firstOrCreate(
                                    ['slug' => $childSlug],
                                    [
                                        'category_id' => $category->id,
                                        'title' => $childTitle,
                                        'content' => "# $childTitle\n\nContent coming soon...",
                                        'is_published' => true,
                                        'published_at' => now(),
                                        'parent_id' => $parentPage->id
                                    ]
                                );
                            }
                        }
                    } else {
                        // Level 2 Is a Direct Page
                        DocumentationPage::firstOrCreate(
                            ['slug' => $itemSlug],
                            [
                                'category_id' => $category->id,
                                'title' => $itemData,
                                'content' => "# $itemData\n\nContent coming soon...",
                                'is_published' => true,
                                'published_at' => now(),
                                'parent_id' => null
                            ]
                        );
                    }
                }
            }
        }
    }
}
