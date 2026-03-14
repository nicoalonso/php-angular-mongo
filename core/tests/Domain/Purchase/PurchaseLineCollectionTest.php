<?php

namespace App\Tests\Domain\Purchase;

use App\Domain\Purchase\PurchaseLineCollection;
use App\Tests\Fixtures\Mothers\PurchaseLineMother;
use PHPUnit\Framework\TestCase;

class PurchaseLineCollectionTest extends TestCase
{
    public function testShouldRunWhenAdd(): void
    {
        $collection = new PurchaseLineCollection();

        $line = PurchaseLineMother::bestBuyLine1();
        $collection->add($line);

        $this->assertCount(1, $collection);
    }
}
