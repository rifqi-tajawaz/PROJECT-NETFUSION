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
            'mikrotik-suite.utilities.batch.backup',
            'mikrotik-suite.utilities.batch.dns-ping',
            'mikrotik-suite.utilities.batch.port-scanner',
            'mikrotik-suite.utilities.batch.session-restore',
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
            'mikrotik-suite.network.ipv6.subnetting-generator',
            'mikrotik-suite.network.ipv6.firewall-generator',
            'mikrotik-suite.network.ipv6.neighbor-discovery',
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
            'mikrotik-suite.system.automation.scheduler-builder',
            'mikrotik-suite.system.automation.auto-reboot',
            'mikrotik-suite.system.automation.bandwidth',
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
            'mikrotik-suite.network.enterprise.ldp-vpls',
            'mikrotik-suite.network.enterprise.traffic-engineering',
        ];

        foreach ($routes as $route) {
            $response = $this->actingAs($user)->get(route($route));
            $response->assertStatus(200);
        }
    }
}
