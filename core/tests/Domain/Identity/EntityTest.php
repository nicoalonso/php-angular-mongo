<?php

namespace App\Tests\Domain\Identity;

use App\Domain\Identity\Entity;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

class DummyEntity extends Entity {}

class EntityTest extends TestCase
{
    public function testShouldRunWhenCreate(): void
    {
        $entity = new DummyEntity('creator-id');

        $this->assertNotEmpty($entity->getId());
        $this->assertEquals('creator-id', $entity->getCreatedBy());
        $this->assertInstanceOf(DateTimeImmutable::class, $entity->getCreatedAt());
        $this->assertNull($entity->getUpdatedBy());
        $this->assertNull($entity->getUpdatedAt());
    }

    public function testShouldRunWhenUpdate(): void
    {
        $entity = new DummyEntity('creator-id');
        $entity->updated('updater-id');

        $this->assertEquals('updater-id', $entity->getUpdatedBy());
        $this->assertInstanceOf(DateTimeImmutable::class, $entity->getUpdatedAt());
    }
}
