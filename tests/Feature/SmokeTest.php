<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SmokeTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Disable ReCaptcha for testing
        config(['recaptcha.enabled' => false]);
        // Mock Mail to prevent errors and actual sending
        \Illuminate\Support\Facades\Mail::fake();
    }

    /**
     * Test all registered GET routes to ensure no 500 errors.
     */
    public function test_all_get_routes_load_successfully(): void
    {
        $routes = Route::getRoutes();

        $routesTested = 0;
        $ignoredRoutes = [
            'debugbar',
            '_ignition',
            'sanctum',
            'api', // Skip API routes for this smoke test
            'debug-db' // Skip debug DB route as it requires DB connection not present in smoke test
        ];

        foreach ($routes as $route) {
            if (!in_array('GET', $route->methods())) {
                continue;
            }

            $uri = $route->uri();

            // Skip parametrized routes for now (too complex to guess ID)
            if (strpos($uri, '{') !== false) {
                continue;
            }

            // Skip ignored prefixes
            foreach ($ignoredRoutes as $ignored) {
                if (str_starts_with($uri, $ignored)) {
                    continue 2;
                }
            }

            $response = $this->get($uri);

            // We expect 200 (OK) or 302 (Redirect to Login) or 401 (Unauthorized) or 403 (Forbidden)
            // WE DO NOT EXPECT 500 (Server Error)
            $status = $response->status();

            // If 500, fail immediately with the URI
            if ($status >= 500) {
                file_put_contents(storage_path('logs/smoke_failure.log'), "FAILED ROUTE: $uri | STATUS: $status");
                $this->fail("Route [$uri] returned status $status. Server Error.");
            }

            $routesTested++;
        }

        $this->assertTrue($routesTested > 0, "No routes were tested. Something is wrong.");
        echo "\nTested $routesTested routes successfully.\n";
    }
}
