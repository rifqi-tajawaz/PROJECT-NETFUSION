<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class AuthTest extends TestCase
{
    /**
     * Test Login Page Loads
     */
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
     * Test Login Page Loads
     */
    public function test_login_page_can_be_rendered(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
    }

    /**
     * Test Register Page Loads
     */
    public function test_register_page_can_be_rendered(): void
    {
        $response = $this->get('/register');
        $response->assertStatus(200);
    }

    /**
     * Test User Can Login (Redirects to Verification on New Device)
     */
    public function test_users_can_authenticate_but_require_verification_on_new_device(): void
    {
        $user = User::factory()->create([
            'password' => bcrypt('password'), // Ensure password matches hashing
            'is_active' => true,
            'is_locked' => false,
            'email_verified_at' => now(),
        ]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
            'g-recaptcha-response' => 'test-token', // Required by validator
        ]);

        $this->assertAuthenticated();
        // New devices require verification, so specific redirect depends on device trust logic
        // For a fresh test, it redirects to verification required
        $response->assertRedirect(route('auth.verification.required'));
    }

    /**
     * Test User Cannot Login with Invalid Password
     */
    public function test_users_can_not_authenticate_with_invalid_password(): void
    {
        $user = User::factory()->create([
            'password' => bcrypt('password'),
        ]);

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
            'g-recaptcha-response' => 'test-token',
        ]);

        $this->assertGuest();
    }

    /**
     * Test User Can Logout
     */
    public function test_users_can_logout(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/logout');

        $this->assertGuest();
        $response->assertRedirect('/');
    }

    /**
     * Test Verification Required Page for Guest
     */
    public function test_guest_can_access_verification_required_page(): void
    {
        // This page is protected by 'guest' middleware in web.php, so unauthenticated users can see it
        $response = $this->get(route('auth.verification.required'));
        $response->assertStatus(200);
        $response->assertSee('Device Verification');
    }
}
