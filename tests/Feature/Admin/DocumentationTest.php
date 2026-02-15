<?php

namespace Tests\Feature\Admin;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\DocumentationPage;

class DocumentationTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_documentation_list()
    {
        /** @var \App\Models\User $admin */
        $admin = User::factory()->create(['role' => 'admin', 'is_active' => true]);
        
        // Create some pages
        DocumentationPage::create([
            'title' => 'Test Page',
            'slug' => 'test-page',
            'content' => 'Content',
            'is_published' => true,
        ]);

        // Bypass 2FA middleware for test if needed, or mock it
        // The EnforceAdminTwoFactor middleware checks if user has 2FA enabled.
        // We can force enable it for the test user.
        $admin->forceFill([
            'two_factor_secret' => 'secret',
            'two_factor_confirmed_at' => now(),
        ])->save();

        $response = $this->actingAs($admin)->get(route('admin.support.documentation.index'));

        $response->assertStatus(200);
        $response->assertViewHas('pages');
        $response->assertSee('Test Page');
    }
}
