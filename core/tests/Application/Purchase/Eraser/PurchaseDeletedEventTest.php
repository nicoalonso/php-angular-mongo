<?php

namespace App\Tests\Application\Purchase\Eraser;

use App\Application\Purchase\Eraser\PurchaseDeletedEvent;
use App\Tests\Fixtures\Mothers\BookMother;
use App\Tests\Fixtures\Mothers\PurchaseMother;
use PHPUnit\Framework\TestCase;

class PurchaseDeletedEventTest extends TestCase
{
    public function testShouldRunWhenCreate(): void
    {
        $purchase = PurchaseMother::amazonInv1();
        $books = [
            BookMother::romeoAndJuliet()->getDescriptor(),
        ];

        $event = new PurchaseDeletedEvent($purchase, $books);

        self::assertSame(PurchaseDeletedEvent::ACTION, $event->action());
        self::assertSame('purchase', $event->type());
        self::assertSame($purchase, $event->getPurchase());
        self::assertCount(1, $event->getBooks());
    }
}
