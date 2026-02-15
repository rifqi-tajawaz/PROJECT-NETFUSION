<?php

namespace Tests\Feature\MikrotikSuite\Network;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LoadBalancingTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Assume authentication is needed. Create a dummy user.
        $user = User::factory()->create();
        $this->actingAs($user);
        app()->setLocale('en');
    }

    // =========================================================================
    // PAGE LOAD TESTS (Basic Accessibility)
    // =========================================================================

    public function test_ecmp_page_loads()
    {
        $response = $this->get(route('mikrotik-suite.network.load-balancing.ecmp'));
        $response->assertStatus(200);
        // Assert keys present (localization check via view)
        // Since we mock localization or use default files, checking specific strings might be flaky if localization changes.
        // But "ECMP Load Balancing" is the title in blade.
        $response->assertSee('ECMP Load Balancing');
    }

    public function test_pcc_page_loads()
    {
        $response = $this->get(route('mikrotik-suite.network.load-balancing.pcc'));
        $response->assertStatus(200);
        $response->assertSee('PCC Load Balancing');
    }

    public function test_nth_page_loads()
    {
        $response = $this->get(route('mikrotik-suite.network.load-balancing.nth'));
        $response->assertStatus(200);
        $response->assertSee('NTH Load Balancing');
    }

    // =========================================================================
    // ECMP TESTS
    // =========================================================================

    /**
     * Test ECMP with RouterOS v7, Failover ON, Ratio ON.
     * This covers the most complex ECMP logic: Recursive Gateway + Weighted Routes + V7 Syntax.
     */
    public function test_ecmp_v7_generation_complex()
    {
        $data = [
            'wan_count' => 2,
            'ros_version' => 'v7.xx',
            'feature_failover' => true,
            'feature_ratio' => true,

            // WAN 1 (Weight 2)
            'wan_interface_1' => 'ether1-ISP1',
            'wan_gateway_1' => '192.168.1.1',
            'wan_check_1' => '8.8.8.8',
            'wan_ratio_1' => 2,

            // WAN 2 (Weight 1)
            'wan_interface_2' => 'ether2-ISP2',
            'wan_gateway_2' => '192.168.2.1',
            'wan_check_2' => '1.1.1.1',
            'wan_ratio_2' => 1,
        ];

        $response = $this->postJson(route('mikrotik-suite.network.load-balancing.ecmp.generate'), $data);

        $response->assertStatus(200)
            ->assertJsonStructure(['status', 'script']);

        $script = $response->json('script');

        // Headers
        $this->assertStringContainsString('NETFUSION ECMP LOAD BALANCING', $script);

        // Recursive Gateway Logic
        $this->assertStringContainsString('scope="10"', $script);
        $this->assertStringContainsString('target-scope="10"', $script); // Recursive requires target-scope
        $this->assertStringContainsString('NetFusion Failover :: Check ISP-1', $script);

        // Ratio Logic (Repeated Lines for V7)
        // ISP1 has Weight 2, so it should appear multiple times or have logic handling it.
        // Our V7 implementation generates separate lines.
        $this->assertStringContainsString('NetFusion Main Route :: Ratio ISP-1', $script);

        // V7 Syntax check (routing table is implicit for main, but verify we don't see legacy routing-mark spam unless needed)
        // ECMP V7 typically uses multiple gateways in one rule OR separate routes with same dst-address.
        // Our implementation uses separate routes for V7 + Ratio.
    }

    public function test_ecmp_v6_basic()
    {
        $data = [
            'wan_count' => 2,
            'ros_version' => 'v6.xx',
            'feature_failover' => false,
            'feature_ratio' => false,
            'wan_interface_1' => 'ether1',
            'wan_gateway_1' => '10.1.1.1',
            'wan_check_1' => '',
            'wan_weight_1' => 1,
            'wan_interface_2' => 'ether2',
            'wan_gateway_2' => '10.2.2.1',
            'wan_check_2' => '',
            'wan_weight_2' => 1,
        ];

        $response = $this->postJson(route('mikrotik-suite.network.load-balancing.ecmp.generate'), $data);
        $script = $response->json('script');

        // V6 ECMP usually comma separated gateways e.g. gateway=10.1.1.1,10.2.2.1
        // But our implementation might prioritize the user's preferred format.
        // Just verify basic presence.
        $this->assertStringContainsString('NetFusion Main Route', $script);
        // V6 uses comma separated quoted string
        $this->assertStringContainsString('gateway="10.1.1.1,10.2.2.1"', $script);
    }

    // =========================================================================
    // PCC TESTS
    // =========================================================================

    public function test_pcc_v7_generation_with_interface_list()
    {
        $data = [
            'wan_count' => 2,
            'ros_version' => 'v7.xx',
            'local_type' => 'interface-list', // Testing 'In. Interface List'
            'local_target' => 'LAN-LIST',
            'feature_failover' => false,
            'feature_ratio' => false,
            'wan_interface_1' => 'ether1',
            'wan_gateway_1' => '10.1.1.1',
            'wan_check_1' => '',
            'wan_interface_2' => 'ether2',
            'wan_gateway_2' => '10.2.2.1',
            'wan_check_2' => '',
        ];

        $response = $this->postJson(route('mikrotik-suite.network.load-balancing.pcc.generate'), $data);
        $script = $response->json('script');

        $this->assertStringContainsString('NETFUSION PCC LOAD BALANCING', $script);
        $this->assertStringContainsString('(Per Connection Classifier)', $script);

        // Check for Interface List usage
        $this->assertStringContainsString('in-interface-list="LAN-LIST"', $script);

        // Check V7 specific: Routing Tables
        $this->assertStringContainsString('/routing table', $script);
        $this->assertStringContainsString('add name="TO-ISP-1" fib', $script);
    }

    public function test_pcc_v6_generation_with_ip_address()
    {
        $data = [
            'wan_count' => 2,
            'ros_version' => 'v6.xx',
            'local_type' => 'address-list',
            'local_target' => 'LOCAL-NET',
            'feature_failover' => false,
            'feature_ratio' => false,
            'wan_interface_1' => 'ether1',
            'wan_gateway_1' => '10.1.1.1',
            'wan_interface_2' => 'ether2',
            'wan_gateway_2' => '10.2.2.1',
        ];

        $response = $this->postJson(route('mikrotik-suite.network.load-balancing.pcc.generate'), $data);
        $script = $response->json('script');

        // Check for IP Address List usage
        $this->assertStringContainsString('src-address-list="LOCAL-IP"', $script);
        // V6 should NOT have /routing table
        $this->assertStringNotContainsString('/routing table add', $script);
    }

    // =========================================================================
    // NTH TESTS
    // =========================================================================

    public function test_nth_v7_generation_basic()
    {
        $data = [
            'wan_count' => 2,
            'ros_version' => 'v7.xx',
            'local_type' => 'interface',
            'local_target' => 'bridge1',
            'feature_failover' => false,
            'feature_ratio' => false,
            'wan_interface_1' => 'ether1',
            'wan_gateway_1' => '10.1.1.1',
            'wan_interface_2' => 'ether2',
            'wan_gateway_2' => '10.2.2.1',
            'wan_weight_1' => 1,
            'wan_weight_2' => 1,
        ];

        $response = $this->postJson(route('mikrotik-suite.network.load-balancing.nth.generate'), $data);
        $script = $response->json('script');

        $this->assertStringContainsString('NETFUSION NTH LOAD BALANCING', $script);
        // NTH uses 'nth=Every,Packet' syntax
        $this->assertStringContainsString('nth=', $script);
        // V7 Routing Table check
        $this->assertStringContainsString('add name="TO-ISP-1" fib', $script);
    }

    // =========================================================================
    // VALIDATION TESTS
    // =========================================================================

    public function test_validation_fails_on_missing_fields()
    {
        // Missing gateway
        $data = [
            'wan_count' => 2,
            'ros_version' => 'v7.xx',
            'wan_interface_1' => 'ether1',
            // 'wan_gateway_1' => '', // MISSING
        ];

        $response = $this->postJson(route('mikrotik-suite.network.load-balancing.ecmp.generate'), $data);

        // Should return 422 Unprocessable Entity
        $response->assertStatus(422);
    }
}
