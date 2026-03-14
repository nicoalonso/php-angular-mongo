<?php declare(strict_types=1);

namespace App\Infrastructure\Serializer\Messenger\Book;

use App\Application\Book\Inventory\BookInventoryEvent;
use App\Domain\Book\BookDescriptor;
use App\Domain\Identity\Valet;

final class BookCommand
{
    public static function inventory(Valet $data): BookInventoryEvent
    {
        $book = $data->toValet('book');
        $descriptor = new BookDescriptor(
            $book->toString('id'),
            $book->toString('title'),
            $book->toString('isbn'),
        );
        return new BookInventoryEvent($descriptor);
    }
}
