<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use App\Services\Auth\LoginAttemptService;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Notification::fake();
    }

    /** @test */
    public function user_can_login_with_valid_credentials(): void
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'password123',
            'g-recaptcha-response' => 'valid-recaptcha-response',
        ]);

        $this->assertAuthenticatedAs($user);
        $response->assertRedirect('/mikrotik-suite/dashboard');
    }

    /** @test */
    public function user_cannot_login_with_invalid_credentials(): void
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'wrong-password',
            'g-recaptcha-response' => 'valid-recaptcha-response',
        ]);

        $this->assertGuest();
        $response->assertSessionHasErrors('email');
    }

    /** @test */
    public function user_cannot_login_with_invalid_email(): void
    {
        $response = $this->post('/login', [
            'email' => 'nonexistent@example.com',
            'password' => 'password123',
            'g-recaptcha-response' => 'valid-recaptcha-response',
        ]);

        $this->assertGuest();
        $response->assertSessionHasErrors('email');
    }

    /** @test */
    public function inactive_user_cannot_login(): void
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
            'is_active' => false,
            'email_verified_at' => now(),
        ]);

        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'password123',
            'g-recaptcha-response' => 'valid-recaptcha-response',
        ]);

        $this->assertGuest();
        $response->assertSessionHasErrors('email');
    }

    /** @test */
    public function unverified_user_cannot_login_when_verification_required(): void
    {
        config(['auth.must_verify_email' => true]);

        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
            'is_active' => true,
            'email_verified_at' => null,
        ]);

        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'password123',
            'g-recaptcha-response' => 'valid-recaptcha-response',
        ]);

        $this->assertGuest();
        $response->assertSessionHasErrors('email');
    }

    /** @test */
    public function locked_user_cannot_login(): void
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
            'is_active' => true,
            'is_locked' => true,
            'locked_at' => now(),
            'lock_reason' => 'Test lock',
            'email_verified_at' => now(),
        ]);

        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'password123',
            'g-recaptcha-response' => 'valid-recaptcha-response',
        ]);

        $this->assertGuest();
        $response->assertSessionHasErrors('email');
    }

    /** @test */
    public function user_can_logout(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post('/logout');

        $this->assertGuest();
    }

    /** @test */
    public function login_attempts_are_tracked(): void
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        $loginAttemptService = app(LoginAttemptService::class);

        // Make 5 failed attempts
        for ($i = 0; $i < 5; $i++) {
            $this->post('/login', [
                'email' => 'test@example.com',
                'password' => 'wrong-password',
                'g-recaptcha-response' => 'valid-recaptcha-response',
            ]);
        }

        // Check that user is locked out
        $this->assertTrue(
            $loginAttemptService->isLockedOut('test@example.com', '127.0.0.1')
        );

        // Try to login with correct credentials - should still be blocked
        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'password123',
            'g-recaptcha-response' => 'valid-recaptcha-response',
        ]);

        $this->assertGuest();
    }

    /** @test */
    public function login_attempts_are_cleared_on_successful_login(): void
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        $loginAttemptService = app(LoginAttemptService::class);

        // Make some failed attempts
        for ($i = 0; $i < 3; $i++) {
            $this->post('/login', [
                'email' => 'test@example.com',
                'password' => 'wrong-password',
                'g-recaptcha-response' => 'valid-recaptcha-response',
            ]);
        }

        $this->assertEquals(3, $loginAttemptService->getAttemptsCount('test@example.com', '127.0.0.1'));

        // Login successfully
        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'password123',
            'g-recaptcha-response' => 'valid-recaptcha-response',
        ]);

        $this->assertAuthenticatedAs($user);

        // Check that attempts were cleared
        $this->assertEquals(0, $loginAttemptService->getAttemptsCount('test@example.com', '127.0.0.1'));
    }
}
