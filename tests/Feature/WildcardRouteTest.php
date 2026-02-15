<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class WildcardRouteTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that wildcard routes redirect unauthenticated users to login.
     */
    public function test_wildcard_redirects_unauthenticated_users_to_login(): void
    {
        $paths = [
            '/random-page',
            '/admin/hidden',
            '/users/123/edit',
            '/a/b/c/d/e/f/g',
            '/nested/path/with/segments'
        ];

        foreach ($paths as $path) {
            $response = $this->get($path);

            $response->assertStatus(302);
            $response->assertRedirect(route('login'));
        }
    }

    /**
     * Test that wildcard routes redirect authenticated users to the dashboard.
     */
    public function test_wildcard_redirects_authenticated_users_to_dashboard(): void
    {
        $user = User::factory()->create();

        $paths = [
            '/random-page',
            '/admin/hidden',
            '/users/123/edit',
            '/a/b/c/d/e/f/g',
            '/nested/path/with/segments'
        ];

        foreach ($paths as $path) {
            $response = $this->actingAs($user)->get($path);

            $response->assertStatus(302);
            $response->assertRedirect(route('mikrotik-suite.dashboard'));
        }
    }

    /**
     * Test that defined routes are not intercepted by the wildcard route.
     */
    public function test_defined_routes_are_not_intercepted(): void
    {
        // Test /login
        $response = $this->get('/login');
        $response->assertStatus(200);

        // Test public pages (e.g., pricing)
        $response = $this->get('/pricing');
        $response->assertStatus(200);

        // Test support page
        $response = $this->get('/support');
        $response->assertStatus(200);
    }

    /**
     * Test that wildcard routes handle nested paths and special characters.
     */
    public function test_wildcard_handles_nested_paths_and_special_characters(): void
    {
        $paths = [
            '/user@example',
            '/path with spaces', // Browser/HttpClient will encode this
            '/foo_bar-baz.html',
            '/very/deep/nested/structure/that/should/still/redirect',
            '/API/V1/Something' // Case sensitivity check (if applicable, though Laravel routes are usually case insensitive but matching logic is important)
        ];

        foreach ($paths as $path) {
            $response = $this->get($path);
            $response->assertStatus(302);
            $response->assertRedirect(route('login'));
        }
    }
}
