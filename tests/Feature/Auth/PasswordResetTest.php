<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class PasswordResetTest extends TestCase
{
    use RefreshDatabase;

    public function test_reset_password_link_screen_can_be_rendered(): void
    {
        $response = $this->get(route('password.request'));

        $response->assertStatus(200);
        $response->assertViewIs('auth.passwords.email');
    }

    public function test_reset_password_link_can_be_requested(): void
    {
        Notification::fake();

        $user = User::factory()->create();

        $response = $this->from(route('password.request'))->post(route('password.email'), [
            'email' => $user->email,
        ]);

        $response->assertRedirect(route('password.request'));
        $response->assertSessionHas('status', __('passwords.sent'));

        Notification::assertSentTo($user, ResetPassword::class);
    }

    public function test_reset_password_screen_can_be_rendered(): void
    {
        $token = 'test-token';
        $response = $this->get(route('password.reset', ['token' => $token]));

        $response->assertStatus(200);
        $response->assertViewIs('auth.passwords.reset');
        $response->assertViewHas('token', $token);
    }

    public function test_password_can_be_reset(): void
    {
        Notification::fake();

        $user = User::factory()->create([
            'password' => Hash::make('old-password'),
        ]);

        $token = app('auth.password.broker')->createToken($user);

        $newPassword = \Illuminate\Support\Str::random(10) . 'Aa1!';

        $response = $this->post(route('password.update'), [
            'token' => $token,
            'email' => $user->email,
            'password' => $newPassword,
            'password_confirmation' => $newPassword,
        ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect('/mikrotik-suite/dashboard'); // Custom redirect in ResetPasswordController

        $this->assertTrue(Hash::check($newPassword, $user->fresh()->password));
    }
}
