<?php

namespace App\Tests\Domain\Purchase;

use App\Domain\Purchase\PurchaseCollection;
use App\Tests\Fixtures\Mothers\PurchaseMother;
use PHPUnit\Framework\TestCase;

class PurchaseCollectionTest extends TestCase
{
    public function testShouldRunWhenAdd(): void
    {
        $collection = new PurchaseCollection();

        $purchase = PurchaseMother::amazonInv1();
        $collection->add($purchase);

        $this->assertCount(1, $collection);
    }
}
