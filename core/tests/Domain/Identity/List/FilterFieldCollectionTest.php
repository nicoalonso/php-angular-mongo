<?php

namespace App\Tests\Domain\Identity\List;

use App\Domain\Identity\List\FilterField;
use App\Domain\Identity\List\FilterFieldCollection;
use PHPUnit\Framework\TestCase;

class FilterFieldCollectionTest extends TestCase
{
    public function testShouldRunWhenCreate(): void
    {
        $filter = new FilterField('test', 'value1');
        $col = new FilterFieldCollection([$filter]);

        self::assertCount(1, $col);
    }
}
