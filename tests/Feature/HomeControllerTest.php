<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomeControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that the home route redirects guests to the login page.
     */
    public function test_home_redirects_guest_to_login(): void
    {
        $response = $this->get('/');

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    /**
     * Test that the home route redirects authenticated users to the dashboard.
     */
    public function test_home_redirects_authenticated_user_to_dashboard(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/');

        $response->assertStatus(302);
        $response->assertRedirect(route('mikrotik-suite.dashboard'));
    }

    /**
     * Test that a random route redirects guests to login (via catch-all logic).
     */
    public function test_random_route_redirects_guest_to_login(): void
    {
        $response = $this->get('/some-random-route-that-does-not-exist');

        // Since catch-all route uses root() which calls index(), it should redirect to login.
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    /**
     * Test that a random route redirects authenticated users to dashboard (via catch-all logic).
     */
    public function test_random_route_redirects_authenticated_user_to_dashboard(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/another-random-route');

        $response->assertStatus(302);
        $response->assertRedirect(route('mikrotik-suite.dashboard'));
    }
}
