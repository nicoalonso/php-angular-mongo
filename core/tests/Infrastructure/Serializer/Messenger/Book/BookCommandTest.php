<?php

namespace App\Tests\Infrastructure\Serializer\Messenger\Book;

use App\Domain\Identity\Valet;
use App\Infrastructure\Serializer\Messenger\Book\BookCommand;
use PHPUnit\Framework\TestCase;

class BookCommandTest extends TestCase
{
    public function testShouldMakeWhenInventory(): void
    {
        $payload = [
            'book' => [
                'id' => '123',
                'title' => 'Test Book',
                'isbn' => '978-3161484100',
            ],
        ];
        $data = new Valet($payload);
        $event = BookCommand::inventory($data);

        self::assertEquals('book.inventory', $event->action());
        self::assertEquals('book', $event->type());
        $this->assertEquals('123', $event->getDescriptor()->getId());
        $this->assertEquals('Test Book', $event->getDescriptor()->getTitle());
        $this->assertEquals('978-3161484100', $event->getDescriptor()->getIsbn());
    }
}
