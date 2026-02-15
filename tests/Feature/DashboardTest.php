<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        config(['recaptcha.enabled' => false]);
    }

    /**
     * Test Dashboard Redirects Unauthenticated Users
     */
    public function test_dashboard_redirects_guests_to_login(): void
    {
        $response = $this->get(route('mikrotik-suite.dashboard'));
        $response->assertRedirect(route('login'));
    }

    /**
     * Test Dashboard Loads for Authenticated User
     */
    public function test_dashboard_loads_for_authenticated_users(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('mikrotik-suite.dashboard'));

        $response->assertStatus(200);
        $response->assertSee('Dashboard'); // Assuming 'Dashboard' is in the view
    }

    /**
     * Test NetFusion Dashboard Loads
     */
    public function test_netfusion_dashboard_loads_for_authenticated_users(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('mikrotik-suite.netfusion.dashboard'));

        $response->assertStatus(200);
        $response->assertSee('NetFusion');
    }

    /**
     * Test Admin Pages Are Protected
     */
    public function test_admin_pages_require_admin_role(): void
    {
        $user = User::factory()->create(['role' => 'user']);
        $admin = User::factory()->create(['role' => 'admin']);

        // User should be denied
        // Assuming admin routes start with admin. or have a specific path
        // Checking sidebar link for admin.users.index
        $response = $this->actingAs($user)->get(route('admin.users.index'));
        $response->assertStatus(403); // Or 302/404 depending on implementation

        // Admin should see it
        $this->actingAs($admin)->get(route('admin.users.index'))->assertStatus(200);
    }
}
