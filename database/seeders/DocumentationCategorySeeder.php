<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DocumentationCategory;

class DocumentationCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'getting_started' => ['name' => 'Getting Started', 'icon' => 'rocket_launch', 'order' => 1],
            'connectivity' => ['name' => 'Connectivity', 'icon' => 'wifi_tethering', 'order' => 2],
            'network' => ['name' => 'Network', 'icon' => 'hub', 'order' => 3],
            'security' => ['name' => 'Security', 'icon' => 'security', 'order' => 4],
            'monitoring' => ['name' => 'Monitoring', 'icon' => 'insights', 'order' => 5],
            'customization' => ['name' => 'Customization', 'icon' => 'palette', 'order' => 6],
            'qos' => ['name' => 'QoS Manager', 'icon' => 'speed', 'order' => 7],
            'wireless' => ['name' => 'Wireless', 'icon' => 'wifi', 'order' => 8],
            'system' => ['name' => 'System', 'icon' => 'settings_suggest', 'order' => 9],
            'utilities' => ['name' => 'Utilities', 'icon' => 'construction', 'order' => 10],
            'resources' => ['name' => 'Resources', 'icon' => 'source', 'order' => 11],
        ];

        foreach ($categories as $slug => $data) {
            DocumentationCategory::updateOrCreate(
                ['slug' => $slug],
                [
                    'name' => $data['name'],
                    'icon' => $data['icon'],
                    'order' => $data['order']
                ]
            );
        }
    }
}
