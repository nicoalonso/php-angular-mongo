<?php

namespace App\Tests\Application\Book\Inventory;

use App\Application\Book\Inventory\BookInventoryEvent;
use App\Tests\Fixtures\Mothers\BookMother;
use PHPUnit\Framework\TestCase;

class BookInventoryEventTest extends TestCase
{
    public function testShouldRunWhenCreate(): void
    {
        $book = BookMother::romeoAndJuliet();
        $event = new BookInventoryEvent($book->getDescriptor());

        self::assertEquals(BookInventoryEvent::ACTION, $event->action());
        self::assertEquals('book', $event->type());
        self::assertEquals($book->getDescriptor(), $event->getDescriptor());
    }
}
