<?php declare(strict_types=1);

namespace App\Application\Sale\Consumer;

use App\Application\Book\Inventory\BookInventoryEvent;
use App\Domain\Book\BookDescriptor;
use App\Domain\Bus\DomainBus;

final readonly class SaleConsume
{
    public function __construct(private DomainBus $bus) {}

    /**
     * @param BookDescriptor[] $books
     */
    public function dispatch(array $books): void
    {
        foreach ($books as $book) {
            $event = new BookInventoryEvent($book);
            $this->bus->dispatch($event);
        }
    }
}
