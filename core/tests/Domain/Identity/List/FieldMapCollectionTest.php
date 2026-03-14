<?php

namespace App\Tests\Domain\Identity\List;

use App\Domain\Identity\List\FieldMap;
use App\Domain\Identity\List\FieldMapCollection;
use PHPUnit\Framework\TestCase;

class FieldMapCollectionTest extends TestCase
{
    public function testShouldRunWhenCreate(): void
    {
        $fieldMap = new FieldMap(1, 'id');
        $col = new FieldMapCollection([$fieldMap]);

        self::assertCount(1, $col);
    }
}
