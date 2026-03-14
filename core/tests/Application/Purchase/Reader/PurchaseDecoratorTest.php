<?php

namespace App\Tests\Application\Purchase\Reader;

use App\Application\Purchase\Reader\PurchaseDecorator;
use App\Domain\Purchase\PurchaseLineCollection;
use App\Tests\Fixtures\Mothers\PurchaseLineMother;
use App\Tests\Fixtures\Mothers\PurchaseMother;
use BadMethodCallException;
use PHPUnit\Framework\TestCase;

class PurchaseDecoratorTest extends TestCase
{
    public function testShouldRunWhenCreate(): void
    {
        $purchase = PurchaseMother::amazonInv1();
        $lines = new PurchaseLineCollection([
            PurchaseLineMother::amazonLine1(),
            PurchaseLineMother::amazonLine2(),
        ]);

        $decorator = new PurchaseDecorator($purchase, $lines);

        self::assertEquals($purchase, $decorator->getPurchase());
        self::assertCount(2, $decorator->getLines());
        self::assertEquals($purchase->getId(), $decorator->getId());
    }

    public function testShouldFailWhenBadMethod(): void
    {
        $purchase = PurchaseMother::amazonInv1();
        $lines = new PurchaseLineCollection();

        $decorator = new PurchaseDecorator($purchase, $lines);

        $this->expectException(BadMethodCallException::class);
        $decorator->unknownMethod();
    }
}
