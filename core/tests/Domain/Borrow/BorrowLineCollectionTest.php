<?php

namespace App\Tests\Domain\Borrow;

use App\Domain\Borrow\BorrowLineCollection;
use App\Tests\Fixtures\Mothers\BorrowLineMother;
use PHPUnit\Framework\TestCase;

class BorrowLineCollectionTest extends TestCase
{
    public function testShouldRunWhenAddLine(): void
    {
        $collection = new BorrowLineCollection();

        $line = BorrowLineMother::romeoAndJuliet();
        $collection->add($line);

        $this->assertCount(1, $collection);
    }
}
