<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

class SidebarAuditTest extends TestCase
{
    /**
     * Parse sidebar.blade.php and check if all route() calls exist.
     */
    public function test_sidebar_routes_exist()
    {
        $path = resource_path('views/layouts/sidebar.blade.php');
        $content = File::get($path);

        // Match all route('name') or route("name")
        preg_match_all("/route\(['\"]([^'\"]+)['\"]\)/", $content, $matches);

        $routeNames = $matches[1];
        $missingRoutes = [];

        foreach ($routeNames as $name) {
            if (!Route::has($name)) {
                $missingRoutes[] = $name;
            }
        }

        if (!empty($missingRoutes)) {
            $this->fail("The following sidebar routes are missing from the application:\n" . implode("\n", $missingRoutes));
        }

        $this->assertTrue(true);
    }
}
