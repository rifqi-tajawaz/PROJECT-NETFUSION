<?php

namespace Tests\Unit\Services\MikrotikSuite\Utilities;

use App\Services\MikrotikSuite\Utilities\CalculatorsService;
use Tests\TestCase;

class CalculatorsServiceTest extends TestCase
{
    protected CalculatorsService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new CalculatorsService();
    }

    /**
     * Test IP Calculation Logic.
     */
    public function test_calculate_ip_logic()
    {
        // Method expects (string $ip, $mask)
        $ip = '192.168.88.1';
        $mask = '24';

        $result = $this->service->calculateIp($ip, $mask);

        // Assert structure
        $this->assertArrayHasKey('network', $result);
        $this->assertArrayHasKey('broadcast', $result);
        $this->assertArrayHasKey('hosts_count', $result);

        // Assert values for /24
        $this->assertEquals('192.168.88.0', $result['network']);
        $this->assertEquals('192.168.88.255', $result['broadcast']);
        $this->assertEquals('254', $result['hosts_count']);
    }

    /**
     * Test Bandwidth Calculation logic.
     */
    public function test_calculate_bandwidth_logic()
    {
        $data = [
            'total_down' => 100,
            'total_up' => 50,
            'reserved_down_pct' => 10,
            'reserved_up_pct' => 10,
            'tiers' => [
                ['name' => 'Plan A', 'down' => 5, 'up' => 1, 'count' => 10]
            ]
        ];

        $result = $this->service->calculateBandwidth($data);

        $this->assertIsArray($result);
        $this->assertArrayHasKey('analysis', $result);
        $this->assertArrayHasKey('free_down', $result['analysis']);
    }

    /**
     * Test PCQ Calculation logic.
     */
    public function test_calculate_pcq_logic()
    {
        $data = [
            'total_down' => 100,
            'total_up' => 100,
            'rate_down' => 5,
            'rate_up' => 5,
            'active_users' => 10
        ];

        $result = $this->service->calculatePcq($data);

        // Should return limits and script
        $this->assertArrayHasKey('limit_down', $result);
        $this->assertArrayHasKey('script', $result);
        $this->assertStringContainsString('/ip firewall mangle', $result['script']);
    }
}
