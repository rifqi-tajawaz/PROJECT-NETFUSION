<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RenamedRoutesTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test routes handled by BatchOperationsController.
     */
    public function test_batch_operations_routes()
    {
        $user = User::factory()->create();

        $routes = [
            'mikrotik.utilities.batch.backup',
            'mikrotik.utilities.batch.dns-ping',
            'mikrotik.utilities.batch.port-scanner',
            'mikrotik.utilities.batch.session-restore',
        ];

        foreach ($routes as $route) {
            $response = $this->actingAs($user)->get(route($route));
            $response->assertStatus(200);
        }
    }

    /**
     * Test routes handled by Ipv6AddressingController & Ipv6SecurityController.
     */
    public function test_ipv6_routes()
    {
        $user = User::factory()->create();

        $routes = [
            'mikrotik.network.ipv6.subnetting-generator',
            'mikrotik.network.ipv6.firewall-generator',
            'mikrotik.network.ipv6.neighbor-discovery',
        ];

        foreach ($routes as $route) {
            $response = $this->actingAs($user)->get(route($route));
            $response->assertStatus(200);
        }
    }

    /**
     * Test routes handled by AutomationController.
     */
    public function test_automation_routes()
    {
        $user = User::factory()->create();

        $routes = [
            'mikrotik.system.automation.scheduler-builder',
            'mikrotik.system.automation.auto-reboot',
            'mikrotik.system.automation.bandwidth',
        ];

        foreach ($routes as $route) {
            $response = $this->actingAs($user)->get(route($route));
            $response->assertStatus(200);
        }
    }

    /**
     * Test routes handled by EnterpriseMplsController.
     */
    public function test_enterprise_mpls_routes()
    {
        $user = User::factory()->create();

        $routes = [
            'mikrotik.network.enterprise.ldp-vpls',
            'mikrotik.network.enterprise.traffic-engineering',
        ];

        foreach ($routes as $route) {
            $response = $this->actingAs($user)->get(route($route));
            $response->assertStatus(200);
        }
    }
}
