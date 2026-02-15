<?php

return [
    'network' => [
        'load_balancing' => [
            'ecmp' => [
                'title' => 'ECMP Load Balancing',
                'subtitle' => 'Equal Cost Multi-Path (ECMP) - Simple and effective load balancing.',
            ],
            'pcc' => [
                'title' => 'PCC Load Balancing',
                'subtitle' => 'Per Connection Classifier (PCC) - Advanced traffic distribution.',
            ],
            'nth' => [
                'title' => 'NTH Load Balancing',
                'subtitle' => 'Nth Packet Balancing - Round robin packet scheduler.',
            ],
            'form' => [
                'wan_lines' => 'WAN Lines',
                'routeros_version' => 'RouterOS Version',
                'local_target_type' => 'Local Target Type',
                'local_ip_target' => 'Local IP Target',
                'interface_target' => 'Interface Target',
                'interface_list_target' => 'Interface List Target',
                'recursive_gateway' => 'Recursive Gateway',
                'bandwidth_ratio' => 'Bandwidth Ratio',
                'failover' => 'Failover',
                'configuration' => 'Configuration',
                'wan_interface' => 'WAN Interface',
                'gateway_ip' => 'Gateway IP',
                'check' => 'Check',
                'ip_dns_check' => 'IP/DNS Check',
                'speed_mbps' => 'Speed (Mbps)',
                'weight' => 'Weight',
                'sequence' => 'Sequence',
                'number_of_wans' => 'Number of WANs',
                'options' => [
                    'ip_address_list' => 'IP Address List',
                    'in_interface' => 'In. Interface',
                    'in_interface_list' => 'In. Interface List',
                ],
            ],
            'guide' => [
                'title' => 'Gateway Configuration Guide',
                'text' => 'Enter <strong>Gateway IP</strong> or <strong>Interface Name</strong>.<br>Duplicate IPs are auto-formatted as <span class="font-monospace">IP%Interface</span>.',
                'setup_wan' => 'Setup your WAN interfaces and network.',
            ],
            'actions' => [
                'generate_script' => 'Generate Script',
                'copy_script' => 'Copy Script',
                'reset_all' => 'Reset All',
            ],
        ],
    ],
];
