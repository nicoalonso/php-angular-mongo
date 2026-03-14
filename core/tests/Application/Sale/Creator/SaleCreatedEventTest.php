<?php

namespace App\Tests\Application\Sale\Creator;

use App\Application\Sale\Creator\SaleCreatedEvent;
use App\Tests\Fixtures\Mothers\BookMother;
use App\Tests\Fixtures\Mothers\SaleMother;
use PHPUnit\Framework\TestCase;

class SaleCreatedEventTest extends TestCase
{
    public function testShouldRunWhenCreate(): void
    {
        $sale = SaleMother::johnDoeSale1();
        $book = BookMother::romeoAndJuliet();
        $books = [
            $book->getDescriptor(),
        ];

        $event = new SaleCreatedEvent($sale, $books);

        self::assertSame(SaleCreatedEvent::ACTION, $event->action());
        self::assertSame('sale', $event->type());
        self::assertSame($sale, $event->getSale());
        self::assertCount(1, $event->getBooks());
    }
}
