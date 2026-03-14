<?php

namespace App\Tests\Domain\Identity\List;

use App\Domain\Identity\List\Field;
use App\Domain\Identity\List\FieldCollection;
use PHPUnit\Framework\TestCase;

class FieldCollectionTest extends TestCase
{
    public function testShouldRunWhenCreate(): void
    {
        $field = new Field('name');
        $col = new FieldCollection([$field]);

        self::assertCount(1, $col);
    }
}
