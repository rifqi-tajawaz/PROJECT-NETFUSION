<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\Route;

class SidebarRoutesTest extends TestCase
{
    /**
     * Test that all routes in sidebar exist.
     *
     * @return void
     */
    public function test_sidebar_routes_exist()
    {
        $sidebarContent = file_get_contents(resource_path('views/layouts/sidebar.blade.php'));
        
        // Extract all route('...') calls
        preg_match_all("/route\('([^']+)'\)/", $sidebarContent, $matches);
        $routesInSidebar = array_unique($matches[1]);

        $registeredRoutes = collect(Route::getRoutes())->map(function ($route) {
            return $route->getName();
        })->toArray();

        $missingRoutes = [];

        foreach ($routesInSidebar as $routeName) {
            if (!in_array($routeName, $registeredRoutes)) {
                $missingRoutes[] = $routeName;
            }
        }

        if (!empty($missingRoutes)) {
            $this->fail("The following routes used in sidebar.blade.php are not defined: " . implode(', ', $missingRoutes));
        }

        $this->assertTrue(true);
    }
}
