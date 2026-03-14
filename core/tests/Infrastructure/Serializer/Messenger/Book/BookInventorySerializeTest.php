<?php

namespace App\Tests\Infrastructure\Serializer\Messenger\Book;

use App\Application\Book\Inventory\BookInventoryEvent;
use App\Infrastructure\Serializer\Messenger\Book\BookInventorySerialize;
use App\Tests\Fixtures\Mothers\BookMother;
use PHPUnit\Framework\TestCase;

class BookInventorySerializeTest extends TestCase
{
    public function testShouldRunWhenSerialize(): void
    {
        $book = BookMother::romeoAndJuliet();
        $event = new BookInventoryEvent($book->getDescriptor());
        $serializer = new BookInventorySerialize($event);
        $json = $serializer->jsonSerialize();

        $jsonExpected = [
            'action' => $event->action(),
            'type' => $event->type(),
            'book' => [
                'id' => $book->getDescriptor()->getId(),
                'title' => $book->getDescriptor()->getTitle(),
                'isb' => $book->getDescriptor()->getIsbn(),
            ],
        ];
        self::assertEquals($jsonExpected, $json);
    }
}
