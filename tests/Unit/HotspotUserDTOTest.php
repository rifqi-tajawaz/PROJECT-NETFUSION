<?php

namespace Tests\Unit;

use App\DTOs\Mikrotik\HotspotUserDTO;
use PHPUnit\Framework\TestCase;

class HotspotUserDTOTest extends TestCase
{
    public function test_can_create_dto_from_array()
    {
        $data = [
            '.id' => '*1',
            'name' => 'testuser',
            'profile' => 'default',
            'disabled' => 'false',
            'uptime' => '1h',
            'limit-uptime' => '2h',
        ];

        $dto = new HotspotUserDTO($data);

        $this->assertEquals('*1', $dto->id);
        $this->assertEquals('testuser', $dto->name);
        $this->assertEquals('default', $dto->profile);
        $this->assertFalse($dto->isDisabled());
        $this->assertFalse($dto->isExpired());
    }

    public function test_is_expired_logic()
    {
        // Case 1: Limit reached
        $dto = new HotspotUserDTO([
            'uptime' => '2h',
            'limit-uptime' => '2h',
        ]);
        $this->assertTrue($dto->isExpired());

        // Case 2: Limit not reached
        $dto = new HotspotUserDTO([
            'uptime' => '1h',
            'limit-uptime' => '2h',
        ]);
        $this->assertFalse($dto->isExpired());

        // Case 3: Bytes Limit reached
        $dto = new HotspotUserDTO([
            'limit-bytes-total' => '1000',
            'bytes-in' => '600',
            'bytes-out' => '500', // Total 1100
        ]);
        $this->assertTrue($dto->isExpired());
    }

    public function test_is_disabled_logic()
    {
        $dto = new HotspotUserDTO(['disabled' => 'true']);
        $this->assertTrue($dto->isDisabled());

        $dto = new HotspotUserDTO(['disabled' => 'false']);
        $this->assertFalse($dto->isDisabled());
        
        // RouterOS sometimes returns boolean true/false in API depending on client
        $dto = new HotspotUserDTO(['disabled' => true]);
        $this->assertTrue($dto->isDisabled());
    }
}
