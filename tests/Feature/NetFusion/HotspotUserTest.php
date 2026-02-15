<?php

namespace Tests\Feature\NetFusion;

use App\DTOs\Mikrotik\HotspotUserDTO;
use App\Models\User;
use App\Services\NetFusion\Modules\HotspotService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Session;
use Tests\TestCase;

class HotspotUserTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Mock Session for Router Connection
        Session::put('router_session', [
            'ip' => '192.168.88.1',
            'user' => 'admin',
            'password' => 'encrypted_pass',
            'port' => 8728
        ]);
    }

    public function test_index_displays_users()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create();

        // Mock Service
        $this->mock(HotspotService::class, function ($mock) {
            $mock->shouldReceive('getProfiles')->andReturn([['name' => 'default']]);
            $mock->shouldReceive('getUsers')->andReturn([
                ['.id' => '*1', 'name' => 'user1', 'profile' => 'default']
            ]);
            $mock->shouldReceive('getActiveUsers')->andReturn([]);
            
            // We need to mock processStatsAndFilter because Controller calls it
            $dto = new HotspotUserDTO(['.id' => '*1', 'name' => 'user1']);
            $mock->shouldReceive('processStatsAndFilter')
                ->andReturn([
                    'users' => [$dto],
                    'stats' => [
                        'totalCount' => 1,
                        'onlineCount' => 0,
                        'expiredCount' => 0,
                        'disabledCount' => 0,
                        'uniqueComments' => [],
                    ]
                ]);
        });

        $response = $this->actingAs($user)
            ->get(route('mikrotik-suite.netfusion.users.index'));

        $response->assertStatus(200);
        $response->assertViewHas('users');
        $response->assertSee('user1');
    }

    public function test_store_creates_user()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create();

        $this->mock(HotspotService::class, function ($mock) {
            $mock->shouldReceive('addUser')->once();
        });

        $response = $this->actingAs($user)
            ->post(route('mikrotik-suite.netfusion.users.store'), [
                'username' => 'newuser',
                'password' => 'password',
                'profile' => 'default',
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');
    }
}
