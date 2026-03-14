<?php

namespace App\Tests\Domain\Common;

use App\Domain\Common\Address;
use PHPUnit\Framework\TestCase;

class AddressTest extends TestCase
{
    public function testShouldRunWhenCreate(): void
    {
        $address = new Address(
            '123 Main St',
            '12345',
            'Anytown',
            'Alaska',
            'EEUU'
        );

        $this->assertSame('123 Main St', $address->getStreet());
        $this->assertSame('12345', $address->getPostalCode());
        $this->assertSame('Anytown', $address->getCity());
        $this->assertSame('Alaska', $address->getProvince());
        $this->assertSame('EEUU', $address->getCountry());
    }
}
