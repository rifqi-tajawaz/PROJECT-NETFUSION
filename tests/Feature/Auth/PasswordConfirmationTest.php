<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class PasswordConfirmationTest extends TestCase
{
    use RefreshDatabase;

    public function test_confirm_password_screen_can_be_rendered(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('password.confirm'));

        $response->assertStatus(200);
        $response->assertViewIs('auth.passwords.confirm');
    }

    public function test_password_can_be_confirmed(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make('password123'),
        ]);

        $response = $this->actingAs($user)->post(route('password.confirm'), [
            'password' => 'password123',
        ]);

        $response->assertRedirect();
        $response->assertSessionHasNoErrors();
        $response->assertSessionHas('auth.password_confirmed_at');
    }

    public function test_password_is_not_confirmed_with_invalid_password(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make('password123'),
        ]);

        $response = $this->actingAs($user)->post(route('password.confirm'), [
            'password' => 'wrong-password',
        ]);

        $response->assertSessionHasErrors();
        $this->assertFalse(session()->has('auth.password_confirmed_at'));
    }
}
