<?php

namespace App\Tests\Domain\Customer;

use App\Domain\Customer\Membership;
use PHPUnit\Framework\TestCase;

class MembershipTest extends TestCase
{
    public function testShouldRunWhenCreate(): void
    {
        $membership = new Membership('SN00025');

        $this->assertEquals('SN00025', $membership->getNumber());
        $this->assertTrue($membership->isActive());
        $this->assertNull($membership->getEndedAt());
    }

    public function testShouldRunWhenDisabled(): void
    {
        $membership = new Membership('SN00025');
        $membership->disable();

        $this->assertEquals('SN00025', $membership->getNumber());
        $this->assertFalse($membership->isActive());
        $this->assertNotNull($membership->getEndedAt());
    }
}
