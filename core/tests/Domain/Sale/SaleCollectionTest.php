<?php

namespace App\Tests\Domain\Sale;

use App\Domain\Sale\SaleCollection;
use App\Tests\Fixtures\Mothers\SaleMother;
use PHPUnit\Framework\TestCase;

class SaleCollectionTest extends TestCase
{
    public function testShouldRunWhenAdd(): void
    {
        $collection = new SaleCollection();

        $sale = SaleMother::johnDoeSale1();
        $collection->add($sale);

        $this->assertCount(1, $collection);
    }
}
