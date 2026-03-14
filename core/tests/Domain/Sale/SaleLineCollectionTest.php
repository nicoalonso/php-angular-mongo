<?php

namespace App\Tests\Domain\Sale;

use App\Domain\Sale\SaleLineCollection;
use App\Tests\Fixtures\Mothers\SaleLineMother;
use PHPUnit\Framework\TestCase;

class SaleLineCollectionTest extends TestCase
{
    public function testShouldRunWhenAdd(): void
    {
        $collection = new SaleLineCollection();

        $line = SaleLineMother::johnSale1Line1();
        $collection->add($line);

        $this->assertCount(1, $collection);
    }
}
