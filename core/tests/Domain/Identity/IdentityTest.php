<?php

namespace App\Tests\Domain\Identity;

use App\Domain\Identity\Exception\IdEmptyException;
use App\Domain\Identity\Identity;
use PHPUnit\Framework\TestCase;

class DummyIdentity extends Identity {}

class IdentityTest extends TestCase
{
    public function testShouldFailWhenIsNotValid(): void
    {
        $this->expectException(IdEmptyException::class);
        new DummyIdentity('');
    }

    public function testShouldRunWhenIsValid(): void
    {
        $item = new DummyIdentity('1');

        self::assertEquals('1', $item->getId());
    }

    public function testShouldRunWhenGenerateUuid(): void
    {
        $item = new DummyIdentity();

        self::assertNotEmpty($item->getId());
    }

    public function testShouldRunWhenIsSame(): void
    {
        $item1 = new DummyIdentity();
        $item2 = new DummyIdentity();
        $item3 = clone $item1;

        $this->assertFalse($item1->isSame($item2));
        $this->assertTrue($item1->isSame($item3));
    }

    public function testShouldRunWhenCheckUuid(): void
    {
        $item1 = new DummyIdentity();

        self::assertTrue(Identity::checkUuid($item1->getId()));
        self::assertFalse(Identity::checkUuid('wrong uuid'));
    }
}
