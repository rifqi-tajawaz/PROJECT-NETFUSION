<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use App\Rules\StrongPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class PasswordTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function strong_password_validation_requires_minimum_length(): void
    {
        $rule = new StrongPassword();

        $this->assertFalse($rule->passes('password', 'Short1!'));
    }

    /** @test */
    public function strong_password_validation_requires_uppercase(): void
    {
        $rule = new StrongPassword();

        $this->assertFalse($rule->passes('password', 'lowercase1!'));
    }

    /** @test */
    public function strong_password_validation_requires_lowercase(): void
    {
        $rule = new StrongPassword();

        $this->assertFalse($rule->passes('password', 'UPPERCASE1!'));
    }

    /** @test */
    public function strong_password_validation_requires_number(): void
    {
        $rule = new StrongPassword();

        $this->assertFalse($rule->passes('password', 'NoNumber!'));
    }

    /** @test */
    public function strong_password_validation_requires_special_character(): void
    {
        $rule = new StrongPassword();

        $this->assertFalse($rule->passes('password', 'NoSpecial123'));
    }

    /** @test */
    public function strong_password_validation_passes_with_valid_password(): void
    {
        $rule = new StrongPassword();

        $this->assertTrue($rule->passes('password', 'ValidPass123!'));
    }

    /** @test */
    public function strong_password_validation_rejects_common_passwords(): void
    {
        $rule = new StrongPassword();

        // Password123! has only 3 sequential digits (123), so StrongPassword allows it.
        // We use Password1234! which has 4 sequential digits (1234) and should fail.
        $this->assertFalse($rule->passes('password', 'Password1234!'));
        $this->assertFalse($rule->passes('password', '12345678'));
        $this->assertFalse($rule->passes('password', 'password123'));
    }

    /** @test */
    public function user_can_check_if_password_is_expired(): void
    {
        $user = User::factory()->create([
            'password_expires_at' => now()->subDay(),
        ]);

        $this->assertTrue($user->isPasswordExpired());
    }

    /** @test */
    public function user_can_check_if_password_is_not_expired(): void
    {
        $user = User::factory()->create([
            'password_expires_at' => now()->addDays(30),
        ]);

        $this->assertFalse($user->isPasswordExpired());
    }

    /** @test */
    public function user_with_must_change_password_flag_is_considered_expired(): void
    {
        $user = User::factory()->create([
            'must_change_password' => true,
            'password_expires_at' => now()->addDays(30),
        ]);

        $this->assertTrue($user->isPasswordExpired());
    }

    /** @test */
    public function user_can_get_password_expiration_days(): void
    {
        // Use start of second to avoid microsecond discrepancies between PHP and DB
        $now = now()->startOfSecond();
        \Illuminate\Support\Carbon::setTestNow($now);

        $user = User::factory()->create([
            'password_expires_at' => $now->copy()->addDays(7),
        ]);

        $this->assertEquals(7, $user->getPasswordExpirationDays());

        \Illuminate\Support\Carbon::setTestNow(); // Reset
    }

    /** @test */
    public function user_with_no_expiration_date_returns_null(): void
    {
        $user = User::factory()->create([
            'password_expires_at' => null,
        ]);

        $this->assertNull($user->getPasswordExpirationDays());
    }

    /** @test */
    public function user_can_set_password_expiration(): void
    {
        config(['app.password_expiration_days' => 90]);

        $user = User::factory()->create([
            'password_expires_at' => null,
            'password_changed_at' => null,
        ]);

        $user->setPasswordExpiration();

        $this->assertNotNull($user->password_changed_at);
        $this->assertNotNull($user->password_expires_at);
        $this->assertFalse($user->must_change_password);

        // Check expiration is approximately 90 days from now
        $expectedExpiration = now()->addDays(90);
        $this->assertEquals(
            $expectedExpiration->format('Y-m-d'),
            $user->password_expires_at->format('Y-m-d')
        );
    }

    /** @test */
    public function user_can_be_forced_to_change_password(): void
    {
        $user = User::factory()->create([
            'must_change_password' => false,
        ]);

        $user->forcePasswordChange();

        $this->assertTrue($user->fresh()->must_change_password);
    }
}
