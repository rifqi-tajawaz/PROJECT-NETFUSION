<?php

return [
    'network' => [
        'load_balancing' => [
            'ecmp' => [
                'title' => 'Load Balancing ECMP',
                'subtitle' => 'Equal Cost Multi-Path (ECMP) - Penyeimbangan beban yang sederhana dan efektif.',
            ],
            'pcc' => [
                'title' => 'Load Balancing PCC',
                'subtitle' => 'Per Connection Classifier (PCC) - Distribusi lalu lintas tingkat lanjut.',
            ],
            'nth' => [
                'title' => 'Load Balancing NTH',
                'subtitle' => 'Nth Packet Balancing - Penjadwal paket round robin.',
            ],
            'form' => [
                'wan_lines' => 'Jalur WAN',
                'routeros_version' => 'Versi RouterOS',
                'local_target_type' => 'Tipe Target Lokal',
                'local_ip_target' => 'Target IP Lokal',
                'interface_target' => 'Target Interface',
                'interface_list_target' => 'Target Daftar Interface',
                'recursive_gateway' => 'Gateway Rekursif',
                'bandwidth_ratio' => 'Rasio Bandwidth',
                'failover' => 'Failover',
                'configuration' => 'Konfigurasi',
                'wan_interface' => 'Interface WAN',
                'gateway_ip' => 'IP Gateway',
                'check' => 'Cek',
                'ip_dns_check' => 'Cek IP/DNS',
                'speed_mbps' => 'Kecepatan (Mbps)',
                'weight' => 'Bobot',
                'sequence' => 'Urutan',
                'number_of_wans' => 'Jumlah WAN',
                'options' => [
                    'ip_address_list' => 'Daftar Alamat IP',
                    'in_interface' => 'In. Interface',
                    'in_interface_list' => 'In. Interface List',
                ],
            ],
            'guide' => [
                'title' => 'Panduan Konfigurasi Gateway',
                'text' => 'Masukkan <strong>IP Gateway</strong> atau <strong>Nama Interface</strong>.<br>IP duplikat diformat otomatis sebagai <span class="font-monospace">IP%Interface</span>.',
                'setup_wan' => 'Atur interface WAN dan jaringan Anda.',
            ],
            'actions' => [
                'generate_script' => 'Buat Script',
                'copy_script' => 'Salin Script',
                'reset_all' => 'Reset Semua',
            ],
        ],
    ],
];
