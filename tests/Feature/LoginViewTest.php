<?php

namespace Tests\Feature;

use Tests\TestCase;

class LoginViewTest extends TestCase
{
    /**
     * Test that the login form contains an email input with the 'name' attribute.
     */
    public function test_login_form_has_email_input_with_name_attribute()
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
        $response->assertSee('name="email"', false);
    }
}
