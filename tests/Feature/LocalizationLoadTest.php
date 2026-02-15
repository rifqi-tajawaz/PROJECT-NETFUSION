<?php

namespace Tests\Feature;

use Tests\TestCase;

class LocalizationLoadTest extends TestCase
{
    public function test_mikrotik_localization_loads()
    {
        // 1. Check Default (Should be EN now)
        $this->assertEquals('en', app()->getLocale(), 'Default locale should be English');
        $this->assertEquals('ECMP Load Balancing', __('mikrotik-suite.network.load_balancing.ecmp.title'));

        // 2. Check ID Manual Switch
        app()->setLocale('id');
        $this->assertEquals('Load Balancing ECMP', __('mikrotik-suite.network.load_balancing.ecmp.title'));
    }
}
