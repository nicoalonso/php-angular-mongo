<?php

namespace App\Tests\Application\Purchase\Creator;

use App\Application\Purchase\Creator\PurchaseCreatedEvent;
use App\Tests\Fixtures\Mothers\BookMother;
use App\Tests\Fixtures\Mothers\PurchaseMother;
use PHPUnit\Framework\TestCase;

class PurchaseCreatedEventTest extends TestCase
{
    public function testShouldRunWhenCreate(): void
    {
        $purchase = PurchaseMother::amazonInv1();
        $book = BookMother::romeoAndJuliet();
        $books = [
            $book->getDescriptor(),
         ];
        $event = new PurchaseCreatedEvent($purchase, $books);


        self::assertEquals('purchase.created', $event->action());
        self::assertEquals('purchase', $event->type());
        self::assertSame($purchase, $event->getPurchase());
        self::assertCount(1, $event->getBooks());
    }
}
