<?php

namespace App\Tests\Application\Purchase\Updater;

use App\Application\Purchase\Updater\PurchaseUpdatedEvent;
use App\Domain\Purchase\Purchase;
use App\Tests\Fixtures\Mothers\BookMother;
use App\Tests\Fixtures\Mothers\PurchaseMother;
use PHPUnit\Framework\TestCase;

class PurchaseUpdatedEventTest extends TestCase
{
    public function testShouldCreateEvent(): void
    {
        $purchase = PurchaseMother::amazonInv1();
        $books = [
            BookMother::romeoAndJuliet()->getDescriptor(),
            BookMother::donQuijote()->getDescriptor(),
        ];

        $event = new PurchaseUpdatedEvent($purchase, $books);

        self::assertSame(PurchaseUpdatedEvent::ACTION, $event->action());
        self::assertSame('purchase', $event->type());
        self::assertSame($purchase, $event->getPurchase());
        self::assertCount(2, $event->getBooks());
    }
}
