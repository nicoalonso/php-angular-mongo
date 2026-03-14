<?php

namespace App\Tests\Application\Sale\Consumer;

use App\Application\Book\Inventory\BookInventoryEvent;
use App\Application\Sale\Consumer\SaleConsume;
use App\Tests\Doubles\Infrastructure\Bus\DomainBusStub;
use App\Tests\Fixtures\Mothers\BookMother;
use PHPUnit\Framework\TestCase;
use function App\Tests\Doubles\Infrastructure\Bus\assertDispatch;

class SaleConsumeTest extends TestCase
{
    private DomainBusStub $bus;
    private SaleConsume $consumer;

    protected function setUp(): void
    {
        parent::setUp();

        $this->bus = new DomainBusStub();
        $this->consumer = new SaleConsume($this->bus);
    }

    public function testShouldRunWhenDispatch(): void
    {
        $book = BookMother::romeoAndJuliet();
        $books = [
            $book->getDescriptor(),
        ];

        $this->consumer->dispatch($books);

        assertDispatch($this->bus, BookInventoryEvent::class);
    }
}
