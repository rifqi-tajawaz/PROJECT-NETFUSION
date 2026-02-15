<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

use Illuminate\Support\Str;

class UserProfileTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Mock Mail to prevent errors
        \Illuminate\Support\Facades\Mail::fake();
    }

    public function test_user_profile_page_loads_with_correct_variables()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('user.profile'));

        $response->assertStatus(200);
        $response->assertViewHas(['activities', 'sessions', 'user', 'twoFactorData']);
    }

    public function test_user_can_update_profile_and_generates_audit_log()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('user.profile.update'), [
            'name' => 'Updated Name',
            'email' => $user->email,
            'phone' => '1234567890',
            'address' => 'New Address',
        ]);

        $response->assertSessionHas('status', 'profile-updated');
        
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Updated Name',
            'phone' => '1234567890',
        ]);

        $this->assertDatabaseHas('security_logs', [
            'user_id' => $user->id,
            'event_type' => 'profile_updated',
        ]);
    }

    public function test_password_update_requires_confirmation_sudo_mode()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create([
            'password' => Hash::make('OldPassword123!'),
        ]);

        // Without password confirmation in session
        $response = $this->actingAs($user)->post(route('user.profile.password'), [
            'current_password' => 'OldPassword123!',
            'password' => 'NewPassword123!',
            'password_confirmation' => 'NewPassword123!',
        ]);

        // Should redirect to password confirmation page
        $response->assertRedirect(route('password.confirm'));
    }

    public function test_password_update_success_with_confirmation_and_history_check()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create([
            'password' => Hash::make('OldPassword123!'),
        ]);

        // Simulate sudo mode confirmed
        $this->withSession(['auth.password_confirmed_at' => time()]);

        // Use a very strong/random password
        $newPassword = Str::random(12) . 'A1!' . Str::random(5);

        $response = $this->actingAs($user)->post(route('user.profile.password'), [
            'current_password' => 'OldPassword123!',
            'password' => $newPassword,
            'password_confirmation' => $newPassword,
        ]);

        $response->assertSessionHas('status', 'password-updated');

        // Check password changed
        $user->refresh();
        $this->assertTrue(Hash::check($newPassword, $user->password));

        // Check history saved
        $this->assertDatabaseHas('password_histories', [
            'user_id' => $user->id,
        ]);

        // Check security log
        $this->assertDatabaseHas('security_logs', [
            'user_id' => $user->id,
            'event_type' => 'password_changed',
        ]);
    }

    public function test_cannot_reuse_recent_password()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create([
            'password' => Hash::make('OldPassword123!'),
        ]);

        // Add old password to history
        $user->passwordHistories()->create([
            'password' => Hash::make('RecentPassword123!'),
        ]);

        $this->withSession(['auth.password_confirmed_at' => time()]);

        $response = $this->actingAs($user)->post(route('user.profile.password'), [
            'current_password' => 'OldPassword123!',
            'password' => 'RecentPassword123!',
            'password_confirmation' => 'RecentPassword123!',
        ]);

        $response->assertSessionHasErrors(['password']);
    }
}
