<?php

namespace App\Tests\Domain\Identity\List;

use App\Domain\Identity\List\SortField;
use App\Domain\Identity\List\SortFieldCollection;
use PHPUnit\Framework\TestCase;

class SortFieldCollectionTest extends TestCase
{
    public function testShouldRunWhenCreate(): void
    {
        $sort = new SortField('test');
        $col = new SortFieldCollection([$sort]);

        self::assertCount(1, $col);
    }
}
