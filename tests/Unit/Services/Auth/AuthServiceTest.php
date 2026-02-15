<?php

namespace Tests\Unit\Services\Auth;

use App\Contracts\Auth\AuthenticationStrategy;
use App\Models\User;
use App\Repositories\UserRepository;
use App\Services\Auth\AuthLogger;
use App\Services\Auth\AuthService;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Mockery;
use Tests\TestCase;

class AuthServiceTest extends TestCase
{
    // use RefreshDatabase; // No database usage if fully mocked logic? 
    // Actually we mock repositories, so no need for DB unless User model needs it.
    // User::class is Eloquent, so mocking it is tricky but returning a mock object is fine.

    protected $logger;
    protected $userRepository;
    protected $authService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->logger = Mockery::mock(AuthLogger::class);
        $this->userRepository = Mockery::mock(UserRepository::class);
        $this->authService = new AuthService($this->logger, $this->userRepository);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_authenticate_success()
    {
        $strategy = Mockery::mock(AuthenticationStrategy::class);
        $user = Mockery::mock(User::class);
        $user->shouldReceive('getAuthIdentifier')->andReturn(1);

        $credentials = ['email' => 'test@example.com', 'password' => 'password'];
        $request = Request::create('/login', 'POST');

        $strategy->shouldReceive('authenticate')
            ->once()
            ->with($credentials)
            ->andReturn($user);

        $this->logger->shouldReceive('logLogin')
            ->once()
            ->with(1, 'web', '127.0.0.1', Mockery::any());

        Auth::shouldReceive('guard')->with('web')->andReturnSelf();
        Auth::shouldReceive('login')->with($user, false);

        $result = $this->authService->authenticate($strategy, $credentials, $request);

        $this->assertEquals($user, $result);
    }

    public function test_authenticate_failure()
    {
        $strategy = Mockery::mock(AuthenticationStrategy::class);

        $credentials = ['email' => 'test@example.com', 'password' => 'wrong'];
        $request = Request::create('/login', 'POST');

        $strategy->shouldReceive('authenticate')
            ->once()
            ->with($credentials)
            ->andReturn(null);

        $this->logger->shouldReceive('logFailedLogin')
            ->once()
            ->with('test@example.com', '127.0.0.1', Mockery::any());

        // Auth::login should NOT be called
        Auth::shouldReceive('guard')->never();
        Auth::shouldReceive('login')->never();

        $result = $this->authService->authenticate($strategy, $credentials, $request);

        $this->assertNull($result);
    }

    public function test_register_success()
    {
        $data = [
            'name' => 'Test User',
            'email' => 'new@example.com',
            'password' => 'password',
            'password_confirmation' => 'password'
        ];

        $user = new User();
        $user->name = 'Test User';
        $user->email = 'new@example.com';

        $this->userRepository->shouldReceive('create')
            ->once()
            ->with(Mockery::on(function ($arg) use ($data) {
                return $arg['name'] === $data['name'] &&
                    $arg['email'] === $data['email'] &&
                    Hash::check($data['password'], $arg['password']);
            }))
            ->andReturn($user);

        $result = $this->authService->register($data);

        $this->assertEquals($user->email, $result->email);
    }
}
