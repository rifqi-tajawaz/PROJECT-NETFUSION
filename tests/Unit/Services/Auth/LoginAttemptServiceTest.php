<?php

namespace Tests\Unit\Services\Auth;

use App\Services\Auth\LoginAttemptService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class LoginAttemptServiceTest extends TestCase
{
    protected LoginAttemptService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = app(LoginAttemptService::class);
        Cache::flush();
    }

    /** @test */
    public function it_records_failed_login_attempts(): void
    {
        $email = 'test@example.com';
        $ip = '127.0.0.1';

        $this->service->recordFailedAttempt($email, $ip);

        $this->assertEquals(1, $this->service->getAttemptsCount($email, $ip));
    }

    /** @test */
    public function it_increments_attempts_on_multiple_failures(): void
    {
        $email = 'test@example.com';
        $ip = '127.0.0.1';

        $this->service->recordFailedAttempt($email, $ip);
        $this->service->recordFailedAttempt($email, $ip);
        $this->service->recordFailedAttempt($email, $ip);

        $this->assertEquals(3, $this->service->getAttemptsCount($email, $ip));
    }

    /** @test */
    public function it_clears_attempts_after_successful_login(): void
    {
        $email = 'test@example.com';
        $ip = '127.0.0.1';

        $this->service->recordFailedAttempt($email, $ip);
        $this->service->recordFailedAttempt($email, $ip);

        $this->assertEquals(2, $this->service->getAttemptsCount($email, $ip));

        $this->service->clearAttempts($email, $ip);

        $this->assertEquals(0, $this->service->getAttemptsCount($email, $ip));
    }

    /** @test */
    public function it_locks_out_after_max_attempts(): void
    {
        $email = 'test@example.com';
        $ip = '127.0.0.1';

        // Record 5 failed attempts
        for ($i = 0; $i < 5; $i++) {
            $this->service->recordFailedAttempt($email, $ip);
        }

        $this->assertTrue($this->service->isLockedOut($email, $ip));
    }

    /** @test */
    public function it_does_not_lock_out_before_max_attempts(): void
    {
        $email = 'test@example.com';
        $ip = '127.0.0.1';

        // Record 4 failed attempts
        for ($i = 0; $i < 4; $i++) {
            $this->service->recordFailedAttempt($email, $ip);
        }

        $this->assertFalse($this->service->isLockedOut($email, $ip));
    }

    /** @test */
    public function it_returns_attempts_remaining(): void
    {
        $email = 'test@example.com';
        $ip = '127.0.0.1';

        $this->assertEquals(5, $this->service->getAttemptsRemaining($email, $ip));

        $this->service->recordFailedAttempt($email, $ip);
        $this->assertEquals(4, $this->service->getAttemptsRemaining($email, $ip));

        $this->service->recordFailedAttempt($email, $ip);
        $this->assertEquals(3, $this->service->getAttemptsRemaining($email, $ip));
    }

    /** @test */
    public function it_returns_lockout_time_remaining(): void
    {
        $email = 'test@example.com';
        $ip = '127.0.0.1';

        // Record 5 failed attempts to trigger lockout
        for ($i = 0; $i < 5; $i++) {
            $this->service->recordFailedAttempt($email, $ip);
        }

        $remaining = $this->service->getLockoutTimeRemaining($email, $ip);

        $this->assertNotNull($remaining);
        $this->assertGreaterThan(0, $remaining);
    }

    /** @test */
    public function it_returns_null_when_not_locked_out(): void
    {
        $email = 'test@example.com';
        $ip = '127.0.0.1';

        $remaining = $this->service->getLockoutTimeRemaining($email, $ip);

        $this->assertNull($remaining);
    }

    /** @test */
    public function it_treats_different_ips_as_separate_attempts(): void
    {
        $email = 'test@example.com';
        $ip1 = '127.0.0.1';
        $ip2 = '127.0.0.2';

        $this->service->recordFailedAttempt($email, $ip1);
        $this->service->recordFailedAttempt($email, $ip1);

        $this->service->recordFailedAttempt($email, $ip2);

        $this->assertEquals(2, $this->service->getAttemptsCount($email, $ip1));
        $this->assertEquals(1, $this->service->getAttemptsCount($email, $ip2));
    }

    /** @test */
    public function it_permanently_blacklists_after_excessive_attempts(): void
    {
        $email = 'test@example.com';
        $ip = '127.0.0.1';

        // Record 20 failed attempts to trigger permanent blacklist
        for ($i = 0; $i < 20; $i++) {
            $this->service->recordFailedAttempt($email, $ip);
        }

        $this->assertTrue($this->service->isLockedOut($email, $ip));

        $remaining = $this->service->getLockoutTimeRemaining($email, $ip);
        $this->assertEquals(-1, $remaining); // -1 indicates permanent lockout
    }

    /** @test */
    public function it_can_remove_from_blacklist(): void
    {
        $email = 'test@example.com';
        $ip = '127.0.0.1';

        // Record 20 failed attempts to trigger permanent blacklist
        for ($i = 0; $i < 20; $i++) {
            $this->service->recordFailedAttempt($email, $ip);
        }

        $this->assertTrue($this->service->isLockedOut($email, $ip));

        // Remove from blacklist
        $this->service->removeFromBlacklist($email, $ip);

        // Should no longer be locked out
        $this->assertFalse($this->service->isLockedOut($email, $ip));
    }

    /** @test */
    public function it_uses_combined_key_for_email_and_ip(): void
    {
        $email1 = 'user1@example.com';
        $email2 = 'user2@example.com';
        $ip = '127.0.0.1';

        $this->service->recordFailedAttempt($email1, $ip);
        $this->service->recordFailedAttempt($email2, $ip);

        // Each email should have separate attempt counts
        $this->assertEquals(1, $this->service->getAttemptsCount($email1, $ip));
        $this->assertEquals(1, $this->service->getAttemptsCount($email2, $ip));
    }
}
