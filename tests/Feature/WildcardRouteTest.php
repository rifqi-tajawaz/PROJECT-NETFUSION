<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class WildcardRouteTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that unauthenticated users are redirected to login when accessing random routes.
     *
     * @dataProvider invalidPathsProvider
     */
    public function test_wildcard_redirects_unauthenticated_users_to_login(string $path): void
    {
        $response = $this->get($path);

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    /**
     * Test that authenticated users are redirected to the dashboard when accessing random routes.
     *
     * @dataProvider invalidPathsProvider
     */
    public function test_wildcard_redirects_authenticated_users_to_dashboard(string $path): void
    {
        // Must verify email to avoid middleware redirection
        $user = User::factory()->create(['email_verified_at' => now()]);

        $response = $this->actingAs($user)->get($path);

        $response->assertStatus(302);
        $response->assertRedirect(route('mikrotik-suite.dashboard'));
    }

    /**
     * Test that defined routes are not intercepted by the wildcard route.
     */
    public function test_defined_routes_are_not_intercepted(): void
    {
        // Public route
        $response = $this->get(route('pricing'));
        $response->assertStatus(200);

        // Login route (guest)
        $response = $this->get(route('login'));
        $response->assertStatus(200);

        // Home route (guest redirects to login, handled by HomeController::index not root)
        $response = $this->get('/');
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    /**
     * Data provider for invalid paths.
     * Includes simple paths, nested paths, and paths with special characters.
     */
    public static function invalidPathsProvider(): array
    {
        return [
            ['/random-page'],
            ['/admin/hidden-but-not-really'],
            ['/users/123/edit/something/else'],
            ['/a/b/c/d/e/f'],
            ['/foo-bar'],
            ['/foo_bar'],
            ['/user@example'],
            ['/path with spaces'], // URL encoding handles this usually, but good to check
            ['/path%20with%20encoded%20spaces'],
        ];
    }
}
