<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WildcardRouteTest extends TestCase
{
    use RefreshDatabase;
    public function test_defined_routes_are_not_intercepted(): void
    {
    }
}
