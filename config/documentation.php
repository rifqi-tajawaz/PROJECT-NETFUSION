<?php

return [
    'getting_started' => [
        'title' => 'documentation.getting_started',
        'icon' => 'rocket_launch',
        'items' => [
            'index' => 'documentation.introduction',
            'installation' => 'documentation.installation',
            'requirements' => 'documentation.requirements',
            'license' => 'documentation.license',
        ]
    ],
    'connectivity' => [
        'title' => 'documentation.connectivity',
        'icon' => 'wifi_tethering',
        'items' => [
            'hotspot' => [
                'title' => 'documentation.hotspot',
                'items' => [
                    'hotspot-wizard' => 'documentation.wizard',
                    'hotspot-users' => 'documentation.user_generator',
                    'hotspot-qr' => 'documentation.qr_code',
                    'hotspot-templates' => 'documentation.login_templates',
                ]
            ],
            'iot' => [
                'title' => 'documentation.iot',
                'items' => [
                    'iot-mqtt' => 'documentation.mqtt_config',
                    'iot-lorawan' => 'documentation.lorawan_gateway',
                ]
            ],
            'pppoe' => [
                'title' => 'documentation.pppoe',
                'items' => [
                    'pppoe-server' => 'documentation.pppoe_server',
                    'pppoe-secrets' => 'documentation.secrets_generator',
                ]
            ],
            'vpn_config' => [
                'title' => 'documentation.vpn_config',
                'items' => [
                    'vpn-tunnel' => 'documentation.tunnel_manager',
                    'vpn-l2tp' => 'documentation.l2tp_server',
                    'vpn-openvpn' => 'documentation.openvpn',
                    'vpn-wireguard' => 'documentation.wireguard',
                ]
            ]
        ]
    ],
    'customization' => [
        'title' => 'documentation.customization',
        'icon' => 'palette',
        'items' => [
            'branding' => [
                'title' => 'documentation.branding',
                'items' => [
                    'branding-maker' => 'documentation.branding_maker',
                    'branding-assets' => 'documentation.logo_assets',
                ]
            ],
            'templates' => [
                'title' => 'documentation.templates',
                'items' => [
                    'templates-editor' => 'documentation.template_editor',
                    'templates-login' => 'documentation.hotspot_login',
                ]
            ]
        ]
    ],
    'network' => [
        'title' => 'documentation.network',
        'icon' => 'hub',
        'items' => [
            'load_balancing' => [
                'title' => 'documentation.load_balancing',
                'items' => [
                    'pcc' => 'documentation.pcc_method',
                    'nth' => 'documentation.nth_method',
                    'ecmp' => 'documentation.ecmp_method',
                ]
            ],
            'routing' => [
                'title' => 'documentation.routing',
                'items' => [
                    'routing-bgp' => 'documentation.bgp_generator',
                    'routing-ospf' => 'documentation.ospf_generator',
                    'routing-static' => 'documentation.static_routes',
                ]
            ],
            'switching' => [
                'title' => 'documentation.switching',
                'items' => [
                    'switching-vlan' => 'documentation.vlan_mgmt',
                    'switching-bonding' => 'documentation.interface_bonding',
                ]
            ]
        ]
    ],
    'security' => [
        'title' => 'documentation.security',
        'icon' => 'security',
        'items' => [
            'hardening' => [
                'title' => 'documentation.hardening',
                'items' => [
                    'security-hardening' => 'documentation.system_hardening',
                    'security-rogue' => 'documentation.dhcp_rogue',
                ]
            ],
            'firewall' => [
                'title' => 'documentation.firewall',
                'items' => [
                    'firewall-rules' => 'documentation.filter_rules',
                    'firewall-mangle' => 'documentation.mangle_rules',
                    'firewall-l7' => 'documentation.layer7_proto',
                ]
            ],
            'advanced' => [
                'title' => 'documentation.advanced',
                'items' => [
                    'security-advanced' => 'documentation.ddos_protection',
                    'security-port-knocking' => 'documentation.port_knocking',
                ]
            ]
        ]
    ],
    'monitoring' => [
        'title' => 'documentation.monitoring',
        'icon' => 'insights',
        'items' => [
            'traffic_tools' => [
                'title' => 'documentation.traffic_tools',
                'items' => [
                    'monitoring-traffic' => 'documentation.traffic_monitor',
                    'monitoring-sniffer' => 'documentation.traffic_sniffer',
                ]
            ],
            'discovery' => [
                'title' => 'documentation.discovery',
                'items' => [
                    'monitoring-scanner' => 'documentation.network_scanner',
                    'monitoring-neighbours' => 'documentation.neighbour_viewer',
                ]
            ],
            'logs' => [
                'title' => 'documentation.logs',
                'items' => [
                    'monitoring-logs' => 'documentation.log_analyzer',
                    'monitoring-syslog' => 'documentation.remote_syslog',
                ]
            ]
        ]
    ],
    'qos' => [
        'title' => 'documentation.qos',
        'icon' => 'speed',
        'items' => [
            'queue_mgmt' => [
                'title' => 'documentation.queue_mgmt',
                'items' => [
                    'qos-simple' => 'documentation.simple_queues',
                    'qos-tree' => 'documentation.queue_tree',
                    'qos-pcq' => 'documentation.pcq_setup',
                ]
            ],
            'app_prioritization' => [
                'title' => 'documentation.app_prioritization',
                'items' => [
                    'qos-gaming' => 'documentation.gaming_traffic',
                    'qos-streaming' => 'documentation.streaming_traffic',
                    'qos-voip' => 'documentation.voip_traffic',
                ]
            ]
        ]
    ],
    'resources' => [
        'title' => 'documentation.resources',
        'icon' => 'source',
        'items' => [
            'billing' => [
                'title' => 'documentation.billing',
                'items' => [
                    'billing-mikhmon' => 'documentation.mikhmon_server',
                    'billing-radius' => 'documentation.radius_server',
                ]
            ],
            'files' => [
                'title' => 'documentation.files',
                'items' => [
                    'resources-downloads' => 'documentation.download_manager',
                ]
            ]
        ]
    ],
    'system' => [
        'title' => 'documentation.system',
        'icon' => 'settings_suggest',
        'items' => [
            'maintenance' => [
                'title' => 'documentation.maintenance',
                'items' => [
                    'system-backup' => 'documentation.backup_restore',
                    'system-identity' => 'documentation.identity_users',
                ]
            ],
            'automation' => [
                'title' => 'documentation.automation',
                'items' => [
                    'system-scheduler' => 'documentation.scheduler_builder',
                    'system-scripts' => 'documentation.script_repository',
                ]
            ]
        ]
    ],
    'utilities' => [
        'title' => 'documentation.utilities',
        'icon' => 'construction',
        'items' => [
            'calculators' => [
                'title' => 'documentation.calculators',
                'items' => [
                    'utilities-ip-calc' => 'documentation.ip_calculator',
                    'utilities-raid' => 'documentation.raid_calculator',
                ]
            ],
            'batch_tools' => [
                'title' => 'documentation.batch_tools',
                'items' => [
                    'utilities-batch-cmd' => 'documentation.batch_command',
                    'utilities-mass-config' => 'documentation.mass_config',
                ]
            ]
        ]
    ],
    'wireless' => [
        'title' => 'documentation.wireless',
        'icon' => 'wifi',
        'items' => [
            'planning' => [
                'title' => 'documentation.planning',
                'items' => [
                    'wireless-link-calc' => 'documentation.link_budget_calc',
                    'wireless-fresnel' => 'documentation.fresnel_zone',
                ]
            ],
            'configuration' => [
                'title' => 'documentation.configuration',
                'items' => [
                    'wireless-ap' => 'documentation.ap_setup',
                    'wireless-capsman' => 'documentation.capsman',
                ]
            ]
        ]
    ],
];
